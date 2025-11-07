import { useRouter } from 'expo-router';

// Dentro del componente:
const router = useRouter();

const handleProfilePress = () => {
  if (isLoggedIn) {
    router.push('/profile'); // Ir al perfil
  } else {
    router.push('/login'); // Ir a login
  }
};  