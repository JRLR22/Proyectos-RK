import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import { useEffect, useState } from "react";
import {
  Alert,
  Modal,
  Platform,
  Pressable,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";
import { getColors } from '../constants/colors';
import { useTheme } from '../contexts/ThemeContext';
import { useAuth} from '../contexts/AuthContext';

export default function DrawerMenu({ visible, onClose }) {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  const [user, setUser] = useState(null);

  useEffect(() => {
    if (visible) {
      loadUserData();
    }
  }, [visible]);

  const loadUserData = async () => {
    try {
      const userData = await AsyncStorage.getItem('userData');
      if (userData) {
        setUser(JSON.parse(userData));
      }
    } catch (error) {
      console.error('Error cargando usuario:', error);
    }
  };
  //Nuevo logout llamando a AuthContext
  const { logout } = useAuth();

  const handleLogout = () => {
    logout();
  };

  const menuItems = [
    { icon: "home-outline", label: "Inicio", route: "/" },
    { icon: "grid-outline", label: "Categorías", route: "/categories" },
    { icon: "cart-outline", label: "Mis Pedidos", route: "/orders" },
    { icon: "heart-outline", label: "Favoritos", route: "/favorites" },
  ];

  const accountItems = [
    { icon: "person-outline", label: "Mi Perfil", route: "/profile" },
    { icon: "settings-outline", label: "Configuración", route: "/settings" },
    { icon: "help-circle-outline", label: "Ayuda", route: "/help" },
  ];

  const navigateTo = (route) => {
    onClose();
    router.push(route);
  };

  return (
    <Modal
      animationType="slide"
      transparent={true}
      visible={visible}
      onRequestClose={onClose}
    >
      <View style={styles.overlay}>
        <Pressable style={styles.backdrop} onPress={onClose} />
        
        <View style={[styles.drawer, { backgroundColor: colors.surface }]}>
          <ScrollView style={styles.drawerContent} showsVerticalScrollIndicator={false}>
            {/* Header del usuario */}
            <View style={[styles.userHeader, { backgroundColor: colors.primary }]}>
              <TouchableOpacity 
                style={styles.closeButton} 
                onPress={onClose}
              >
                <Ionicons name="close" size={24} color="#fff" />
              </TouchableOpacity>
              
              <View style={styles.avatarContainer}>
                <Ionicons name="person" size={40} color="#fff" />
              </View>
              
              <Text style={styles.userName}>
                {user?.first_name} {user?.last_name}
              </Text>
              <Text style={styles.userEmail}>{user?.email}</Text>
            </View>

            {/* Navegación principal */}
            <View style={styles.section}>
              <Text style={[styles.sectionTitle, { color: colors.textTertiary }]}>
                NAVEGACIÓN
              </Text>
              {menuItems.map((item, index) => (
                <TouchableOpacity
                  key={index}
                  style={styles.menuItem}
                  onPress={() => navigateTo(item.route)}
                >
                  <Ionicons name={item.icon} size={24} color={colors.text} />
                  <Text style={[styles.menuText, { color: colors.text }]}>
                    {item.label}
                  </Text>
                  <Ionicons name="chevron-forward" size={20} color={colors.textTertiary} />
                </TouchableOpacity>
              ))}
            </View>

            <View style={[styles.divider, { backgroundColor: colors.border }]} />

            {/* Cuenta */}
            <View style={styles.section}>
              <Text style={[styles.sectionTitle, { color: colors.textTertiary }]}>
                CUENTA
              </Text>
              {accountItems.map((item, index) => (
                <TouchableOpacity
                  key={index}
                  style={styles.menuItem}
                  onPress={() => navigateTo(item.route)}
                >
                  <Ionicons name={item.icon} size={24} color={colors.text} />
                  <Text style={[styles.menuText, { color: colors.text }]}>
                    {item.label}
                  </Text>
                  <Ionicons name="chevron-forward" size={20} color={colors.textTertiary} />
                </TouchableOpacity>
              ))}
            </View>

            <View style={[styles.divider, { backgroundColor: colors.border }]} />

            {/* Cerrar sesión */}
            <TouchableOpacity 
              style={styles.logoutItem}
              onPress={handleLogout}
            >
              <Ionicons name="log-out-outline" size={24} color={colors.error} />
              <Text style={[styles.logoutText, { color: colors.error }]}>
                Cerrar Sesión
              </Text>
            </TouchableOpacity>

            <Text style={[styles.version, { color: colors.textTertiary }]}>
              Versión 1.0.0
            </Text>
          </ScrollView>
        </View>
      </View>
    </Modal>
  );
}

const styles = StyleSheet.create({
  overlay: {
    flex: 1,
    flexDirection: 'row',
  },
  backdrop: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
  },
  drawer: {
    width: 300,
    shadowColor: '#000',
    shadowOffset: { width: -2, height: 0 },
    shadowOpacity: 0.25,
    shadowRadius: 8,
    elevation: 5,
  },
  drawerContent: {
    flex: 1,
  },
  userHeader: {
    paddingTop: 50,
    paddingBottom: 24,
    paddingHorizontal: 20,
    position: 'relative',
  },
  closeButton: {
    position: 'absolute',
    top: 10,
    right: 10,
    padding: 8,
    zIndex: 1,
  },
  avatarContainer: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: 'rgba(255, 255, 255, 0.3)',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 12,
    borderWidth: 3,
    borderColor: '#fff',
  },
  userName: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 4,
  },
  userEmail: {
    fontSize: 14,
    color: 'rgba(255, 255, 255, 0.9)',
  },
  section: {
    paddingTop: 16,
  },
  sectionTitle: {
    fontSize: 12,
    fontWeight: '600',
    paddingHorizontal: 20,
    paddingBottom: 8,
    letterSpacing: 0.5,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 14,
    paddingHorizontal: 20,
  },
  menuText: {
    flex: 1,
    fontSize: 16,
    marginLeft: 16,
    fontWeight: '500',
  },
  divider: {
    height: 1,
    marginVertical: 8,
    marginHorizontal: 20,
  },
  logoutItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 14,
    paddingHorizontal: 20,
    marginTop: 8,
  },
  logoutText: {
    flex: 1,
    fontSize: 16,
    marginLeft: 16,
    fontWeight: '600',
  },
  version: {
    textAlign: 'center',
    fontSize: 12,
    paddingVertical: 24,
  },
});