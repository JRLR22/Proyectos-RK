
/**
 * Configuración de la API según el entorno
 * 
 * Para desarrollo:
 * - iOS Simulator: usa localhost
 * - Android Emulator: usa 10.0.2.2 (IP especial que apunta a localhost de la PC)
 * - Dispositivo físico: usa la IP de tu internet (ej: 192.168.1.x)
 */

const getDevelopmentUrl = () => {

   return 'http://192.168.100.2:8000';  // Reemplaza con tu IP

  // Android Emulator, cambia a:
  // return 'http://10.0.2.2:8000'; 

  // iOS Simulator y web pueden usar localhost
  // return 'http://localhost:8000';
};

const API_URLS = {
  development: getDevelopmentUrl(),
  production: 'https://api.gonvill.com', // Cambiemos esto cuando tengamos la API en producción
};

// Detecta automáticamente el entorno
const ENV = __DEV__ ? 'development' : 'production';

export const API_BASE_URL = API_URLS[ENV];

// Utilidades útiles para hacer llamadas a la API
export const API_ENDPOINTS = {
  books: '/api/books',
  categories: '/api/categories',
  auth: {
    login: '/api/auth/login',
    register: '/api/auth/register',
    profile: '/api/auth/profile',
  },
  cart: '/api/cart',
  orders: '/api/orders',
};

// Helper para construir URLs completas
export const getImageUrl = (imagePath) => {
  if (!imagePath) return null;
  return `${API_BASE_URL}/img/${imagePath}`;
};

// Helper para hacer fetch con configuración base
export const apiFetch = async (endpoint, options = {}) => {
  const url = endpoint.startsWith('http') ? endpoint : `${API_BASE_URL}${endpoint}`;
  
  const defaultOptions = {
    headers: {
      'Content-Type': 'application/json',
      ...options.headers,
    },
  };

  const response = await fetch(url, { ...defaultOptions, ...options });
  
  if (!response.ok) {
    throw new Error(`API Error: ${response.status} ${response.statusText}`);
  }
  
  return response.json();
};

export default {
  API_BASE_URL,
  API_ENDPOINTS,
  getImageUrl,
  apiFetch,
};