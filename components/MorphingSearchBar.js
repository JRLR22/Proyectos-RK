import { Ionicons } from '@expo/vector-icons';
import { useRef, useState } from 'react';
import { Animated, Dimensions, StyleSheet, TextInput, TouchableOpacity } from 'react-native';

const { width: SCREEN_WIDTH } = Dimensions.get('window');

export const MorphingSearchBar = ({ value, onChangeText, onClear, darkMode }) => {
  const [expanded, setExpanded] = useState(false);
  const animation = useRef(new Animated.Value(0)).current;
  const searchInput = useRef(null);

  const toggleSearch = () => {
    const toValue = expanded ? 0 : 1;
    
    Animated.spring(animation, {
      toValue,
      friction: 8,
      tension: 40,
      useNativeDriver: false,
    }).start();

    setExpanded(!expanded);
    
    if (!expanded) {
      setTimeout(() => searchInput.current?.focus(), 300);
    } else {
      searchInput.current?.blur();
      onClear();
    }
  };

  const width = animation.interpolate({
    inputRange: [0, 1],
    outputRange: [50, SCREEN_WIDTH - 32], // Cambiado de 100 a 32 para más ancho
  });

  const borderRadius = animation.interpolate({
    inputRange: [0, 1],
    outputRange: [25, 16],
  });

  const iconRotate = animation.interpolate({
    inputRange: [0, 1],
    outputRange: ['0deg', '90deg'],
  });

  return (
    <Animated.View 
      style={[
        styles.searchContainer,
        darkMode && styles.searchContainerDark,
        { width, borderRadius }
      ]}
    >
      <TouchableOpacity onPress={toggleSearch} style={styles.searchButton}>
        <Animated.View style={{ transform: [{ rotate: iconRotate }] }}>
          <Ionicons 
            name={expanded ? "close" : "search"} 
            size={24} 
            color={darkMode ? "#fff" : "#000"} 
          />
        </Animated.View>
      </TouchableOpacity>

      {expanded && (
        <>
          <TextInput
            ref={searchInput}
            style={[styles.input, darkMode && styles.inputDark]}
            placeholder="Buscar libros o autores..."
            placeholderTextColor={darkMode ? "#666" : "#999"}
            value={value}
            onChangeText={onChangeText}
            returnKeyType="search"
            blurOnSubmit={false}
            autoCorrect={false}
            autoCapitalize="none"
          />
          
          {value?.length > 0 && (
            <TouchableOpacity 
              onPress={onClear}
              style={styles.clearButton}
            >
              <Ionicons name="close-circle" size={20} color={darkMode ? "#666" : "#999"} />
            </TouchableOpacity>
          )}
        </>
      )}
    </Animated.View>
  );
};

const styles = StyleSheet.create({
  
  searchContainer: {
    height: 50,
    backgroundColor: '#fff',
    flexDirection: 'row',
    alignItems: 'center',
    marginTop:10,
    marginLeft: 10,
    paddingLeft: 10,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 8,
    elevation: 8,
    borderRadius: 16,
  },
  searchContainerDark: {
    backgroundColor: '#1E1E1E',
  },
  searchButton: {
    padding: 4,
  },
  input: {
    flex: 1,
    marginLeft: 12,
    fontSize: 16,
    color: '#000',
  },
  inputDark: {
    color: '#fff',
  },
  clearButton: {
    padding: 8,
    marginLeft: 4,
  },
});