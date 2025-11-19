import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import {
  ActivityIndicator,
  Alert,
  Animated,
  Dimensions,
  FlatList,
  Image,
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
import { useCart } from '../contexts/CartContext';
import { useTheme } from '../contexts/ThemeContext';
import DrawerMenu from "../screens/DrawerMenu";

const { width: SCREEN_WIDTH } = Dimensions.get('window');

export default function HomeScreen() {
  const router = useRouter();
  const params = useLocalSearchParams(); 
  const { addToCart, getCartCount } = useCart();
  const { darkMode } = useTheme();

  // Estados existentes
  const [drawerVisible, setDrawerVisible] = useState(false);
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

  const scrollY = useRef(new Animated.Value(0)).current;
  const [currentSlide, setCurrentSlide] = useState(0);

  // --- Carousel refs y helpers (pegar aquí) ---
  const carouselScrollRef = useRef(null);
  const isUserScrolling = useRef(false);
  const scrollTimer = useRef(null);
  const autoScrollInterval = useRef(null);

  // Ref para evitar stale closures con el índice actual
  const currentSlideRef = useRef(0);


  const API_BASE_URL = "http://localhost:8000";

  // Memorizar libros destacados
  const featuredBooks = useMemo(() => {
    let featured = books.filter(book => 
      book.category_name === "Novedades" || book.stock_quantity > 20
    );
    
    if (featured.length < 5) {
      featured = books.filter(book => book.stock_quantity > 0).slice(0, 5);
    }
    
    featured = featured.slice(0, 5);
    
    console.log('📚 Libros destacados:', featured.length, featured.map(b => b.title));
    return featured;
  }, [books]);

  // Auto-scroll del carousel
  useEffect(() => {
    if (featuredBooks.length <= 1) {
      if (autoScrollInterval.current) {
        clearInterval(autoScrollInterval.current);
      }
      return;
    }

    if (autoScrollInterval.current) {
      clearInterval(autoScrollInterval.current);
    }

    autoScrollInterval.current = setInterval(() => {
      if (isUserScrolling.current) return;

      const next = (currentSlideRef.current + 1) % featuredBooks.length;

      try {
        carouselScrollRef.current?.scrollToIndex?.({ index: next, animated: true });
      } catch (err) {
        // fallback más robusto
        carouselScrollRef.current?.scrollToOffset?.({
          offset: next * SCREEN_WIDTH,
          animated: true,
        });
      }

      currentSlideRef.current = next;
      setCurrentSlide(next);
    }, 5000);

    return () => {
      if (autoScrollInterval.current) {
        clearInterval(autoScrollInterval.current);
      }
    };
  }, [featuredBooks]);


  // Header collapsible animation
  const headerHeight = scrollY.interpolate({
    inputRange: [0, 100],
    outputRange: [80, 60],
    extrapolate: 'clamp',
  });

  const headerOpacity = scrollY.interpolate({
    inputRange: [0, 50],
    outputRange: [1, 0.98],
    extrapolate: 'clamp',
  });

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
    try {
      const token = await AsyncStorage.getItem('userToken');
      setIsLoggedIn(!!token);
    } catch (error) {
      console.error("Error verificando sesión:", error);
    }
  };
  
  const loadUser = async () => {
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
      console.error("Error leyendo información del usuario:", error);
    }
  };

  const loadFavorites = async (key) => {
    try {
      const favData = await AsyncStorage.getItem(key);
      setFavorites(favData ? JSON.parse(favData) : []);
    } catch (error) {
      console.error("Error cargando favoritos:", error);
    }
  };

  const isFavorite = useCallback((bookId) => {
    return favorites.some(fav => fav.book_id === bookId);
  }, [favorites]);

  const toggleFavorite = useCallback(async (book) => {
    try {
      if (!favKey) {
        Alert.alert("Inicia sesión", "Necesitas iniciar sesión para agregar favoritos");
        return;
      }

      const isCurrentlyFavorite = favorites.some(fav => fav.book_id === book.book_id);
      let updatedFavorites;

      if (isCurrentlyFavorite) {
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
      Alert.alert("Error", "No se pudo guardar el favorito");
    }
  }, [favorites, favKey]);

  const fetchBooks = useCallback(async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/books`);
      if (!response.ok) {
        throw new Error('Error al obtener libros del servidor');
      }
      const data = await response.json();
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
  }, [API_BASE_URL]);

  const fetchCategories = useCallback(async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/categories`);
      const data = await response.json();
      
      const categoryNames = data.map(cat => cat.name);
      setCategories(['Todos', ...categoryNames]);
    } catch (error) {
      console.error("Error al obtener categorías:", error);
    }
  }, [API_BASE_URL]);

  useEffect(() => {
    fetchBooks();
    fetchCategories();
  }, []);

  useEffect(() => {
    const featuredIds = featuredBooks.map(fb => fb.book_id);
    let filtered = books.filter(book => !featuredIds.includes(book.book_id));

    if (selectedCategory !== "Todos") {
      filtered = filtered.filter(book => book.category_name === selectedCategory);
    }

    if (searchQuery.trim() !== "") {
      const query = searchQuery.toLowerCase();
      filtered = filtered.filter(book =>
        book.title.toLowerCase().includes(query) ||
        (book.authors && book.authors.toLowerCase().includes(query))
      );
    }

    setFilteredBooks(filtered);
  }, [searchQuery, selectedCategory, books, featuredBooks]);

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    fetchBooks();
    fetchCategories();
  }, [fetchBooks, fetchCategories]);

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

  const handleProfilePress = useCallback(() => {
    if (isLoggedIn) {
      router.push('/profile');
    } else {
      router.push('login');
    }
  }, [isLoggedIn, router]);

  const handleCartPress = useCallback(() => {
    router.push('/cart');
  }, [router]);

  const getCardSize = useCallback((index) => {
    const pattern = index % 6;
    return (pattern === 0 || pattern === 3) ? 'large' : 'small';
  }, []);

  const handleAddToCart = useCallback((item) => {
    if (item.stock_quantity > 0) {
      addToCart(item);
      if (Platform.OS === 'web') {
        alert(`"${item.title}" agregado al carrito`);
      } else {
        Alert.alert("Agregado", `"${item.title}" se agregó al carrito`);
      }
    }
  }, [addToCart]);

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
    </Animated.View>
  ), [darkMode, headerHeight, headerOpacity, handleCartPress, handleProfilePress, getCartCount, isLoggedIn]);

