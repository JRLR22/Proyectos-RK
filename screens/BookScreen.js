// Pantalla de detalle individual de cada libro

import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as Haptics from 'expo-haptics';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { useEffect, useRef, useState } from "react";
import {
  Alert,
  Animated,
  Dimensions,
  Image,
  PanResponder,
  Platform,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from "react-native";
import LoadingAnimation from '../components/LoadingAnimation';
import { API_BASE_URL, getImageUrl } from '../config/api';
import { useCart } from '../contexts/CartContext';
import { useTheme } from '../contexts/ThemeContext';
import ConfettiCannon from 'react-native-confetti-cannon';

const { width: SCREEN_WIDTH } = Dimensions.get('window');

export default function BookScreen() {
  const router = useRouter();
  const params = useLocalSearchParams(); 
  const bookId = params.id || params.bookId; 
  
  const { addToCart } = useCart();
  const { darkMode } = useTheme();

  const [book, setBook] = useState(null);
  const [loading, setLoading] = useState(true);
  const [isFavorite, setIsFavorite] = useState(false);
  const [quantity, setQuantity] = useState(1);
  const [showConfetti, setShowConfetti] = useState(false);
  const confettiRef = useRef(null);

  //  Animaciones
  const scrollY = useRef(new Animated.Value(0)).current;
  const fadeAnim = useRef(new Animated.Value(0)).current;
  const scaleAnim = useRef(new Animated.Value(0.8)).current;
  const heartScale = useRef(new Animated.Value(1)).current;
  const buttonScale = useRef(new Animated.Value(1)).current;

  //  Animaciones 3D para la portada
  const rotateX = useRef(new Animated.Value(0)).current;
  const rotateY = useRef(new Animated.Value(0)).current;
  const coverScale = useRef(new Animated.Value(1)).current;
  const shine = useRef(new Animated.Value(0)).current;
  const bookOpen = useRef(new Animated.Value(0)).current; // Nueva Animación de apertura

  useEffect(() => {
    if (bookId) {
      fetchBookDetail();
      checkIfFavorite();
    } else {
      console.log('No se recibió bookId');
      setLoading(false);
    }
  }, [bookId]);

  //  Animación de entrada cuando carga el libro
  useEffect(() => {
    if (book) {
      Animated.parallel([
        Animated.timing(fadeAnim, {
          toValue: 1,
          duration: 600,
          useNativeDriver: true,
        }),
        Animated.spring(scaleAnim, {
          toValue: 1,
          friction: 8,
          tension: 40,
          useNativeDriver: true,
        }),
      ]).start();
    }
  }, [book]);

  //  PanResponder para la portada del libro

const coverPanResponder = useRef(
  PanResponder.create({
    onStartShouldSetPanResponder: () => true,
    onMoveShouldSetPanResponder: () => true,
    
    onPanResponderGrant: (evt) => {
      const { locationX, locationY } = evt.nativeEvent;
      updateCoverRotation(locationX, locationY);
      animateBookOpen(); // Abre el libro al tocar
    },
    
    onPanResponderMove: (evt) => {
      const { locationX, locationY } = evt.nativeEvent;
      updateCoverRotation(locationX, locationY);
    },
    
    onPanResponderRelease: () => {
      resetCoverRotation();
      animateBookClose(); //  Cierra el libro al soltar
    },
    
    onPanResponderTerminate: () => {
      resetCoverRotation();
      animateBookClose(); // Cierra el libro si se cancela
    },
  })
).current;

  const updateCoverRotation = (x, y) => {
    const width = SCREEN_WIDTH * 0.6;
    const height = 350;
    
    const normalizedX = ((x / width) - 0.5) * 2;
    const normalizedY = ((y / height) - 0.5) * 2;

    Animated.parallel([
      Animated.spring(rotateX, {
        toValue: -normalizedY * 10,
        useNativeDriver: true,
        friction: 6,
        tension: 50,
      }),
      Animated.spring(rotateY, {
        toValue: normalizedX * 10,
        useNativeDriver: true,
        friction: 6,
        tension: 50,
      }),
      Animated.spring(coverScale, {
        toValue: 1.05,
        useNativeDriver: true,
        friction: 6,
        tension: 50,
      }),
      Animated.timing(shine, {
        toValue: 1,
        duration: 200,
        useNativeDriver: true,
      }),
    ]).start();
  };

  const resetCoverRotation = () => {
    Animated.parallel([
      Animated.spring(rotateX, {
        toValue: 0,
        useNativeDriver: true,
        friction: 6,
        tension: 50,
      }),
      Animated.spring(rotateY, {
        toValue: 0,
        useNativeDriver: true,
        friction: 6,
        tension: 50,
      }),
      Animated.spring(coverScale, {
        toValue: 1,
        useNativeDriver: true,
        friction: 6,
        tension: 50,
      }),
      Animated.timing(shine, {
        toValue: 0,
        duration: 300,
        useNativeDriver: true,
      }),
    ]).start();
  };

  // Animar apertura del libro
const animateBookOpen = () => {
  Animated.spring(bookOpen, {
    toValue: 1,
    friction: 8,
    tension: 40,
    useNativeDriver: true,
  }).start();
};

// Cerrar el libro
const animateBookClose = () => {
  Animated.spring(bookOpen, {
    toValue: 0,
    friction: 8,
    tension: 40,
    useNativeDriver: true,
  }).start();
};

  // Efecto parallax en la imagen al hacer scroll
  const imageTranslateY = scrollY.interpolate({
    inputRange: [0, 300],
    outputRange: [0, -100],
    extrapolate: 'clamp',
  });

  const imageScale = scrollY.interpolate({
    inputRange: [0, 300],
    outputRange: [1, 1.2],
    extrapolate: 'clamp',
  });

const fetchBookDetail = async () => {
  try {
    console.log('🔍 Obteniendo libro con ID:', bookId);
    
    const response = await fetch(`${API_BASE_URL}/api/books`);
    
    if (!response.ok) {
      throw new Error(`Error ${response.status}: ${response.statusText}`);
    }
    
    const allBooks = await response.json();
    console.log('📚 Total de libros:', allBooks.length);
    
    const foundBook = allBooks.find(book => book.book_id === parseInt(bookId));
    
    if (!foundBook) {
      throw new Error('Libro no encontrado');
    }
    
    console.log('✅ Libro encontrado:', foundBook);
    setBook(foundBook);
    setLoading(false);
  } catch (error) {
    console.error("❌ Error obteniendo detalle:", error);
    setLoading(false);
    Alert.alert("Error", "No se pudo cargar el libro: " + error.message);
  }
};

  const checkIfFavorite = async () => {
    try {
      const userData = await AsyncStorage.getItem('userData');
      if (!userData) return;
      
      const user = JSON.parse(userData);
      const favKey = `favorites_${user.user_id}`;
      const favData = await AsyncStorage.getItem(favKey);
      
      if (favData) {
        const favorites = JSON.parse(favData);
        setIsFavorite(favorites.some(fav => fav.book_id === parseInt(bookId)));
      }
    } catch (error) {
      console.error("Error verificando favorito:", error);
    }
  };

  const toggleFavorite = async () => {
    try {
      const userData = await AsyncStorage.getItem('userData');
      if (!userData) {
        Alert.alert("Inicia sesión", "Necesitas iniciar sesión para agregar favoritos");
        return;
      }

      //  Animación del corazón
      Animated.sequence([
        Animated.spring(heartScale, {
          toValue: 1.3,
          friction: 3,
          useNativeDriver: true,
        }),
        Animated.spring(heartScale, {
          toValue: 1,
          friction: 3,
          useNativeDriver: true,
        }),
      ]).start();

      //  Vibración
      if (Platform.OS !== 'web') {
        Haptics.impactAsync(Haptics.ImpactFeedbackStyle.Medium);
      }

      const user = JSON.parse(userData);
      const favKey = `favorites_${user.user_id}`;
      const favData = await AsyncStorage.getItem(favKey);
      let favorites = favData ? JSON.parse(favData) : [];

      if (isFavorite) {
        favorites = favorites.filter(fav => fav.book_id !== book.book_id);
        setIsFavorite(false);
      } else {
        favorites.push(book);
        setIsFavorite(true);
      }

      await AsyncStorage.setItem(favKey, JSON.stringify(favorites));
      
      if (Platform.OS === 'web') {
        alert(isFavorite ? 'Eliminado de favoritos' : 'Agregado a favoritos');
      } else {
        Alert.alert(
          isFavorite ? "Eliminado" : "Agregado",
          isFavorite ? "Se quitó de tus favoritos" : "Se agregó a tus favoritos"
        );
      }
    } catch (error) {
      console.error("Error guardando favorito:", error);
    }
  };

const handleAddToCart = () => {
  if (book.stock_quantity === 0) {
    Alert.alert("Sin stock", "Este libro no está disponible");
    return;
  }

  // Animación del botón
  Animated.sequence([
    Animated.spring(buttonScale, {
      toValue: 0.9,
      friction: 3,
      useNativeDriver: true,
    }),
    Animated.spring(buttonScale, {
      toValue: 1,
      friction: 3,
      useNativeDriver: true,
    }),
  ]).start();

  //  ACTIVAR CONFETTI
  setShowConfetti(true);

  // Vibración de éxito
  if (Platform.OS !== 'web') {
    Haptics.notificationAsync(Haptics.NotificationFeedbackType.Success);
  }

  // Agregar al carrito
  for (let i = 0; i < quantity; i++) {
    addToCart(book);
  }

  // Mensaje de éxito
  if (Platform.OS === 'web') {
    alert(`${quantity} ${quantity === 1 ? 'libro agregado' : 'libros agregados'} al carrito 🎉`);
  } else {
    Alert.alert(
      "¡Agregado! 🎉",
      `${quantity} ${quantity === 1 ? 'libro' : 'libros'} ${quantity === 1 ? 'agregado' : 'agregados'} al carrito`
    );
  }

  // OCULTAR CONFETTI después de 2.5 segundos
  setTimeout(() => setShowConfetti(false), 2500);
};

  const getStatusConfig = (stock) => {
    if (stock === 0) {
      return { color: "#F44336", icon: "close-circle", text: "Agotado" };
    } else if (stock <= 5) {
      return { color: "#FF9800", icon: "alert-circle", text: `Solo ${stock} disponibles` };
    } else if (stock <= 20) {
      return { color: "#FFC107", icon: "checkmark-circle", text: `${stock} disponibles` };
    } else {
      return { color: "#4CAF50", icon: "checkmark-circle", text: "En stock" };
    }
  };

  if (loading) {
    return <LoadingAnimation message="Cargando detalles..." />;
  }

  if (!book) {
    return (
      <View style={[styles.errorContainer, darkMode && styles.errorContainerDark]}>
        <Ionicons name="alert-circle" size={64} color="#999" />
        <Text style={styles.errorText}>Libro no encontrado</Text>
        <TouchableOpacity style={styles.backButton} onPress={() => router.push('/')}>
          <Text style={styles.backButtonText}>Volver</Text>
        </TouchableOpacity>
      </View>
    );
  }

  const statusConfig = getStatusConfig(book.stock_quantity);

  // Estilos animados para la portada 3D
  const coverAnimatedStyle = {
    transform: [
      { perspective: 800 },
      { 
        rotateX: rotateX.interpolate({
          inputRange: [-10, 10],
          outputRange: ['-10deg', '10deg']
        })
      },
      { 
        rotateY: rotateY.interpolate({
          inputRange: [-10, 10],
          outputRange: ['-10deg', '10deg']
        })
      },
      { scale: coverScale },
    ],
  };

  return (
    <View style={[styles.container, darkMode && styles.containerDark]}>
      <StatusBar barStyle={darkMode ? "light-content" : "dark-content"} />

      {/* Header flotante */}
      <Animated.View style={[styles.header, darkMode && styles.headerDark]}>
        <TouchableOpacity style={styles.headerButton} onPress={() => router.back()}>
          <Ionicons name="arrow-back" size={24} color={darkMode ? "#000000ff" : "#000"} />
        </TouchableOpacity>

        <Animated.View style={{ transform: [{ scale: heartScale }] }}>
          <TouchableOpacity style={styles.headerButton} onPress={toggleFavorite}>
            <Ionicons 
              name={isFavorite ? "heart" : "heart-outline"} 
              size={24} 
              color={isFavorite ? "#F44336" : (darkMode ? "#000000ff" : "#000")} 
            />
          </TouchableOpacity>
        </Animated.View>
      </Animated.View>

      <Animated.ScrollView 
        showsVerticalScrollIndicator={false}
        onScroll={Animated.event(
          [{ nativeEvent: { contentOffset: { y: scrollY } } }],
          { useNativeDriver: true }
        )}
        scrollEventThrottle={16}
      >
        {/* Imagen del libro con parallax y efecto 3D */}
        <Animated.View 
          style={[
            styles.imageSection,
            {
              transform: [
                { translateY: imageTranslateY },
                { scale: imageScale }
              ]
            }
          ]}
        >
      <Animated.View
        {...coverPanResponder.panHandlers}
        style={[
          styles.bookImageWrapper,
          coverAnimatedStyle,
          { 
            opacity: fadeAnim, 
            transform: [...coverAnimatedStyle.transform, { scale: scaleAnim }] 
          }
        ]}
      >
        {book.cover_image ? (
          <View style={styles.bookWithPages}>
            {/*  Páginas del libro con animación de apertura */}
            <Animated.View 
              style={[
                styles.bookPages,
                {
                  transform: [
                    {
                      translateX: bookOpen.interpolate({
                        inputRange: [0, 1],
                        outputRange: [0, -15], // Se mueven a la izquierda
                      })
                    }
                  ]
                }
              ]}
            >
              <Animated.View 
                style={[
                  styles.page, 
                  styles.page1,
                  {
                    opacity: bookOpen.interpolate({
                      inputRange: [0, 1],
                      outputRange: [1, 0.3], // Se desvanece al abrir
                    })
                  }
                ]} 
              />
              <Animated.View 
                style={[
                  styles.page, 
                  styles.page2,
                  {
                    opacity: bookOpen.interpolate({
                      inputRange: [0, 1],
                      outputRange: [1, 0.5],
                    })
                  }
                ]} 
              />
              <Animated.View 
                style={[
                  styles.page, 
                  styles.page3,
                  {
                    opacity: bookOpen.interpolate({
                      inputRange: [0, 1],
                      outputRange: [1, 0.7],
                    })
                  }
                ]} 
              />
              
              {/*  Páginas interiores que se ven al abrir */}
              <Animated.View
                style={[
                  styles.innerPages,
                  {
                    opacity: bookOpen,
                    transform: [
                      {
                        translateX: bookOpen.interpolate({
                          inputRange: [0, 1],
                          outputRange: [0, -10],
                        })
                      }
                    ]
                  }
                ]}
              >
                {/* Simulación de texto en las páginas */}
                <View style={styles.pageLines}>
                  <View style={styles.textLine} />
                  <View style={styles.textLine} />
                  <View style={styles.textLine} />
                  <View style={styles.textLine} />
                  <View style={styles.textLine} />
                </View>
              </Animated.View>
            </Animated.View>
            
            {/*  Portada principal con animación de apertura */}
            <Animated.View 
              style={[
                styles.bookFrontCover,
                {
                  transform: [
                    {
                      rotateY: bookOpen.interpolate({
                        inputRange: [0, 1],
                        outputRange: ['0deg', '-15deg'], // Rota ligeramente
                      })
                    },
                    { perspective: 1000 }
                  ]
                }
              ]}
            >
              <Image
                source={{ uri: getImageUrl(book.cover_image) }}
                style={styles.bookImage}
                resizeMode="cover"
              />
              
              {/*  Efecto de brillo */}
              <Animated.View 
                style={[
                  styles.coverShineOverlay,
                  {
                    opacity: shine.interpolate({
                      inputRange: [0, 1],
                      outputRange: [0, 0.3]
                    }),
                  }
                ]}
              >
                <Animated.View 
                  style={{
                    position: 'absolute',
                    top: -50,
                    bottom: -50,
                    width: 100,
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    shadowColor: '#fff',
                    shadowOffset: { width: 0, height: 0 },
                    shadowOpacity: 1,
                    shadowRadius: 30,
                    transform: [
                      { rotate: '15deg' },
                      {
                        translateX: rotateY.interpolate({
                          inputRange: [-10, 10],
                          outputRange: [-150, 150]
                        })
                      }
                    ]
                  }}
                />
              </Animated.View>
              
              {/*  Borde dorado elegante */}
              <View style={styles.bookBorder} />
            </Animated.View>
          </View>
        ) : (
          <View style={styles.placeholderImage}>
            <Ionicons name="book" size={80} color="#999" />
          </View>
        )}
      </Animated.View>
        </Animated.View>

        {/* Información del libro */}
        <Animated.View 
          style={[
            styles.infoSection, 
            darkMode && styles.infoSectionDark,
            { opacity: fadeAnim }
          ]}
        >
          {/* Badge de disponibilidad */}
          <View style={[styles.statusBadge, { backgroundColor: statusConfig.color }]}>
            <Ionicons name={statusConfig.icon} size={16} color="#fff" />
            <Text style={styles.statusText}>{statusConfig.text}</Text>
          </View>

          {/* Título */}
          <Text style={[styles.title, darkMode && styles.titleDark]}>
            {book.title}
          </Text>

          {/* Autor */}
          <View style={styles.authorRow}>
            <Ionicons name="person" size={18} color="#ffa3c2" />
            <Text style={[styles.author, darkMode && styles.authorDark]}>
              {book.authors || "Autor desconocido"}
            </Text>
          </View>

          {/* Categoría */}
          {book.category_name && (
            <View style={styles.categoryRow}>
              <Ionicons name="bookmark" size={18} color="#ffa3c2" />
              <Text style={[styles.category, darkMode && styles.categoryDark]}>
                {book.category_name}
              </Text>
            </View>
          )}

          {/* Precio */}
          <View style={styles.priceSection}>
            <Text style={[styles.priceLabel, darkMode && styles.priceLabelDark]}>
              Precio
            </Text>
            <Text style={styles.price}>${parseFloat(book.price).toFixed(2)}</Text>
          </View>

          {/* Descripción */}
          {book.description && (
            <View style={styles.descriptionSection}>
              <Text style={[styles.sectionTitle, darkMode && styles.sectionTitleDark]}>
                Descripción
              </Text>
              <Text style={[styles.description, darkMode && styles.descriptionDark]}>
                {book.description}
              </Text>
            </View>
          )}

          {/* Detalles adicionales */}
          <View style={styles.detailsSection}>
            <Text style={[styles.sectionTitle, darkMode && styles.sectionTitleDark]}>
              Detalles
            </Text>

            <View style={styles.detailRow}>
              <Text style={[styles.detailLabel, darkMode && styles.detailLabelDark]}>
                ISBN:
              </Text>
              <Text style={[styles.detailValue, darkMode && styles.detailValueDark]}>
                {book.isbn || "No disponible"}
              </Text>
            </View>

            <View style={styles.detailRow}>
              <Text style={[styles.detailLabel, darkMode && styles.detailLabelDark]}>
                Editorial:
              </Text>
              <Text style={[styles.detailValue, darkMode && styles.detailValueDark]}>
                {book.publisher || "No disponible"}
              </Text>
            </View>

            <View style={styles.detailRow}>
              <Text style={[styles.detailLabel, darkMode && styles.detailLabelDark]}>
                Año de publicación:
              </Text>
              <Text style={[styles.detailValue, darkMode && styles.detailValueDark]}>
                {book.publication_year || "No disponible"}
              </Text>
            </View>
          </View>
        </Animated.View>
      </Animated.ScrollView>

      {/* Footer con controles de cantidad y botón de compra */}
      <View style={[styles.footer, darkMode && styles.footerDark]}>
        <View style={styles.quantitySection}>
          <Text style={[styles.quantityLabel, darkMode && styles.quantityLabelDark]}>
            Cantidad
          </Text>
          <View style={styles.quantityControls}>
            <TouchableOpacity
              style={[styles.quantityButton, darkMode && styles.quantityButtonDark]}
              onPress={() => setQuantity(Math.max(1, quantity - 1))}
            >
              <Ionicons name="remove" size={20} color={darkMode ? "#fff" : "#000"} />
            </TouchableOpacity>

            <Text style={[styles.quantityValue, darkMode && styles.quantityValueDark]}>
              {quantity}
            </Text>

            <TouchableOpacity
              style={[styles.quantityButton, darkMode && styles.quantityButtonDark]}
              onPress={() => setQuantity(Math.min(book.stock_quantity, quantity + 1))}
              disabled={quantity >= book.stock_quantity}
            >
              <Ionicons 
                name="add" 
                size={20} 
                color={quantity >= book.stock_quantity ? "#999" : (darkMode ? "#fff" : "#000")} 
              />
            </TouchableOpacity>
          </View>
        </View>

        <Animated.View style={{ transform: [{ scale: buttonScale }] }}>
          <TouchableOpacity
            style={[
              styles.addToCartButton,
              book.stock_quantity === 0 && styles.addToCartButtonDisabled
            ]}
            onPress={handleAddToCart}
            disabled={book.stock_quantity === 0}
          >
            <Ionicons name="cart" size={20} color="#fff" style={{ marginRight: 8 }} />
            <Text style={styles.addToCartText}>
              {book.stock_quantity === 0 ? "Agotado" : "Agregar al carrito"}
            </Text>
          </TouchableOpacity>
        </Animated.View>
      </View>
        {/* 🎉 Confetti */}
              {showConfetti && (
                <ConfettiCannon
                  ref={confettiRef}
                  count={80}
                  origin={{ x: SCREEN_WIDTH / 2, y: 0 }}
                  autoStart={true}
                  fadeOut={true}
                  fallSpeed={2800}
                  colors={['#ffa3c2', '#ff8fb3', '#4CAF50', '#FFD700', '#FF6B6B', '#4ECDC4']}
                  explosionSpeed={400}
                />
              )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#F5F5F5",
  },
  containerDark: {
    backgroundColor: "#121212",
  },
  header: {
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    flexDirection: "row",
    justifyContent: "space-between",
    paddingTop: 20,
    paddingHorizontal: 16,
    paddingBottom: 16,
    zIndex: 10,
    backgroundColor: "rgba(255, 255, 255, 0.95)",
  },
  headerDark: {
    backgroundColor: "rgba(18, 18, 18, 0.95)",
  },
  headerButton: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: "rgba(255, 255, 255, 0.9)",
    justifyContent: "center",
    alignItems: "center",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  imageSection: {
    height: 550,
    backgroundColor: "#fff",
    justifyContent: "center",
    alignItems: "center",
    paddingTop: 20,
  },
  bookImage: {
    width: SCREEN_WIDTH * 0.6,
    height: 350,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.2,
    shadowRadius: 12,
    elevation: 8,
  },
  placeholderImage: {
    width: SCREEN_WIDTH * 0.6,
    height: 350,
    backgroundColor: "#f0f0f0",
    justifyContent: "center",
    alignItems: "center",
    borderRadius: 8,
  },
  infoSection: {
    backgroundColor: "#fff",
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    marginTop: -24,
    padding: 24,
    paddingBottom: 120,
  },
  infoSectionDark: {
    backgroundColor: "#1A1A1A",
  },
  statusBadge: {
    flexDirection: "row",
    alignItems: "center",
    alignSelf: "flex-start",
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 12,
    marginBottom: 16,
    gap: 6,
  },
  statusText: {
    color: "#fff",
    fontSize: 12,
    fontWeight: "600",
  },
  title: {
    fontSize: 28,
    fontWeight: "bold",
    color: "#1A1A1A",
    marginBottom: 12,
    lineHeight: 36,
  },
  titleDark: {
    color: "#fff",
  },
  authorRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 8,
    gap: 8,
  },
  author: {
    fontSize: 16,
    color: "#666",
  },
  authorDark: {
    color: "#aaa",
  },
  categoryRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 20,
    gap: 8,
  },
  category: {
    fontSize: 14,
    color: "#666",
  },
  categoryDark: {
    color: "#aaa",
  },
  priceSection: {
    marginBottom: 24,
  },
  priceLabel: {
    fontSize: 14,
    color: "#999",
    marginBottom: 4,
  },
  priceLabelDark: {
    color: "#666",
  },
  price: {
    fontSize: 36,
    fontWeight: "bold",
    color: "#4CAF50",
  },
  descriptionSection: {
    marginBottom: 24,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: "600",
    color: "#1A1A1A",
    marginBottom: 12,
  },
  sectionTitleDark: {
    color: "#fff",
  },
  description: {
    fontSize: 15,
    color: "#666",
    lineHeight: 24,
  },
  descriptionDark: {
    color: "#aaa",
  },
  detailsSection: {
    marginBottom: 24,
  },
  detailRow: {
    flexDirection: "row",
    justifyContent: "space-between",
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#f0f0f0",
  },
  detailLabel: {
    fontSize: 14,
    color: "#999",
  },
  detailLabelDark: {
    color: "#666",
  },
  detailValue: {
    fontSize: 14,
    color: "#333",
    fontWeight: "500",
  },
  detailValueDark: {
    color: "#ccc",
  },
  footer: {
    position: "absolute",
    bottom: 0,
    left: 0,
    right: 0,
    backgroundColor: "#fff",
    padding: 16,
    paddingBottom: 32,
    borderTopWidth: 1,
    borderTopColor: "#E0E0E0",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: -2 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 10,
  },
  footerDark: {
    backgroundColor: "#1A1A1A",
    borderTopColor: "#333",
  },
  quantitySection: {
    marginBottom: 12,
  },
  quantityLabel: {
    fontSize: 14,
    color: "#666",
    marginBottom: 8,
  },
  quantityLabelDark: {
    color: "#aaa",
  },
  quantityControls: {
    flexDirection: "row",
    alignItems: "center",
    gap: 16,
  },
  quantityButton: {
    width: 36,
    height: 36,
    borderRadius: 18,
    backgroundColor: "#f0f0f0",
    justifyContent: "center",
    alignItems: "center",
  },
  quantityButtonDark: {
    backgroundColor: "#2A2A2A",
  },
  quantityValue: {
    fontSize: 18,
    fontWeight: "600",
    color: "#000",
    minWidth: 40,
    textAlign: "center",
  },
  quantityValueDark: {
    color: "#fff",
  },
  addToCartButton: {
    flexDirection: "row",
    backgroundColor: "#ffa3c2",
    paddingVertical: 16,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    shadowColor: "#ffa3c2",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 5,
  },
  addToCartButtonDisabled: {
    backgroundColor: "#999",
    opacity: 0.5,
  },
  addToCartText: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "600",
  },
  errorContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#F5F5F5",
  },
  errorContainerDark: {
    backgroundColor: "#121212",
  },
  errorText: {
    fontSize: 18,
    color: "#999",
    marginTop: 16,
    marginBottom: 24,
  },
  backButton: {
    backgroundColor: "#ffa3c2",
    paddingHorizontal: 24,
    paddingVertical: 12,
    borderRadius: 8,
  },
  backButtonText: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "600",
  },
  //  NUEVOS ESTILOS 
