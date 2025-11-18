import { Stack } from 'expo-router';
import { CartProvider } from '../contexts/CartContext';
import { ThemeProvider } from '../contexts/ThemeContext';
import { AuthProvider } from '../contexts/AuthContext';

export default function RootLayout() {
  return (
      <ThemeProvider>
        <CartProvider>
          <AuthProvider>
            <Stack
              screenOptions={{
                headerShown: false,
             }}
            >
              <Stack.Screen name="login" />
              <Stack.Screen name="profile" />
              <Stack.Screen name="cart" />
            </Stack>
          </AuthProvider>
        </CartProvider>
      </ThemeProvider>
  );
}