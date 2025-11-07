import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Image } from "react-native";
import DrawerMenu from "../screens/DrawerMenu";
import { useCart } from './CartContext';

import { useLocalSearchParams, useRouter } from 'expo-router';
import { useCallback, useEffect, useState } from "react";
import {
  ActivityIndicator,
  Alert,
  FlatList,
  Platform,
  RefreshControl,
  StatusBar,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View
} from "react-native";
import 'react-native-gesture-handler';

export default function HomeScreen() {
  const router = useRouter();
  const params = useLocalSearchParams();
  const [drawerVisible, setDrawerVisible] = useState(false);
  const { addToCart, getCartCount } = useCart();

  // Estados
  const [books, setBooks] = useState([]);
  const [filteredBooks, setFilteredBooks] = useState([]);
  const [categories, setCategories] = useState(["Todos"]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("Todos");
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  // 🔧 CAMBIA ESTO POR TU IP LOCAL
  const API_BASE_URL = "http://localhost:8000"; 

  // Verifica si hay sesión al cargar
  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    const token = await AsyncStorage.getItem('userToken');
    setIsLoggedIn(!!token);
  };

  // Obtener libros desde la API
  const fetchBooks = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/books`);
      const data = await response.json();
      console.log("📚 Libros obtenidos:", data.length);
      setBooks(data);
      setFilteredBooks(data);
      setLoading(false);
      setRefreshing(false);
    } catch (error) {
      console.error("❌ Error al obtener libros:", error);
      setLoading(false);
      setRefreshing(false);
    }
  };

  // Obtener categorías desde la API
  const fetchCategories = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/categories`);
      const data = await response.json();
      console.log("📂 Categorías obtenidas:", data.length);
      
      // Extraer nombres de categorías y agregar "Todos" al inicio
      const categoryNames = data.map(cat => cat.category_name);
      setCategories(['Todos', ...categoryNames]);
    } catch (error) {
      console.error("❌ Error al obtener categorías:", error);
      // Si falla, mantener "Todos" por defecto
    }
  };

  useEffect(() => {
    fetchBooks();
    fetchCategories();
  }, []);

  // Efecto para aplicar categoría desde params
  useEffect(() => {
    if (params.category && categories.length > 0) {
      setSelectedCategory(params.category);
    }
  }, [params.category, categories]);

  // Filtrar libros por búsqueda y categoría
  useEffect(() => {
    let filtered = books;

    // Filtrar por categoría
    if (selectedCategory !== "Todos") {
      filtered = filtered.filter(
        (book) => book.category_name === selectedCategory
      );
    }

    // Filtrar por búsqueda
    if (searchQuery.trim() !== "") {
      filtered = filtered.filter(
        (book) =>
          book.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
          (book.authors && book.authors.toLowerCase().includes(searchQuery.toLowerCase()))
      );
    }

    setFilteredBooks(filtered);
  }, [searchQuery, selectedCategory, books]);

  const onRefresh = () => {
    setRefreshing(true);
    fetchBooks();
    fetchCategories();
  };

  // Configuración de estado del libro
  const getStatusConfig = (stock_quantity) => {
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
  };

  const handleProfilePress = () => {
    if (isLoggedIn) {
      router.push('/profile');
    } else {
      router.push('login');
    }
  };

  const handleCartPress = () => {
    router.push('/cart');
  };

  // HEADER - Optimizado con useCallback para evitar re-renders innecesarios
  const renderHeader = useCallback(() => (
    <View style={styles.headerContainer}>
      <View style={styles.header}>
        {/* Logo de Gonvill */}
        <View style={styles.logoContainer}>
          <Image
            source={require('../assets/logo_Gonvill_pink.png')}
            style={{ width: 100, height: 50 }}
            resizeMode="contain"
          />
        </View>

        {/* Iconos de navegación */}
        <View style={styles.headerIcons}>
          {/* Carrito */}
          <TouchableOpacity style={styles.iconButton} onPress={handleCartPress}>
            <Ionicons name="cart-outline" size={26} color="#1A1A1A" />
            <View style={styles.cartBadge}>
              <Text style={styles.cartBadgeText}>{getCartCount()}</Text>
            </View>
          </TouchableOpacity>

          {/* Perfil */}
          <TouchableOpacity style={styles.iconButton} onPress={handleProfilePress}>
            <Ionicons 
              name={isLoggedIn ? "person" : "person-outline"} 
              size={26} 
              color="#1A1A1A" 
            />
          </TouchableOpacity>

          {/* Menú */}
          <TouchableOpacity onPress={() => setDrawerVisible(true)}>
            <Ionicons name="menu" size={28} color="#1A1A1A" />
          </TouchableOpacity>
        </View>
      </View>

      {/* Barra de búsqueda */}
      <View style={styles.searchContainer}>
        <Ionicons name="search" size={20} color="#666" style={styles.searchIcon} />
        <TextInput
          style={styles.searchInput}
          placeholder="Buscar libros o autores..."
          placeholderTextColor="#999"
          value={searchQuery}
          onChangeText={setSearchQuery}
        />
        {searchQuery.length > 0 && (
          <TouchableOpacity onPress={() => setSearchQuery("")}>
            <Ionicons name="close-circle" size={20} color="#999" />
          </TouchableOpacity>
        )}
      </View>

      {/* Categorías */}
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
              ]}
              onPress={() => setSelectedCategory(item)}
            >
              <Text
                style={[
                  styles.categoryText,
                  selectedCategory === item && styles.categoryTextActive,
                ]}
              >
                {item}
              </Text>
            </TouchableOpacity>
          )}
        />
      )}

      <Text style={styles.resultsText}>
        {filteredBooks.length} {filteredBooks.length === 1 ? "libro encontrado" : "libros encontrados"}
      </Text>
    </View>
  ), [searchQuery, selectedCategory, filteredBooks.length, isLoggedIn, getCartCount, categories]);

  // TARJETA DE LIBRO - Optimizado con useCallback
  const renderBook = useCallback(({ item }) => {
    const statusConfig = getStatusConfig(item.stock_quantity);

    return (
      <TouchableOpacity 
        style={styles.bookCard} 
        activeOpacity={0.8}
        onPress={() => console.log("Ver detalles del libro:", item.title)}
      >
        {/* Imagen de portada */}
        <View style={styles.bookImageContainer}>
          {item.cover_image ? (
            <Image 
              source={{ uri: `${API_BASE_URL}/img/${item.cover_image}` }}
              style={styles.bookImage}
              resizeMode="cover"
              onError={(e) => {
                console.log("❌ Error cargando imagen:", item.title, e.nativeEvent.error);
              }}
            />
          ) : (
            <View style={styles.bookImagePlaceholder}>
              <Ionicons name="book" size={40} color="#999" />
              <Text style={styles.noImageText}>Sin portada</Text>
            </View>
          )}
          
          {/* Badge de estado */}
          <View style={[styles.statusBadge, { backgroundColor: statusConfig.color }]}>
            <Ionicons name={statusConfig.icon} size={14} color="#fff" />
            <Text style={styles.statusText}>{statusConfig.text}</Text>
          </View>
        </View>

        {/* Información del libro */}
        <View style={styles.bookInfo}>
          <Text style={styles.bookTitle} numberOfLines={2}>
            {item.title}
          </Text>

          <View style={styles.authorRow}>
            <Ionicons name="person-outline" size={14} color="#666" />
            <Text style={styles.bookAuthor} numberOfLines={1}>
              {item.authors || "Autor desconocido"}
            </Text>
          </View>

          <View style={styles.bookFooter}>
            <View>
              <Text style={styles.priceLabel}>Precio</Text>
              <Text style={styles.bookPrice}>
                ${parseFloat(item.price).toFixed(2)}
              </Text>
            </View>

            <TouchableOpacity 
              style={[
                styles.addButton,
                item.stock_quantity === 0 && styles.addButtonDisabled
              ]}
              onPress={() => {
                if (item.stock_quantity > 0) {
                  addToCart(item);
                  if (Platform.OS === 'web') {
                    alert(`"${item.title}" agregado al carrito`);
                  } else {
                    Alert.alert("Agregado", `"${item.title}" se agregó al carrito`);
                  }
                }
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
    );
  }, [addToCart, API_BASE_URL]);

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#ffa3c2" />
        <Text style={styles.loadingText}>Cargando libros...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#fff" />
      
      <FlatList
        data={filteredBooks}
        keyExtractor={(item) => item.book_id.toString()}
        renderItem={renderBook}
        ListHeaderComponent={renderHeader}
        contentContainerStyle={styles.listContent}
        numColumns={2}
        columnWrapperStyle={styles.columnWrapper}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            colors={["#ffa3c2"]}
          />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="search-outline" size={64} color="#ccc" />
            <Text style={styles.emptyText}>No se encontraron libros</Text>
            <Text style={styles.emptySubtext}>Intenta con otra búsqueda</Text>
          </View>
        }
      />
      
      <DrawerMenu 
        visible={drawerVisible}
        onClose={() => setDrawerVisible(false)}
      />
    </View>
  );
}

