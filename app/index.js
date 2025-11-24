import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as Haptics from 'expo-haptics';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import {
  Alert,
  Animated,
  Dimensions,
  FlatList,
  Image,
  Modal,
  PanResponder,
  Platform,
  RefreshControl,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from "react-native";
import ConfettiCannon from 'react-native-confetti-cannon';
import 'react-native-gesture-handler';
import LoadingAnimation from '../components/LoadingAnimation';
import { MorphingSearchBar } from '../components/MorphingSearchBar';
import { API_ENDPOINTS, apiFetch, getImageUrl } from '../config/api';
import { useCart } from '../contexts/CartContext';
import { useTheme } from '../contexts/ThemeContext';
import DrawerMenu from "../screens/DrawerMenu";

// Obtenemos el ancho de la pantalla para usarlo en el carrusel
const { width: SCREEN_WIDTH } = Dimensions.get('window');


// Componente separado para cada tarjeta de libro con efecto 3D
const BookCard3D = ({ item, index, darkMode, onPress, onAddToCart, onToggleFavorite, isFavorite, getStatusConfig, getCardSize }) => {
  // Animaciones para el efecto 3D
  const rotateX = useRef(new Animated.Value(0)).current;
  const rotateY = useRef(new Animated.Value(0)).current;
  const scale = useRef(new Animated.Value(1)).current;
  const shine = useRef(new Animated.Value(0)).current; //  Brillo
  const cardDimensions = useRef({ width: 0, height: 0 });

  // Animaciones de entrada
  const fadeAnim = useRef(new Animated.Value(0)).current;
  const slideAnim = useRef(new Animated.Value(50)).current;

  const statusConfig = getStatusConfig(item.stock_quantity);
  const isBookFavorite = isFavorite(item.book_id);
  const cardSize = getCardSize(index);

  // Animación de entrada al montar el componente
  useEffect(() => {
    Animated.parallel([
      Animated.timing(fadeAnim, {
        toValue: 1,
        duration: 600,
        delay: (index % 10) * 80, // Solo los primeros 10 tienen delay
        useNativeDriver: true,
      }),
      Animated.spring(slideAnim, {
        toValue: 0,
        delay: (index % 10) * 80,
        friction: 8,
        tension: 40,
        useNativeDriver: true,
      }),
    ]).start();
  }, []);

  // PanResponder para capturar los gestos táctiles
  const panResponder = useRef(
    PanResponder.create({
      onStartShouldSetPanResponder: () => true,
      onMoveShouldSetPanResponder: () => true,
      
      onPanResponderGrant: (evt) => {
        const { locationX, locationY } = evt.nativeEvent;
        updateRotation(locationX, locationY);
      },
      
      onPanResponderMove: (evt) => {
        const { locationX, locationY } = evt.nativeEvent;
        updateRotation(locationX, locationY);
      },
      
      onPanResponderRelease: () => {
        resetRotation();
      },
      
      onPanResponderTerminate: () => {
        resetRotation();
      },
    })
  ).current;

  const updateRotation = (x, y) => {
    if (!cardDimensions.current.width) return;

    const { width, height } = cardDimensions.current;
    
    const normalizedX = ((x / width) - 0.5) * 2;
    const normalizedY = ((y / height) - 0.5) * 2;

    Animated.parallel([
      Animated.spring(rotateX, {
        toValue: -normalizedY * 15,
        useNativeDriver: true,
        friction: 6,
        tension: 60,
      }),
      Animated.spring(rotateY, {
        toValue: normalizedX * 15,
        useNativeDriver: true,
        friction: 6,
        tension: 60,
      }),
      Animated.spring(scale, {
        toValue: 1.08,
        useNativeDriver: true,
        friction: 6,
        tension: 60,
      }),
      // Activar el brillo
      Animated.timing(shine, {
        toValue: 1,
        duration: 200,
        useNativeDriver: true,
      }),
    ]).start();
  };

  const resetRotation = () => {
    Animated.parallel([
      Animated.spring(rotateX, {
        toValue: 0,
        useNativeDriver: true,
        friction: 6,
        tension: 60,
      }),
      Animated.spring(rotateY, {
        toValue: 0,
        useNativeDriver: true,
        friction: 6,
        tension: 60,
      }),
      Animated.spring(scale, {
        toValue: 1,
        useNativeDriver: true,
        friction: 6,
        tension: 60,
      }),
      // Ocultar el brillo
      Animated.timing(shine, {
        toValue: 0,
        duration: 300,
        useNativeDriver: true,
      }),
    ]).start();
  };

  const onLayout = (event) => {
    const { width, height } = event.nativeEvent.layout;
    cardDimensions.current = { width, height };
  };

  //  Estilo animado mejorado con sombra dinámica
//  Estilo animado mejorado (SIN shadowOffset animado)
const animatedStyle = {
  opacity: fadeAnim,
  transform: [
    { translateY: slideAnim },
    { perspective: 800 },
    { 
      rotateX: rotateX.interpolate({
        inputRange: [-20, 20],
        outputRange: ['-20deg', '20deg']
      })
    },
    { 
      rotateY: rotateY.interpolate({
        inputRange: [-20, 20],
        outputRange: ['-20deg', '10deg']
      })
    },
    { scale: scale },
  ],
};

  return (
    <Animated.View 
      style={[
        styles.bookCard,
        darkMode && styles.bookCardDark,
        cardSize === 'large' && styles.bookCardLarge,
        animatedStyle,
      ]}
      onLayout={onLayout}
      {...panResponder.panHandlers}
    >
      <TouchableOpacity 
        activeOpacity={0.9}
        onPress={() => onPress(item.book_id)}
        style={styles.bookCardInner}
      >
        <View style={[
          styles.bookImageContainer,
          cardSize === 'large' && styles.bookImageContainerLarge
        ]}>
          {item.cover_image ? (
            <Image 
              source={{ uri: getImageUrl(item.cover_image) }}
              style={styles.bookImage}
              resizeMode="cover"
            />
          ) : (
            <View style={styles.bookImagePlaceholder}>
              <Ionicons name="book" size={40} color="#999" />
              <Text style={styles.noImageText}>Sin portada</Text>
            </View>
          )}
          
          <View style={styles.imageGradient} />

          {/*  Capa de brillo animada */}
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

          {/* Badge de disponibilidad */}
          <View style={[styles.statusBadge, { backgroundColor: statusConfig.color }]}>
            <Ionicons name={statusConfig.icon} size={14} color="#fff" />
            <Text style={styles.statusText}>{statusConfig.text}</Text>
          </View>

          {/*  Badge de Popular/Bestseller */}
          {item.stock_quantity > 0 && item.stock_quantity <= 5 && (
            <View style={styles.bestsellerBadge}>
              <Ionicons name="flame" size={12} color="#fff" />
              <Text style={styles.bestsellerText}>¡Popular!</Text>
            </View>
          )}

          {/*  Badge de Nuevo */}
          {item.category_name === "Novedades" && (
            <View style={styles.newBadge}>
              <Ionicons name="sparkles" size={12} color="#fff" />
              <Text style={styles.newText}>Nuevo</Text>
            </View>
          )}

          {/* Botón de favoritos */}
          <TouchableOpacity 
            style={[
              styles.favoriteButton,
              isBookFavorite && styles.favoriteButtonActive
            ]}
            onPress={(e) => {
              e.stopPropagation();
              onToggleFavorite(item);
            }}
          >
            <Ionicons 
              name={isBookFavorite ? "heart" : "heart-outline"} 
              size={22} 
              color={isBookFavorite ? "#F44336" : "#fff"} 
            />
          </TouchableOpacity>
        </View>

        <View style={styles.bookInfo}>
          <Text style={[styles.bookTitle, darkMode && styles.bookTitleDark]} numberOfLines={2}>
            {item.title}
          </Text>

          <View style={styles.authorRow}>
            <Ionicons name="person-outline" size={14} color={darkMode ? "#999" : "#666"} />
            <Text style={[styles.bookAuthor, darkMode && styles.bookAuthorDark]} numberOfLines={1}>
              {item.authors || "Autor desconocido"}
            </Text>
          </View>

          <View style={styles.bookFooter}>
            <View>
              <Text style={[styles.priceLabel, darkMode && styles.priceLabelDark]}>Precio</Text>
              <Text style={styles.bookPrice}>
                ${parseFloat(item.price).toFixed(2)}
              </Text>
            </View>

            <TouchableOpacity 
              style={[
                styles.addButton,
                item.stock_quantity === 0 && styles.addButtonDisabled
              ]}
              onPress={(e) => {
                e.stopPropagation();
                onAddToCart(item);
              }}
              disabled={item.stock_quantity === 0}
            >
              <Ionicons 
                name={item.stock_quantity === 0 ? "close" : "add"} 
                size={20} 
                color="#fff" 
              />
            </TouchableOpacity>
          </View>
        </View>
      </TouchableOpacity>
    </Animated.View>
  );
};


export default function HomeScreen() {
  const router = useRouter();
  const params = useLocalSearchParams(); 
  const { addToCart, getCartCount } = useCart();
  const { darkMode } = useTheme();


  // Estados para manejar los datos y la interfaz
  const [drawerVisible, setDrawerVisible] = useState(false); // Para abrir/cerrar el menú lateral
  const [books, setBooks] = useState([]); // Todos los libros que vienen de la API
  const [filteredBooks, setFilteredBooks] = useState([]); // Libros después de aplicar filtros
  const [categories, setCategories] = useState(["Todos"]); // Categorías disponibles
  const [loading, setLoading] = useState(true); // Para mostrar la carga
  const [refreshing, setRefreshing] = useState(false); // Para el gesto de "pull to refresh"
  const [searchQuery, setSearchQuery] = useState(""); // Lo que el usuario escribe en la búsqueda
  const [selectedCategory, setSelectedCategory] = useState("Todos"); // Categoría seleccionada
  const [isLoggedIn, setIsLoggedIn] = useState(false); // Si el usuario tiene sesión activa
  const [favorites, setFavorites] = useState([]); // Lista de favoritos del usuario
  const [favKey, setFavKey] = useState(null); // Clave para guardar favoritos en AsyncStorage
  const [showConfetti, setShowConfetti] = useState(false);// Estados para el confetti
  const confettiRef = useRef(null);//
  const [searchModalVisible, setSearchModalVisible] = useState(false);

  // Referencias y valores para animaciones y el carrusel
  const scrollY = useRef(new Animated.Value(0)).current; // Para animar el header al hacer scroll
  const [currentSlide, setCurrentSlide] = useState(0); // Slide actual del carrusel

  // Referencias específicas del carrusel para controlar el auto-scroll
  const carouselScrollRef = useRef(null); // Referencia al FlatList del carrusel
  const isUserScrolling = useRef(false); // Para saber si el usuario está tocando el carrusel
  const scrollTimer = useRef(null); // Timer para reactivar el auto-scroll
  const autoScrollInterval = useRef(null); // Intervalo del auto-scroll automático
  const currentSlideRef = useRef(0); // Referencia al índice actual (evita problemas con closures)



  // useMemo calcula los libros destacados solo cuando cambia el array de books
  // Esto optimiza el rendimiento porque no recalcula en cada render
  const featuredBooks = useMemo(() => {
    // Primero buscamos libros de "Novedades" o con mucho stock
    let featured = books.filter(book => 
      book.category_name === "Novedades" || book.stock_quantity > 20
    );
    
    // Si no hay suficientes, tomamos cualquier libro con stock
    if (featured.length < 5) {
      featured = books.filter(book => book.stock_quantity > 0).slice(0, 5);
    }
    
    // Limitamos a 5 libros destacados máximo
    featured = featured.slice(0, 5);
    
    console.log('📚 Libros destacados:', featured.length, featured.map(b => b.title));
    return featured;
  }, [books]);

  // Este useEffect maneja el auto-scroll del carrusel
  useEffect(() => {
    // Si hay 1 o menos libros, no tiene sentido hacer auto-scroll
    if (featuredBooks.length <= 1) {
      if (autoScrollInterval.current) {
        clearInterval(autoScrollInterval.current);
      }
      return;
    }

    // Limpiamos cualquier intervalo anterior
    if (autoScrollInterval.current) {
      clearInterval(autoScrollInterval.current);
    }

    // Creamos un nuevo intervalo que cambia de slide cada 5 segundos
    autoScrollInterval.current = setInterval(() => {
      // Si el usuario está tocando, no hacemos nada
      if (isUserScrolling.current) return;

      // Calculamos el siguiente índice (cuando llega al final, vuelve a 0)
      const next = (currentSlideRef.current + 1) % featuredBooks.length;

      try {
        // Intentamos hacer scroll al siguiente índice
        carouselScrollRef.current?.scrollToIndex?.({ index: next, animated: true });
      } catch (err) {
        // Si falla scrollToIndex, usamos scrollToOffset como alternativa
        carouselScrollRef.current?.scrollToOffset?.({
          offset: next * SCREEN_WIDTH,
          animated: true,
        });
      }

      // Actualizamos el índice actual
      currentSlideRef.current = next;
      setCurrentSlide(next);
    }, 5000); // Cada 5 segundos

    // Limpieza cuando el componente se desmonta o cambian los libros destacados
    return () => {
      if (autoScrollInterval.current) {
        clearInterval(autoScrollInterval.current);
      }
    };
  }, [featuredBooks]);

  // Animaciones para el header que se hace más pequeño al hacer scroll
  const headerHeight = scrollY.interpolate({
    inputRange: [0, 100], // Rango de scroll
    outputRange: [80, 60], // De 80px a 60px
    extrapolate: 'clamp', // No pasarse de estos valores
  });

  const headerOpacity = scrollY.interpolate({
    inputRange: [0, 50],
    outputRange: [1, 0.98], // Casi imperceptible, solo un toque de transparencia
    extrapolate: 'clamp',
  });

  // Si viene una categoría por parámetro de navegación, la seleccionamos
  useEffect(() => {
    if (params.category) {
      setSelectedCategory(params.category);
      setSearchQuery(""); // Limpiamos la búsqueda
    }
  }, [params.category]);

  // Al montar el componente, verificamos si hay sesión activa
  useEffect(() => {
    checkAuth();
    loadUser();
  }, []);

  // Verifica si hay un token guardado (usuario con sesión activa)
  const checkAuth = async () => {
    try {
      const token = await AsyncStorage.getItem('userToken');
      setIsLoggedIn(!!token); // Convertimos a booleano
    } catch (error) {
      console.error("Error verificando sesión:", error);
    }
  };
  
  // Carga los datos del usuario y sus favoritos
  const loadUser = async () => {
    try {
      const raw = await AsyncStorage.getItem('userData');
      if (!raw) {
        setFavKey(null);
        setFavorites([]);
        return;
      }
      const user = JSON.parse(raw);
      // Creamos una clave única para los favoritos de este usuario
      const key = `favorites_${user.user_id}`;
      setFavKey(key);
      loadFavorites(key);
    } catch (error) {
      console.error("Error leyendo información del usuario:", error);
    }
  };

  // Carga los favoritos desde AsyncStorage
  const loadFavorites = async (key) => {
    try {
      const favData = await AsyncStorage.getItem(key);
      setFavorites(favData ? JSON.parse(favData) : []);
    } catch (error) {
      console.error("Error cargando favoritos:", error);
    }
  };

  // useCallback memoriza la función para que no se recree en cada render
  // Verifica si un libro está en favoritos
  const isFavorite = useCallback((bookId) => {
    return favorites.some(fav => fav.book_id === bookId);
  }, [favorites]);

  // Agrega o quita un libro de favoritos
  const toggleFavorite = useCallback(async (book) => {
    try {
      // Si no hay clave de favoritos, el usuario no ha iniciado sesión
      if (!favKey) {
        Alert.alert("Inicia sesión", "Necesitas iniciar sesión para agregar favoritos");
        return;
      }

      const isCurrentlyFavorite = favorites.some(fav => fav.book_id === book.book_id);
      let updatedFavorites;

      if (isCurrentlyFavorite) {
        // Quitamos el libro de favoritos
        updatedFavorites = favorites.filter(fav => fav.book_id !== book.book_id);
        if (Platform.OS === 'web') {
          alert('Eliminado de favoritos');
        } else {
          Alert.alert("Eliminado", "Se quitó de tus favoritos");
        }
      } else {
        // Agregamos el libro a favoritos
        updatedFavorites = [...favorites, book];
        if (Platform.OS === 'web') {
          alert('Agregado a favoritos');
        } else {
          Alert.alert("Agregado", "Se agregó a tus favoritos");
        }
      }
      
      // Actualizamos el estado y guardamos en AsyncStorage
      setFavorites(updatedFavorites);
      await AsyncStorage.setItem(favKey, JSON.stringify(updatedFavorites));
    } catch (error) {
      console.error("Error guardando favorito:", error);
      Alert.alert("Error", "No se pudo guardar el favorito");
    }
  }, [favorites, favKey]);

  // Obtiene todos los libros de la API
  const fetchBooks = useCallback(async () => {
    try {
      const data = await apiFetch(API_ENDPOINTS.books);
      if (!Array.isArray(data)) {
        throw new Error('Formato de datos inválido');
      }
      setBooks(data);
      setLoading(false);
      setRefreshing(false);
    } catch (error) {
      console.error("Error al obtener libros:", error);
      setLoading(false);
      setRefreshing(false);
      Alert.alert(
        "Error de conexión",
        "No se pudieron cargar los libros. Verifica tu conexión.",
        [
          { text: "Reintentar", onPress: () => fetchBooks() },
          { text: "Cancelar", style: "cancel" }
        ]
      );
    }
  }, []);

  // Obtiene las categorías disponibles
  const fetchCategories = useCallback(async () => {
    try {
      const data = await apiFetch(API_ENDPOINTS.categories);
      const categoryNames = data.map(cat => cat.name);
      const limitedCategories = categoryNames.slice(0, 5);
      setCategories(['Todos', ...limitedCategories]);
    } catch (error) {
      console.error("Error al obtener categorías:", error);
    }
  }, []);

  // Al montar, traemos libros y categorías
  useEffect(() => {
    fetchBooks();
    fetchCategories();
  }, []);

  // Este useEffect filtra los libros según búsqueda y categoría
  useEffect(() => {
    // Primero quitamos los libros destacados para que no aparezcan duplicados
    const featuredIds = featuredBooks.map(fb => fb.book_id);
    let filtered = books.filter(book => !featuredIds.includes(book.book_id));

    // Filtramos por categoría si no es "Todos"
    if (selectedCategory !== "Todos") {
      filtered = filtered.filter(book => book.category_name === selectedCategory);
    }

    // Filtramos por búsqueda si hay texto
    if (searchQuery.trim() !== "") {
      const query = searchQuery.toLowerCase();
      filtered = filtered.filter(book =>
        book.title.toLowerCase().includes(query) ||
        (book.authors && book.authors.toLowerCase().includes(query))
      );
    }

    setFilteredBooks(filtered);
  }, [searchQuery, selectedCategory, books, featuredBooks]);

  // Función para el gesto de "jalar hacia abajo para actualizar"
  const onRefresh = useCallback(() => {
    setRefreshing(true);
    fetchBooks();
    fetchCategories();
  }, [fetchBooks, fetchCategories]);

  // Retorna la configuración visual según el stock disponible
  const getStatusConfig = useCallback((stock_quantity) => {
    if (stock_quantity === 0) {
      return { color: "#F44336", icon: "close-circle", text: "Agotado" };
    } else if (stock_quantity > 0 && stock_quantity <= 5) {
      return { color: "#FF9800", icon: "alert-circle", text: `Solo ${stock_quantity}` };
    } else if (stock_quantity > 5 && stock_quantity <= 20) {
      return { color: "#FFC107", icon: "checkmark-circle", text: `${stock_quantity} disponibles` };
    } else if (stock_quantity > 20) {
      return { color: "#4CAF50", icon: "checkmark-circle", text: "Disponible" };
    } else {
      return { color: "#b1b1b1ff", icon: "help-circle", text: "Sin info" };
    }
  }, []);

  // Maneja el clic en el icono de perfil
  const handleProfilePress = useCallback(() => {
    if (isLoggedIn) {
      router.push('/profile'); // Si hay sesión, va al perfil
    } else {
      router.push('/login'); // Si no, va al login
    }
  }, [isLoggedIn, router]);

  // Navega al carrito
  const handleCartPress = useCallback(() => {
    router.push('/cart');
  }, [router]);

  // Esta función ya no se usa porque ahora todas las tarjetas son del mismo tamaño
  const getCardSize = useCallback((index) => {
    const pattern = index % 6;
    return (pattern === 0 || pattern === 3) ? 'large' : 'small';
  }, []);

// Agrega un libro al carrito
const handleAddToCart = useCallback((item) => {
  if (item.stock_quantity > 0) {
    addToCart(item);
    
    // Activa el confetti
    setShowConfetti(true);
    
    // Vibración más satisfactoria
    if (Platform.OS !== 'web') {
      Haptics.notificationAsync(Haptics.NotificationFeedbackType.Success);
    }
    
    if (Platform.OS === 'web') {
      alert(`"${item.title}" agregado al carrito 🎉`);
    } else {
      Alert.alert("¡Agregado! 🎉", `"${item.title}" está en tu carrito`);
    }
    
    // Oculta confetti después de 2.5 segundos
    setTimeout(() => setShowConfetti(false), 2500);
  }
}, [addToCart]);

  // Renderiza el header superior con logo e iconos
  const renderStaticHeader = useCallback(() => (
    <Animated.View 
      style={[
        styles.staticHeader, 
        darkMode && styles.staticHeaderDark,
        {
          height: headerHeight,
          opacity: headerOpacity,
        }
      ]}
    >
      <View style={styles.logoContainer}>
        <Image
          source={require('../assets/images/logo_Gonvill_pink.png')}
          style={{ width: 100, height: 50 }}
          resizeMode="contain"
        />
      </View>

      <View style={styles.headerIcons}>
        {/* Icono del carrito con badge de cantidad */}
        <TouchableOpacity style={styles.iconButton} onPress={handleCartPress}>
          <Ionicons name="cart-outline" size={26} color={darkMode ? "#fff" : "#1A1A1A"} />
          <View style={styles.cartBadge}>
            <Text style={styles.cartBadgeText}>{getCartCount()}</Text>
          </View>
        </TouchableOpacity>

        {/* Icono de perfil que cambia si hay sesión */}
        <TouchableOpacity style={styles.iconButton} onPress={handleProfilePress}>
          <Ionicons 
            name={isLoggedIn ? "person" : "person-outline"} 
            size={26} 
            color={isLoggedIn ? "#ffa3c2" : (darkMode ? "#fff" : "#1A1A1A")} 
          />
        </TouchableOpacity>

        {/* Icono de menú hamburguesa */}
        <TouchableOpacity onPress={() => setDrawerVisible(true)}>
          <Ionicons name="menu" size={28} color={darkMode ? "#fff" : "#1A1A1A"} />
        </TouchableOpacity>
      </View>
    </Animated.View>
  ), [darkMode, headerHeight, headerOpacity, handleCartPress, handleProfilePress, getCartCount, isLoggedIn]);

  // Renderiza el carrusel de libros destacados
  const renderHeroCarousel = useCallback(() => {
    if (featuredBooks.length === 0) return null;

    // Maneja el clic en los indicadores (puntitos de abajo)
    const handleIndicatorPress = (idx) => {
      isUserScrolling.current = true;
      clearTimeout(scrollTimer.current);

      carouselScrollRef.current?.scrollToIndex({ index: idx, animated: true });
      currentSlideRef.current = idx;
      setCurrentSlide(idx);

      // Después de 4 segundos, reactivamos el auto-scroll
      scrollTimer.current = setTimeout(() => {
        isUserScrolling.current = false;
      }, 4000);
    };

    // Cuando el usuario empieza a tocar el carrusel
    const handleScrollBeginDrag = () => {
      isUserScrolling.current = true;
      if (scrollTimer.current) clearTimeout(scrollTimer.current);
    };

    // Cuando el usuario suelta el carrusel
    const handleScrollEndDrag = () => {
      scrollTimer.current = setTimeout(() => {
        isUserScrolling.current = false;
      }, 4000);
    };

    // Detecta cambios mientras se hace scroll
    const handleScroll = (e) => {
      const index = Math.round(e.nativeEvent.contentOffset.x / SCREEN_WIDTH);
      if (index !== currentSlideRef.current) {
        currentSlideRef.current = index;
        setCurrentSlide(index);
      }
    };

    // Cuando termina el momentum del scroll
    const handleMomentumEnd = (e) => {
      const index = Math.round(e.nativeEvent.contentOffset.x / SCREEN_WIDTH);
      currentSlideRef.current = index;
      setCurrentSlide(index);
    };

    // Si scrollToIndex falla, usamos scrollToOffset como fallback
    const handleScrollToIndexFailed = (info) => {
      const offset = info.index * SCREEN_WIDTH;
      carouselScrollRef.current?.scrollToOffset?.({ offset, animated: true });
    };

    return (
      <View style={styles.carouselContainer}>
        <FlatList
          key={"featured-carousel"} // Key fijo para evitar reinicios
          ref={carouselScrollRef}
          data={featuredBooks}
          horizontal // Scroll horizontal
          pagingEnabled // Se ajusta a cada pantalla completa
          showsHorizontalScrollIndicator={false}
          decelerationRate="fast"
          onScrollBeginDrag={handleScrollBeginDrag}
          onScrollEndDrag={handleScrollEndDrag}
          onMomentumScrollEnd={handleMomentumEnd}
          onScrollToIndexFailed={handleScrollToIndexFailed}
          // getItemLayout mejora el rendimiento al decirle el tamaño exacto
          getItemLayout={(data, index) => ({
            length: SCREEN_WIDTH,
            offset: SCREEN_WIDTH * index,
            index,
          })}
          keyExtractor={(item) => item.book_id?.toString() ?? Math.random().toString()}
          renderItem={({ item: book }) => (
            <TouchableOpacity
              key={book.book_id}
              style={styles.carouselSlide}
              activeOpacity={0.95}
              onPress={() => router.push(`/book/${book.book_id}`)}
            >
              {/* Imagen de fondo borrosa */}
              {book.cover_image ? (
                <Image
                  source={{ uri: getImageUrl(book.cover_image) }}
                  style={styles.carouselImage}
                  blurRadius={40} // Efecto blur
                />
              ) : (
                <View style={[styles.carouselImage, { backgroundColor: '#333' }]} />
              )}
              {/* Capa oscura encima */}
              <View style={styles.carouselGradient} />

              <View style={styles.carouselContent}>
                {/* Portada del libro nítida */}
                <View style={styles.carouselBookCover}>
                  {book.cover_image ? (
                    <Image
                      source={{ uri: getImageUrl(book.cover_image) }}
                      style={styles.carouselCoverImage}
                      resizeMode="cover"
                    />
                  ) : (
                    <View style={styles.bookImagePlaceholder}>
                      <Ionicons name="book" size={60} color="#999" />
                    </View>
                  )}
                </View>

                {/* Información del libro */}
                <View style={styles.carouselInfo}>
                  <View style={styles.featuredBadge}>
                    <Ionicons name="star" size={12} color="#fff" style={{ marginRight: 4 }} />
                    <Text style={styles.featuredBadgeText}>DESTACADO</Text>
                  </View>
                  <Text style={styles.carouselTitle} numberOfLines={2}>{book.title}</Text>
                  <Text style={styles.carouselAuthor} numberOfLines={1}>{book.authors || 'Autor desconocido'}</Text>
                  <View style={styles.carouselFooter}>
                    <Text style={styles.carouselPrice}>${parseFloat(book.price).toFixed(2)}</Text>
                    <TouchableOpacity
                      style={styles.carouselButton}
                      onPress={(e) => {
                        e.stopPropagation(); // Evita que se active el onPress del slide
                        handleAddToCart(book);
                      }}
                    >
                      <Ionicons name="cart" size={16} color="#fff" style={{ marginRight: 6 }} />
                      <Text style={styles.carouselButtonText}>Agregar</Text>
                    </TouchableOpacity>
                  </View>
                </View>
              </View>
            </TouchableOpacity>
          )}
        />

        {/* Indicadores (puntitos) del carrusel */}
        <View style={styles.carouselIndicators}>
          {featuredBooks.map((_, idx) => (
            <TouchableOpacity
              key={idx}
              style={[styles.indicator, idx === currentSlide ? styles.indicatorActive : null]}
              onPress={() => handleIndicatorPress(idx)}
            />
          ))}
        </View>
      </View>
    );
  }, [featuredBooks, handleAddToCart]);

  // Renderiza la barra de búsqueda y filtros de categorías
  const renderCategoryFilters = useCallback(() => (
    <View style={[styles.filtersContainer, darkMode && styles.filtersContainerDark]}>
      {categories.length > 0 && (
        <FlatList
          horizontal
          showsHorizontalScrollIndicator={false}
          data={categories}
          keyExtractor={(item) => item}
          contentContainerStyle={styles.categoriesContainer}
          renderItem={({ item }) => (
            <TouchableOpacity
              style={[
                styles.categoryChip,
                selectedCategory === item && styles.categoryChipActive,
                darkMode && styles.categoryChipDark,
                darkMode && selectedCategory === item && styles.categoryChipActiveDark,
              ]}
              onPress={() => setSelectedCategory(item)}
            >
              <Text
                style={[
                  styles.categoryText,
                  selectedCategory === item && styles.categoryTextActive,
                  darkMode && selectedCategory !== item && styles.categoryTextDark,
                ]}
              >
                {item}
              </Text>
            </TouchableOpacity>
          )}
          //Botón ver todas las categorías
          ListFooterComponent={() => (
          <TouchableOpacity
            style={[
              styles.categoryChip,
              styles.seeAllChip,
              darkMode && styles.categoryChipDark,
            ]}
            onPress={() => router.push('/categories')} // Navega a pantalla de categorías
          >
            <Ionicons name="grid-outline" size={16} color={darkMode ? "#ffa3c2" : "#ffa3c2"} />
            <Text
              style={[
                styles.categoryText,
                darkMode && styles.categoryTextDark,
                { marginLeft: 6 }
              ]}
            >
              Ver todas
            </Text>
          </TouchableOpacity>
        )}
        />
      )}
    </View>
  ), [selectedCategory, categories, darkMode]);

  // Renderiza cada tarjeta de libro
  const renderBook = useCallback(({ item, index }) => (
    <BookCard3D
      item={item}
      index={index}
      darkMode={darkMode}
      onPress={(bookId) => router.push(`/book/${bookId}`)}
      onAddToCart={handleAddToCart}
      onToggleFavorite={toggleFavorite}
      isFavorite={isFavorite}
      getStatusConfig={getStatusConfig}
      getCardSize={getCardSize}
    />
  ), [darkMode, handleAddToCart, toggleFavorite, isFavorite, getStatusConfig, getCardSize, router]);

  // Componente que va en el header del FlatList (carrusel + filtros)
  const ListHeaderComponent = useMemo(() => (
    <>
      {renderHeroCarousel()}
      {renderCategoryFilters()}
    </>
  ), [renderHeroCarousel, renderCategoryFilters]);

  // Componente que se muestra cuando no hay resultados
  const ListEmptyComponent = useCallback(() => (
    <View style={styles.emptyContainer}>
      <Ionicons name="search-outline" size={64} color={darkMode ? "#555" : "#ccc"} />
      <Text style={[styles.emptyText, darkMode && styles.emptyTextDark]}>
        No se encontraron libros
      </Text>
      <Text style={[styles.emptySubtext, darkMode && styles.emptySubtextDark]}>
        Intenta con otra búsqueda
      </Text>
    </View>
  ), [darkMode]);

  // Mientras está cargando
  if (loading) {
    return <LoadingAnimation message="Cargando libros..." />;
  }

  // Vista principal de la pantalla
  return (
    <View style={[styles.container, darkMode && styles.containerDark]}>
      <StatusBar 
        barStyle={darkMode ? "light-content" : "dark-content"} 
        backgroundColor={darkMode ? "#1A1A1A" : "#fff"} 
      />
      
      {renderStaticHeader()}
      
      {/* FlatList principal con todos los libros */}
      <Animated.FlatList
        data={filteredBooks}
        keyExtractor={(item) => item.book_id.toString()}
        renderItem={renderBook}
        ListHeaderComponent={ListHeaderComponent}
        ListEmptyComponent={ListEmptyComponent}
        contentContainerStyle={styles.listContent}
        numColumns={2} // Dos columnas
        key="book-list-2-columns" // Key fijo para evitar warnings al cambiar numColumns
        columnWrapperStyle={styles.columnWrapper}
        // Este Animated.event conecta el scroll con la variable scrollY para animar el header
        onScroll={Animated.event(
          [{ nativeEvent: { contentOffset: { y: scrollY } } }],
          { useNativeDriver: false } // false porque animamos height que no soporta native driver
        )}
        scrollEventThrottle={16} // Actualiza cada 16ms (60fps)
        // RefreshControl es el gesto de "jalar hacia abajo para recargar"
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            colors={["#ffa3c2"]}
            tintColor="#ffa3c2"
          />
        }
        // Optimizaciones de rendimiento
        removeClippedSubviews={true} // Remueve vistas fuera de pantalla del DOM
        maxToRenderPerBatch={10} // Renderiza 10 elementos por lote
        updateCellsBatchingPeriod={50} // Espera 50ms entre lotes
        initialNumToRender={6} // Renderiza 6 elementos inicialmente
        windowSize={5} // Mantiene 5 "ventanas" de elementos en memoria
      />
      
      {/* Menú lateral */}
      <DrawerMenu 
        visible={drawerVisible}
        onClose={() => setDrawerVisible(false)}
      />

      {/* 🔍 Botón flotante de búsqueda */}
      <TouchableOpacity
        style={[styles.floatingSearchButton, darkMode && styles.floatingSearchButtonDark]}
        onPress={() => setSearchModalVisible(true)}
        activeOpacity={0.9}
      >
        <Ionicons name="search" size={24} color="#fff" />
      </TouchableOpacity>

      {/* 🔍 Modal de búsqueda */}
      <Modal
        visible={searchModalVisible}
        animationType="slide"
        transparent={true}
        onRequestClose={() => setSearchModalVisible(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={[styles.searchModalContainer, darkMode && styles.searchModalContainerDark]}>
            {/* Header del modal */}
            <View style={styles.modalHeader}>
              <Text style={[styles.modalTitle, darkMode && styles.modalTitleDark]}>
                Buscar libros
              </Text>
              <TouchableOpacity
                onPress={() => setSearchModalVisible(false)}
                style={styles.closeButton}
              >
                <Ionicons name="close" size={28} color={darkMode ? "#fff" : "#1A1A1A"} />
              </TouchableOpacity>
            </View>

            {/* Barra de búsqueda en el modal */}
            <View style={styles.modalSearchContainer}>
              <MorphingSearchBar
                value={searchQuery}
                onChangeText={setSearchQuery}
                onClear={() => setSearchQuery("")}
                darkMode={darkMode}
                autoFocus={true}
              />
            </View>

            {/* Sugerencias o resultados rápidos */}
            {searchQuery.trim() === "" ? (
              <View style={styles.searchSuggestions}>
                <Text style={[styles.suggestionsTitle, darkMode && styles.suggestionsTitleDark]}>
                  Búsquedas populares
                </Text>
                {["CREEPY", "QUÍMICA 1 BACHILLERATO", "UNA MUJER COMO TÚ", "CÓDIGO BESTSELLER"].map((title) => (
                  <TouchableOpacity
                    key={title}
                    style={[styles.suggestionChip, darkMode && styles.suggestionChipDark]}
                    onPress={() => {
                      setSearchQuery(title);
                      setSearchModalVisible(false);
                    }}
                  >
                    <Ionicons name="trending-up" size={16} color="#ffa3c2" />
                    <Text style={[styles.suggestionText, darkMode && styles.suggestionTextDark]}>
                      {title}
                    </Text>
                  </TouchableOpacity>
                ))}
              </View>
            ) : (
              <View style={styles.searchResults}>
                <Text style={[styles.resultsCount, darkMode && styles.resultsCountDark]}>
                  {filteredBooks.length} {filteredBooks.length === 1 ? 'resultado' : 'resultados'}
                </Text>
                <TouchableOpacity
                  style={styles.viewResultsButton}
                  onPress={() => setSearchModalVisible(false)}
                >
                  <Text style={styles.viewResultsText}>Ver resultados</Text>
                  <Ionicons name="arrow-forward" size={18} color="#ffa3c2" />
                </TouchableOpacity>
              </View>
            )}
          </View>
        </View>
      </Modal>

      {/* Confetizaso */}
       {showConfetti && (
        <ConfettiCannon
          ref={confettiRef}
          count={60}
          origin={{ x: SCREEN_WIDTH / 2, y: -10 }}
          autoStart={true}
          fadeOut={true}
          fallSpeed={2500}
          colors={['#ffa3c2', '#ff8fb3', '#4CAF50', '#FFD700', '#FF5722']}
          explosionSpeed={350}
        />
      )}


    </View>
  );
}

// Estilos de la pantalla
const styles = StyleSheet.create({
  // Contenedor principal
  container: {
    flex: 1,
    backgroundColor: "#F5F5F5",
  },
  containerDark: {
    backgroundColor: "#121212",
  },
  
  // Pantalla de carga
  loadingContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#fff",
  },
  loadingContainerDark: {
    backgroundColor: "#121212",
  },
  loadingText: {
    marginTop: 12,
    fontSize: 16,
    color: "#666",
  },
  loadingTextDark: {
    color: "#ccc",
  },
  
  // Header superior con logo e iconos
  staticHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    backgroundColor: "rgba(255, 255, 255, 0.98)",
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#E0E0E0",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 3, // Sombra en Android
  },
  staticHeaderDark: {
    backgroundColor: "rgba(26, 26, 26, 0.98)",
    borderBottomColor: "#333",
  },
  
  logoContainer: {
    flexDirection: "row",
    alignItems: "center",
  },
  
  // Contenedor de iconos (carrito, perfil, menú)
  headerIcons: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
  },
  iconButton: {
    position: "relative",
    padding: 8,
  },
  
  // Badge rojo del carrito con la cantidad
  cartBadge: {
    position: "absolute",
    top: 4,
    right: 4,
    backgroundColor: "#F44336",
    borderRadius: 10,
    minWidth: 18,
    height: 18,
    justifyContent: "center",
    alignItems: "center",
  },
  cartBadgeText: {
    color: "#fff",
    fontSize: 11,
    fontWeight: "bold",
  },

  // Estilos del carrusel de libros destacados
  carouselContainer: {
    height: 300,
    marginBottom: 10,
    marginTop: 10,
  },
  carouselSlide: {
    width: SCREEN_WIDTH, // Cada slide ocupa todo el ancho
    height: 400,
    position: 'relative',
  },
  carouselImage: {
    width: '100%',
    height: '100%',
    position: 'absolute', // Fondo absoluto
  },
  carouselGradient: {
    position: 'absolute',
    width: '100%',
    height: '100%',
    backgroundColor: 'rgba(0,0,0,0.5)', // Capa oscura encima del fondo
  },
  carouselContent: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'flex-top',
    marginTop: 50,
    paddingHorizontal: 20,
    paddingBottom: 20,
    gap: 16,
  },
  carouselBookCover: {
    width: 140,
    height: 200,
    borderRadius: 12,
    overflow: 'hidden',
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.4,
    shadowRadius: 12,
    elevation: 10,
  },
  carouselCoverImage: {
    width: '100%',
    height: '100%',
  },
  carouselInfo: {
    flex: 1,
    paddingBottom: 8,
  },
  featuredBadge: {
    backgroundColor: '#ffa3c2',
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 12,
    alignSelf: 'flex-start',
    marginBottom: 8,
    flexDirection: 'row',
    alignItems: 'center',
  },
  featuredBadgeText: {
    color: '#fff',
    fontSize: 11,
    fontWeight: 'bold',
  },
  carouselTitle: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 6,
    textShadowColor: 'rgba(0, 0, 0, 0.5)', // Sombra en el texto para legibilidad
    textShadowOffset: { width: 0, height: 2 },
    textShadowRadius: 4,
  },
  carouselAuthor: {
    fontSize: 16,
    color: '#e0e0e0',
    marginBottom: 12,
  },
  carouselFooter: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  carouselPrice: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#4CAF50',
  },
  carouselButton: {
    backgroundColor: '#ffa3c2',
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderRadius: 20,
    shadowColor: "#ffa3c2",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 5,
    flexDirection: 'row',
    alignItems: 'center',
  },
  carouselButtonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 14,
  },
  // Indicadores (puntitos) del carrusel
  carouselIndicators: {
    position: 'absolute',
    bottom: 12,
    left: 0,
    right: 0,
    flexDirection: 'row',
    justifyContent: 'center',
    gap: 6,
  },
  indicator: {
    width: 8,
    height: 8,
    borderRadius: 4,
    backgroundColor: 'rgba(255, 255, 255, 0.4)',
  },
  indicatorActive: {
    width: 24, // El activo es más ancho
    backgroundColor: '#ffa3c2',
  },
  
  // Contenedor de búsqueda y filtros
  filtersContainer: {
    backgroundColor: "#fff",
    paddingBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: "#E0E0E0",
  },
  filtersContainerDark: {
    backgroundColor: "#1A1A1A",
    borderBottomColor: "#333",
  },
  
  // Barra de búsqueda
  searchContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "rgba(245, 245, 245, 0.8)",
    borderRadius: 16,
    paddingHorizontal: 12,
    marginHorizontal: 16,
    marginTop: 12,
    marginBottom: 12,
    height: 48,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 4,
    elevation: 2,
  },
  searchContainerDark: {
    backgroundColor: "rgba(42, 42, 42, 0.8)",
  },
  searchIcon: {
    marginRight: 8,
  },
  searchInput: {
    flex: 1,
    fontSize: 16,
    color: "#1A1A1A",
  },
  searchInputDark: {
    color: "#fff",
  },
  
  // Lista horizontal de categorías
  categoriesContainer: {
    paddingHorizontal: 16,
    paddingVertical: 8,
  },
  categoryChip: {
    paddingHorizontal: 16,
    paddingVertical: 10,
    backgroundColor: "rgba(245, 245, 245, 0.8)",
    borderRadius: 20,
    marginRight: 8,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 1,
  },
  categoryChipActive: {
    backgroundColor: "#ffa3c2", // Rosa cuando está seleccionada
  },
  categoryChipDark: {
    backgroundColor: "rgba(42, 42, 42, 0.8)",
  },
  categoryChipActiveDark: {
    backgroundColor: "#ffa3c2",
  },
  categoryText: {
    fontSize: 14,
    color: "#666",
    fontWeight: "500",
  },
  categoryTextActive: {
    color: "#fff",
    fontWeight: "600",
  },
  categoryTextDark: {
    color: "#ccc",
  },
  
  // Texto de resultados encontrados
  resultsText: {
    paddingHorizontal: 16,
    paddingTop: 8,
    fontSize: 14,
    color: "#666",
  },
  resultsTextDark: {
    color: "#999",
  },
  
  // Estilos del FlatList
  listContent: {
    paddingBottom: 16,
    paddingTop: 4,
  },
  columnWrapper: {
    paddingHorizontal: 12,
    justifyContent: 'space-between', // Espacio uniforme entre las 2 columnas
  },
  
  // Tarjeta de libro
  bookCard: {
    width: (SCREEN_WIDTH - 48) / 2, // Ancho calculado para 2 columnas con márgenes
    backgroundColor: "#fff",
    borderRadius: 20,
    marginBottom: 20,
    marginTop: 20,
    overflow: "hidden",
    elevation: 4,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.12,
    shadowRadius: 12,
    borderWidth: 1,
    borderColor: "rgba(0, 0, 0, 0.04)",
    backfaceVisibility: 'hidden',
  },
  bookCardDark: {
    backgroundColor: "#1E1E1E",
    borderColor: "rgba(255, 255, 255, 0.06)",
  },
  bookCardInner: {
    flex: 1,
  },
  
  // Contenedor de la imagen de portada
  bookImageContainer: {
    position: "relative",
    width: "100%",
    height: 240,
    backgroundColor: "#FAFAFA",
  },
  bookImage: {
    width: "100%",
    height: "100%",
  },
  imageOverlay: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    height: '30%',
    backgroundColor: 'transparent',
  },
  // Placeholder cuando no hay imagen
  bookImagePlaceholder: {
    width: "100%",
    height: "100%",
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#F8F8F8",
  },
  noImageText: {
    marginTop: 8,
    fontSize: 11,
    color: "#bbb",
    fontStyle: 'italic',
  },
  noImageTextDark: {
    color: "#666",
  },
  
  // Badge de disponibilidad (esquina superior derecha)
  statusBadge: {
    position: "absolute",
    top: 10,
    right: 10,
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 8,
    paddingVertical: 5,
    borderRadius: 12,
    gap: 3,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 3,
  },
  statusText: {
    color: "#fff",
    fontSize: 10,
    fontWeight: "700",
    letterSpacing: 0.3,
  },
  
  // Botón de favoritos (corazón en esquina superior izquierda)
  favoriteButton: {
    position: "absolute",
    top: 10,
    left: 10,
    backgroundColor: "rgba(0, 0, 0, 0.45)",
    width: 36,
    height: 36,
    borderRadius: 18,
    justifyContent: "center",
    alignItems: "center",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.3,
    shadowRadius: 4,
    elevation: 3,
  },
  favoriteButtonActive: {
    backgroundColor: "rgba(255, 255, 255, 0.95)", // Fondo blanco cuando está activo
  },
  
  // Información del libro (título, autor, precio)
  bookInfo: {
    padding: 16,
    flex: 1,
  },
  bookTitle: {
    fontSize: 15,
    fontWeight: "700",
    color: "#1A1A1A",
    marginBottom: 8,
    lineHeight: 20,
    letterSpacing: 0.2,
  },
  bookTitleDark: {
    color: "#fff",
  },
  
  // Fila del autor con icono
  authorRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 12,
    gap: 5,
  },
  bookAuthor: {
    fontSize: 12,
    color: "#888",
    flex: 1,
    fontStyle: 'italic', // Cursiva para darle estilo de librería
  },
  bookAuthorDark: {
    color: "#aaa",
  },
  
  // Línea divisora entre autor y precio
  divider: {
    height: 1,
    backgroundColor: "rgba(0, 0, 0, 0.06)",
    marginBottom: 12,
  },
  dividerDark: {
    backgroundColor: "rgba(255, 255, 255, 0.1)",
  },
  
  // Footer de la tarjeta con precio y botón
  bookFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
  },
  priceContainer: {
    flex: 1,
  },
  priceLabel: {
    fontSize: 10,
    color: "#999",
    marginBottom: 3,
    fontWeight: "500",
    letterSpacing: 0.5,
  },
  priceLabelDark: {
    color: "#666",
  },
  bookPrice: {
    fontSize: 20,
    fontWeight: "800",
    color: "#2E7D32",
    letterSpacing: 0.3,
  },
  bookPriceDark: {
    color: "#4CAF50",
  },
  
  // Botón circular de agregar al carrito
  addButton: {
    backgroundColor: "#ffa3c2",
    width: 44,
    height: 44,
    borderRadius: 22,
    justifyContent: "center",
    alignItems: "center",
    shadowColor: "#ffa3c2",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.35,
    shadowRadius: 8,
    elevation: 5,
  },
  addButtonDark: {
    backgroundColor: "#ff8fb3",
  },
  addButtonDisabled: {
    backgroundColor: "#999",
    opacity: 0.4,
    shadowOpacity: 0.1,
  },
  
  // Vista cuando no hay resultados
  emptyContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    paddingVertical: 80,
  },
  emptyText: {
    fontSize: 18,
    fontWeight: "600",
    color: "#666",
    marginTop: 16,
  },
  emptyTextDark: {
    color: "#999",
  },
  emptySubtext: {
    fontSize: 14,
    color: "#999",
    marginTop: 8,
  },
  emptySubtextDark: {
    color: "#666",
  },

