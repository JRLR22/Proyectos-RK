// Spinner estilo Apple/Google 

import { useEffect, useRef } from 'react';
import { Animated, Easing, StyleSheet, View } from 'react-native';
import { useTheme } from '../contexts/ThemeContext';

export default function SpinnerLoader() {
  const { darkMode } = useTheme();
  const spinAnim = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    Animated.loop(
      Animated.timing(spinAnim, {
        toValue: 1,
        duration: 1000,
        easing: Easing.linear,
        useNativeDriver: true,
      })
    ).start();
  }, []);

  const rotate = spinAnim.interpolate({
    inputRange: [0, 1],
    outputRange: ['0deg', '360deg'],
  });

  return (
    <View style={[styles.container, darkMode && styles.containerDark]}>
      <Animated.View
        style={[
          styles.spinner,
          {
            transform: [{ rotate }],
          },
        ]}
      >
        {[...Array(12)].map((_, i) => (
          <View
            key={i}
            style={[
              styles.bar,
              {
                transform: [
                  { rotate: `${i * 30}deg` },
                  { translateY: -20 },
                ],
                opacity: 1 - (i * 0.08),
              },
            ]}
          />
        ))}
      </Animated.View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#F5F5F5',
  },
  containerDark: {
    backgroundColor: '#121212',
  },
  spinner: {
    width: 50,
    height: 50,
    position: 'relative',
  },
  bar: {
    position: 'absolute',
    width: 3,
    height: 10,
    borderRadius: 1.5,
    backgroundColor: '#ffa3c2',
    left: '50%',
    top: '50%',
    marginLeft: -1.5,
    marginTop: -5,
  },
});