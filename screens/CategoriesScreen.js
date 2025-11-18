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
import { getColors } from '../constants/colors';
import { useTheme } from '../contexts/ThemeContext';

export default function CategoriesScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const API_BASE_URL = "http://10.0.2.2:8000"; 

  const fetchCategories = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/categories`);
      const data = await response.json();
      console.log("📂 Categorías obtenidas:", data.length);
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

  const getCategoryColor = (index) => {
    return colors.categoryColors[index % colors.categoryColors.length];
  };

  const renderCategory = ({ item, index }) => {
    if (!item || !item.name) {
      console.warn('⚠️ Categoría sin nombre:', item);
      return null;
    }

    const icon = getCategoryIcon(item.name);
    const color = getCategoryColor(index);
    const bookCount = item.books_count || 0;

    return (
      <TouchableOpacity
        style={[styles.categoryCard, { backgroundColor: colors.card }]}
        activeOpacity={0.7}
        onPress={() => {
          console.log('📂 Categoría seleccionada:', item.name);
          router.push({
            pathname: '/',
            params: { category: item.name }
          });
        }}
      >
        <View style={[styles.iconContainer, { backgroundColor: color }]}>
          <Ionicons name={icon} size={32} color="#fff" />
        </View>

        <View style={styles.categoryInfo}>
          <Text style={[styles.categoryName, { color: colors.text }]} numberOfLines={2}>
            {item.name}
          </Text>
          <View style={styles.bookCountContainer}>
            <Ionicons name="book" size={14} color={colors.textSecondary} />
            <Text style={[styles.bookCount, { color: colors.textSecondary }]}>
              {bookCount} {bookCount === 1 ? 'libro' : 'libros'}
            </Text>
          </View>
        </View>

        <Ionicons name="chevron-forward" size={20} color={colors.textTertiary} />
      </TouchableOpacity>
    );
  };

  if (loading) {
    return (
      <View style={[styles.loadingContainer, { backgroundColor: colors.background }]}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={[styles.loadingText, { color: colors.textSecondary }]}>
          Cargando categorías...
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
          onPress={() => router.push('/')}
        >
          <Ionicons name="arrow-back" size={24} color={colors.text} />
        </TouchableOpacity>

        <Text style={[styles.headerTitle, { color: colors.text }]}>Categorías</Text>
        
        <View style={{ width: 40 }} />
      </View>

      {/* Banner informativo */}
      <View style={[styles.banner, { backgroundColor: colors.card }]}>
        <View style={[styles.bannerIconContainer, { backgroundColor: colors.primaryLight }]}>
          <Ionicons name="apps" size={24} color={colors.primary} />
        </View>
        <View style={styles.bannerTextContainer}>
          <Text style={[styles.bannerTitle, { color: colors.text }]}>
            Explora por Categorías
          </Text>
          <Text style={[styles.bannerSubtitle, { color: colors.textSecondary }]}>
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
            colors={[colors.primary]}
            tintColor={colors.primary}
          />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="grid-outline" size={64} color={colors.textTertiary} />
            <Text style={[styles.emptyText, { color: colors.textTertiary }]}>
              No hay categorías disponibles
            </Text>
            <TouchableOpacity 
              style={[styles.retryButton, { backgroundColor: colors.primary }]}
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
  banner: {
    flexDirection: 'row',
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
    marginBottom: 4,
  },
  bannerSubtitle: {
    fontSize: 13,
  },
  listContent: {
    padding: 16,
  },
  categoryCard: {
    flexDirection: 'row',
    alignItems: 'center',
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
    marginBottom: 6,
  },
  bookCountContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
  },
  bookCount: {
    fontSize: 13,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 80,
  },
  emptyText: {
    fontSize: 16,
    marginTop: 16,
    marginBottom: 20,
  },
  retryButton: {
    flexDirection: 'row',
    alignItems: 'center',
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