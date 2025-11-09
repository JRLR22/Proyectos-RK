import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import { useState } from "react";
import {
  Alert,
  Platform,
  ScrollView,
  StatusBar,
  StyleSheet,
  Switch,
  Text,
  TouchableOpacity,
  View
} from "react-native";
import { getColors } from '../constants/colors';
import { useTheme } from '../contexts/ThemeContext';

export default function SettingsScreen() {
  const router = useRouter();
  const { darkMode, toggleTheme } = useTheme();
  const colors = getColors(darkMode);
  const [notifications, setNotifications] = useState(true);
  const [emailUpdates, setEmailUpdates] = useState(false);

  const handleLogout = async () => {
    if (Platform.OS === 'web') {
      if (confirm('¿Estás seguro que deseas cerrar sesión?')) {
        await AsyncStorage.removeItem('userToken');
        router.replace('/login');
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
            onPress: async () => {
              await AsyncStorage.removeItem('userToken');
              router.replace('/login');
            }
          }
        ]
      );
    }
  };

  const clearCache = () => {
    if (Platform.OS === 'web') {
      alert('Caché limpiado exitosamente');
    } else {
      Alert.alert("Éxito", "Caché limpiado exitosamente");
    }
  };

  const SettingItem = ({ icon, title, subtitle, onPress, showArrow = true, rightElement }) => (
    <TouchableOpacity 
      style={[styles.settingItem, { borderBottomColor: colors.borderLight }]}
      onPress={onPress}
      disabled={!onPress && !rightElement}
    >
      <View style={styles.settingLeft}>
        <View style={[styles.iconContainer, { backgroundColor: colors.primaryLight }]}>
          <Ionicons name={icon} size={22} color={colors.primary} />
        </View>
        <View style={styles.settingText}>
          <Text style={[styles.settingTitle, { color: colors.text }]}>{title}</Text>
          {subtitle && (
            <Text style={[styles.settingSubtitle, { color: colors.textSecondary }]}>
              {subtitle}
            </Text>
          )}
        </View>
      </View>
      {rightElement ? rightElement : showArrow && (
        <Ionicons name="chevron-forward" size={20} color={colors.textTertiary} />
      )}
    </TouchableOpacity>
  );

  const SettingSection = ({ title, children }) => (
    <View style={styles.section}>
      <Text style={[styles.sectionTitle, { color: colors.textTertiary }]}>{title}</Text>
      <View style={[styles.sectionContent, { backgroundColor: colors.card }]}>
        {children}
      </View>
    </View>
  );

  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <StatusBar barStyle={colors.statusBar} backgroundColor={colors.surface} />

      {/* Header */}
      <View style={[styles.header, { 
        backgroundColor: colors.surface,
        borderBottomColor: colors.border 
      }]}>
        <TouchableOpacity 
          style={styles.backButton}
          onPress={() => router.replace('/')}
        >
          <Ionicons name="arrow-back" size={24} color={colors.text} />
        </TouchableOpacity>
        <Text style={[styles.headerTitle, { color: colors.text }]}>Configuración</Text>
        <View style={{ width: 40 }} />
      </View>

      <ScrollView contentContainerStyle={styles.scrollContent}>
        {/* Cuenta */}
        <SettingSection title="CUENTA">
          <SettingItem
            icon="person-outline"
            title="Perfil"
            subtitle="Edita tu información personal"
            onPress={() => router.push('/profile')}
          />
          <SettingItem
            icon="lock-closed-outline"
            title="Cambiar contraseña"
            subtitle="Actualiza tu contraseña"
            onPress={() => console.log('Cambiar contraseña')}
          />
          <SettingItem
            icon="card-outline"
            title="Métodos de pago"
            subtitle="Gestiona tus tarjetas"
            onPress={() => console.log('Métodos de pago')}
          />
        </SettingSection>

        {/* Notificaciones */}
        <SettingSection title="NOTIFICACIONES">
          <SettingItem
            icon="notifications-outline"
            title="Notificaciones push"
            subtitle="Recibe alertas en tu dispositivo"
            showArrow={false}
            rightElement={
              <Switch
                value={notifications}
                onValueChange={setNotifications}
                trackColor={{ false: colors.border, true: colors.primary }}
                thumbColor="#fff"
              />
            }
          />
          <SettingItem
            icon="mail-outline"
            title="Correos promocionales"
            subtitle="Ofertas y novedades"
            showArrow={false}
            rightElement={
              <Switch
                value={emailUpdates}
                onValueChange={setEmailUpdates}
                trackColor={{ false: colors.border, true: colors.primary }}
                thumbColor="#fff"
              />
            }
          />
        </SettingSection>

        {/* Apariencia */}
        <SettingSection title="APARIENCIA">
          <SettingItem
            icon="moon-outline"
            title="Modo oscuro"
            subtitle={darkMode ? "Activado" : "Desactivado"}
            showArrow={false}
            rightElement={
              <Switch
                value={darkMode}
                onValueChange={toggleTheme}
                trackColor={{ false: colors.border, true: colors.primary }}
                thumbColor="#fff"
              />
            }
          />
          <SettingItem
            icon="text-outline"
            title="Tamaño de texto"
            subtitle="Ajusta el tamaño de la fuente"
            onPress={() => console.log('Tamaño texto')}
          />
        </SettingSection>

        {/* Otros */}
        <SettingSection title="OTROS">
          <SettingItem
            icon="trash-outline"
            title="Limpiar caché"
            subtitle="Libera espacio de almacenamiento"
            onPress={clearCache}
          />
          <SettingItem
            icon="shield-checkmark-outline"
            title="Privacidad"
            subtitle="Política de privacidad"
            onPress={() => console.log('Privacidad')}
          />
          <SettingItem
            icon="document-text-outline"
            title="Términos y condiciones"
            onPress={() => console.log('Términos')}
          />
          <SettingItem
            icon="information-circle-outline"
            title="Acerca de"
            subtitle="Versión 1.0.0"
            onPress={() => console.log('Acerca de')}
          />
        </SettingSection>

        {/* Cerrar sesión */}
        <TouchableOpacity 
          style={[styles.logoutButton, { backgroundColor: colors.card }]} 
          onPress={handleLogout}
        >
          <Ionicons name="log-out-outline" size={22} color={colors.error} />
          <Text style={[styles.logoutText, { color: colors.error }]}>Cerrar sesión</Text>
        </TouchableOpacity>

        <View style={styles.footer}>
          <Text style={[styles.footerText, { color: colors.textTertiary }]}>
            Librería Gonvill © 2025
          </Text>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingHorizontal: 16,
    paddingVertical: 16,
    borderBottomWidth: 1,
  },
  backButton: {
    padding: 8,
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: "600",
  },
  scrollContent: {
    paddingBottom: 32,
  },
  section: {
    marginTop: 24,
  },
  sectionTitle: {
    fontSize: 13,
    fontWeight: '600',
    paddingHorizontal: 16,
    marginBottom: 8,
  },
  sectionContent: {
  },
  settingItem: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 16,
    paddingHorizontal: 16,
    borderBottomWidth: 1,
  },
  settingLeft: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  iconContainer: {
    width: 40,
    height: 40,
    borderRadius: 20,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  settingText: {
    flex: 1,
  },
  settingTitle: {
    fontSize: 15,
    fontWeight: '500',
    marginBottom: 2,
  },
  settingSubtitle: {
    fontSize: 13,
  },
  logoutButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    marginHorizontal: 16,
    marginTop: 24,
    padding: 16,
    borderRadius: 12,
    gap: 8,
  },
  logoutText: {
    fontSize: 16,
    fontWeight: '600',
  },
  footer: {
    alignItems: 'center',
    marginTop: 32,
  },
  footerText: {
    fontSize: 13,
  },
});