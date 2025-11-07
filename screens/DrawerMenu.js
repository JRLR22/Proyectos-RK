import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import { useEffect, useState } from "react";
import {
  Modal,
  Platform,
  Pressable,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";

export default function DrawerMenu({ visible, onClose }) {
  const router = useRouter();
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

  const handleLogout = async () => {
    const performLogout = async () => {
      try {
        await AsyncStorage.removeItem('userToken');
        await AsyncStorage.removeItem('userData');
        onClose();
        router.replace('/');
      } catch (error) {
        console.error('Error al cerrar sesión:', error);
      }
    };

    if (Platform.OS === 'web') {
      if (window.confirm("¿Estás seguro de que deseas cerrar sesión?")) {
        await performLogout();
      }
    } else {
      Alert.alert(
        "Cerrar sesión",
        "¿Estás seguro de que deseas cerrar sesión?",
        [
          { text: "Cancelar", style: "cancel" },
          { text: "Cerrar sesión", style: "destructive", onPress: performLogout }
        ]
      );
    }
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
        {/* Fondo oscuro clickeable */}
        <Pressable style={styles.backdrop} onPress={onClose} />
        
        {/* Drawer */}
        <View style={styles.drawer}>
          <ScrollView style={styles.drawerContent} showsVerticalScrollIndicator={false}>
            {/* Header del usuario */}
            <View style={styles.userHeader}>
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
              <Text style={styles.sectionTitle}>NAVEGACIÓN</Text>
              {menuItems.map((item, index) => (
                <TouchableOpacity
                  key={index}
                  style={styles.menuItem}
                  onPress={() => navigateTo(item.route)}
                >
                  <Ionicons name={item.icon} size={24} color="#1A1A1A" />
                  <Text style={styles.menuText}>{item.label}</Text>
                  <Ionicons name="chevron-forward" size={20} color="#999" />
                </TouchableOpacity>
              ))}
            </View>

            {/* Divider */}
            <View style={styles.divider} />

            {/* Cuenta */}
            <View style={styles.section}>
              <Text style={styles.sectionTitle}>CUENTA</Text>
              {accountItems.map((item, index) => (
                <TouchableOpacity
                  key={index}
                  style={styles.menuItem}
                  onPress={() => navigateTo(item.route)}
                >
                  <Ionicons name={item.icon} size={24} color="#1A1A1A" />
                  <Text style={styles.menuText}>{item.label}</Text>
                  <Ionicons name="chevron-forward" size={20} color="#999" />
                </TouchableOpacity>
              ))}
            </View>

            {/* Divider */}
            <View style={styles.divider} />

            {/* Cerrar sesión */}
            <TouchableOpacity 
              style={styles.logoutItem}
              onPress={handleLogout}
            >
              <Ionicons name="log-out-outline" size={24} color="#F44336" />
              <Text style={styles.logoutText}>Cerrar Sesión</Text>
            </TouchableOpacity>

            {/* Versión */}
            <Text style={styles.version}>Versión 1.0.0</Text>
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
    backgroundColor: '#fff',
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
    backgroundColor: '#ffa3c2',
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
    color: '#999',
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
    color: '#1A1A1A',
    marginLeft: 16,
    fontWeight: '500',
  },
  divider: {
    height: 1,
    backgroundColor: '#F0F0F0',
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
    color: '#F44336',
    marginLeft: 16,
    fontWeight: '600',
  },
  version: {
    textAlign: 'center',
    fontSize: 12,
    color: '#999',
    paddingVertical: 24,
  },
});