import AsyncStorage from '@react-native-async-storage/async-storage';
import { createContext, useContext, useEffect, useState } from 'react';

const CartContext = createContext();

export const useCart = () => {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart debe usarse dentro de un CartProvider');
  }
  return context;
};

export const CartProvider = ({ children }) => {
  const [cartItems, setCartItems] = useState([]);
  const [loading, setLoading] = useState(true);

  // Cargar carrito desde AsyncStorage al iniciar
  useEffect(() => {
    loadCart();
  }, []);

  // Guardar carrito cada vez que cambie
  useEffect(() => {
    if (!loading) {
      saveCart();
    }
  }, [cartItems]);

  const loadCart = async () => {
    try {
      const cartData = await AsyncStorage.getItem('cart');
      if (cartData) {
        setCartItems(JSON.parse(cartData));
      }
      setLoading(false);
    } catch (error) {
      console.error('Error cargando carrito:', error);
      setLoading(false);
    }
  };

  const saveCart = async () => {
    try {
      await AsyncStorage.setItem('cart', JSON.stringify(cartItems));
    } catch (error) {
      console.error('Error guardando carrito:', error);
    }
  };

  // Agregar producto al carrito
  const addToCart = (product) => {
    setCartItems((prevItems) => {
      const existingItem = prevItems.find(item => item.book_id === product.book_id);
      
      if (existingItem) {
        // Si ya existe, incrementa la cantidad
        return prevItems.map(item =>
          item.book_id === product.book_id
            ? { ...item, quantity: item.quantity + 1 }
            : item
        );
      } else {
        // Si no existe, agrégalo con cantidad 1
        return [...prevItems, { ...product, quantity: 1 }];
      }
    });
  };

  // Eliminar producto del carrito
  const removeFromCart = (bookId) => {
    setCartItems(prevItems => prevItems.filter(item => item.book_id !== bookId));
  };

  // Actualizar cantidad de un producto
  const updateQuantity = (bookId, quantity) => {
    if (quantity <= 0) {
      removeFromCart(bookId);
      return;
    }

    setCartItems(prevItems =>
      prevItems.map(item =>
        item.book_id === bookId
          ? { ...item, quantity: Math.min(quantity, item.stock_quantity) }
          : item
      )
    );
  };

  // Incrementar cantidad
  const incrementQuantity = (bookId) => {
    setCartItems(prevItems =>
      prevItems.map(item =>
        item.book_id === bookId && item.quantity < item.stock_quantity
          ? { ...item, quantity: item.quantity + 1 }
          : item
      )
    );
  };

  // Decrementar cantidad
  const decrementQuantity = (bookId) => {
    setCartItems(prevItems =>
      prevItems.map(item =>
        item.book_id === bookId && item.quantity > 1
          ? { ...item, quantity: item.quantity - 1 }
          : item
      )
    );
  };

  // Vaciar carrito
  const clearCart = () => {
    setCartItems([]);
  };

  // Calcular total
  const getCartTotal = () => {
    return cartItems.reduce((total, item) => {
      return total + (parseFloat(item.price) * item.quantity);
    }, 0);
  };

  // Obtener cantidad total de items
  const getCartCount = () => {
    return cartItems.reduce((count, item) => count + item.quantity, 0);
  };

  const value = {
    cartItems,
    addToCart,
    removeFromCart,
    updateQuantity,
    incrementQuantity,
    decrementQuantity,
    clearCart,
    getCartTotal,
    getCartCount,
    loading,
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
};


export default CartContext;