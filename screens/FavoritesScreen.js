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
import { getColors } from '../constants/colors';
import { useCart } from '../contexts/CartContext';
import { useTheme } from '../contexts/ThemeContext';

export default function FavoritesScreen() {
  const router = useRouter();
  const { addToCart } = useCart();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  
  const [favorites, setFavorites] = useState([]);
  const [favKey, setFavKey] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const API_BASE_URL = "http://localhost:8000";

  useEffect(() => {
    loadUser();
  }, []);

  const loadUser = async () => {
    try{
      const raw = await AsyncStorage.getItem('userData');
      console.log("RAW USER DATA =", raw);
      if(!raw){
        setFavKey(null);
        return;
      }
      const user = JSON.parse(raw);
      setFavKey(`favorites_${user.user_id}`);
    } catch(error){
      console.error("Error leyendo Información de Usuario", error);
      setFavKey(null);
    }
  };

  useEffect(() => {
    if(!favKey){
      setLoading(false);
      return;
    }
    loadFavorites();
  }, [favKey]);

  const loadFavorites = async () => {
    try {
      const favData = await AsyncStorage.getItem(favKey);
      setFavorites(favData ? JSON.parse(favData):[]);
    } catch (error) {
      console.error("Error cargando favoritos:", error);
    }
      setLoading(false);
      setRefreshing(false);
  };

  const removeFavorite = async (bookId) => {
    try {
      const updated = favorites.filter(item => item.book_id !== bookId);
      setFavorites(updated);
      await AsyncStorage.setItem(favKey, JSON.stringify(updated));
      
      if (Platform.OS === 'web') {
        alert('Eliminado de favoritos');
      } else {
        Alert.alert("Eliminado", "Se quitó de tus favoritos");
      }
    } catch (error) {
      console.error("Error eliminando favorito:", error);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadFavorites();
  };

  const renderBook = useCallback(({ item }) => {
    return (
      <View style={[styles.bookCard, { backgroundColor: colors.card }]}>
        {/* Imagen */}
        <View style={[styles.bookImageContainer, { backgroundColor: colors.surface }]}>
          {item.cover_image ? (
            <Image 
              source={{ uri: `${API_BASE_URL}/img/${item.cover_image}` }}
              style={styles.bookImage}
              resizeMode="cover"
            />
          ) : (
            <View style={styles.bookImagePlaceholder}>
              <Ionicons name="book" size={40} color={colors.textTertiary} />
            </View>
          )}
        </View>

        {/* Info */}
        <View style={styles.bookInfo}>
          <Text style={[styles.bookTitle, { color: colors.text }]} numberOfLines={2}>
            {item.title}
          </Text>
          <Text style={[styles.bookAuthor, { color: colors.textSecondary }]} numberOfLines={1}>
            {item.authors || "Autor desconocido"}
          </Text>
          <Text style={[styles.bookPrice, { color: colors.success }]}>
            ${parseFloat(item.price).toFixed(2)}
          </Text>
        </View>

        {/* Acciones */}
        <View style={styles.bookActions}>
          <TouchableOpacity
            style={[styles.addButton, { backgroundColor: colors.success }]}
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
            style={[styles.removeButton, { 
              backgroundColor: darkMode ? '#4d2020' : '#FFE5E5' 
            }]}
            onPress={() => removeFavorite(item.book_id)}
          >
            <Ionicons name="heart" size={20} color={colors.error} />
          </TouchableOpacity>
        </View>
      </View>
    );
  }, [favorites, darkMode]);

  if (loading) {
    return (
      <View style={[styles.loadingContainer, { backgroundColor: colors.background }]}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={[styles.loadingText, { color: colors.textSecondary }]}>
          Cargando favoritos...
        </Text>
      </View>
    );
  }

  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <StatusBar barStyle={colors.statusBar} backgroundColor={colors.surface} />

      {/* Header */}
      <View style={[styles.header, { 
        backgroundColor: colors.surface,
        borderBottomColor: colors.border 
      }]}>
        <TouchableOpacity 
          style={styles.backButton}
          onPress={() => router.replace('/')}
        >
          <Ionicons name="arrow-back" size={24} color={colors.text} />
        </TouchableOpacity>
        <Text style={[styles.headerTitle, { color: colors.text }]}>Favoritos</Text>
        <View style={{ width: 40 }} />
      </View>

      <FlatList
        data={favorites}
        keyExtractor={(item) => item.book_id.toString()}
        renderItem={renderBook}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl 
            refreshing={refreshing} 
            onRefresh={onRefresh} 
            colors={[colors.primary]}
            tintColor={colors.primary}
          />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="heart-outline" size={64} color={colors.textTertiary} />
            <Text style={[styles.emptyText, { color: colors.text }]}>
              No tienes favoritos
            </Text>
            <Text style={[styles.emptySubtext, { color: colors.textSecondary }]}>
              Guarda libros que te interesen
            </Text>
            <TouchableOpacity 
              style={[styles.exploreButton, { backgroundColor: colors.primary }]}
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
  },
  loadingContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
  },
  loadingText: {
    marginTop: 12,
    fontSize: 16,
  },
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingHorizontal: 16,
    paddingVertical: 16,
    borderBottomWidth: 1,
  },
  backButton: {
    padding: 8,
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: "600",
  },
  listContent: {
    padding: 16,
  },
  bookCard: {
    flexDirection: 'row',
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
    marginBottom: 6,
  },
  bookAuthor: {
    fontSize: 13,
    marginBottom: 8,
  },
  bookPrice: {
    fontSize: 18,
    fontWeight: 'bold',
  },
  bookActions: {
    justifyContent: 'center',
    alignItems: 'center',
    gap: 12,
  },
  addButton: {
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  removeButton: {
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
    marginTop: 16,
  },
  emptySubtext: {
    fontSize: 14,
    marginTop: 8,
    marginBottom: 24,
  },
  exploreButton: {
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