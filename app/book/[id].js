// Este archivo solo conecta la ruta con el screen

import { useLocalSearchParams } from 'expo-router';
import BookScreen from '../../screens/BookScreen';

export default function BookRoute() {
  const { id } = useLocalSearchParams();
  
  return <BookScreen bookId={id} />;
}