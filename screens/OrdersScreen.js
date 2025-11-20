import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
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
import { API_BASE_URL } from '../config/api';
import { getColors } from '../constants/colors';
import { useTheme } from '../contexts/ThemeContext';

export default function OrdersScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);


  useEffect(() => {
    fetchOrders();
  }, []);

  const fetchOrders = async () => {
    try {
      const token = await AsyncStorage.getItem('userToken');
      if (!token) {
        setLoading(false);
        return;
      }

      const response = await fetch(`${API_BASE_URL}/api/orders`, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json' 
        }
      });

      if (response.ok) {
        const data = await response.json();
        console.log("📦 Órdenes obtenidas:", data.length);
        setOrders(data);
      }
      setLoading(false);
      setRefreshing(false);
    } catch (error) {
      console.error("❌ Error al obtener órdenes:", error);
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    fetchOrders();
  };

  const getStatusConfig = (status) => {
    switch (status?.toLowerCase()) {
      case 'pending':
        return { color: '#FFC107', icon: 'time-outline', text: 'Pendiente' };
      case 'processing':
        return { color: '#2196F3', icon: 'sync-outline', text: 'Procesando' };
      case 'shipped':
        return { color: '#9C27B0', icon: 'airplane-outline', text: 'Enviado' };
      case 'delivered':
        return { color: '#4CAF50', icon: 'checkmark-circle', text: 'Entregado' };
      case 'cancelled':
        return { color: '#F44336', icon: 'close-circle', text: 'Cancelado' };
      default:
        return { color: '#999', icon: 'help-circle', text: status || 'Desconocido' };
    }
  };

  const renderOrder = ({ item }) => {
    const statusConfig = getStatusConfig(item.status);
    const orderDate = new Date(item.created_at).toLocaleDateString('es-MX');
    const total = parseFloat(item.total_amount || 0);

    return (
      <TouchableOpacity
        style={[styles.orderCard, { backgroundColor: colors.card }]}
        activeOpacity={0.7}
        onPress={() => {
          console.log('Ver orden:', item.order_id);
        }}
      >
        <View style={[styles.orderHeader, { borderBottomColor: colors.borderLight }]}>
          <View>
            <Text style={[styles.orderId, { color: colors.text }]}>
              Orden #{item.order_id}
            </Text>
            <Text style={[styles.orderDate, { color: colors.textSecondary }]}>
              {orderDate}
            </Text>
          </View>
          <View style={[styles.statusBadge, { backgroundColor: statusConfig.color }]}>
            <Ionicons name={statusConfig.icon} size={14} color="#fff" />
            <Text style={styles.statusText}>{statusConfig.text}</Text>
          </View>
        </View>

        <View style={styles.orderBody}>
          <View style={styles.orderRow}>
            <Ionicons name="cube-outline" size={18} color={colors.textSecondary} />
            <Text style={[styles.orderInfo, { color: colors.textSecondary }]}>
              {item.items_count || 0} {item.items_count === 1 ? 'artículo' : 'artículos'}
            </Text>
          </View>
          
          {item.shipping_address && (
            <View style={styles.orderRow}>
              <Ionicons name="location-outline" size={18} color={colors.textSecondary} />
              <Text style={[styles.orderInfo, { color: colors.textSecondary }]} numberOfLines={1}>
                {item.shipping_address}
              </Text>
            </View>
          )}
        </View>

        <View style={[styles.orderFooter, { borderTopColor: colors.borderLight }]}>
          <View>
            <Text style={[styles.totalLabel, { color: colors.textTertiary }]}>
              Total
            </Text>
            <Text style={[styles.totalAmount, { color: colors.success }]}>
              ${total.toFixed(2)}
            </Text>
          </View>
          <Ionicons name="chevron-forward" size={20} color={colors.textTertiary} />
        </View>
      </TouchableOpacity>
    );
  };

  if (loading) {
    return (
      <View style={[styles.loadingContainer, { backgroundColor: colors.background }]}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={[styles.loadingText, { color: colors.textSecondary }]}>
          Cargando órdenes...
        </Text>
      </View>
    );
  }

  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <StatusBar barStyle={colors.statusBar} backgroundColor={colors.surface} />

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
        <Text style={[styles.headerTitle, { color: colors.text }]}>Mis Órdenes</Text>
        <View style={{ width: 40 }} />
      </View>

      <FlatList
        data={orders}
        keyExtractor={(item) => item.order_id?.toString()}
        renderItem={renderOrder}
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
            <Ionicons name="receipt-outline" size={64} color={colors.textTertiary} />
            <Text style={[styles.emptyText, { color: colors.text }]}>
              No tienes órdenes
            </Text>
            <Text style={[styles.emptySubtext, { color: colors.textSecondary }]}>
              Tus compras aparecerán aquí
            </Text>
            <TouchableOpacity 
              style={[styles.shopButton, { backgroundColor: colors.primary }]}
              onPress={() => router.replace('/')}
            >
              <Text style={styles.shopButtonText}>Explorar libros</Text>
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
  orderCard: {
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  orderHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    marginBottom: 12,
    paddingBottom: 12,
    borderBottomWidth: 1,
  },
  orderId: {
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 4,
  },
  orderDate: {
    fontSize: 13,
  },
  statusBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 12,
    gap: 4,
  },
  statusText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: '600',
  },
  orderBody: {
    marginBottom: 12,
    gap: 8,
  },
  orderRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  orderInfo: {
    fontSize: 14,
    flex: 1,
  },
  orderFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingTop: 12,
    borderTopWidth: 1,
  },
  totalLabel: {
    fontSize: 12,
    marginBottom: 2,
  },
  totalAmount: {
    fontSize: 20,
    fontWeight: 'bold',
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
  shopButton: {
    paddingHorizontal: 24,
    paddingVertical: 12,
    borderRadius: 8,
  },
  shopButtonText: {
    color: '#fff',
    fontSize: 15,
    fontWeight: '600',
  },
});