// NUEVOS ESTILOS 
  // Efecto de brillo
shineOverlay: {
  position: 'absolute',
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  overflow: 'hidden',
  pointerEvents: 'none', // No interfiere con los toques
},
shineGradient: {
  width: '200%',
  height: '100%',
  backgroundColor: 'transparent',
  borderLeftWidth: 60,
  borderLeftColor: 'rgba(255, 255, 255, 0.6)',
  borderRightWidth: 60,
  borderRightColor: 'transparent',
  transform: [{ rotate: '15deg' }],
  marginLeft: -50,
},

// Badge de Bestseller/Popular
bestsellerBadge: {
  position: "absolute",
  top: 50,
  right: 10,
  flexDirection: "row",
  alignItems: "center",
  paddingHorizontal: 8,
  paddingVertical: 5,
  borderRadius: 12,
  backgroundColor: "rgba(255, 87, 34, 0.95)",
  gap: 3,
  shadowColor: "#FF5722",
  shadowOffset: { width: 0, height: 2 },
  shadowOpacity: 0.4,
  shadowRadius: 4,
  elevation: 3,
},
bestsellerText: {
  color: "#fff",
  fontSize: 10,
  fontWeight: "700",
  letterSpacing: 0.3,
},

// Badge de Nuevo
newBadge: {
  position: "absolute",
  top: 50,
  left: 10,
  flexDirection: "row",
  alignItems: "center",
  paddingHorizontal: 8,
  paddingVertical: 5,
  borderRadius: 12,
  backgroundColor: "rgba(76, 175, 80, 0.95)",
  gap: 3,
  shadowColor: "#4CAF50",
  shadowOffset: { width: 0, height: 2 },
  shadowOpacity: 0.4,
  shadowRadius: 4,
  elevation: 3,
},
newText: {
  color: "#fff",
  fontSize: 10,
  fontWeight: "700",
  letterSpacing: 0.3,
},
// ✨ ESTILOS PARA BÚSQUEDA FLOTANTE Y MODAL

