import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRouter } from 'expo-router';
import { useState } from "react";
import {
  Alert,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from "react-native";
import { getColors } from '../constants/colors';
import { useTheme } from '../contexts/ThemeContext';
import { useCart } from '../contexts/CartContext';

export default function LoginScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  
  const [isLogin, setIsLogin] = useState(true);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [phone, setPhone] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const { reloadUser } = useCart();

  const API_URL = "http://10.0.2.2:8000/api";

  const validateEmail = (email) => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  };

  const handleLogin = async () => {
    if (!email || !password) {
      Alert.alert("Error", "Por favor completa todos los campos");
      return;
    }

    if (!validateEmail(email)) {
      Alert.alert("Error", "Email inválido");
      return;
    }

    setLoading(true);

    try {
      const response = await fetch(`${API_URL}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Error en login');
      }

      await AsyncStorage.setItem('userToken', data.token);
      await AsyncStorage.setItem('userData', JSON.stringify(data.user));

      //Recargar carrito
      await reloadUser();

      setLoading(false);
      Alert.alert("¡Éxito!", "Has iniciado sesión correctamente");
      router.replace('/');

    } catch (error) {
      setLoading(false);
      Alert.alert("Error", error.message || "No se pudo iniciar sesión");
      console.error("Error en login:", error);
    }
  };

  const handleRegister = async () => {
    if (!firstName || !lastName || !email || !password || !confirmPassword) {
      Alert.alert("Error", "Por favor completa todos los campos obligatorios");
      return;
    }

    if (!validateEmail(email)) {
      Alert.alert("Error", "Email inválido");
      return;
    }

    if (password.length < 6) {
      Alert.alert("Error", "La contraseña debe tener al menos 6 caracteres");
      return;
    }

    if (password !== confirmPassword) {
      Alert.alert("Error", "Las contraseñas no coinciden");
      return;
    }

    setLoading(true);

    try {
      const response = await fetch(`${API_URL}/register`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
          email, 
          password,
          first_name: firstName,
          last_name: lastName,
          phone: phone || null,
        })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Error en registro');
      }

      await AsyncStorage.setItem('userToken', data.token);
      await AsyncStorage.setItem('userData', JSON.stringify(data.user));

      setLoading(false);
      Alert.alert("¡Éxito!", "Cuenta creada correctamente");
      router.replace('/');

    } catch (error) {
      setLoading(false);
      Alert.alert("Error", error.message || "No se pudo crear la cuenta");
      console.error("Error en registro:", error);
    }
  };

  const toggleMode = () => {
    setIsLogin(!isLogin);
    setFirstName("");
    setLastName("");
    setEmail("");
    setPassword("");
    setPhone("");
    setConfirmPassword("");
  };

  return (
    <KeyboardAvoidingView
      style={[styles.container, { backgroundColor: colors.background }]}
      behavior={Platform.OS === "ios" ? "padding" : "height"}
    >
      <ScrollView 
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* Header */}
        <View style={styles.header}>
          <View style={styles.logoContainer}>
            <View style={[styles.logoIcon, { backgroundColor: colors.primary }]}>
              <Ionicons name="book" size={40} color="#fff" />
            </View>
          </View>
          <Text style={[styles.title, { color: colors.primary }]}>
            {isLogin ? "Bienvenido a Gonvill" : "Crear cuenta"}
          </Text>
          <Text style={[styles.subtitle, { color: colors.textSecondary }]}>
            {isLogin 
              ? "Inicia sesión para continuar" 
              : "Regístrate para empezar a comprar"
            }
          </Text>
        </View>

        {/* Formulario */}
        <View style={[styles.form, { backgroundColor: colors.card }]}>
          
          {/* Campos de Registro */}
          {!isLogin && (
            <>
              <View style={[styles.inputContainer, { backgroundColor: colors.surface }]}>
                <Ionicons 
                  name="person-outline" 
                  size={20} 
                  color={colors.textSecondary} 
                  style={styles.inputIcon} 
                />
                <TextInput
                  style={[styles.input, { color: colors.text }]}
                  placeholder="Nombre"
                  placeholderTextColor={colors.textTertiary}
                  value={firstName}
                  onChangeText={setFirstName}
                  autoCapitalize="words"
                />
              </View>

              <View style={[styles.inputContainer, { backgroundColor: colors.surface }]}>
                <Ionicons 
                  name="person-outline" 
                  size={20} 
                  color={colors.textSecondary} 
                  style={styles.inputIcon} 
                />
                <TextInput
                  style={[styles.input, { color: colors.text }]}
                  placeholder="Apellido"
                  placeholderTextColor={colors.textTertiary}
                  value={lastName}
                  onChangeText={setLastName}
                  autoCapitalize="words"
                />
              </View>

              <View style={[styles.inputContainer, { backgroundColor: colors.surface }]}>
                <Ionicons 
                  name="call-outline" 
                  size={20} 
                  color={colors.textSecondary} 
                  style={styles.inputIcon} 
                />
                <TextInput
                  style={[styles.input, { color: colors.text }]}
                  placeholder="Teléfono (opcional)"
                  placeholderTextColor={colors.textTertiary}
                  value={phone}
                  onChangeText={setPhone}
                  keyboardType="phone-pad"
                />
              </View>
            </>
          )}

          {/* Email */}
          <View style={[styles.inputContainer, { backgroundColor: colors.surface }]}>
            <Ionicons 
              name="mail-outline" 
              size={20} 
              color={colors.textSecondary} 
              style={styles.inputIcon} 
            />
            <TextInput
              style={[styles.input, { color: colors.text }]}
              placeholder="Correo electrónico"
              placeholderTextColor={colors.textTertiary}
              value={email}
              onChangeText={setEmail}
              keyboardType="email-address"
              autoCapitalize="none"
            />
          </View>

          {/* Contraseña */}
          <View style={[styles.inputContainer, { backgroundColor: colors.surface }]}>
            <Ionicons 
              name="lock-closed-outline" 
              size={20} 
              color={colors.textSecondary} 
              style={styles.inputIcon} 
            />
            <TextInput
              style={[styles.input, { color: colors.text }]}
              placeholder="Contraseña"
              placeholderTextColor={colors.textTertiary}
              value={password}
              onChangeText={setPassword}
              secureTextEntry={!showPassword}
              autoCapitalize="none"
            />
            <TouchableOpacity onPress={() => setShowPassword(!showPassword)}>
              <Ionicons 
                name={showPassword ? "eye-outline" : "eye-off-outline"} 
                size={20} 
                color={colors.textSecondary} 
              />
            </TouchableOpacity>
          </View>

          {/* Confirmar Contraseña */}
          {!isLogin && (
            <View style={[styles.inputContainer, { backgroundColor: colors.surface }]}>
              <Ionicons 
                name="lock-closed-outline" 
                size={20} 
                color={colors.textSecondary} 
                style={styles.inputIcon} 
              />
              <TextInput
                style={[styles.input, { color: colors.text }]}
                placeholder="Confirmar contraseña"
                placeholderTextColor={colors.textTertiary}
                value={confirmPassword}
                onChangeText={setConfirmPassword}
                secureTextEntry={!showConfirmPassword}
                autoCapitalize="none"
              />
              <TouchableOpacity onPress={() => setShowConfirmPassword(!showConfirmPassword)}>
                <Ionicons 
                  name={showConfirmPassword ? "eye-outline" : "eye-off-outline"} 
                  size={20} 
                  color={colors.textSecondary} 
                />
              </TouchableOpacity>
            </View>
          )}

          {/* Olvidaste contraseña */}
          {isLogin && (
            <TouchableOpacity style={styles.forgotPassword}>
              <Text style={[styles.forgotPasswordText, { color: colors.primary }]}>
                ¿Olvidaste tu contraseña?
              </Text>
            </TouchableOpacity>
          )}

          {/* Botón Principal */}
          <TouchableOpacity
            style={[
              styles.button, 
              { backgroundColor: colors.primary },
              loading && styles.buttonDisabled
            ]}
            onPress={isLogin ? handleLogin : handleRegister}
            disabled={loading}
          >
            <Text style={styles.buttonText}>
              {loading ? "Cargando..." : (isLogin ? "Iniciar sesión" : "Crear cuenta")}
            </Text>
          </TouchableOpacity>

          {/* Divisor */}
          <View style={styles.divider}>
            <View style={[styles.dividerLine, { backgroundColor: colors.border }]} />
            <Text style={[styles.dividerText, { color: colors.textTertiary }]}>O</Text>
            <View style={[styles.dividerLine, { backgroundColor: colors.border }]} />
          </View>

          {/* Toggle */}
          <TouchableOpacity onPress={toggleMode}>
            <Text style={[styles.toggleText, { color: colors.textSecondary }]}>
              {isLogin ? "¿No tienes cuenta? " : "¿Ya tienes cuenta? "}
              <Text style={[styles.toggleTextBold, { color: colors.primary }]}>
                {isLogin ? "Regístrate" : "Inicia sesión"}
              </Text>
            </Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  scrollContent: {
    flexGrow: 1,
    justifyContent: "center",
    padding: 20,
  },
  header: {
    alignItems: "center",
    marginBottom: 40,
  },
  logoContainer: {
    marginBottom: 20,
  },
  logoIcon: {
    width: 80,
    height: 80,
    borderRadius: 40,
    justifyContent: "center",
    alignItems: "center",
  },
  title: {
    fontSize: 28,
    fontWeight: "bold",
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 16,
    textAlign: "center",
  },
  form: {
    borderRadius: 16,
    padding: 24,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
    elevation: 3,
  },
  inputContainer: {
    flexDirection: "row",
    alignItems: "center",
    borderRadius: 12,
    paddingHorizontal: 16,
    marginBottom: 16,
    height: 56,
  },
  inputIcon: {
    marginRight: 12,
  },
  input: {
    flex: 1,
    fontSize: 16,
  },
  forgotPassword: {
    alignSelf: "flex-end",
    marginBottom: 20,
  },
  forgotPasswordText: {
    fontSize: 14,
    fontWeight: "500",
  },
  button: {
    borderRadius: 12,
    height: 56,
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 20,
  },
  buttonDisabled: {
    opacity: 0.6,
  },
  buttonText: {
    color: "#fff",
    fontSize: 18,
    fontWeight: "600",
  },
  divider: {
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 20,
  },
  dividerLine: {
    flex: 1,
    height: 1,
  },
  dividerText: {
    marginHorizontal: 16,
    fontSize: 14,
  },
  toggleText: {
    textAlign: "center",
    fontSize: 15,
  },
  toggleTextBold: {
    fontWeight: "600",
  },
});