import { Ionicons } from "@expo/vector-icons";
import { useRouter } from 'expo-router';
import { useEffect, useState } from "react";
import {
  ActivityIndicator,
  FlatList,
  RefreshControl,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from "react-native";

export default function CategoriesScreen() {
  const router = useRouter();
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const API_BASE_URL = "http://localhost:8000"; 

  // Obtener categorías desde la API
  const fetchCategories = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/categories`);
      const data = await response.json();
      console.log("📂 Categorías obtenidas:", data.length);
      console.log("📊 Primera categoría:", data[0]); // Para ver si books_count viene
      setCategories(data);
      setLoading(false);
      setRefreshing(false);
    } catch (error) {
      console.error("❌ Error al obtener categorías:", error);
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    fetchCategories();
  }, []);

  const onRefresh = () => {
    setRefreshing(true);
    fetchCategories();
  };

  // Iconos según categoría
  const getCategoryIcon = (categoryName) => {
    if (!categoryName || typeof categoryName !== 'string') {
      return 'book-outline';
    }
    
    const name = categoryName.toLowerCase();
    if (name.includes('infantiles') || name.includes('niños')) return 'happy-outline';
    if (name.includes('juveniles') || name.includes('adolescentes')) return 'school-outline';
    if (name.includes('textos escolares') || name.includes('universitarios')) return 'book-outline';
    if (name.includes('novedades') || name.includes('nuevo')) return 'sparkles-outline';
    if (name.includes('libros para todos') || name.includes('todos')) return 'library-outline';
    if (name.includes('autoayuda') || name.includes('motivación')) return 'fitness-outline';
    if (name.includes('cocina') || name.includes('recetas')) return 'restaurant-outline';
    if (name.includes('ciencia')) return 'flask-outline';
    if (name.includes('terror') || name.includes('horror')) return 'skull-outline';
    if (name.includes('tecnología') || name.includes('computación')) return 'laptop-outline';
    return 'book-outline';
  };

  // Colores según categoría
  const getCategoryColor = (index) => {
    const colors = [
      '#FF6B9D', // Rosa
      '#4ECDC4', // Turquesa
      '#95E1D3', // Verde agua
      '#FFD93D', // Amarillo
      '#6BCB77', // Verde
      '#4D96FF', // Azul
      '#C44569', // Rojo oscuro
      '#A8E6CF', // Verde claro
    ];
    return colors[index % colors.length];
  };

  const renderCategory = ({ item, index }) => {
    if (!item || !item.name) {
      console.warn('⚠️ Categoría sin nombre:', item);
      return null;
    }

    const icon = getCategoryIcon(item.name);
    const color = getCategoryColor(index);
    const bookCount = item.books_count || 0; // 👈 Ahora viene de la API

    return (
      <TouchableOpacity
        style={styles.categoryCard}
        activeOpacity={0.7}
        onPress={() => {
          console.log('📂 Categoría seleccionada:', item.name);
          router.push({
            pathname: '/',
            params: { category: item.name }
          });
        }}
      >
        {/* Icono de categoría */}
        <View style={[styles.iconContainer, { backgroundColor: color }]}>
          <Ionicons name={icon} size={32} color="#fff" />
        </View>

        {/* Información */}
        <View style={styles.categoryInfo}>
          <Text style={styles.categoryName} numberOfLines={2}>
            {item.name}
          </Text>
          <View style={styles.bookCountContainer}>
            <Ionicons name="book" size={14} color="#666" />
            <Text style={styles.bookCount}>
              {bookCount} {bookCount === 1 ? 'libro' : 'libros'}
            </Text>
          </View>
        </View>

        {/* Flecha */}
        <Ionicons name="chevron-forward" size={20} color="#999" />
      </TouchableOpacity>
    );
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#ffa3c2" />
        <Text style={styles.loadingText}>Cargando categorías...</Text>
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
          onPress={() => router.push('/')}
        >
          <Ionicons name="arrow-back" size={24} color="#1A1A1A" />
        </TouchableOpacity>

        <Text style={styles.headerTitle}>Categorías</Text>
        
        <View style={{ width: 40 }} />
      </View>

      {/* Banner informativo */}
      <View style={styles.banner}>
        <View style={styles.bannerIconContainer}>
          <Ionicons name="apps" size={24} color="#ffa3c2" />
        </View>
        <View style={styles.bannerTextContainer}>
          <Text style={styles.bannerTitle}>Explora por Categorías</Text>
          <Text style={styles.bannerSubtitle}>
            Encuentra libros organizados por tema
          </Text>
        </View>
      </View>

      {/* Lista de categorías */}
      <FlatList
        data={categories}
        keyExtractor={(item, index) => item.category_id?.toString() || `category-${index}`}
        renderItem={renderCategory}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            colors={["#ffa3c2"]}
          />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="grid-outline" size={64} color="#ccc" />
            <Text style={styles.emptyText}>No hay categorías disponibles</Text>
            <TouchableOpacity 
              style={styles.retryButton}
              onPress={fetchCategories}
            >
              <Ionicons name="refresh" size={20} color="#fff" />
              <Text style={styles.retryButtonText}>Reintentar</Text>
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
  banner: {
    flexDirection: 'row',
    backgroundColor: '#fff',
    marginHorizontal: 16,
    marginTop: 16,
    marginBottom: 8,
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  bannerIconContainer: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: '#fff5f9',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  bannerTextContainer: {
    flex: 1,
  },
  bannerTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#1A1A1A',
    marginBottom: 4,
  },
  bannerSubtitle: {
    fontSize: 13,
    color: '#666',
  },
  listContent: {
    padding: 16,
  },
  categoryCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  iconContainer: {
    width: 60,
    height: 60,
    borderRadius: 30,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 16,
  },
  categoryInfo: {
    flex: 1,
  },
  categoryName: {
    fontSize: 16,
    fontWeight: '600',
    color: '#1A1A1A',
    marginBottom: 6,
  },
  bookCountContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
  },
  bookCount: {
    fontSize: 13,
    color: '#666',
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 80,
  },
  emptyText: {
    fontSize: 16,
    color: '#999',
    marginTop: 16,
    marginBottom: 20,
  },
  retryButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffa3c2',
    paddingHorizontal: 20,
    paddingVertical: 12,
    borderRadius: 8,
    gap: 8,
  },
  retryButtonText: {
    color: '#fff',
    fontSize: 15,
    fontWeight: '600',
  },
});