// ESTILOS
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#F5F5F5",
  },
  loadingContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#fff",
  },
  loadingText: {
    marginTop: 12,
    fontSize: 16,
    color: "#666",
  },
  headerContainer: {
    backgroundColor: "#fff",
    paddingBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: "#E0E0E0",
  },
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 12,
  },
  logoContainer: {
    flexDirection: "row",
    alignItems: "center",
  },
  headerIcons: {
    flexDirection: "row",
    alignItems: "center",
    gap: 12,
  },
  iconButton: {
    position: "relative",
    padding: 8,
  },
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
  searchContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "#F5F5F5",
    borderRadius: 12,
    paddingHorizontal: 12,
    marginHorizontal: 16,
    marginBottom: 12,
    height: 48,
  },
  searchIcon: {
    marginRight: 8,
  },
  searchInput: {
    flex: 1,
    fontSize: 16,
    color: "#1A1A1A",
  },
  categoriesContainer: {
    paddingHorizontal: 16,
    paddingVertical: 8,
  },
  categoryChip: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    backgroundColor: "#F5F5F5",
    borderRadius: 20,
    marginRight: 8,
  },
  categoryChipActive: {
    backgroundColor: "#ffa3c2",
  },
  categoryText: {
    fontSize: 14,
    color: "#666",
    fontWeight: "500",
  },
  categoryTextActive: {
    color: "#fff",
  },
  resultsText: {
    paddingHorizontal: 16,
    paddingTop: 8,
    fontSize: 14,
    color: "#666",
  },
  listContent: {
    paddingBottom: 16,
  },
  columnWrapper: {
    paddingHorizontal: 8,
  },
  bookCard: {
    flex: 1,
    backgroundColor: "#fff",
    borderRadius: 12,
    margin: 8,
    overflow: "hidden",
    elevation: 2,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  bookImageContainer: {
    position: "relative",
    width: "100%",
    height: 200,
    backgroundColor: "#F5F5F5",
  },
  bookImage: {
    width: "100%",
    height: "100%",
  },
  bookImagePlaceholder: {
    width: "100%",
    height: "100%",
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#FAFAFA",
  },
  noImageText: {
    marginTop: 8,
    fontSize: 12,
    color: "#999",
  },
  statusBadge: {
    position: "absolute",
    top: 8,
    right: 8,
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
    gap: 4,
  },
  statusText: {
    color: "#fff",
    fontSize: 11,
    fontWeight: "600",
  },
  bookInfo: {
    padding: 12,
  },
  bookTitle: {
    fontSize: 15,
    fontWeight: "600",
    color: "#1A1A1A",
    marginBottom: 6,
    lineHeight: 20,
  },
  authorRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 12,
    gap: 4,
  },
  bookAuthor: {
    fontSize: 13,
    color: "#666",
    flex: 1,
  },
  bookFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "flex-end",
  },
  priceLabel: {
    fontSize: 11,
    color: "#999",
    marginBottom: 2,
  },
  bookPrice: {
    fontSize: 18,
    fontWeight: "bold",
    color: "#2E7D32",
  },
  addButton: {
    backgroundColor: "#2E7D32",
    width: 36,
    height: 36,
    borderRadius: 18,
    justifyContent: "center",
    alignItems: "center",
  },
  addButtonDisabled: {
    backgroundColor: "#999",
    opacity: 0.5,
  },
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
  emptySubtext: {
    fontSize: 14,
    color: "#999",
    marginTop: 8,
  },
});