import { Image, StyleSheet, Text, View } from 'react-native';

export default function BookCard({ book }) {
  const author = book.authors?.[0]
    ? `${book.authors[0].first_name} ${book.authors[0].last_name}`
    : 'Autor desconocido';

  return (
    <View style={styles.card}>
      <Image
        source={{ uri: 'https://cdn-icons-png.flaticon.com/512/29/29302.png' }}
        style={styles.image}
      />
      <View style={{ flex: 1 }}>
        <Text style={styles.title}>{book.title}</Text>
        <Text style={styles.author}>{author}</Text>
        <Text style={styles.price}>${book.price}</Text>
        <Text style={styles.status}>
          {book.status === 'En stock' ? 'Disponible' : 'Agotado'}
        </Text>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  card: {
    flexDirection: 'row',
    backgroundColor: '#f9f9f9',
    marginVertical: 8,
    padding: 10,
    borderRadius: 10,
    alignItems: 'center',
  },
  image: {
    width: 60,
    height: 80,
    resizeMode: 'contain',
    marginRight: 10,
  },
  title: { fontSize: 16, fontWeight: 'bold', color: '#222' },
  author: { fontSize: 14, color: '#555' },
  price: { fontSize: 15, color: '#008000', marginTop: 4 },
  status: { fontSize: 12, color: '#999', marginTop: 2 },
});
