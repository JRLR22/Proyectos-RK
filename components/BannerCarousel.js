import { Image, ScrollView, StyleSheet } from 'react-native';

export default function BannerCarousel() {
  const banners = [
    { id: 1, uri: 'https://via.placeholder.com/400x120?text=Envíos+Gratis' },
    { id: 2, uri: 'https://via.placeholder.com/400x120?text=Rebajas' },
    { id: 3, uri: 'https://via.placeholder.com/400x120?text=School+Shop' },
  ];

  return (
    <ScrollView
      horizontal
      showsHorizontalScrollIndicator={false}
      pagingEnabled
      style={styles.carousel}
    >
      {banners.map((b) => (
        <Image key={b.id} source={{ uri: b.uri }} style={styles.image} />
      ))}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  carousel: { height: 120, marginBottom: 15 },
  image: {
    width: 350,
    height: 120,
    marginRight: 10,
    borderRadius: 8,
  },
});
