import Constants from 'expo-constants';
import { Platform } from 'react-native';

/**
 *  CONFIGURACIÓN AUTOMÁTICA DE API
 * 
 * Detecta automáticamente dónde estás corriendo la app y usa la URL correcta.
 * 
 * Soporta:
 *  iOS Simulator
 *  Android Emulator  
 *  Dispositivo físico (Android/iOS)
 *  Expo Go
 *  Web
 */

// Pon la IP local una sola vez
  // Para encontrar la IP:
  // - Windows: abre CMD y escribe "ipconfig", busca "IPv4"
  // - Mac/Linux: abre Terminal y escribe "ifconfig" o "ip addr"

const LOCAL_IP = '192.168.100.2'; // Aquí va la ip

// Puerto de tu backend
const BACKEND_PORT = '8000';

/**
 * Detecta automáticamente la mejor URL según el entorno
 */
const getApiUrl = () => {
  // Si estamos en producción, usa la URL de producción
  if (!__DEV__) {
    return 'https://api.gonvill.com';
  }

  // WEB (navegador)
  if (Platform.OS === 'web') {
    return `http://localhost:${BACKEND_PORT}`;
  }

  // Para móviles, verificamos si es dispositivo real o emulador
  const isPhysicalDevice = Constants.isDevice === true || 
                          Constants.isDevice === undefined; // Fix para cuando es undefined

  //  iOS Simulator
  if (Platform.OS === 'ios' && !isPhysicalDevice) {
    return `http://localhost:${BACKEND_PORT}`;
  }

  //  Android Emulator (solo cuando sabemos 100% que es emulador)
  if (Platform.OS === 'android' && Constants.isDevice === false) {
    return `http://10.0.2.2:${BACKEND_PORT}`;
  }

  //  Dispositivo físico (default seguro)
    // Si hay duda, siempre usa la IP local (funciona en Expo Go)
  return `http://${LOCAL_IP}:${BACKEND_PORT}`;
};

// URL base de la API
export const API_BASE_URL = getApiUrl();

//  Endpoints organizados
export const API_ENDPOINTS = {
  // Libros
  books: '/api/books',
  bookById: (id) => `/api/books/${id}`,
  
  // Categorías
  categories: '/api/categories',
  categoryBooks: (categoryId) => `/api/categories/${categoryId}/books`,
  
  // Autenticación
  login: '/api/login',
  register: '/api/register',
  profile: '/api/profile',
  logout: '/api/logout',
  
  // Carrito
  cart: '/api/cart',
  addToCart: '/api/cart/add',
  updateCart: '/api/cart/update',
  removeFromCart: '/api/cart/remove',
  clearCart: '/api/cart/clear',
  
  // Órdenes
  orders: '/api/orders',
  orderById: (id) => `/api/orders/${id}`,
  createOrder: '/api/orders/create',
  
  // Favoritos
  favorites: '/api/favorites',
  addFavorite: '/api/favorites/add',
  removeFavorite: '/api/favorites/remove',

};

/**
 * Construye URLs de imágenes
 */
export const getImageUrl = (imagePath) => {
  if (!imagePath) return null;
  
  // Si ya es una URL completa, la devuelve tal cual
  if (imagePath.startsWith('http')) {
    return imagePath;
  }
  
  // Si es una ruta relativa, la combina con la URL base
  return `${API_BASE_URL}/img/${imagePath}`;
};

/**
 * Helper mejorado para hacer fetch con manejo de errores
 */
export const apiFetch = async (endpoint, options = {}) => {
  try {
    // Si el endpoint ya es una URL completa, la usa directamente
    const url = endpoint.startsWith('http') 
      ? endpoint 
      : `${API_BASE_URL}${endpoint}`;
    
    // Configuración por defecto
    const defaultOptions = {
      headers: {
        'Content-Type': 'application/json',
        ...options.headers,
      },
    };

    console.log(` API Request: ${options.method || 'GET'} ${url}`);

    const response = await fetch(url, { ...defaultOptions, ...options });
    
    // Manejo de errores HTTP
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new Error(
        errorData.message || 
        `Error ${response.status}: ${response.statusText}`
      );
    }
    
    const data = await response.json();
    console.log(` API Response:`, data);
    
    return data;

  } catch (error) {
    console.error(`❌ API Error:`, error);
    
    // Errores de red
    if (error.message.includes('Network request failed')) {
      throw new Error('Sin conexión a internet. Verifica tu red.');
    }
    
    // Errores de timeout
    if (error.message.includes('timeout')) {
      throw new Error('La petición tardó demasiado. Intenta de nuevo.');
    }
    
    // Otros errores
    throw error;
  }
};

/**
 * Helper para hacer peticiones autenticadas
 */
export const apiAuthFetch = async (endpoint, options = {}, token) => {
  return apiFetch(endpoint, {
    ...options,
    headers: {
      ...options.headers,
      'Authorization': `Bearer ${token}`,
    },
  });
};

/**
 *  Helper para debug: muestra la configuración actual
 */
export const debugApiConfig = () => {
  const isPhysicalDevice = Constants.isDevice === true || 
                          Constants.isDevice === undefined;
  
  console.log('🔧 ===== Configuración de la API =====');
  console.log('📱 Platforma:', Platform.OS);
  console.log('🔍 Constants.isDevice:', Constants.isDevice);
  console.log(' Se detectó dispositivo físico?:', isPhysicalDevice);
  console.log('🏗️  Modo desarrollo?:', __DEV__);
  console.log('🌐 URL DE LA API:', API_BASE_URL);
  console.log('📍 IP LOCAL:', LOCAL_IP);
  console.log('🔌 PUERTO:', BACKEND_PORT);
  console.log('================================');
};

// Para debug en desarrollo
if (__DEV__) {
  debugApiConfig();
}

export default {
  API_BASE_URL,
  API_ENDPOINTS,
  getImageUrl,
  apiFetch,
  apiAuthFetch,
  debugApiConfig,
};