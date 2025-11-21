import { Ionicons } from "@expo/vector-icons";
import { useRouter } from 'expo-router';
import { useEffect, useMemo, useRef, useState } from "react";
import {
  Animated,
  Dimensions,
  ScrollView,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from "react-native";
import { BarChart, LineChart } from 'react-native-chart-kit';
import { API_ENDPOINTS, apiFetch } from '../config/api';
import { useTheme } from '../contexts/ThemeContext';

const { width: SCREEN_WIDTH } = Dimensions.get('window');

export default function StatsScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const [books, setBooks] = useState([]);
  const [loading, setLoading] = useState(true);
  
  // Animaciones mejoradas
  const fadeAnim = useRef(new Animated.Value(0)).current;
  const slideAnim = useRef(new Animated.Value(50)).current;
  const scaleAnim = useRef(new Animated.Value(0.9)).current;

  useEffect(() => {
    loadBooks();
  }, []);

  const loadBooks = async () => {
    try {
      const data = await apiFetch(API_ENDPOINTS.books);
      setBooks(data);
      setLoading(false);
      animateEntry();
    } catch (error) {
      console.error("Error cargando libros:", error);
      setLoading(false);
    }
  };

  const animateEntry = () => {
    Animated.parallel([
      Animated.timing(fadeAnim, {
        toValue: 1,
        duration: 600,
        useNativeDriver: true,
      }),
      Animated.timing(slideAnim, {
        toValue: 0,
        duration: 600,
        useNativeDriver: true,
      }),
      Animated.spring(scaleAnim, {
        toValue: 1,
        tension: 50,
        friction: 7,
        useNativeDriver: true,
      }),
    ]).start();
  };

  // Cálculos optimizados
  const stats = useMemo(() => {
    if (books.length === 0) return null;

    const categoryCount = {};
    const categoryRevenue = {};
    let totalValue = 0;
    let totalStock = 0;
    let available = 0;
    let soldOut = 0;
    let lowStock = 0;

    books.forEach(book => {
      const price = parseFloat(book.price);
      const stock = book.stock_quantity;
      const category = book.category_name;

      categoryCount[category] = (categoryCount[category] || 0) + 1;
      categoryRevenue[category] = (categoryRevenue[category] || 0) + (price * stock);

      totalValue += price * stock;
      totalStock += stock;

      if (stock === 0) soldOut++;
      else if (stock <= 5) lowStock++;
      else available++;
    });

    const topCategories = Object.entries(categoryRevenue)
      .sort(([, a], [, b]) => b - a)
      .slice(0, 5);

    const priceRanges = [0, 0, 0, 0, 0];
    books.forEach(book => {
      const price = parseFloat(book.price);
      if (price < 100) priceRanges[0]++;
      else if (price < 200) priceRanges[1]++;
      else if (price < 300) priceRanges[2]++;
      else if (price < 400) priceRanges[3]++;
      else priceRanges[4]++;
    });

    const bestSeller = books.reduce((max, book) => 
      book.stock_quantity > max.stock_quantity ? book : max
    );

    const avgPrice = books.reduce((sum, b) => sum + parseFloat(b.price), 0) / books.length;

    const mostExpensive = books.reduce((max, book) => 
      parseFloat(book.price) > parseFloat(max.price) ? book : max
    );

    return {
      categoryCount,
      categoryRevenue,
      totalValue,
      totalStock,
      available,
      soldOut,
      lowStock,
      topCategories,
      priceRanges,
      bestSeller,
      avgPrice,
      mostExpensive,
      totalBooks: books.length,
      availabilityRate: ((available / books.length) * 100).toFixed(0)
    };
  }, [books]);

  if (loading) {
    return (
      <View style={[styles.loadingContainer, darkMode && styles.loadingContainerDark]}>
        <Text style={[styles.loadingText, darkMode && styles.loadingTextDark]}>
          Cargando estadísticas...
        </Text>
      </View>
    );
  }

  if (!stats) return null;

  // Configuración única para todas las gráficas
  const chartConfig = {
    backgroundColor: darkMode ? '#1E1E1E' : '#fff',
    backgroundGradientFrom: darkMode ? '#1E1E1E' : '#fff',
    backgroundGradientTo: darkMode ? '#2A2A2A' : '#f8f8f8',
    decimalPlaces: 0,
    color: (opacity = 1) => `rgba(255, 163, 194, ${opacity})`,
    labelColor: (opacity = 1) => darkMode ? `rgba(255, 255, 255, ${opacity})` : `rgba(26, 26, 26, ${opacity})`,
    propsForDots: {
      r: '6',
      strokeWidth: '2',
      stroke: '#ffa3c2'
    },
    propsForBackgroundLines: {
      strokeDasharray: '',
      stroke: darkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)',
    },
  };

  return (
    <View style={[styles.container, darkMode && styles.containerDark]}>
      <StatusBar barStyle={darkMode ? "light-content" : "dark-content"} />

      {/* Header mejorado */}
      <View style={[styles.header, darkMode && styles.headerDark]}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backButton}>
          <Ionicons name="arrow-back" size={24} color={darkMode ? "#fff" : "#000"} />
        </TouchableOpacity>
        <View style={styles.headerContent}>
          <Text style={[styles.headerTitle, darkMode && styles.headerTitleDark]}>
            📊 Estadísticas
          </Text>
          <Text style={[styles.headerSubtitle, darkMode && styles.headerSubtitleDark]}>
            Análisis de inventario
          </Text>
        </View>
        <View style={{ width: 40 }} />
      </View>

      <ScrollView 
        style={styles.scrollView} 
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContent}
      >
        {/* Cards superiores - 2x2 Grid */}
        <Animated.View 
          style={[
            styles.metricsGrid,
            {
              opacity: fadeAnim,
              transform: [{ translateY: slideAnim }]
            }
          ]}
        >
          <View style={styles.metricsRow}>
            <View style={[styles.metricCard, styles.metricCardPrimary]}>
              <Text style={styles.metricEmoji}>📚</Text>
              <Text style={styles.metricValue}>{stats.totalBooks}</Text>
              <Text style={styles.metricLabel}>Total Libros</Text>
            </View>

            <View style={[styles.metricCard, styles.metricCardSuccess]}>
              <Text style={styles.metricEmoji}>💰</Text>
              <Text style={styles.metricValue}>
                ${(stats.totalValue / 1000).toFixed(1)}K
              </Text>
              <Text style={styles.metricLabel}>Valor Total</Text>
            </View>
          </View>

          <View style={styles.metricsRow}>
            <View style={[styles.metricCard, styles.metricCardInfo]}>
              <Text style={styles.metricEmoji}>📦</Text>
              <Text style={styles.metricValue}>{stats.totalStock}</Text>
              <Text style={styles.metricLabel}>En Stock</Text>
            </View>

            <View style={[styles.metricCard, styles.metricCardWarning]}>
              <Text style={styles.metricEmoji}>⚡</Text>
              <Text style={styles.metricValue}>{stats.availabilityRate}%</Text>
              <Text style={styles.metricLabel}>Disponible</Text>
            </View>
          </View>
        </Animated.View>

        {/* Estado del inventario con barras */}
        <Animated.View 
          style={[
            styles.statusCard,
            darkMode && styles.statusCardDark,
            { opacity: fadeAnim, transform: [{ scale: scaleAnim }] }
          ]}
        >
          <Text style={[styles.cardTitle, darkMode && styles.cardTitleDark]}>
            🎯 Estado del Inventario
          </Text>
          
          <View style={styles.statusBars}>
            <View style={styles.statusItem}>
              <View style={styles.statusHeader}>
                <Text style={[styles.statusLabel, darkMode && styles.statusLabelDark]}>
                  Disponible
                </Text>
                <Text style={[styles.statusNumber, { color: '#4CAF50' }]}>
                  {stats.available}
                </Text>
              </View>
              <View style={styles.barContainer}>
                <View 
                  style={[
                    styles.barFill, 
                    { 
                      width: `${(stats.available / stats.totalBooks) * 100}%`,
                      backgroundColor: '#4CAF50'
                    }
                  ]} 
                />
              </View>
            </View>

            <View style={styles.statusItem}>
              <View style={styles.statusHeader}>
                <Text style={[styles.statusLabel, darkMode && styles.statusLabelDark]}>
                  Bajo Stock
                </Text>
                <Text style={[styles.statusNumber, { color: '#FF9800' }]}>
                  {stats.lowStock}
                </Text>
              </View>
              <View style={styles.barContainer}>
                <View 
                  style={[
                    styles.barFill, 
                    { 
                      width: `${(stats.lowStock / stats.totalBooks) * 100}%`,
                      backgroundColor: '#FF9800'
                    }
                  ]} 
                />
              </View>
            </View>

            <View style={styles.statusItem}>
              <View style={styles.statusHeader}>
                <Text style={[styles.statusLabel, darkMode && styles.statusLabelDark]}>
                  Agotado
                </Text>
                <Text style={[styles.statusNumber, { color: '#F44336' }]}>
                  {stats.soldOut}
                </Text>
              </View>
              <View style={styles.barContainer}>
                <View 
                  style={[
                    styles.barFill, 
                    { 
                      width: `${(stats.soldOut / stats.totalBooks) * 100}%`,
                      backgroundColor: '#F44336'
                    }
                  ]} 
                />
              </View>
            </View>
          </View>
        </Animated.View>

        {/* Gráfica de distribución de precios */}
        <Animated.View 
          style={[
            styles.chartCard,
            darkMode && styles.chartCardDark,
            { opacity: fadeAnim }
          ]}
        >
          <Text style={[styles.cardTitle, darkMode && styles.cardTitleDark]}>
            💵 Distribución de Precios
          </Text>
          <Text style={[styles.chartSubtitle, darkMode && styles.chartSubtitleDark]}>
            Cantidad de libros por rango de precio
          </Text>
          
          <LineChart
            data={{
              labels: ['<$100', '$100-200', '$200-300', '$300-400', '>$400'],
              datasets: [{ 
                data: stats.priceRanges.length > 0 ? stats.priceRanges : [1],
                color: (opacity = 1) => `rgba(255, 163, 194, ${opacity})`,
                strokeWidth: 3
              }]
            }}
            width={SCREEN_WIDTH - 64}
            height={220}
            chartConfig={chartConfig}
            bezier
            style={styles.chart}
            withInnerLines={true}
            withVerticalLines={false}
          />
        </Animated.View>

        {/* Top categorías */}
        <Animated.View 
          style={[
            styles.chartCard,
            darkMode && styles.chartCardDark,
            { opacity: fadeAnim }
          ]}
        >
          <Text style={[styles.cardTitle, darkMode && styles.cardTitleDark]}>
            🏆 Top Categorías por Ingresos
          </Text>
          <Text style={[styles.chartSubtitle, darkMode && styles.chartSubtitleDark]}>
            Valor total en inventario por categoría
          </Text>
          
          <BarChart
            data={{
              labels: stats.topCategories.map(([cat]) => 
                cat.length > 8 ? cat.substring(0, 7) + '.' : cat
              ),
              datasets: [{
                data: stats.topCategories.length > 0 
                  ? stats.topCategories.map(([, rev]) => Math.round(rev))
                  : [1]
              }]
            }}
            width={SCREEN_WIDTH - 64}
            height={240}
            chartConfig={{
              ...chartConfig,
              fillShadowGradientFrom: '#ffa3c2',
              fillShadowGradientTo: '#ff8fb3',
              fillShadowGradientOpacity: 1,
            }}
            style={styles.chart}
            showValuesOnTopOfBars={true}
            fromZero={true}
            withInnerLines={false}
          />
        </Animated.View>

        {/* Insights mejorados */}
        <Animated.View 
          style={[
            styles.insightsCard,
            darkMode && styles.insightsCardDark,
            { opacity: fadeAnim }
          ]}
        >
          <Text style={[styles.cardTitle, darkMode && styles.cardTitleDark]}>
            💡 Insights Destacados
          </Text>

          <View style={styles.insightItem}>
            <View style={[styles.insightIcon, { backgroundColor: '#4CAF50' }]}>
              <Text style={styles.insightIconText}>🌟</Text>
            </View>
            <View style={styles.insightContent}>
              <Text style={[styles.insightLabel, darkMode && styles.insightLabelDark]}>
                Mejor Stock
              </Text>
              <Text style={[styles.insightValue, darkMode && styles.insightValueDark]} numberOfLines={1}>
                {stats.bestSeller.title}
              </Text>
              <Text style={[styles.insightDetail, darkMode && styles.insightDetailDark]}>
                {stats.bestSeller.stock_quantity} unidades disponibles
              </Text>
            </View>
          </View>

          <View style={[styles.insightDivider, darkMode && styles.insightDividerDark]} />

          <View style={styles.insightItem}>
            <View style={[styles.insightIcon, { backgroundColor: '#FFD700' }]}>
              <Text style={styles.insightIconText}>💎</Text>
            </View>
            <View style={styles.insightContent}>
              <Text style={[styles.insightLabel, darkMode && styles.insightLabelDark]}>
                Más Valioso
              </Text>
              <Text style={[styles.insightValue, darkMode && styles.insightValueDark]} numberOfLines={1}>
                {stats.mostExpensive.title}
              </Text>
              <Text style={[styles.insightDetail, darkMode && styles.insightDetailDark]}>
                ${parseFloat(stats.mostExpensive.price).toFixed(2)}
              </Text>
            </View>
          </View>

          <View style={[styles.insightDivider, darkMode && styles.insightDividerDark]} />

          <View style={styles.insightItem}>
            <View style={[styles.insightIcon, { backgroundColor: '#FF5722' }]}>
              <Text style={styles.insightIconText}>📊</Text>
            </View>
            <View style={styles.insightContent}>
              <Text style={[styles.insightLabel, darkMode && styles.insightLabelDark]}>
                Precio Promedio
              </Text>
              <Text style={[styles.insightValue, darkMode && styles.insightValueDark]}>
                ${stats.avgPrice.toFixed(2)}
              </Text>
              <Text style={[styles.insightDetail, darkMode && styles.insightDetailDark]}>
                En toda la librería
              </Text>
            </View>
          </View>
        </Animated.View>

        <View style={{ height: 40 }} />
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F5F5F5',
  },
  containerDark: {
    backgroundColor: '#121212',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#F5F5F5',
  },
  loadingContainerDark: {
    backgroundColor: '#121212',
  },
  loadingText: {
    fontSize: 16,
    color: '#666',
  },
  loadingTextDark: {
    color: '#ccc',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingTop: 50,
    paddingBottom: 16,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#E0E0E0',
  },
  headerDark: {
    backgroundColor: '#1A1A1A',
    borderBottomColor: '#333',
  },
  backButton: {
    padding: 8,
  },
  headerContent: {
    alignItems: 'center',
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: '600',
    color: '#1A1A1A',
  },
  headerTitleDark: {
    color: '#fff',
  },
  headerSubtitle: {
    fontSize: 12,
    color: '#666',
  },
  headerSubtitleDark: {
    color: '#999',
  },
  scrollView: {
    flex: 1,
  },
  scrollContent: {
    padding: 16,
  },
  metricsGrid: {
    gap: 12,
    marginBottom: 16,
  },
  metricsRow: {
    flexDirection: 'row',
    gap: 12,
  },
  metricCard: {
    flex: 1,
    padding: 20,
    borderRadius: 16,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 4,
  },
  metricCardPrimary: {
    backgroundColor: '#ffa3c2',
  },
  metricCardSuccess: {
    backgroundColor: '#4CAF50',
  },
  metricCardInfo: {
    backgroundColor: '#2196F3',
  },
  metricCardWarning: {
    backgroundColor: '#FF9800',
  },
  metricEmoji: {
    fontSize: 32,
    marginBottom: 8,
  },
  metricValue: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 4,
  },
  metricLabel: {
    fontSize: 12,
    color: 'rgba(255,255,255,0.9)',
    fontWeight: '500',
  },
  statusCard: {
    backgroundColor: '#fff',
    padding: 20,
    borderRadius: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 4,
  },
  statusCardDark: {
    backgroundColor: '#1E1E1E',
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1A1A1A',
    marginBottom: 16,
  },
  cardTitleDark: {
    color: '#fff',
  },
  statusBars: {
    gap: 20,
  },
  statusItem: {
    gap: 8,
  },
  statusHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  statusLabel: {
    fontSize: 14,
    color: '#666',
    fontWeight: '500',
  },
  statusLabelDark: {
    color: '#aaa',
  },
  statusNumber: {
    fontSize: 18,
    fontWeight: 'bold',
  },
  barContainer: {
    height: 12,
    backgroundColor: '#f0f0f0',
    borderRadius: 6,
    overflow: 'hidden',
  },
  barFill: {
    height: '100%',
    borderRadius: 6,
  },
  chartCard: {
    backgroundColor: '#fff',
    padding: 20,
    borderRadius: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 4,
  },
  chartCardDark: {
    backgroundColor: '#1E1E1E',
  },
  chartSubtitle: {
    fontSize: 12,
    color: '#999',
    marginBottom: 16,
  },
  chartSubtitleDark: {
    color: '#666',
  },
  chart: {
    marginVertical: 8,
    borderRadius: 16,
  },
  insightsCard: {
    backgroundColor: '#fff',
    padding: 20,
    borderRadius: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 4,
  },
  insightsCardDark: {
    backgroundColor: '#1E1E1E',
  },
  insightItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 16,
  },
  insightIcon: {
    width: 50,
    height: 50,
    borderRadius: 25,
    justifyContent: 'center',
    alignItems: 'center',
  },
  insightIconText: {
    fontSize: 24,
  },
  insightContent: {
    flex: 1,
  },
  insightLabel: {
    fontSize: 12,
    color: '#999',
    marginBottom: 4,
  },
  insightLabelDark: {
    color: '#666',
  },
  insightValue: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1A1A1A',
    marginBottom: 2,
  },
  insightValueDark: {
    color: '#fff',
  },
  insightDetail: {
    fontSize: 12,
    color: '#666',
  },
  insightDetailDark: {
    color: '#999',
  },
  insightDivider: {
    height: 1,
    backgroundColor: '#f0f0f0',
    marginVertical: 16,
  },
  insightDividerDark: {
    backgroundColor: '#333',
  },
});