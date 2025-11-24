import { Ionicons } from "@expo/vector-icons";
import { useEffect, useRef } from "react";
import { Animated, StyleSheet, Text, View } from "react-native";
import { useTheme } from '../contexts/ThemeContext';

export default function SplashScreen() {
  const { darkMode } = useTheme();
  const fadeAnim = useRef(new Animated.Value(0)).current;
  const scaleAnim = useRef(new Animated.Value(0.8)).current;
  const rotateAnim = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    // Animación de entrada
    Animated.parallel([
      Animated.timing(fadeAnim, {
        toValue: 1,
        duration: 800,
        useNativeDriver: true,
      }),
      Animated.spring(scaleAnim, {
        toValue: 1,
        friction: 4,
        tension: 40,
        useNativeDriver: true,
      }),
    ]).start();

    // Animación de rotación continua del ícono
    Animated.loop(
      Animated.timing(rotateAnim, {
        toValue: 1,
        duration: 2000,
        useNativeDriver: true,
      })
    ).start();
  }, []);

  const spin = rotateAnim.interpolate({
    inputRange: [0, 1],
    outputRange: ['0deg', '360deg']
  });

  return (
    <View style={[styles.container, darkMode && styles.containerDark]}>
      <Animated.View 
        style={[
          styles.content,
          {
            opacity: fadeAnim,
            transform: [{ scale: scaleAnim }]
          }
        ]}
      >
        <Animated.View 
          style={[
            styles.iconContainer,
            { transform: [{ rotate: spin }] }
          ]}
        >
          <Ionicons 
            name="book" 
            size={80} 
            color="#ffa3c2" 
          />
        </Animated.View>
        
        <Text style={[styles.title, darkMode && styles.titleDark]}>
          Gonvill
        </Text>
        
        <Text style={[styles.subtitle, darkMode && styles.subtitleDark]}>
          Cargando tu biblioteca...
        </Text>
        
        {/* Indicador de carga */}
        <View style={styles.loaderContainer}>
          <View style={[styles.loader, darkMode && styles.loaderDark]} />
        </View>
      </Animated.View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    justifyContent: 'center',
    alignItems: 'center',
  },
  containerDark: {
    backgroundColor: '#121212',
  },
  content: {
    alignItems: 'center',
  },
  iconContainer: {
    width: 120,
    height: 120,
    borderRadius: 60,
    backgroundColor: 'rgba(255, 163, 194, 0.1)',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 24,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#1A1A1A',
    marginBottom: 8,
  },
  titleDark: {
    color: '#fff',
  },
  subtitle: {
    fontSize: 16,
    color: '#666',
    marginBottom: 32,
  },
  subtitleDark: {
    color: '#999',
  },
  loaderContainer: {
    width: 200,
    height: 4,
    backgroundColor: 'rgba(255, 163, 194, 0.2)',
    borderRadius: 2,
    overflow: 'hidden',
  },
  loader: {
    width: '50%',
    height: '100%',
    backgroundColor: '#ffa3c2',
    borderRadius: 2,
    animation: 'slide 1.5s infinite',
  },
  loaderDark: {
    backgroundColor: '#ff8fb3',
  },
});