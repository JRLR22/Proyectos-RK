import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import { useEffect, useState } from "react";
import {
    ActivityIndicator,
    Alert, Platform, ScrollView,
    StatusBar,
    StyleSheet,
    Text,
    TouchableOpacity,
    View
} from "react-native";
import { getColors } from '../constants/colors';
import { useTheme } from '../contexts/ThemeContext';

export default function ProfileScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadUserData();
  }, []);

  const loadUserData = async () => {
    try {
      const userData = await AsyncStorage.getItem('userData');
      if (userData) {
        setUser(JSON.parse(userData));
      } else {
        router.replace('/login');
      }
      setLoading(false);
    } catch (error) {
      console.error('Error cargando usuario:', error);
      setLoading(false);
    }
  };

  const handleLogout = async () => {
    console.log("Botón presionado");
    
    const performLogout = async () => {
      try {
        await AsyncStorage.removeItem('userToken');
        await AsyncStorage.removeItem('userData');
        
        console.log("Datos borrados de AsyncStorage");
        router.replace('/');
        console.log("Navegación ejecutada");
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
          {
            text: "Cerrar sesión",
            style: "destructive",
            onPress: performLogout
          }
        ]
      );
    }
  };

  if (loading) {
    return (
      <View style={[styles.loadingContainer, { backgroundColor: colors.background }]}>
        <ActivityIndicator size="large" color={colors.primary} />
      </View>
    );
  }

  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <StatusBar barStyle={colors.statusBar} backgroundColor={colors.surface} />
      
      {/* Header */}
      <View style={[styles.header, { 
        backgroundColor: colors.surface,
        borderBottomColor: colors.border 
      }]}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backButton}>
          <Ionicons name="arrow-back" size={24} color={colors.text} />
        </TouchableOpacity>
        <Text style={[styles.headerTitle, { color: colors.text }]}>Mi Perfil</Text>
        <View style={{ width: 40 }} />
      </View>

      <ScrollView style={styles.content}>
        {/* Avatar y nombre */}
        <View style={[styles.profileHeader, { backgroundColor: colors.card }]}>
          <View style={[styles.avatarContainer, { backgroundColor: colors.primary }]}>
            <Ionicons name="person" size={48} color="#fff" />
          </View>
          <Text style={[styles.userName, { color: colors.text }]}>
            {user?.first_name} {user?.last_name}
          </Text>
          <Text style={[styles.userEmail, { color: colors.textSecondary }]}>
            {user?.email}
          </Text>
        </View>

        {/* Información del usuario */}
        <View style={styles.section}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>
            Información Personal
          </Text>
          
          <View style={[styles.infoCard, { backgroundColor: colors.card }]}>
            <View style={styles.infoRow}>
              <Ionicons name="person-outline" size={20} color={colors.textSecondary} />
              <View style={styles.infoText}>
                <Text style={[styles.infoLabel, { color: colors.textTertiary }]}>
                  Nombre
                </Text>
                <Text style={[styles.infoValue, { color: colors.text }]}>
                  {user?.first_name} {user?.last_name}
                </Text>
              </View>
            </View>

            <View style={[styles.divider, { backgroundColor: colors.borderLight }]} />

            <View style={styles.infoRow}>
              <Ionicons name="mail-outline" size={20} color={colors.textSecondary} />
              <View style={styles.infoText}>
                <Text style={[styles.infoLabel, { color: colors.textTertiary }]}>
                  Correo
                </Text>
                <Text style={[styles.infoValue, { color: colors.text }]}>
                  {user?.email}
                </Text>
              </View>
            </View>

            {user?.phone && (
              <>
                <View style={[styles.divider, { backgroundColor: colors.borderLight }]} />
                <View style={styles.infoRow}>
                  <Ionicons name="call-outline" size={20} color={colors.textSecondary} />
                  <View style={styles.infoText}>
                    <Text style={[styles.infoLabel, { color: colors.textTertiary }]}>
                      Teléfono
                    </Text>
                    <Text style={[styles.infoValue, { color: colors.text }]}>
                      {user.phone}
                    </Text>
                  </View>
                </View>
              </>
            )}

            {user?.address && (
              <>
                <View style={[styles.divider, { backgroundColor: colors.borderLight }]} />
                <View style={styles.infoRow}>
                  <Ionicons name="location-outline" size={20} color={colors.textSecondary} />
                  <View style={styles.infoText}>
                    <Text style={[styles.infoLabel, { color: colors.textTertiary }]}>
                      Dirección
                    </Text>
                    <Text style={[styles.infoValue, { color: colors.text }]}>
                      {user.address}
                    </Text>
                  </View>
                </View>
              </>
            )}
          </View>
        </View>

        {/* Botón de cerrar sesión */}
        <TouchableOpacity 
          style={[styles.logoutButton, { 
            backgroundColor: colors.card,
            borderColor: colors.error 
          }]}
          onPress={handleLogout}
        >
          <Ionicons name="log-out-outline" size={20} color={colors.error} />
          <Text style={[styles.logoutText, { color: colors.error }]}>
            Cerrar sesión
          </Text>
        </TouchableOpacity>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  loadingContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
  },
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 16,
    borderBottomWidth: 1,
  },
  backButton: {
    padding: 8,
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: "600",
  },
  content: {
    flex: 1,
  },
  profileHeader: {
    alignItems: "center",
    paddingVertical: 32,
    marginBottom: 16,
  },
  avatarContainer: {
    width: 100,
    height: 100,
    borderRadius: 50,
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 16,
  },
  userName: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 4,
  },
  userEmail: {
    fontSize: 16,
  },
  section: {
    marginBottom: 24,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: "600",
    paddingHorizontal: 16,
    marginBottom: 12,
  },
  infoCard: {
    paddingHorizontal: 16,
  },
  infoRow: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 16,
  },
  infoText: {
    flex: 1,
    marginLeft: 12,
  },
  infoLabel: {
    fontSize: 14,
    marginBottom: 4,
  },
  infoValue: {
    fontSize: 16,
    fontWeight: "500",
  },
  divider: {
    height: 1,
    marginLeft: 48,
  },
  logoutButton: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    marginHorizontal: 16,
    marginVertical: 32,
    paddingVertical: 16,
    borderRadius: 12,
    borderWidth: 1,
    gap: 8,
  },
  logoutText: {
    fontSize: 16,
    fontWeight: "600",
  },
});