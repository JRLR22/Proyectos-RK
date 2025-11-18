import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { useCallback, useEffect, useState } from "react";
import { ActivityIndicator, Alert, FlatList, Image, Platform, RefreshControl, StatusBar, StyleSheet, Text, TextInput, TouchableOpacity, View } from "react-native";
import 'react-native-gesture-handler';
import { useCart } from '../contexts/CartContext';
import { useTheme } from '../contexts/ThemeContext';
import DrawerMenu from "../screens/DrawerMenu";

export default function HomeScreen() {
  const router = useRouter();
  const params = useLocalSearchParams(); 
  const [drawerVisible, setDrawerVisible] = useState(false);
  const { addToCart, getCartCount } = useCart();
  const { darkMode } = useTheme();

  // Estados
  const [books, setBooks] = useState([]);
  const [filteredBooks, setFilteredBooks] = useState([]);
  const [categories, setCategories] = useState(["Todos"]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("Todos");
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [favorites, setFavorites] = useState([]);
  const [favKey, setFavKey] = useState(null);

  const API_BASE_URL = "http://10.0.2.2:8000";

  useEffect(() => {
    if (params.category) {
      setSelectedCategory(params.category);
      setSearchQuery("");
    }
  }, [params.category]);

  useEffect(() => {
    checkAuth();
    loadUser();
  }, []);

  const checkAuth = async () => {
    const token = await AsyncStorage.getItem('userToken');
    setIsLoggedIn(!!token);
  };
  
  const loadUser = async () => {
    console.log(await AsyncStorage.getAllKeys())

    try {
      const raw = await AsyncStorage.getItem('userData');
      if (!raw) {
        setFavKey(null);
        setFavorites([]);
        return;
      }

      const user = JSON.parse(raw);
      const key = `favorites_${user.user_id}`;

      setFavKey(key);
      loadFavorites(key);

    } catch (error) {
      console.error("Error leyendo la Información del Usuario:", error);
    }
  };

  const loadFavorites = async (key) => {
    try {
      const favData = await AsyncStorage.getItem(key);
        setFavorites(favData ? JSON.parse(favData):[]);
    } catch (error) {
      console.error("Error cargando favoritos:", error);
    }
  };

  const isFavorite = (bookId) => {
    return favorites.some(fav => fav.book_id === bookId);
  };

  const toggleFavorite = async (book) => {
    try {
      if (!favKey) {
        Alert.alert("Inicia sesión", "Necesitas iniciar sesión para agregar favoritos");
        return;
      }
      let updatedFavorites;
      
      if (isFavorite(book.book_id)) {
        updatedFavorites = favorites.filter(fav => fav.book_id !== book.book_id);
        if (Platform.OS === 'web') {
          alert('Eliminado de favoritos');
        } else {
          Alert.alert("Eliminado", "Se quitó de tus favoritos");
        }
      } else {
        updatedFavorites = [...favorites, book];
        if (Platform.OS === 'web') {
          alert('Agregado a favoritos');
        } else {
          Alert.alert("Agregado", "Se agregó a tus favoritos");
        }
      }
      
      setFavorites(updatedFavorites);
      await AsyncStorage.setItem(favKey, JSON.stringify(updatedFavorites));
    } catch (error) {
      console.error("Error guardando favorito:", error);
    }
  };

  const fetchBooks = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/books`);
      const data = await response.json();
      console.log("Libros obtenidos:", data.length);
      setBooks(data);
      setFilteredBooks(data);
      setLoading(false);
      setRefreshing(false);
    } catch (error) {
      console.error("Error al obtener libros:", error);
      setLoading(false);
      setRefreshing(false);
    }
  };

  const fetchCategories = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/categories`);
      const data = await response.json();
      console.log("Categorías obtenidas:", data.length);
      setCategories(['Todos',"Libros para todos","Terror","Novedades","Juveniles","Infantiles","Textos escolares"]);
    } catch (error) {
      console.error("Error al obtener categorías:", error);
    }
  };

  useEffect(() => {
    fetchBooks();
    fetchCategories();
  }, []);

  useEffect(() => {
    let filtered = books;

    if (selectedCategory !== "Todos") {
      filtered = filtered.filter(
        (book) => book.category_name === selectedCategory
      );
    }

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

  // ✅ Header fijo (Logo + íconos) - NO se recarga
  const renderStaticHeader = () => (
    <View style={[styles.staticHeader, darkMode && styles.staticHeaderDark]}>
      <View style={styles.logoContainer}>
        <Image
          source={require('../assets/images/logo_Gonvill_pink.png')}
          style={{ width: 100, height: 50 }}
          resizeMode="contain"
        />
      </View>

      <View style={styles.headerIcons}>
        <TouchableOpacity style={styles.iconButton} onPress={handleCartPress}>
          <Ionicons name="cart-outline" size={26} color={darkMode ? "#fff" : "#1A1A1A"} />
          <View style={styles.cartBadge}>
            <Text style={styles.cartBadgeText}>{getCartCount()}</Text>
          </View>
        </TouchableOpacity>

        <TouchableOpacity style={styles.iconButton} onPress={handleProfilePress}>
          <Ionicons 
            name={isLoggedIn ? "person" : "person-outline"} 
            size={26} 
            color={isLoggedIn ? "#ffa3c2" : (darkMode ? "#fff" : "#1A1A1A")} 
          />
        </TouchableOpacity>

        <TouchableOpacity onPress={() => setDrawerVisible(true)}>
          <Ionicons name="menu" size={28} color={darkMode ? "#fff" : "#1A1A1A"} />
        </TouchableOpacity>
      </View>
    </View>
  );

  // ✅ Búsqueda, categorías y contador (dentro del scroll)
  const renderSearchAndFilters = useCallback(() => (
    <View style={[styles.filtersContainer, darkMode && styles.filtersContainerDark]}>
      <View style={[styles.searchContainer, darkMode && styles.searchContainerDark]}>
        <Ionicons name="search" size={20} color={darkMode ? "#999" : "#666"} style={styles.searchIcon} />
        <TextInput
          style={[styles.searchInput, darkMode && styles.searchInputDark]}
          placeholder="Buscar libros o autores..."
          placeholderTextColor={darkMode ? "#666" : "#999"}
          value={searchQuery}
          onChangeText={setSearchQuery}
        />
        {searchQuery.length > 0 && (
          <TouchableOpacity onPress={() => setSearchQuery("")}>
            <Ionicons name="close-circle" size={20} color={darkMode ? "#666" : "#999"} />
          </TouchableOpacity>
        )}
      </View>

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
        />
      )}

      <Text style={[styles.resultsText, darkMode && styles.resultsTextDark]}>
        {filteredBooks.length} {filteredBooks.length === 1 ? "libro encontrado" : "libros encontrados"}
      </Text>
    </View>
  ), [searchQuery, selectedCategory, filteredBooks.length, categories, darkMode]);

  const renderBook = useCallback(({ item }) => {
    const statusConfig = getStatusConfig(item.stock_quantity);
    const isBookFavorite = isFavorite(item.book_id);

    return (
      <TouchableOpacity 
        style={[styles.bookCard, darkMode && styles.bookCardDark]} 
        activeOpacity={0.8}
        onPress={() => console.log("Ver detalles del libro:", item.title)}
      >
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
          
          <View style={[styles.statusBadge, { backgroundColor: statusConfig.color }]}>
            <Ionicons name={statusConfig.icon} size={14} color="#fff" />
            <Text style={styles.statusText}>{statusConfig.text}</Text>
          </View>

          <TouchableOpacity 
            style={[
              styles.favoriteButton,
              isBookFavorite && styles.favoriteButtonActive
            ]}
            onPress={() => toggleFavorite(item)}
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
  }, [addToCart, API_BASE_URL, favorites, darkMode]);

  if (loading) {
    return (
      <View style={[styles.loadingContainer, darkMode && styles.loadingContainerDark]}>
        <ActivityIndicator size="large" color="#ffa3c2" />
        <Text style={[styles.loadingText, darkMode && styles.loadingTextDark]}>Cargando libros...</Text>
      </View>
    );
  }

  return (
    <View style={[styles.container, darkMode && styles.containerDark]}>
      <StatusBar 
        barStyle={darkMode ? "light-content" : "dark-content"} 
        backgroundColor={darkMode ? "#1A1A1A" : "#fff"} 
      />
      
      {/* ✅ Header fijo arriba - NO SE RECARGA */}
      {renderStaticHeader()}
      
      {/* ✅ FlatList con búsqueda y libros */}
      <FlatList
        data={filteredBooks}
        keyExtractor={(item) => item.book_id.toString()}
        renderItem={renderBook}
        ListHeaderComponent={renderSearchAndFilters}
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
            <Ionicons name="search-outline" size={64} color={darkMode ? "#555" : "#ccc"} />
            <Text style={[styles.emptyText, darkMode && styles.emptyTextDark]}>No se encontraron libros</Text>
            <Text style={[styles.emptySubtext, darkMode && styles.emptySubtextDark]}>Intenta con otra búsqueda</Text>
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
  
  // ✅ NUEVO: Header estático fijo
  staticHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    backgroundColor: "#fff",
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#E0E0E0",
  },
  staticHeaderDark: {
    backgroundColor: "#1A1A1A",
    borderBottomColor: "#333",
  },
  
  // ✅ NUEVO: Container para filtros
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
    marginTop: 12,
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
  favoriteButton: {
    position: "absolute",
    top: 8,
    left: 8,
    backgroundColor: "rgba(0, 0, 0, 0.5)",
    width: 36,
    height: 36,
    borderRadius: 18,
    justifyContent: "center",
    alignItems: "center",
  },
  favoriteButtonActive: {
    backgroundColor: "rgba(255, 255, 255, 0.95)",
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

  // ESTILOS MODO OSCURO
  containerDark: {
    backgroundColor: "#121212",
  },
  loadingContainerDark: {
    backgroundColor: "#121212",
  },
  loadingTextDark: {
    color: "#ccc",
  },
  searchContainerDark: {
    backgroundColor: "#2A2A2A",
  },
  searchInputDark: {
    color: "#fff",
  },
  categoryChipDark: {
    backgroundColor: "#2A2A2A",
  },
  categoryChipActiveDark: {
    backgroundColor: "#ffa3c2",
  },
  categoryTextDark: {
    color: "#ccc",
  },
  resultsTextDark: {
    color: "#999",
  },
  bookCardDark: {
    backgroundColor: "#1E1E1E",
  },
  bookTitleDark: {
    color: "#fff",
  },
  bookAuthorDark: {
    color: "#999",
  },
  priceLabelDark: {
    color: "#666",
  },
  emptyTextDark: {
    color: "#999",
  },
  emptySubtextDark: {
    color: "#666",
  },
});