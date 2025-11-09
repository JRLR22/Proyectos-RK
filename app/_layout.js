import { Stack } from 'expo-router';
import { CartProvider } from '../contexts/CartContext';
import { ThemeProvider } from '../contexts/ThemeContext';

export default function RootLayout() {
  return (
    <ThemeProvider>
      <CartProvider>
        <Stack
          screenOptions={{
            headerShown: false,
          }}
        >
          <Stack.Screen name="login" />
          <Stack.Screen name="profile" />
          <Stack.Screen name="cart" />
        </Stack>
      </CartProvider>
    </ThemeProvider>
  );
}