// Botón flotante de búsqueda (esquina inferior izquierda)
floatingSearchButton: {
  position: 'absolute',
  bottom: 30,
  left: 20,
  width: 60,
  height: 60,
  borderRadius: 30,
  backgroundColor: '#ffa3c2',
  justifyContent: 'center',
  alignItems: 'center',
  shadowColor: '#ffa3c2',
  shadowOffset: { width: 0, height: 8 },
  shadowOpacity: 0.4,
  shadowRadius: 16,
  elevation: 10,
  zIndex: 100,
},
floatingSearchButtonDark: {
  backgroundColor: '#ff8fb3',
},

// Modal de búsqueda
modalOverlay: {
  flex: 1,
  backgroundColor: 'rgba(0, 0, 0, 0.5)',
  justifyContent: 'flex-end',
},
searchModalContainer: {
  backgroundColor: '#fff',
  borderTopLeftRadius: 24,
  borderTopRightRadius: 24,
  paddingTop: 20,
  paddingHorizontal: 20,
  paddingBottom: 40,
  maxHeight: '80%',
  shadowColor: '#000',
  shadowOffset: { width: 0, height: -4 },
  shadowOpacity: 0.15,
  shadowRadius: 12,
  elevation: 20,
},
searchModalContainerDark: {
  backgroundColor: '#1A1A1A',
},

