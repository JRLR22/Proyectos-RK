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

export default function SettingsScreen() {
  const router = useRouter();
  const [notifications, setNotifications] = useState(true);
  const [emailUpdates, setEmailUpdates] = useState(false);
  const [darkMode, setDarkMode] = useState(false);

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
      style={styles.settingItem}
      onPress={onPress}
      disabled={!onPress && !rightElement}
    >
      <View style={styles.settingLeft}>
        <View style={styles.iconContainer}>
          <Ionicons name={icon} size={22} color="#ffa3c2" />
        </View>
        <View style={styles.settingText}>
          <Text style={styles.settingTitle}>{title}</Text>
          {subtitle && <Text style={styles.settingSubtitle}>{subtitle}</Text>}
        </View>
      </View>
      {rightElement ? rightElement : showArrow && (
        <Ionicons name="chevron-forward" size={20} color="#999" />
      )}
    </TouchableOpacity>
  );

  const SettingSection = ({ title, children }) => (
    <View style={styles.section}>
      <Text style={styles.sectionTitle}>{title}</Text>
      <View style={styles.sectionContent}>
        {children}
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#fff" />

      {/* Header */}
      <View style={styles.header}>
        <TouchableOpacity 
          style={styles.backButton}
          onPress={() => router.replace('/')}
        >
          <Ionicons name="arrow-back" size={24} color="#1A1A1A" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Configuración</Text>
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
                trackColor={{ false: "#ddd", true: "#ffa3c2" }}
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
                trackColor={{ false: "#ddd", true: "#ffa3c2" }}
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
            subtitle="Próximamente"
            showArrow={false}
            rightElement={
              <Switch
                value={darkMode}
                onValueChange={setDarkMode}
                trackColor={{ false: "#ddd", true: "#ffa3c2" }}
                thumbColor="#fff"
                disabled
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
        <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
          <Ionicons name="log-out-outline" size={22} color="#F44336" />
          <Text style={styles.logoutText}>Cerrar sesión</Text>
        </TouchableOpacity>

        <View style={styles.footer}>
          <Text style={styles.footerText}>Librería Gonvill © 2025</Text>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#F5F5F5",
  },
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    paddingHorizontal: 16,
    paddingVertical: 16,
    backgroundColor: "#fff",
    borderBottomWidth: 1,
    borderBottomColor: "#E0E0E0",
  },
  backButton: {
    padding: 8,
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: "600",
    color: "#1A1A1A",
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
    color: '#999',
    paddingHorizontal: 16,
    marginBottom: 8,
  },
  sectionContent: {
    backgroundColor: '#fff',
  },
  settingItem: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 16,
    paddingHorizontal: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#F0F0F0',
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
    backgroundColor: '#fff5f9',
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
    color: '#1A1A1A',
    marginBottom: 2,
  },
  settingSubtitle: {
    fontSize: 13,
    color: '#999',
  },
  logoutButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#fff',
    marginHorizontal: 16,
    marginTop: 24,
    padding: 16,
    borderRadius: 12,
    gap: 8,
  },
  logoutText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#F44336',
  },
  footer: {
    alignItems: 'center',
    marginTop: 32,
  },
  footerText: {
    fontSize: 13,
    color: '#999',
  },
});