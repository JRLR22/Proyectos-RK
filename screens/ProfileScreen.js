import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import { useEffect, useState } from "react";
import {
  ActivityIndicator,
  ScrollView,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from "react-native";
import { getColors } from '../constants/colors';
import { useAuth } from '../contexts/AuthContext';
import { useTheme } from '../contexts/ThemeContext';

export default function ProfileScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  
  // Estado para guardar los datos del usuario que viene del AsyncStorage
  const [user, setUser] = useState(null);
  // Estado para manejar la carga inicial mientras obtenemos los datos
  const [loading, setLoading] = useState(true);

  // Cuando la pantalla se monta, cargamos los datos del usuario
  useEffect(() => {
    loadUserData();
  }, []);

  // Función que lee los datos del usuario desde el AsyncStorage
  // AsyncStorage es como una base de datos local del teléfono
  const loadUserData = async () => {
    try {
      const userData = await AsyncStorage.getItem('userData');
      if (userData) {
        // Convertimos el texto JSON a un objeto de JavaScript
        setUser(JSON.parse(userData));
      } else {
        // Si no hay datos guardados, mandamos al usuario al login
        router.push('/login');
      }
      setLoading(false);
    } catch (error) {
      console.error('Error cargando usuario:', error);
      setLoading(false);
    }
  };

  // Usamos la función logout del contexto de autenticación
  const { logout } = useAuth();

  // Función que se ejecuta cuando el usuario presiona cerrar sesión
  const handleLogout = () => {
    logout();
  };

  // Mientras cargamos los datos, mostramos un spinner
  if (loading) {
    return (
      <View style={[styles.loadingContainer, { backgroundColor: colors.background }]}>
        <ActivityIndicator size="large" color={colors.primary} />
      </View>
    );
  }

  // Una vez que tenemos los datos, mostramos la pantalla completa
  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <StatusBar barStyle={colors.statusBar} backgroundColor={colors.surface} />
      
      {/* Header superior con botón de regresar y título */}
      <View style={[styles.header, { 
        backgroundColor: colors.surface,
        borderBottomColor: colors.border 
      }]}>
        <TouchableOpacity onPress={() => router.back()} style={styles.backButton}>
          <Ionicons name="arrow-back" size={24} color={colors.text} />
        </TouchableOpacity>
        <Text style={[styles.headerTitle, { color: colors.text }]}>Mi Perfil</Text>
        {/* Este View vacío es para centrar el título usando space-between */}
        <View style={{ width: 40 }} />
      </View>

      <ScrollView style={styles.content}>
        {/* Sección del avatar y nombre del usuario */}
        <View style={[styles.profileHeader, { backgroundColor: colors.card }]}>
          {/* Avatar circular con icono de persona */}
          <View style={[styles.avatarContainer, { backgroundColor: colors.primary }]}>
            <Ionicons name="person" size={48} color="#fff" />
          </View>
          {/* Nombre completo del usuario */}
          <Text style={[styles.userName, { color: colors.text }]}>
            {user?.first_name} {user?.last_name}
          </Text>
          {/* Email del usuario */}
          <Text style={[styles.userEmail, { color: colors.textSecondary }]}>
            {user?.email}
          </Text>
        </View>

        {/* Sección de información personal detallada */}
        <View style={styles.section}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>
            Información Personal
          </Text>
          
          {/* Tarjeta con toda la información del usuario */}
          <View style={[styles.infoCard, { backgroundColor: colors.card }]}>
            {/* Fila de nombre */}
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

            {/* Línea divisora entre campos */}
            <View style={[styles.divider, { backgroundColor: colors.borderLight }]} />

            {/* Fila de correo */}
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

            {/* Fila de teléfono (solo se muestra si el usuario tiene teléfono registrado) */}
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

            {/* Fila de dirección (solo se muestra si el usuario tiene dirección registrada) */}
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

        {/* Botón de cerrar sesión al final */}
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
  // Contenedor principal que ocupa toda la pantalla
  container: {
    flex: 1,
  },
  // Contenedor que se muestra mientras carga
  loadingContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
  },
  // Barra superior de la pantalla
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
  // Sección del encabezado con avatar y nombre
  profileHeader: {
    alignItems: "center",
    paddingVertical: 32,
    marginBottom: 16,
  },
  // Círculo donde va el icono de usuario
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
  // Tarjeta que contiene la información del usuario
  infoCard: {
    paddingHorizontal: 16,
  },
  // Cada fila de información (nombre, email, teléfono, etc.)
  infoRow: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 16,
  },
  infoText: {
    flex: 1,
    marginLeft: 12,
  },
  // Etiqueta pequeña que dice "Nombre", "Correo", etc.
  infoLabel: {
    fontSize: 14,
    marginBottom: 4,
  },
  // El valor real del campo (el nombre, el email, etc.)
  infoValue: {
    fontSize: 16,
    fontWeight: "500",
  },
  // Línea que separa cada campo de información
  divider: {
    height: 1,
    marginLeft: 48, // Margen para que no toque el icono
  },
  // Botón de cerrar sesión
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