bookImageWrapper: {
  position: 'relative',
},
coverShineOverlay: {
  position: 'absolute',
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  overflow: 'hidden',
  pointerEvents: 'none',
  borderRadius: 8,
},
coverShineGradient: {
  width: '200%',
  height: '100%',
  backgroundColor: 'transparent',
  borderLeftWidth: 50,
  borderLeftColor: 'rgba(255, 255, 255, 0.7)',
  borderRightWidth: 50,
  borderRightColor: 'transparent',
  transform: [{ rotate: '20deg' }],
  marginLeft: -40,
},
// ESTILOS PARA LIBRO 3D CON PÁGINAS Y ANIMACIÓN

bookWithPages: {
  position: 'relative',
  width: SCREEN_WIDTH * 0.6,
  height: 350,
},

bookPages: {
  position: 'absolute',
  top: 0,
  right: 0,
  width: '100%',
  height: '100%',
},

page: {
  position: 'absolute',
  width: '98%',
  height: '99%',
  backgroundColor: '#f9f7f0', // Color crema tipo página
  borderRadius: 6,
  shadowColor: '#000',
  shadowOffset: { width: 3, height: 3 },
  shadowOpacity: 0.25,
  shadowRadius: 5,
  elevation: 4,
  borderWidth: 1,
  borderColor: '#e8e4d8',
  borderRightWidth: 2,
  borderRightColor: '#d4ceb8',
},

