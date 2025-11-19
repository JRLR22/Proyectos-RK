// screens/StoresScreen.js - VERSIÓN MEJORADA CON ZOOM AUTOMÁTICO Y ESRI 3D

import { Ionicons } from "@expo/vector-icons";
import { useRouter } from 'expo-router';
import { useRef, useState } from "react";
import {
    Linking,
    Modal,
    Platform,
    ScrollView,
    StatusBar,
    StyleSheet,
    Text,
    TouchableOpacity,
    View
} from "react-native";
import { WebView } from 'react-native-webview';
import { getColors } from '../constants/colors';
import { useTheme } from '../contexts/ThemeContext';

export default function StoresScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  const webViewRef = useRef(null);
  const [selectedStore, setSelectedStore] = useState(null);
  const [selectedMap, setSelectedMap] = useState('osm');
  const [showMapSelector, setShowMapSelector] = useState(false);

  const stores = [
    {
      id: 1,
      name: "Gonvill Centro",
      address: "Av. Álvaro Obregón 123, Centro, Culiacán",
      phone: "+526671234567",
      latitude: 24.8091,
      longitude: -107.3940
    },
    {
      id: 2,
      name: "Gonvill Norte",
      address: "Blvd. Emiliano Zapata 456, Culiacán",
      phone: "+526671234568",
      latitude: 24.8291,
      longitude: -107.3840
    },
    {
      id: 3,
      name: "Gonvill Sur",
      address: "Av. Universidad 789, Culiacán",
      phone: "+526671234569",
      latitude: 24.7891,
      longitude: -107.4040
    }
  ];

  // 🗺️ Mapas mejorados - SOLO OSM, ESRI Satélite y ESRI 3D
  const mapProviders = [
    {
      id: 'osm',
      name: 'OpenStreetMap',
      description: 'Estándar detallado',
      icon: 'map-outline',
      type: '2d',
      light: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      dark: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
      attribution: '© OpenStreetMap'
    },
    {
      id: 'esri-satellite',
      name: 'ESRI Satélite',
      description: 'Vista satelital HD',
      icon: 'planet-outline',
      type: '2d',
      light: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
      dark: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
      attribution: '© ESRI'
    },
    {
      id: 'esri-streets',
      name: 'ESRI Calles',
      description: 'Navegación urbana',
      icon: 'car-outline',
      type: '2d',
      light: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}',
      dark: 'https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}',
      attribution: '© ESRI'
    },
    {
      id: 'esri-topo',
      name: 'ESRI Topográfico',
      description: 'Relieve y elevación',
      icon: 'stats-chart-outline',
      type: '2d',
      light: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}',
      dark: 'https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Dark_Gray_Base/MapServer/tile/{z}/{y}/{x}',
      attribution: '© ESRI'
    },
    {
      id: 'esri-3d',
      name: 'ESRI 3D Scene',
      description: 'Vista 3D con rotación',
      icon: 'cube-outline',
      type: '3d',
      attribution: '© ESRI'
    }
  ];

  const currentMapProvider = mapProviders.find(m => m.id === selectedMap);

  const handleCall = (phone) => {
    Linking.openURL(`tel:${phone}`);
  };

  const handleDirections = (latitude, longitude) => {
    const scheme = Platform.select({ 
      ios: 'maps:0,0?q=', 
      android: 'geo:0,0?q=' 
    });
    const latLng = `${latitude},${longitude}`;
    const label = selectedStore?.name || 'Gonvill';
    const url = Platform.select({
      ios: `${scheme}${label}@${latLng}`,
      android: `${scheme}${latLng}(${label})`
    });

    Linking.openURL(url);
  };

  // 🎯 Función para hacer zoom desde las tarjetas de tiendas
  const handleZoomToStore = (store) => {
    setSelectedStore(store);
    
    if (webViewRef.current) {
      webViewRef.current.injectJavaScript(`
        if (window.zoomToStore) {
          window.zoomToStore(${store.id});
        }
        true;
      `);
    }
  };

  // HTML para mapas 2D (Leaflet)
  const generate2DMapHTML = () => {
    const tileLayer = darkMode ? currentMapProvider.dark : currentMapProvider.light;

    return `
    <!DOCTYPE html>
    <html>
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
      <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
      <style>
        body { margin: 0; padding: 0; }
        #map { width: 100%; height: 100vh; }
        .leaflet-popup-content-wrapper {
          border-radius: 12px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .custom-popup {
          text-align: center;
          padding: 8px;
          min-width: 200px;
        }
        .popup-title {
          font-size: 16px;
          font-weight: bold;
          color: #ffa3c2;
          margin-bottom: 8px;
        }
        .popup-address {
          font-size: 13px;
          color: #666;
          margin-bottom: 8px;
        }
        .popup-phone {
          color: #ffa3c2;
          text-decoration: none;
          font-weight: 600;
        }
      </style>
    </head>
    <body>
      <div id="map"></div>
      <script>
        const map = L.map('map', {
          zoomControl: true,
          attributionControl: true
        }).setView([24.8091, -107.3940], 13);
        
        L.tileLayer('${tileLayer}', {
          attribution: '${currentMapProvider.attribution}',
          maxZoom: 19,
          minZoom: 10
        }).addTo(map);

        // Icono personalizado mejorado con sombra
        const pinkIcon = L.icon({
          iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48ZmlsdGVyIGlkPSJzaGFkb3ciPjxmZUdhdXNzaWFuQmx1ciBpbj0iU291cmNlQWxwaGEiIHN0ZERldmlhdGlvbj0iMiIvPjxmZU9mZnNldCBkeT0iMiIgcmVzdWx0PSJvZmZzZXRibHVyIi8+PGZlRmxvb2QgZmxvb2QtY29sb3I9IiMwMDAiIGZsb29kLW9wYWNpdHk9IjAuMyIvPjxmZUNvbXBvc2l0ZSBpbjI9Im9mZnNldGJsdXIiIG9wZXJhdG9yPSJpbiIvPjxmZU1lcmdlPjxmZU1lcmdlTm9kZS8+PGZlTWVyZ2VOb2RlIGluPSJTb3VyY2VHcmFwaGljIi8+PC9mZU1lcmdlPjwvZmlsdGVyPjwvZGVmcz48Y2lyY2xlIGN4PSIyNCIgY3k9IjIwIiByPSIxNiIgZmlsbD0iI2ZmYTNjMiIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjMiIGZpbHRlcj0idXJsKCNzaGFkb3cpIi8+PHBhdGggZD0iTTE4IDIwaDEyTTI0IDE0djEyIiBzdHJva2U9IiNmZmYiIHN0cm9rZS13aWR0aD0iMyIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+PHBhdGggZD0iTTI0IDM2bC00IDhsNCAtNGw0IDQiIGZpbGw9IiNmZmEzYzIiIHN0cm9rZT0iI2ZmZiIgc3Ryb2tlLXdpZHRoPSIyIi8+PC9zdmc+',
          iconSize: [48, 48],
          iconAnchor: [24, 44],
          popupAnchor: [0, -44]
        });

        const markers = [];

        ${stores.map((store, index) => `
          const marker${index} = L.marker([${store.latitude}, ${store.longitude}], { icon: pinkIcon })
            .addTo(map)
            .bindPopup(\`
              <div class="custom-popup">
                <div class="popup-title">📚 ${store.name}</div>
                <div class="popup-address">${store.address}</div>
                <a href="tel:${store.phone}" class="popup-phone">
                  📞 ${store.phone}
                </a>
              </div>
            \`)
            .on('click', function() {
              map.flyTo([${store.latitude}, ${store.longitude}], 16, {
                animate: true,
                duration: 1.5
              });
              
              window.ReactNativeWebView.postMessage(JSON.stringify(${JSON.stringify(store)}));
            });
          
          markers.push(marker${index});
        `).join('\n')}

        // Función para zoom desde React Native
        window.zoomToStore = function(storeId) {
          const storeData = ${JSON.stringify(stores)};
          const store = storeData.find(s => s.id === storeId);
          if (store) {
            map.flyTo([store.latitude, store.longitude], 16, {
              animate: true,
              duration: 1.5
            });
            
            const marker = markers[storeId - 1];
            if (marker) {
              setTimeout(() => marker.openPopup(), 500);
            }
          }
        };
      </script>
    </body>
    </html>
  `;
  };

  // HTML para mapa 3D (ESRI ArcGIS)
  const generate3DMapHTML = () => {
    return `
    <!DOCTYPE html>
    <html>
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://js.arcgis.com/4.28/esri/themes/light/main.css">
      <script src="https://js.arcgis.com/4.28/"></script>
      <style>
        html, body, #viewDiv { 
          padding: 0; 
          margin: 0; 
          height: 100%; 
          width: 100%; 
        }
      </style>
    </head>
    <body>
      <div id="viewDiv"></div>
      <script>
        require([
          "esri/Map",
          "esri/views/SceneView",
          "esri/Graphic",
          "esri/layers/GraphicsLayer"
        ], function(Map, SceneView, Graphic, GraphicsLayer) {
          
          const graphicsLayer = new GraphicsLayer();
          
          const map = new Map({
            basemap: "satellite",
            ground: "world-elevation",
            layers: [graphicsLayer]
          });
          
          const view = new SceneView({
            container: "viewDiv",
            map: map,
            camera: {
              position: {
                longitude: -107.3940,
                latitude: 24.8091,
                z: 2000
              },
              tilt: 60,
              heading: 0
            }
          });

          const stores = ${JSON.stringify(stores)};

          stores.forEach(store => {
            const point = {
              type: "point",
              longitude: store.longitude,
              latitude: store.latitude,
              z: 50
            };

            const markerSymbol = {
              type: "simple-marker",
              color: [255, 163, 194, 0.9],
              size: "16px",
              outline: {
                color: [255, 255, 255],
                width: 3
              }
            };

            const pointGraphic = new Graphic({
              geometry: point,
              symbol: markerSymbol,
              attributes: store,
              popupTemplate: {
                title: "📚 " + store.name,
                content: store.address + "<br>📞 " + store.phone
              }
            });

            graphicsLayer.add(pointGraphic);
          });

          view.on("click", function(event) {
            view.hitTest(event).then(function(response) {
              if (response.results.length) {
                const graphic = response.results[0].graphic;
                if (graphic.attributes) {
                  window.ReactNativeWebView.postMessage(
                    JSON.stringify(graphic.attributes)
                  );
                  
                  view.goTo({
                    target: graphic.geometry,
                    zoom: 17,
                    tilt: 65,
                    heading: 30
                  }, {
                    duration: 2000
                  });
                }
              }
            });
          });

          window.zoomToStore = function(storeId) {
            const store = stores.find(s => s.id === storeId);
            if (store) {
              view.goTo({
                center: [store.longitude, store.latitude],
                zoom: 17,
                tilt: 65,
                heading: 30
              }, {
                duration: 2000
              });
            }
          };
        });
      </script>
    </body>
    </html>
  `;
  };

  const currentMapHTML = currentMapProvider?.type === '3d' ? generate3DMapHTML() : generate2DMapHTML();

  const handleMessage = (event) => {
    try {
      const store = JSON.parse(event.nativeEvent.data);
      setSelectedStore(store);
    } catch (error) {
      console.error('Error parsing message:', error);
    }
  };

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
        <Text style={[styles.headerTitle, { color: colors.text }]}>
          Nuestras Sucursales
        </Text>
        
        <TouchableOpacity 
          style={styles.mapSelectorButton}
          onPress={() => setShowMapSelector(true)}
        >
          <Ionicons name="layers-outline" size={24} color={colors.text} />
        </TouchableOpacity>
      </View>

      {/* Banner del mapa actual */}
      <View style={[styles.mapBanner, { backgroundColor: colors.primaryLight }]}>
        <Ionicons name={currentMapProvider.icon} size={18} color={colors.primary} />
        <Text style={[styles.mapBannerText, { color: colors.primary }]}>
          {currentMapProvider.name} • {currentMapProvider.description}
        </Text>
      </View>

      {/* Mapa */}
      <View style={styles.mapContainer}>
        <WebView
          ref={webViewRef}
          key={selectedMap}
          originWhitelist={['*']}
          source={{ html: currentMapHTML }}
          style={styles.map}
          onMessage={handleMessage}
          javaScriptEnabled={true}
          domStorageEnabled={true}
        />
      </View>

      {/* Lista de sucursales */}
      <ScrollView style={styles.storesList}>
        {stores.map((store) => (
          <TouchableOpacity
            key={store.id}
            style={[
              styles.storeCard,
              { backgroundColor: colors.card },
              selectedStore?.id === store.id && { 
                borderColor: colors.primary, 
                borderWidth: 2 
              }
            ]}
            onPress={() => handleZoomToStore(store)}
          >
            <View style={styles.storeCardContent}>
              <View style={[styles.storeIcon, { backgroundColor: colors.primaryLight }]}>
                <Ionicons name="storefront" size={24} color={colors.primary} />
              </View>
              
              <View style={styles.storeInfo}>
                <Text style={[styles.storeName, { color: colors.text }]}>
                  {store.name}
                </Text>
                <Text style={[styles.storeAddress, { color: colors.textSecondary }]}>
                  {store.address}
                </Text>
              </View>
            </View>

            <View style={styles.storeActions}>
              <TouchableOpacity
                style={[styles.actionButton, { backgroundColor: colors.success }]}
                onPress={() => handleCall(store.phone)}
              >
                <Ionicons name="call" size={18} color="#fff" />
                <Text style={styles.actionText}>Llamar</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={[styles.actionButton, { backgroundColor: colors.primary }]}
                onPress={() => handleDirections(store.latitude, store.longitude)}
              >
                <Ionicons name="navigate" size={18} color="#fff" />
                <Text style={styles.actionText}>Ir</Text>
              </TouchableOpacity>
            </View>
          </TouchableOpacity>
        ))}
      </ScrollView>

      {/* Modal selector de mapas */}
      <Modal
        visible={showMapSelector}
        animationType="slide"
        transparent={true}
        onRequestClose={() => setShowMapSelector(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={[styles.modalContent, { backgroundColor: colors.surface }]}>
            <View style={styles.modalHeader}>
              <Text style={[styles.modalTitle, { color: colors.text }]}>
                Selecciona un mapa
              </Text>
              <TouchableOpacity onPress={() => setShowMapSelector(false)}>
                <Ionicons name="close" size={28} color={colors.text} />
              </TouchableOpacity>
            </View>

            <ScrollView style={styles.mapList}>
              {mapProviders.map((provider) => (
                <TouchableOpacity
                  key={provider.id}
                  style={[
                    styles.mapOption,
                    { borderBottomColor: colors.borderLight },
                    selectedMap === provider.id && { 
                      backgroundColor: colors.primaryLight 
                    }
                  ]}
                  onPress={() => {
                    setSelectedMap(provider.id);
                    setShowMapSelector(false);
                  }}
                >
                  <View style={[
                    styles.mapOptionIcon,
                    { backgroundColor: selectedMap === provider.id ? colors.primary : colors.primaryLight }
                  ]}>
                    <Ionicons 
                      name={provider.icon} 
                      size={24} 
                      color={selectedMap === provider.id ? "#fff" : colors.primary} 
                    />
                  </View>

                  <View style={styles.mapOptionText}>
                    <Text style={[
                      styles.mapOptionName, 
                      { color: colors.text }
                    ]}>
                      {provider.name}
                    </Text>
                    <Text style={[
                      styles.mapOptionDescription, 
                      { color: colors.textSecondary }
                    ]}>
                      {provider.description}
                    </Text>
                  </View>

                  {selectedMap === provider.id && (
                    <Ionicons name="checkmark-circle" size={24} color={colors.primary} />
                  )}
                </TouchableOpacity>
              ))}
            </ScrollView>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1 
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
    flex: 1,
    textAlign: 'center',
  },
  mapSelectorButton: {
    padding: 8,
  },
  mapBanner: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 8,
    gap: 8,
  },
  mapBannerText: {
    fontSize: 13,
    fontWeight: '600',
  },
  mapContainer: {
    height: 300,
  },
  map: { 
    flex: 1 
  },
  storesList: {
    flex: 1,
    padding: 16,
  },
  storeCard: {
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  storeCardContent: {
    flexDirection: 'row',
    marginBottom: 12,
  },
  storeIcon: {
    width: 48,
    height: 48,
    borderRadius: 24,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  storeInfo: {
    flex: 1,
  },
  storeName: { 
    fontSize: 16, 
    fontWeight: "600", 
    marginBottom: 4 
  },
  storeAddress: { 
    fontSize: 14,
    lineHeight: 20,
  },
  storeActions: {
    flexDirection: 'row',
    gap: 12,
  },
  actionButton: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    padding: 12,
    borderRadius: 8,
    gap: 8,
  },
  actionText: {
    color: '#fff',
    fontWeight: '600',
    fontSize: 14,
  },
  
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    justifyContent: 'flex-end',
  },
  modalContent: {
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
    maxHeight: '70%',
  },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#E0E0E0',
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: '600',
  },
  mapList: {
    padding: 16,
  },
  mapOption: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    borderRadius: 12,
    marginBottom: 8,
    borderBottomWidth: 1,
  },
  mapOptionIcon: {
    width: 48,
    height: 48,
    borderRadius: 24,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  mapOptionText: {
    flex: 1,
  },
  mapOptionName: {
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 2,
  },
  mapOptionDescription: {
    fontSize: 13,
  },
});