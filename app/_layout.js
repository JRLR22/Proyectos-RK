import { Stack } from 'expo-router';
import { AuthProvider, useAuth } from '../contexts/AuthContext';
import { CartProvider } from '../contexts/CartContext';
import { ThemeProvider } from '../contexts/ThemeContext';
import SplashScreen from '../screens/SplashScreen';

// Componente interno que maneja el splash
function AppNavigator() {
  const { isLoading } = useAuth();

  // Mientras verifica la sesión, muestra el splash
  if (isLoading) {
    return <SplashScreen />;
  }

  return (
    <Stack
      screenOptions={{
        headerShown: false,
        animation: 'slide_from_right', // Animación suave entre pantallas
      }}
    >
      {/* Pantalla principal (home) */}
      <Stack.Screen name="index" />
      
      {/* Login y Registro */}
      <Stack.Screen 
        name="login" 
        options={{
          animation: 'fade',
        }}
      />
      
      {/* Perfil */}
      <Stack.Screen name="profile" />
      
      {/* Carrito */}
      <Stack.Screen name="cart" />
      
      {/* Detalle de libro */}
      <Stack.Screen 
        name="book/[id]" 
        options={{
          animation: 'slide_from_bottom',
        }}
      />
      
      {/* Categorías */}
      <Stack.Screen name="categories" />
      
      
      {/* Favoritos */}
      <Stack.Screen name="favorites" />
      
      {/* Pedidos */}
      <Stack.Screen name="orders" />
    </Stack>
  );
}

// Layout principal con todos los providers en orden correcto
export default function RootLayout() {
  return (
    <ThemeProvider>
      <CartProvider>
        <AuthProvider>
          <AppNavigator />
        </AuthProvider>
      </CartProvider>
    </ThemeProvider>
  );
}