// Header del modal
modalHeader: {
  flexDirection: 'row',
  justifyContent: 'space-between',
  alignItems: 'center',
  marginBottom: 20,
},
modalTitle: {
  fontSize: 24,
  fontWeight: 'bold',
  color: '#1A1A1A',
},
modalTitleDark: {
  color: '#fff',
},
closeButton: {
  padding: 4,
},

// Contenedor de la barra de búsqueda en el modal
modalSearchContainer: {
  marginBottom: 24,
},

// Sugerencias de búsqueda
searchSuggestions: {
  marginTop: 8,
},
suggestionsTitle: {
  fontSize: 14,
  fontWeight: '600',
  color: '#666',
  marginBottom: 12,
  textTransform: 'uppercase',
  letterSpacing: 0.5,
},
suggestionsTitleDark: {
  color: '#999',
},
suggestionChip: {
  flexDirection: 'row',
  alignItems: 'center',
  backgroundColor: '#f5f5f5',
  paddingVertical: 12,
  paddingHorizontal: 16,
  borderRadius: 12,
  marginBottom: 8,
  gap: 10,
},
suggestionChipDark: {
  backgroundColor: '#2A2A2A',
},
suggestionText: {
  fontSize: 15,
  color: '#333',
  fontWeight: '500',
},
suggestionTextDark: {
  color: '#fff',
},

// Resultados de búsqueda
searchResults: {
  marginTop: 8,
},
resultsCount: {
  fontSize: 14,
  color: '#666',
  marginBottom: 16,
},
resultsCountDark: {
  color: '#999',
},
viewResultsButton: {
  flexDirection: 'row',
  alignItems: 'center',
  justifyContent: 'center',
  backgroundColor: 'rgba(255, 163, 194, 0.1)',
  paddingVertical: 16,
  paddingHorizontal: 24,
  borderRadius: 12,
  borderWidth: 1.5,
  borderColor: '#ffa3c2',
  gap: 8,
},
viewResultsText: {
  color: '#ffa3c2',
  fontSize: 16,
  fontWeight: '600',
},
  seeAllChip: {
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#ffa3c2',
    borderStyle: 'dashed',
  },
});