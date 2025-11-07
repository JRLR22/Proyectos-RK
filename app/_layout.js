import { Stack } from 'expo-router';
import { CartProvider } from './CartContext';

export default function RootLayout() {
  return (
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
  );
}