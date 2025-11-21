// Pantalla de detalle individual de cada libro

import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { useEffect, useState } from "react";
import {
    Alert,
    Dimensions,
    Image,
    Platform,
    ScrollView,
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

  useEffect(() => {
    if (bookId) {
      fetchBookDetail();
      checkIfFavorite();
    } else {
      console.log('No se recibió bookId');
      setLoading(false);
    }
  }, [bookId]);


const fetchBookDetail = async () => {
  try {
    console.log('🔍 Obteniendo libro con ID:', bookId);
    
    // Obtenemos TODOS los libros
    const response = await fetch(`${API_BASE_URL}/api/books`);
    
    if (!response.ok) {
      throw new Error(`Error ${response.status}: ${response.statusText}`);
    }
    
    const allBooks = await response.json();
    console.log('📚 Total de libros:', allBooks.length);
    
    // Buscamos el libro específico por ID
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

    for (let i = 0; i < quantity; i++) {
      addToCart(book);
    }

    if (Platform.OS === 'web') {
      alert(`${quantity} ${quantity === 1 ? 'libro agregado' : 'libros agregados'} al carrito`);
    } else {
      Alert.alert(
        "Agregado",
        `${quantity} ${quantity === 1 ? 'libro' : 'libros'} ${quantity === 1 ? 'agregado' : 'agregados'} al carrito`
      );
    }
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
        <TouchableOpacity style={styles.backButton} onPress={() => router.back()}>
          <Text style={styles.backButtonText}>Volver</Text>
        </TouchableOpacity>
      </View>
    );
  }

  const statusConfig = getStatusConfig(book.stock_quantity);

  return (
    <View style={[styles.container, darkMode && styles.containerDark]}>
      <StatusBar barStyle={darkMode ? "light-content" : "dark-content"} />

      {/* Header flotante */}
      <View style={[styles.header, darkMode && styles.headerDark]}>
        <TouchableOpacity style={styles.headerButton} onPress={() => router.back()}>
          <Ionicons name="arrow-back" size={24} color={darkMode ? "#000000ff" : "#000"} />
        </TouchableOpacity>

        <TouchableOpacity style={styles.headerButton} onPress={toggleFavorite}>
          <Ionicons 
            name={isFavorite ? "heart" : "heart-outline"} 
            size={24} 
            color={isFavorite ? "#F44336" : (darkMode ? "#000000ff" : "#000")} 
          />
        </TouchableOpacity>
      </View>

      <ScrollView showsVerticalScrollIndicator={false}>
        {/* Imagen del libro */}
        <View style={styles.imageSection}>
          {book.cover_image ? (
            <Image
              source={{ uri: getImageUrl(book.cover_image) }}
              style={styles.bookImage}
              resizeMode="contain"
            />
          ) : (
            <View style={styles.placeholderImage}>
              <Ionicons name="book" size={80} color="#999" />
            </View>
          )}
        </View>

        {/* Información del libro */}
        <View style={[styles.infoSection, darkMode && styles.infoSectionDark]}>
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
        </View>
      </ScrollView>

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
      </View>
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
    paddingTop: 50,
    paddingHorizontal: 16,
    paddingBottom: 26,
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
    paddingTop: 100,
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
});