page1: {
  top: 8,
  right: 8,
  zIndex: 1,
  backgroundColor: '#faf8f3',
},

page2: {
  top: 4,
  right: 4,
  zIndex: 2,
  backgroundColor: '#f9f7f1',
},

page3: {
  top: 0,
  right: 0,
  zIndex: 3,
  backgroundColor: '#f9f7f0',
},

// Páginas interiores visibles al abrir
innerPages: {
  position: 'absolute',
  width: '95%',
  height: '97%',
  top: '1.5%',
  right: '2.5%',
  backgroundColor: '#fdfbf7',
  borderRadius: 6,
  zIndex: 4,
  padding: 20,
  shadowColor: '#000',
  shadowOffset: { width: -2, height: 0 },
  shadowOpacity: 0.15,
  shadowRadius: 4,
},

pageLines: {
  flex: 1,
  justifyContent: 'space-evenly',
  paddingVertical: 40,
},

textLine: {
  height: 2,
  backgroundColor: '#e0ddd0',
  marginBottom: 15,
  borderRadius: 1,
},

bookFrontCover: {
  position: 'relative',
  width: '100%',
  height: '100%',
  zIndex: 10,
  borderRadius: 8,
  overflow: 'hidden',
  shadowColor: '#000',
  shadowOffset: { width: 6, height: 10 },
  shadowOpacity: 0.45,
  shadowRadius: 20,
  elevation: 15,
  backgroundColor: '#fff',
},

// Borde decorativo dorado
bookBorder: {
  position: 'absolute',
  top: 12,
  left: 12,
  right: 12,
  bottom: 12,
  borderWidth: 2,
  borderColor: 'rgba(218, 165, 32, 0.3)', // Dorado sutil
  borderRadius: 6,
  pointerEvents: 'none',
},

bookImage: {
  width: '100%',
  height: '100%',
},
});