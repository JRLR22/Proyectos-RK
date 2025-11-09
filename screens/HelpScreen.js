import { Ionicons } from "@expo/vector-icons";
import { useRouter } from 'expo-router';
import { useState } from "react";
import {
  Linking,
  ScrollView,
  StatusBar,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from "react-native";
import { getColors } from '../constants/colors';
import { useTheme } from '../contexts/ThemeContext';

export default function HelpScreen() {
  const router = useRouter();
  const { darkMode } = useTheme();
  const colors = getColors(darkMode);
  const [expandedIndex, setExpandedIndex] = useState(null);

  const faqs = [
    {
      question: "¿Cómo puedo realizar un pedido?",
      answer: "Navega por el catálogo, selecciona los libros que desees y agrégalos al carrito. Luego ve al carrito y completa el proceso de compra."
    },
    {
      question: "¿Cuánto tarda la entrega?",
      answer: "Las entregas generalmente toman entre 3-5 días hábiles dependiendo de tu ubicación. Recibirás un número de seguimiento por correo."
    },
    {
      question: "¿Puedo cancelar mi pedido?",
      answer: "Puedes cancelar tu pedido dentro de las primeras 2 horas después de realizarlo. Ve a 'Mis Órdenes' y selecciona la opción de cancelar."
    },
    {
      question: "¿Qué métodos de pago aceptan?",
      answer: "Aceptamos tarjetas de crédito/débito (Visa, Mastercard, American Express), PayPal y transferencias bancarias."
    },
    {
      question: "¿Hacen envíos internacionales?",
      answer: "Actualmente solo realizamos envíos dentro de México. Estamos trabajando para expandir nuestro servicio."
    },
    {
      question: "¿Cómo puedo rastrear mi pedido?",
      answer: "Una vez enviado tu pedido, recibirás un correo con el número de guía. También puedes ver el estado en la sección 'Mis Órdenes'."
    },
    {
      question: "¿Tienen política de devoluciones?",
      answer: "Sí, aceptamos devoluciones dentro de los 30 días posteriores a la compra. Los libros deben estar en perfectas condiciones."
    },
    {
      question: "¿Cómo actualizo mi información de perfil?",
      answer: "Ve a tu perfil desde el menú principal, donde podrás editar tu nombre, dirección, teléfono y otros datos."
    }
  ];

  const contactOptions = [
    {
      icon: "mail-outline",
      title: "Email",
      subtitle: "soporte@gonvill.com",
      action: () => Linking.openURL('mailto:soporte@gonvill.com')
    },
    {
      icon: "call-outline",
      title: "Teléfono",
      subtitle: "+52 667 123 4567",
      action: () => Linking.openURL('tel:+526671234567')
    },
    {
      icon: "logo-whatsapp",
      title: "WhatsApp",
      subtitle: "Chatea con nosotros",
      action: () => Linking.openURL('https://wa.me/526671234567')
    },
    {
      icon: "time-outline",
      title: "Horario",
      subtitle: "Lun-Vie: 9AM-6PM",
      action: null
    }
  ];

  const FAQItem = ({ item, index }) => {
    const isExpanded = expandedIndex === index;
    
    return (
      <TouchableOpacity
        style={[styles.faqItem, { borderBottomColor: colors.borderLight }]}
        onPress={() => setExpandedIndex(isExpanded ? null : index)}
        activeOpacity={0.7}
      >
        <View style={styles.faqHeader}>
          <Text style={[styles.faqQuestion, { color: colors.text }]}>
            {item.question}
          </Text>
          <Ionicons 
            name={isExpanded ? "chevron-up" : "chevron-down"} 
            size={20} 
            color={colors.textSecondary} 
          />
        </View>
        {isExpanded && (
          <Text style={[styles.faqAnswer, { color: colors.textSecondary }]}>
            {item.answer}
          </Text>
        )}
      </TouchableOpacity>
    );
  };

  const ContactOption = ({ item }) => (
    <TouchableOpacity
      style={[styles.contactOption, { borderBottomColor: colors.borderLight }]}
      onPress={item.action}
      disabled={!item.action}
      activeOpacity={item.action ? 0.7 : 1}
    >
      <View style={[styles.contactIconContainer, { backgroundColor: colors.primaryLight }]}>
        <Ionicons name={item.icon} size={24} color={colors.primary} />
      </View>
      <View style={styles.contactText}>
        <Text style={[styles.contactTitle, { color: colors.text }]}>
          {item.title}
        </Text>
        <Text style={[styles.contactSubtitle, { color: colors.textSecondary }]}>
          {item.subtitle}
        </Text>
      </View>
      {item.action && (
        <Ionicons name="chevron-forward" size={20} color={colors.textTertiary} />
      )}
    </TouchableOpacity>
  );

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
          Ayuda y Soporte
        </Text>
        <View style={{ width: 40 }} />
      </View>

      <ScrollView contentContainerStyle={styles.scrollContent}>
        {/* Banner de bienvenida */}
        <View style={[styles.welcomeBanner, { 
          backgroundColor: colors.surface,
          borderBottomColor: colors.border 
        }]}>
          <View style={styles.welcomeIconContainer}>
            <Ionicons name="help-circle" size={48} color={colors.primary} />
          </View>
          <Text style={[styles.welcomeTitle, { color: colors.text }]}>
            ¿En qué podemos ayudarte?
          </Text>
          <Text style={[styles.welcomeSubtitle, { color: colors.textSecondary }]}>
            Encuentra respuestas a preguntas frecuentes o contáctanos directamente
          </Text>
        </View>

        {/* Preguntas Frecuentes */}
        <View style={styles.section}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>
            Preguntas Frecuentes
          </Text>
          <View style={[styles.faqContainer, { backgroundColor: colors.card }]}>
            {faqs.map((faq, index) => (
              <FAQItem key={index} item={faq} index={index} />
            ))}
          </View>
        </View>

        {/* Contacto */}
        <View style={styles.section}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>
            Contáctanos
          </Text>
          <View style={[styles.contactContainer, { backgroundColor: colors.card }]}>
            {contactOptions.map((option, index) => (
              <ContactOption key={index} item={option} />
            ))}
          </View>
        </View>

        {/* Recursos adicionales */}
        <View style={styles.section}>
          <Text style={[styles.sectionTitle, { color: colors.text }]}>
            Recursos Adicionales
          </Text>
          <View style={styles.resourcesContainer}>
            <TouchableOpacity 
              style={[styles.resourceButton, { backgroundColor: colors.card }]}
              onPress={() => console.log('Guía de usuario')}
            >
              <Ionicons name="book-outline" size={22} color={colors.primary} />
              <Text style={[styles.resourceText, { color: colors.text }]}>
                Guía de usuario
              </Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={[styles.resourceButton, { backgroundColor: colors.card }]}
              onPress={() => console.log('Reportar problema')}
            >
              <Ionicons name="bug-outline" size={22} color={colors.primary} />
              <Text style={[styles.resourceText, { color: colors.text }]}>
                Reportar problema
              </Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={[styles.resourceButton, { backgroundColor: colors.card }]}
              onPress={() => console.log('Sugerencias')}
            >
              <Ionicons name="bulb-outline" size={22} color={colors.primary} />
              <Text style={[styles.resourceText, { color: colors.text }]}>
                Enviar sugerencia
              </Text>
            </TouchableOpacity>
          </View>
        </View>

        <View style={styles.footer}>
          <Text style={[styles.footerText, { color: colors.textSecondary }]}>
            ¿No encuentras lo que buscas?
          </Text>
          <TouchableOpacity 
            style={[styles.footerButton, { backgroundColor: colors.primary }]}
          >
            <Text style={styles.footerButtonText}>Enviar mensaje</Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
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
  },
  scrollContent: {
    paddingBottom: 32,
  },
  welcomeBanner: {
    padding: 24,
    alignItems: 'center',
    borderBottomWidth: 1,
  },
  welcomeIconContainer: {
    marginBottom: 16,
  },
  welcomeTitle: {
    fontSize: 22,
    fontWeight: '600',
    marginBottom: 8,
    textAlign: 'center',
  },
  welcomeSubtitle: {
    fontSize: 14,
    textAlign: 'center',
    lineHeight: 20,
  },
  section: {
    marginTop: 24,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '600',
    paddingHorizontal: 16,
    marginBottom: 12,
  },
  faqContainer: {
  },
  faqItem: {
    paddingHorizontal: 16,
    paddingVertical: 16,
    borderBottomWidth: 1,
  },
  faqHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  faqQuestion: {
    fontSize: 15,
    fontWeight: '500',
    flex: 1,
    paddingRight: 12,
  },
  faqAnswer: {
    fontSize: 14,
    marginTop: 12,
    lineHeight: 20,
  },
  contactContainer: {
    borderRadius: 12,
    marginHorizontal: 16,
    overflow: 'hidden',
  },
  contactOption: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
  },
  contactIconContainer: {
    width: 48,
    height: 48,
    borderRadius: 24,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  contactText: {
    flex: 1,
  },
  contactTitle: {
    fontSize: 15,
    fontWeight: '500',
    marginBottom: 2,
  },
  contactSubtitle: {
    fontSize: 13,
  },
  resourcesContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    paddingHorizontal: 16,
    gap: 12,
  },
  resourceButton: {
    flex: 1,
    minWidth: '45%',
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
    gap: 8,
  },
  resourceText: {
    fontSize: 14,
    fontWeight: '500',
    textAlign: 'center',
  },
  footer: {
    alignItems: 'center',
    marginTop: 32,
    paddingHorizontal: 16,
  },
  footerText: {
    fontSize: 15,
    marginBottom: 16,
  },
  footerButton: {
    paddingHorizontal: 32,
    paddingVertical: 12,
    borderRadius: 8,
  },
  footerButtonText: {
    color: '#fff',
    fontSize: 15,
    fontWeight: '600',
  },
});