const renderHeroCarousel = useCallback(() => {
  if (featuredBooks.length === 0) return null;

  const handleIndicatorPress = (idx) => {
    isUserScrolling.current = true;
    clearTimeout(scrollTimer.current);

    carouselScrollRef.current?.scrollToIndex({ index: idx, animated: true });
    currentSlideRef.current = idx;
    setCurrentSlide(idx);

    scrollTimer.current = setTimeout(() => {
      isUserScrolling.current = false;
    }, 4000);
  };

  const handleScrollBeginDrag = () => {
    isUserScrolling.current = true;
    if (scrollTimer.current) clearTimeout(scrollTimer.current);
  };

  const handleScrollEndDrag = () => {
    scrollTimer.current = setTimeout(() => {
      isUserScrolling.current = false;
    }, 4000);
  };

      const handleScroll = (e) => {
      const index = Math.round(e.nativeEvent.contentOffset.x / SCREEN_WIDTH);
      if (index !== currentSlideRef.current) {
        currentSlideRef.current = index;
        setCurrentSlide(index);
      }
    };

  const handleMomentumEnd = (e) => {
    const index = Math.round(e.nativeEvent.contentOffset.x / SCREEN_WIDTH);
    currentSlideRef.current = index;
    setCurrentSlide(index);
  };

  const handleScrollToIndexFailed = (info) => {
    // fallback robusto
    const offset = info.index * SCREEN_WIDTH;
    carouselScrollRef.current?.scrollToOffset?.({ offset, animated: true });
  };

  return (
    <View style={styles.carouselContainer}>
      <FlatList
        key={"featured-carousel"}          // evita reinicios
        ref={carouselScrollRef}
        data={featuredBooks}
        horizontal
        pagingEnabled
        showsHorizontalScrollIndicator={false}
        decelerationRate="fast"
        onScrollBeginDrag={handleScrollBeginDrag}
        onScrollEndDrag={handleScrollEndDrag}
        onMomentumScrollEnd={handleMomentumEnd}
        onScrollToIndexFailed={handleScrollToIndexFailed}
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
            onPress={() => console.log('Ver libro:', book.title)}
          >
            {book.cover_image ? (
              <Image
                source={{ uri: `${API_BASE_URL}/img/${book.cover_image}` }}
                style={styles.carouselImage}
                blurRadius={40}
              />
            ) : (
              <View style={[styles.carouselImage, { backgroundColor: '#333' }]} />
            )}
            <View style={styles.carouselGradient} />

            <View style={styles.carouselContent}>
              <View style={styles.carouselBookCover}>
                {book.cover_image ? (
                  <Image
                    source={{ uri: `${API_BASE_URL}/img/${book.cover_image}` }}
                    style={styles.carouselCoverImage}
                    resizeMode="cover"
                  />
                ) : (
                  <View style={styles.bookImagePlaceholder}>
                    <Ionicons name="book" size={60} color="#999" />
                  </View>
                )}
              </View>

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
                    style={styles.carouselButton} onPress={(e) => {e.stopPropagation();handleAddToCart(book);
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
}, [featuredBooks, handleAddToCart, API_BASE_URL]); 



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

  const renderBook = useCallback(({ item, index }) => {
    const statusConfig = getStatusConfig(item.stock_quantity);
    const isBookFavorite = isFavorite(item.book_id);
    const cardSize = getCardSize(index);

    return (
      <View style={[
        styles.bookCard,
        darkMode && styles.bookCardDark,
        cardSize === 'large' && styles.bookCardLarge,
      ]}>
        <TouchableOpacity 
          activeOpacity={0.8}
          onPress={() => console.log("Ver detalles del libro:", item.title)}
          style={styles.bookCardInner}
        >
          <View style={[
            styles.bookImageContainer,
            cardSize === 'large' && styles.bookImageContainerLarge
          ]}>
            {item.cover_image ? (
              <Image 
                source={{ uri: `${API_BASE_URL}/img/${item.cover_image}` }}
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
                onPress={() => handleAddToCart(item)}
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
      </View>
    );
  }, [darkMode, getStatusConfig, isFavorite, getCardSize, toggleFavorite, handleAddToCart, API_BASE_URL]);

  const ListHeaderComponent = useCallback(() => (
    <>
      {renderHeroCarousel()}
      {renderSearchAndFilters()}
    </>
  ), [renderHeroCarousel, renderSearchAndFilters]);

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

  if (loading) {
    return (
      <View style={[styles.loadingContainer, darkMode && styles.loadingContainerDark]}>
        <ActivityIndicator size="large" color="#ffa3c2" />
        <Text style={[styles.loadingText, darkMode && styles.loadingTextDark]}>
          Cargando libros...
        </Text>
      </View>
    );
  }

  return (
    <View style={[styles.container, darkMode && styles.containerDark]}>
      <StatusBar 
        barStyle={darkMode ? "light-content" : "dark-content"} 
        backgroundColor={darkMode ? "#1A1A1A" : "#fff"} 
      />
      
      {renderStaticHeader()}
      
      <Animated.FlatList
        data={filteredBooks}
        keyExtractor={(item) => item.book_id.toString()}
        renderItem={renderBook}
        ListHeaderComponent={ListHeaderComponent}
        ListEmptyComponent={ListEmptyComponent}
        contentContainerStyle={styles.listContent}
        numColumns={2}
        key="book-list-2-columns"
        columnWrapperStyle={styles.columnWrapper}
        onScroll={Animated.event(
          [{ nativeEvent: { contentOffset: { y: scrollY } } }],
          { useNativeDriver: false }
        )}
        scrollEventThrottle={16}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            colors={["#ffa3c2"]}
            tintColor="#ffa3c2"
          />
        }
        removeClippedSubviews={true}
        maxToRenderPerBatch={10}
        updateCellsBatchingPeriod={50}
        initialNumToRender={6}
        windowSize={5}
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
  containerDark: {
    backgroundColor: "#121212",
  },
  
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
    elevation: 3,
  },
  staticHeaderDark: {
    backgroundColor: "rgba(26, 26, 26, 0.98)",
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

  carouselContainer: {
    height: 300,
    marginBottom: 16,
    marginTop:16,
  },
  carouselSlide: {
    width: SCREEN_WIDTH,
    height: 400,
    position: 'relative',
  },
  carouselImage: {
    width: '100%',
    height: '100%',
    position: 'absolute',
  },
  carouselGradient: {
    position: 'absolute',
    width: '100%',
    height: '100%',
    backgroundColor: 'rgba(0,0,0,0.5)',
  },
  carouselContent: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'flex-top',
    marginTop:50,
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
    textShadowColor: 'rgba(0, 0, 0, 0.5)',
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
    width: 24,
    backgroundColor: '#ffa3c2',
  },
  
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
    backgroundColor: "#ffa3c2",
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
  
  resultsText: {
    paddingHorizontal: 16,
    paddingTop: 8,
    fontSize: 14,
    color: "#666",
  },
  resultsTextDark: {
    color: "#999",
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
    borderRadius: 16,
    margin: 8,
    overflow: "hidden",
    elevation: 3,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
  },
  bookCardDark: {
    backgroundColor: "#1E1E1E",
  },
  bookCardLarge: {
    flex: 2,
    minHeight: 420,
  },
  bookCardInner: {
    flex: 1,
  },
  
  bookImageContainer: {
    position: "relative",
    width: "100%",
    height: 220,
    backgroundColor: "#F5F5F5",
  },
  bookImageContainerLarge: {
    height: 280,
  },
  bookImage: {
    width: "100%",
    height: "100%",
  },
  imageGradient: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    height: '40%',
    backgroundColor: 'transparent',
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
    top: 12,
    right: 12,
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 16,
    gap: 4,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
    elevation: 3,
  },
  statusText: {
    color: "#fff",
    fontSize: 11,
    fontWeight: "700",
  },
  
  favoriteButton: {
    position: "absolute",
    top: 12,
    left: 12,
    backgroundColor: "rgba(0, 0, 0, 0.5)",
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: "center",
    alignItems: "center",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.3,
    shadowRadius: 4,
    elevation: 3,
  },
  favoriteButtonActive: {
    backgroundColor: "rgba(255, 255, 255, 0.95)",
  },
  
  bookInfo: {
    padding: 14,
    flex: 1,
  },
  bookTitle: {
    fontSize: 16,
    fontWeight: "700",
    color: "#1A1A1A",
    marginBottom: 8,
    lineHeight: 22,
  },
  bookTitleDark: {
    color: "#fff",
  },
  
  authorRow: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 14,
    gap: 6,
  },
  bookAuthor: {
    fontSize: 13,
    color: "#666",
    flex: 1,
  },
  bookAuthorDark: {
    color: "#999",
  },
  
  bookFooter: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "flex-end",
    marginTop: 'auto',
  },
  priceLabel: {
    fontSize: 11,
    color: "#999",
    marginBottom: 4,
    fontWeight: "500",
  },
  priceLabelDark: {
    color: "#666",
  },
  bookPrice: {
    fontSize: 20,
    fontWeight: "bold",
    color: "#2E7D32",
  },
  
  addButton: {
    backgroundColor: "#2E7D32",
    width: 42,
    height: 42,
    borderRadius: 21,
    justifyContent: "center",
    alignItems: "center",
    shadowColor: "#2E7D32",
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.3,
    shadowRadius: 6,
    elevation: 4,
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
});