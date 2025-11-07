import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import { useCallback, useEffect, useState } from "react";
import {
    ActivityIndicator,
    Alert,
    FlatList,
    Image,
    Platform,
    RefreshControl,
    StatusBar,
    StyleSheet,
    Text,
    TouchableOpacity,
    View
} from "react-native";
import { useCart } from '../app/CartContext';

export default function FavoritesScreen() {
  const router = useRouter();
  const { addToCart } = useCart();
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const API_BASE_URL = "http://localhost:8000";

  useEffect(() => {
    loadFavorites();
  }, []);

  const loadFavorites = async () => {
    try {
      const favData = await AsyncStorage.getItem('favorites');
      if (favData) {
        setFavorites(JSON.parse(favData));
      }
      setLoading(false);
      setRefreshing(false);
    } catch (error) {
      console.error("❌ Error cargando favoritos:", error);
      setLoading(false);
      setRefreshing(false);
    }
  };

  const removeFavorite = async (bookId) => {
    try {
      const updated = favorites.filter(item => item.book_id !== bookId);
      setFavorites(updated);
      await AsyncStorage.setItem('favorites', JSON.stringify(updated));
      
      if (Platform.OS === 'web') {
        alert('Eliminado de favoritos');
      } else {
        Alert.alert("Eliminado", "Se quitó de tus favoritos");
      }
    } catch (error) {
      console.error("❌ Error eliminando favorito:", error);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadFavorites();
  };

  const renderBook = useCallback(({ item }) => {
    return (
      <View style={styles.bookCard}>
        {/* Imagen */}
        <View style={styles.bookImageContainer}>
          {item.cover_image ? (
            <Image 
              source={{ uri: `${API_BASE_URL}/img/${item.cover_image}` }}
              style={styles.bookImage}
              resizeMode="cover"
            />
          ) : (
            <View style={styles.bookImagePlaceholder}>
              <Ionicons name="book" size={40} color="#999" />
            </View>
          )}
        </View>

        {/* Info */}
        <View style={styles.bookInfo}>
          <Text style={styles.bookTitle} numberOfLines={2}>
            {item.title}
          </Text>
          <Text style={styles.bookAuthor} numberOfLines={1}>
            {item.authors || "Autor desconocido"}
          </Text>
          <Text style={styles.bookPrice}>
            ${parseFloat(item.price).toFixed(2)}
          </Text>
        </View>

        {/* Acciones */}
        <View style={styles.bookActions}>
          <TouchableOpacity
            style={styles.addButton}
            onPress={() => {
              addToCart(item);
              if (Platform.OS === 'web') {
                alert('Agregado al carrito');
              } else {
                Alert.alert("Agregado", "Se agregó al carrito");
              }
            }}
          >
            <Ionicons name="cart" size={20} color="#fff" />
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.removeButton}
            onPress={() => removeFavorite(item.book_id)}
          >
            <Ionicons name="heart" size={20} color="#F44336" />
          </TouchableOpacity>
        </View>
      </View>
    );
  }, [favorites]);

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#ffa3c2" />
        <Text style={styles.loadingText}>Cargando favoritos...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#fff" />

      {/* Header */}
      <View style={styles.header}>
        <TouchableOpacity 
          style={styles.backButton}
          onPress={() => router.replace('/')}
        >
          <Ionicons name="arrow-back" size={24} color="#1A1A1A" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Favoritos</Text>
        <View style={{ width: 40 }} />
      </View>

      <FlatList
        data={favorites}
        keyExtractor={(item) => item.book_id.toString()}
        renderItem={renderBook}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} colors={["#ffa3c2"]} />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="heart-outline" size={64} color="#ccc" />
            <Text style={styles.emptyText}>No tienes favoritos</Text>
            <Text style={styles.emptySubtext}>Guarda libros que te interesen</Text>
            <TouchableOpacity 
              style={styles.exploreButton}
              onPress={() => router.replace('/')}
            >
              <Text style={styles.exploreButtonText}>Explorar libros</Text>
            </TouchableOpacity>
          </View>
        }
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
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingHorizontal: 16,
    paddingVertical: 16,
    backgroundColor: "#fff",
    borderBottomWidth: 1,
    borderBottomColor: "#E0E0E0",
  },
  backButton: {
    padding: 8,
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: "600",
    color: "#1A1A1A",
  },
  listContent: {
    padding: 16,
  },
  bookCard: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 12,
    marginBottom: 12,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  bookImageContainer: {
    width: 80,
    height: 110,
    borderRadius: 8,
    overflow: 'hidden',
    backgroundColor: '#F5F5F5',
  },
  bookImage: {
    width: '100%',
    height: '100%',
  },
  bookImagePlaceholder: {
    width: '100%',
    height: '100%',
    justifyContent: 'center',
    alignItems: 'center',
  },
  bookInfo: {
    flex: 1,
    marginLeft: 12,
    justifyContent: 'center',
  },
  bookTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: '#1A1A1A',
    marginBottom: 6,
  },
  bookAuthor: {
    fontSize: 13,
    color: '#666',
    marginBottom: 8,
  },
  bookPrice: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#2E7D32',
  },
  bookActions: {
    justifyContent: 'center',
    alignItems: 'center',
    gap: 12,
  },
  addButton: {
    backgroundColor: '#2E7D32',
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  removeButton: {
    backgroundColor: '#FFE5E5',
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 80,
  },
  emptyText: {
    fontSize: 18,
    fontWeight: '600',
    color: '#666',
    marginTop: 16,
  },
  emptySubtext: {
    fontSize: 14,
    color: '#999',
    marginTop: 8,
    marginBottom: 24,
  },
  exploreButton: {
    backgroundColor: '#ffa3c2',
    paddingHorizontal: 24,
    paddingVertical: 12,
    borderRadius: 8,
  },
  exploreButtonText: {
    color: '#fff',
    fontSize: 15,
    fontWeight: '600',
  },
});