// colors.js - Sistema centralizado de colores para Light/Dark Mode

export const Colors = {
  light: {
    // Backgrounds
    background: '#F5F5F5',
    surface: '#FFFFFF',
    card: '#FFFFFF',
    
    // Textos
    text: '#1A1A1A',
    textSecondary: '#666666',
    textTertiary: '#999999',
    
    // Bordes
    border: '#E0E0E0',
    borderLight: '#F0F0F0',
    
    // Primarios
    primary: '#ffa3c2',
    primaryLight: '#fff5f9',
    success: '#2E7D32',
    error: '#F44336',
    
    // StatusBar
    statusBar: 'dark-content',
    
    // Categorías (colores vibrantes - se mantienen igual)
    categoryColors: [
      '#FF6B9D', '#4ECDC4', '#95E1D3', '#FFD93D', 
      '#6BCB77', '#4D96FF', '#C44569', '#A8E6CF'
    ],
  },
  
  dark: {
    // Backgrounds
    background: '#121212',
    surface: '#1E1E1E',
    card: '#2A2A2A',
    
    // Textos
    text: '#FFFFFF',
    textSecondary: '#B3B3B3',
    textTertiary: '#808080',
    
    // Bordes
    border: '#333333',
    borderLight: '#2A2A2A',
    
    // Primarios
    primary: '#ffa3c2',
    primaryLight: '#3d2430',
    success: '#4CAF50',
    error: '#EF5350',
    
    // StatusBar
    statusBar: 'light-content',
    
    // Categorías (colores vibrantes - se mantienen igual)
    categoryColors: [
      '#FF6B9D', '#4ECDC4', '#95E1D3', '#FFD93D', 
      '#6BCB77', '#4D96FF', '#C44569', '#A8E6CF'
    ],
  }
};

// Hook helper para obtener colores según el tema
export const getColors = (darkMode) => {
  return darkMode ? Colors.dark : Colors.light;
};