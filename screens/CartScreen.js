import { Ionicons } from "@expo/vector-icons";
import { useRouter } from 'expo-router';
import {
  Alert,
  FlatList,
  Image,
  Platform,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";
import { getColors } from '../constants/colors';
import { useCart } from "../contexts/CartContext";
import { useTheme } from '../contexts/ThemeContext';

export default function CartScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  
  const {
    cartItems,
    removeFromCart,
    incrementQuantity,
    decrementQuantity,
    clearCart,
    getCartTotal,
  } = useCart();

  const API_BASE_URL = "http://localhost:8000";

  const getImageUrl = (imagePath) => {
    if (!imagePath) return null;
    if (imagePath.startsWith('http')) return imagePath;
    return `${API_BASE_URL}/img/${imagePath}`;
  };

  const handleRemoveItem = (bookId, title) => {
    if (Platform.OS === 'web') {
      if (window.confirm(`¿Eliminar "${title}" del carrito?`)) {
        removeFromCart(bookId);
      }
    } else {
      Alert.alert(
        "Eliminar producto",
        `¿Deseas eliminar "${title}" del carrito?`,
        [
          { text: "Cancelar", style: "cancel" },
          { 
            text: "Eliminar", 
            style: "destructive",
            onPress: () => removeFromCart(bookId)
          }
        ]
      );
    }
  };

  const handleClearCart = () => {
    if (Platform.OS === 'web') {
      if (window.confirm("¿Vaciar todo el carrito?")) {
        clearCart();
      }
    } else {
      Alert.alert(
        "Vaciar carrito",
        "¿Estás seguro de que deseas eliminar todos los productos?",
        [
          { text: "Cancelar", style: "cancel" },
          { 
            text: "Vaciar", 
            style: "destructive",
            onPress: clearCart
          }
        ]
      );
    }
  };

  const handleCheckout = () => {
    console.log("Proceder al pago");
    if (Platform.OS === 'web') {
      alert("La función de checkout estará disponible pronto");
    } else {
      Alert.alert(
        "Próximamente",
        "La función de checkout estará disponible pronto"
      );
    }
  };

  const renderCartItem = ({ item }) => {
    const imageUrl = getImageUrl(item.cover_image);
    const subtotal = parseFloat(item.price) * item.quantity;

    return (
      <View style={[styles.cartItem, { backgroundColor: colors.card }]}>
        {/* Imagen del producto */}
        <View style={[styles.itemImageContainer, { backgroundColor: colors.surface }]}>
          {imageUrl ? (
            <Image
              source={{ uri: imageUrl }}
              style={styles.itemImage}
              resizeMode="cover"
            />
          ) : (
            <View style={styles.itemImagePlaceholder}>
              <Ionicons name="book" size={32} color={colors.textTertiary} />
            </View>
          )}
        </View>

        {/* Información del producto */}
        <View style={styles.itemInfo}>
          <Text style={[styles.itemTitle, { color: colors.text }]} numberOfLines={2}>
            {item.title}
          </Text>
          <Text style={[styles.itemAuthor, { color: colors.textSecondary }]} numberOfLines={1}>
            {item.authors || "Autor desconocido"}
          </Text>
          <Text style={[styles.itemPrice, { color: colors.success }]}>
            ${parseFloat(item.price).toFixed(2)}
          </Text>

          {/* Controles de cantidad */}
          <View style={styles.quantityControls}>
            <TouchableOpacity
              style={[styles.quantityButton, { 
                backgroundColor: colors.surface,
                borderColor: colors.border 
              }]}
              onPress={() => decrementQuantity(item.book_id)}
            >
              <Ionicons name="remove" size={18} color={colors.text} />
            </TouchableOpacity>

            <Text style={[styles.quantityText, { color: colors.text }]}>
              {item.quantity}
            </Text>

            <TouchableOpacity
              style={[
                styles.quantityButton,
                { 
                  backgroundColor: colors.surface,
                  borderColor: colors.border 
                },
                item.quantity >= item.stock_quantity && styles.quantityButtonDisabled
              ]}
              onPress={() => incrementQuantity(item.book_id)}
              disabled={item.quantity >= item.stock_quantity}
            >
              <Ionicons 
                name="add" 
                size={18} 
                color={item.quantity >= item.stock_quantity ? colors.textTertiary : colors.text} 
              />
            </TouchableOpacity>

            <Text style={[styles.subtotalText, { color: colors.success }]}>
              ${subtotal.toFixed(2)}
            </Text>
          </View>

          {/* Mensaje de stock */}
          {item.quantity >= item.stock_quantity && (
            <Text style={[styles.stockWarning, { color: colors.error }]}>
              Stock máximo alcanzado
            </Text>
          )}
        </View>

        {/* Botón eliminar */}
        <TouchableOpacity
          style={styles.deleteButton}
          onPress={() => handleRemoveItem(item.book_id, item.title)}
        >
          <Ionicons name="trash-outline" size={20} color={colors.error} />
        </TouchableOpacity>
      </View>
    );
  };

  const renderEmptyCart = () => (
    <View style={styles.emptyContainer}>
      <Ionicons name="cart-outline" size={80} color={colors.textTertiary} />
      <Text style={[styles.emptyTitle, { color: colors.text }]}>
        Tu carrito está vacío
      </Text>
      <Text style={[styles.emptySubtitle, { color: colors.textSecondary }]}>
        Agrega productos para comenzar tu compra
      </Text>
      <TouchableOpacity
        style={[styles.shopButton, { backgroundColor: colors.success }]}
        onPress={() => router.push('/')}
      >
        <Ionicons name="storefront-outline" size={20} color="#fff" />
        <Text style={styles.shopButtonText}>Explorar productos</Text>
      </TouchableOpacity>
    </View>
  );

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
          onPress={() => router.back()}
        >
          <Ionicons name="arrow-back" size={24} color={colors.text} />
        </TouchableOpacity>

        <Text style={[styles.headerTitle, { color: colors.text }]}>
          Artículos seleccionados
        </Text>

        {cartItems.length > 0 && (
          <TouchableOpacity
            style={styles.clearButton}
            onPress={handleClearCart}
          >
            <Text style={[styles.clearButtonText, { color: colors.error }]}>
              Vaciar
            </Text>
          </TouchableOpacity>
        )}

        {cartItems.length === 0 && <View style={{ width: 60 }} />}
      </View>

      {/* Contenido */}
      {cartItems.length === 0 ? (
        renderEmptyCart()
      ) : (
        <>
          <FlatList
            data={cartItems}
            keyExtractor={(item) => item.book_id.toString()}
            renderItem={renderCartItem}
            contentContainerStyle={styles.listContent}
            showsVerticalScrollIndicator={false}
          />

          {/* Footer con total y botón de pago */}
          <View style={[styles.footer, { 
            backgroundColor: colors.surface,
            borderTopColor: colors.border 
          }]}>
            <View style={styles.totalContainer}>
              <View>
                <Text style={[styles.totalLabel, { color: colors.textSecondary }]}>
                  Total
                </Text>
                <Text style={[styles.totalAmount, { color: colors.text }]}>
                  ${getCartTotal().toFixed(2)}
                </Text>
              </View>

              <TouchableOpacity
                style={[styles.checkoutButton, { backgroundColor: colors.success }]}
                onPress={handleCheckout}
              >
                <Text style={styles.checkoutButtonText}>
                  Comprar
                </Text>
                <Ionicons name="arrow-forward" size={20} color="#fff" />
              </TouchableOpacity>
            </View>
          </View>
        </>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
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
  clearButton: {
    padding: 8,
  },
  clearButtonText: {
    fontSize: 16,
    fontWeight: "500",
  },
  listContent: {
    padding: 16,
  },
  cartItem: {
    flexDirection: "row",
    borderRadius: 12,
    padding: 12,
    marginBottom: 12,
    elevation: 2,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  itemImageContainer: {
    width: 80,
    height: 110,
    borderRadius: 8,
    overflow: "hidden",
  },
  itemImage: {
    width: "100%",
    height: "100%",
  },
  itemImagePlaceholder: {
    width: "100%",
    height: "100%",
    justifyContent: "center",
    alignItems: "center",
  },
  itemInfo: {
    flex: 1,
    marginLeft: 12,
    marginRight: 8,
  },
  itemTitle: {
    fontSize: 15,
    fontWeight: "600",
    marginBottom: 4,
  },
  itemAuthor: {
    fontSize: 13,
    marginBottom: 8,
  },
  itemPrice: {
    fontSize: 16,
    fontWeight: "bold",
    marginBottom: 12,
  },
  quantityControls: {
    flexDirection: "row",
    alignItems: "center",
    gap: 8,
  },
  quantityButton: {
    width: 32,
    height: 32,
    borderRadius: 16,
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 1,
  },
  quantityButtonDisabled: {
    opacity: 0.5,
  },
  quantityText: {
    fontSize: 16,
    fontWeight: "600",
    minWidth: 30,
    textAlign: "center",
  },
  subtotalText: {
    fontSize: 14,
    fontWeight: "600",
    marginLeft: "auto",
  },
  stockWarning: {
    fontSize: 11,
    marginTop: 4,
  },
  deleteButton: {
    padding: 8,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    paddingHorizontal: 32,
  },
  emptyTitle: {
    fontSize: 20,
    fontWeight: "600",
    marginTop: 24,
    marginBottom: 8,
  },
  emptySubtitle: {
    fontSize: 16,
    textAlign: "center",
    marginBottom: 32,
  },
  shopButton: {
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 24,
    paddingVertical: 14,
    borderRadius: 12,
    gap: 8,
  },
  shopButtonText: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "600",
  },
  footer: {
    borderTopWidth: 1,
    paddingHorizontal: 16,
    paddingVertical: 16,
  },
  totalContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
  },
  totalLabel: {
    fontSize: 14,
    marginBottom: 4,
  },
  totalAmount: {
    fontSize: 24,
    fontWeight: "bold",
  },
  checkoutButton: {
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 24,
    paddingVertical: 14,
    borderRadius: 12,
    gap: 8,
  },
  checkoutButtonText: {
    color: "#fff",
    fontSize: 16,
    fontWeight: "600",
  },
});