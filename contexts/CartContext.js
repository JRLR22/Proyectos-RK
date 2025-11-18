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
  const [cartKey, setCartKey] = useState(null);

  //Cargar usuario
  useEffect(() => {
    loadUser();
  }, []);

  // Cargar carrito con cartKey
  useEffect(() => {
    if(!cartKey){
      setCartItems([]);
      setLoading(false);
      return;
    }
    loadCart();
  }, [cartKey]);

  const loadUser = async () => {
    try {
      const raw = await AsyncStorage.getItem('userData');
      if (!raw) {
        setCartKey(null);
        setCartItems([]);
        setLoading(false);
        return;
      }
      const user = JSON.parse(raw);
      const key = `cart_${user.user_id}`;
      if(cartKey && cartKey !== key){
        setCartItems([]);
      }
      setCartKey(key);
    } catch (error) {
      console.error("Error leyendo Información de Usuario:", error);
      setCartKey(null);
    }
  };

  const loadCart = async () => {
    try {
      const cartData = await AsyncStorage.getItem(cartKey);
      setCartItems(cartData ? JSON.parse(cartData):[]);
    } catch (error) {
      console.error('Error cargando carrito:', error);
    } finally{
      setLoading(false);
    }
  };

  const saveCart = async (items) => {
    if(!cartKey)
      return;
    try {
      await AsyncStorage.setItem(cartKey, JSON.stringify(items));
    } catch (error) {
      console.error('Error guardando carrito:', error);
    }
  };

  // Agregar producto al carrito
  const addToCart = (product) => {
    setCartItems((prevItems) => {
      const existingItem = prevItems.find(item => item.book_id === product.book_id);
      let updatedItems;
      //Si existe incrementa la cantidad
      if (existingItem) {
        updatedItems = prevItems.map(item =>
          item.book_id === product.book_id
            ? { ...item, quantity: item.quantity + 1 }
            : item
        );
      } else {
        updatedItems = [...prevItems, { ...product, quantity: 1 }];
      }
      saveCart(updatedItems);
      return updatedItems;
    });
  };

  // Eliminar producto del carrito
  const removeFromCart = (bookId) => {
    setCartItems(prevItems => {
      const updatedItems = prevItems.filter(item => item.book_id !== bookId);
      saveCart(updatedItems);
      return updatedItems;
    });
  };

  // Actualizar cantidad de un producto
  const updateQuantity = (bookId, quantity) => {
    if (quantity <= 0) {
      removeFromCart(bookId);
      return;
    }
    setCartItems(prevItems => {
      const updatedItems = prevItems.map(item =>
        item.book_id === bookId
          ? { ...item, quantity: Math.min(quantity, item.stock_quantity) }
          : item
      );
      saveCart(updatedItems);
      return updatedItems;
    });
  };

  // Incrementar cantidad
  const incrementQuantity = (bookId) => {
    setCartItems(prevItems => {
      const updatedItems = prevItems.map(item =>
        item.book_id === bookId && item.quantity < item.stock_quantity
          ? { ...item, quantity: item.quantity + 1 }
          : item
      );
      saveCart(updatedItems);
      return updatedItems;
    });
  };

  // Decrementar cantidad
  const decrementQuantity = (bookId) => {
    setCartItems(prevItems => {
      const updatedItems = prevItems.map(item =>
        item.book_id === bookId && item.quantity > 1
          ? { ...item, quantity: item.quantity - 1 }
          : item
      );
      saveCart(updatedItems);
      return updatedItems;
    });
  };

  // Vaciar carrito
  const clearCart = () => {
    setCartItems([]);
    saveCart([]);
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

  const reloadUser = async () => {
    await loadUser();
  };

  const clearUserCart = () => {
    setCartKey(null);
    setCartItems([]);
    setLoading(false);
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
    reloadUser,
    clearUserCart,
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
};


export default CartContext;