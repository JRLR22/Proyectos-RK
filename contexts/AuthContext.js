import AsyncStorage from "@react-native-async-storage/async-storage";
import { createContext, useContext } from "react";
import { Platform, Alert } from "react-native";
import { useRouter } from "expo-router";
import { useCart } from "../contexts/CartContext";

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {

    const router = useRouter();
    const { clearUserCart } = useCart();
    
    const logout = async () => {
    const performLogout = async () => {
      try {
        clearUserCart();
        await AsyncStorage.removeItem('userToken');
        await AsyncStorage.removeItem('userData');
        router.replace('/');
      } catch (error) {
      }
    };
    if (Platform.OS === 'web') {
      if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
        await performLogout();
      }
    } else {
      Alert.alert(
        "Cerrar sesión",
        "¿Estás seguro que deseas cerrar sesión?",
        [
          { text: "Cancelar", style: "cancel" },
          {
            text: "Cerrar sesión",
            style: "destructive",
            onPress: performLogout
          }
        ]
      );
    }
  };

  return (
    <AuthContext.Provider value={{ logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
