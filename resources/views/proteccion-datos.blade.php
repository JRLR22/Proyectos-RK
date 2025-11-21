<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Replica Librerías Gonvill</title>
<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <style>
                body {
                    position: relative;
                    z-index: 0;
                }
                
                main .grid img {
                    position: relative;
                    z-index: 1 !important;
                }
                
                main .grid .absolute {
                    z-index: 10 !important;
                }
                
                .sticky {
                    z-index: 100 !important;
                }
            </style>
</head>
<body>
    <!-- Header Superior -->
        <div class="bg-[#ffa3c2] text-white py-2 sticky top-0 z-[100]">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <div class="flex gap-3">
                    <a href="#" class="bg-blue-700 rounded-full w-8 h-8 flex items-center justify-center hover:bg-blue-800">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="bg-sky-400 rounded-full w-8 h-8 flex items-center justify-center hover:bg-sky-500">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                    <a href="#" class="bg-pink-600 rounded-full w-8 h-8 flex items-center justify-center hover:bg-pink-700">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="bg-red-600 rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-700">
                        <i class="fab fa-youtube text-sm"></i>
                    </a>
                </div>
                <div class="flex gap-4 items-center">
                    <a href="#" class="hover:underline">Contacto</a>
                    @if (Auth::check())
                        {{-- Si el usuario está autenticado, muestra su nombre --}}
                        <a href="{{ route('profile') }}" class="flex items-center gap-1 hover:underline">
                            <i class="fas fa-user"></i>
                            {{ Auth::user()->first_name }}
                        </a>
                    @else
                        {{-- Si no hay sesión iniciada, muestra "Mi cuenta" --}}
                        <a href="{{ route('mi.cuenta') }}" class="flex items-center gap-1 hover:underline">
                            <i class="fas fa-user"></i>
                            Mi cuenta
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Header Principal -->
        <div class="bg-white shadow-md py-4 sticky top-10 z-[99]">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between gap-4">
                <!-- Logo -->
                <div>
                    <img src="{{ asset('img/logo_Gonvill_pink.png') }}" alt="Gonvill Librerías" class="h-20">
                </div>
                    <!-- Buscador -->
                    <div class="flex-1 max-w-2xl">
                        <div class="flex">
                            <input type="text" placeholder="Título, Autor, ISBN, Código Gonvill" 
                                class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-sky-500">
                            <button class="bg-[#ffa3c2] hover:bg-[#FF82AE] text-white px-6">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <a href="#" class="text-sm text-gray-600 mt-1 inline-block">› Búsqueda avanzada</a>
                    </div>

                    <!-- Carrito -->
                    <div class="flex items-center gap-4">
                        <a href="#" class="relative">
                            <i class="far fa-heart text-2xl text-gray-700"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </a>
                        <a href="#" class="flex items-center gap-2">
                            <div class="relative">
                                <i class="fas fa-shopping-cart text-2xl text-gray-800"></i>
                            </div>
                            <span class="text-gray-700">Mi compra</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Menú de Navegación -->
    <nav class="bg-white border-t border-b border-gray-200 sticky top-32 z-[98]">
        <div class="container mx-auto px-4">
            <ul class="flex gap-8 py-4 justify-center">
                <li><a href="{{ route('inicio') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Inicio</a></li>
                <li class="relative group">
                    <a href="#" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Libros ▾</a>
                    <!-- Megamenú -->
                    <div class="absolute left-0 top-full hidden group-hover:block bg-white shadow-2xl border border-gray-200 w-[800px] p-6 z-[150]">
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <h4 class="font-bold text-gray-800 mb-3">EXPLORAR</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Novedades</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-3">MATERIAS</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Libros para Todos</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Literatura</a></li>                                     
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Diccionario y Referencia</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Entretenimiento y Oficios</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Interés General</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Otros Idiomas</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Textos Escolares y Universitarios</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Ciencias Sociales y Humanidades</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Arquitectura y Diseño</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Ingenierías y Computación</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Ciencias Económicas Administrativas</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Derecho</a></li>
                                </ul>
                            </div>
                            <div>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Ciencias de la Salud</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Agronomía y Medio Ambiente</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Textos Escolares</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Otros Idiomas</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Infantiles</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Juveniles</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Gadgets / Accesorios</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Francés</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Inglés</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Chino</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Italiano</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Alemán</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Portugués</a></li>
                                    <li><a href="#" class="text-gray-600 hover:text-sky-500">Español para Extranjeros</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li><a href="{{ route('impresion.demanda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Impresión bajo demanda</a></li>
                <li><a href="{{ route('sobre.nosotros') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Sobre Nosotros</a></li>
                <li><a href="{{ route('nuestras.librerias') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Nuestras librerías</a></li>
                <li><a href="{{ route('bolsa.trabajo') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Bolsa de trabajo</a></li>
                <li><a href="{{ route('ayuda') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >Ayuda</a></li>
                <li><a href="{{ route('schoolshop') }}" class="text-[#DB7B9E] hover:text-[#ED4585] font-medium px-2 py-1" >SchoolShop</a></li>
            </ul>
        </div>
    </nav>

    <!--AQUI PONER CUERPO EN CODIGO-->
    <div class="max-w-5xl mx-auto py-10 px-6 text-gray-800 leading-relaxed">

        <h1 style="color:#ffa3c2; margin-bottom:20px; font-weight:700; font-family:'Poppins', sans-serif; font-size:25px;">Protección de datos</h1>

        {{-- INFORMACIÓN PERSONAL --}}
        <h2 class="italic text-lg mb-4 text-gray-700">INFORMACIÓN PERSONAL</h2>

        <div class="space-y-4 text-justify">
            <p>
                Conforme a lo dispuesto en la Ley Federal de Protección a los Datos Personales en Posesión de los Particulares y su reglamento, 
                LIBRERÍAS GONVILL, S.A. DE C.V. con domicilio en 8 de Julio # 825, Colonia Moderna, en Guadalajara, Jalisco, 
                (en lo sucesivo LIBRERÍAS GONVILL) emite el presente Aviso de Privacidad para informar que es responsable de la confidencialidad, 
                uso y protección de la información personal que nos llegare a proporcionar por los distintos medios que utilizamos 
                para la difusión de bienes y servicios.
            </p>

            <p>
                Su información personal será utilizada para proveer los servicios y productos que ha solicitado, informarle sobre cambios en los mismos, 
                evaluar la calidad del servicio que le brindamos, realizar estudios internos sobre hábitos de consumo y en general para cualquier tipo 
                de relación jurídica o de negocios que lleve a cabo con nosotros.
                Para las finalidades antes mencionadas, podríamos requerirle su nombre, dirección, teléfono, correo electrónico, RFC, 
                fecha y lugar de nacimiento, sexo, nacionalidad, edad, información crediticia y patrimonial.
            </p>

            <p>
                En el caso de personal de LIBRERÍAS GONVILL y quienes soliciten empleo en nuestra empresa, utilizaremos sus datos para procesar su 
                solicitud de ingreso y verificar si cumple con el perfil y requisitos solicitados, conformar y mantener su expediente laboral 
                mientras exista el vínculo laboral y enviarle comunicados referentes a su actividad en la empresa. 
                Los datos que se recaban son nombre, dirección, teléfono, correo electrónico, fecha y lugar de nacimiento, sexo, nacionalidad, edad, 
                escolaridad, historial laboral, RFC, número de afiliación al IMSS, organización sindical a la que pudiera pertenecer y CURP.
            </p>

            <p>
                La entrega a LIBRERÍAS GONVILL de cualquier solicitud o documento que contenga datos personales para convertirse en cliente, 
                proveedor o empleado de LIBRERÍAS GONVILL, ya sea por el Titular o su representante legal, será considerada como una constancia del 
                consentimiento del Titular para el tratamiento de sus datos personales conforme a las finalidades del presente aviso.
            </p>
        </div>

        {{-- TRATAMIENTO DE DATOS PERSONALES --}}
        <h2 class="text-lg font-semibold mt-10 mb-4 text-gray-700">TRATAMIENTO DE DATOS PERSONALES</h2>

        <div class="space-y-4 text-justify">
            <p>
                Usted tiene derecho de acceder, rectificar y cancelar sus datos personales, así como de oponerse al tratamiento de los mismos, 
                o revocar el consentimiento que para tal fin nos haya otorgado, a través de los procedimientos que hemos implementado. 
                Para conocer los procedimientos, requisitos y plazos, favor de contactar a nuestro departamento de datos personales en nuestro 
                correo electrónico <a href="mailto:datospersonales@gonvill.com.mx" class="text-blue-600 underline">datospersonales@gonvill.com.mx</a> 
                o a los teléfonos (33) 3837-2300 o 01800 360-1919.
            </p>

            <p>
                Le informamos que sus datos personales no serán transferidos a terceros para fines distintos a los necesarios para brindarle 
                oportunamente los servicios y/o productos adquiridos en “LIBRERIAS GONVILL, S.A. DE C.V.”, salvaguardando así su protección y 
                confidencialidad, sin que para ello sea necesario obtener su autorización en términos del artículo 37 de la Ley Federal de Protección 
                de Datos Personales en Posesión de los Particulares.
            </p>

            <p>Nuestro sitio web utiliza el certificado de seguridad SSL de Verisign.</p>

            <p>
                Si usted considera que su derecho de protección de datos personales ha sido afectado por alguna conducta de nuestros empleados o 
                actuaciones o presume que en el tratamiento de sus datos personales existe alguna violación a las disposiciones previstas en la Ley, 
                puede interponer la queja o denuncia correspondiente ante el IFAI. 
                Para mayor información visite <a href="http://ifai.org.mx" target="_blank" class="text-blue-600 underline">ifai.org.mx</a>.
            </p>

            <p class="text-gray-600 italic">Fecha de última actualización: 9/05/2017</p>
        </div>

        {{-- COOKIES --}}
        <h2 class="text-lg font-semibold mt-10 mb-4 text-gray-700">COOKIES</h2>

        <div class="space-y-4 text-justify">
            <p>
                Esta web necesita del uso de "cookies" para su correcto funcionamiento. Una cookie es una pequeña información enviada por el sitio 
                web y que se almacena en el navegador del usuario, de manera que el sitio web puede consultar ciertos datos del usuario.
            </p>

            <p class="font-semibold">Este sitio web utiliza las siguientes Cookies:</p>

            <ul class="list-disc ml-8 space-y-3">
                <li><strong>Sesión</strong><br>
                Dominio: www.gonvill.com.mx<br>
                Necesaria para funcionalidades básicas como mantener los productos del carrito o saber si estás autenticado como cliente.
                </li>

                <li><strong>Analítica Web</strong><br>
                Dominio: www.gonvill.com.mx<br>
                Utiliza Google Analytics para conocer cómo los usuarios usan la web. No obtiene información personal del usuario.
                </li>

                <li><strong>Google AdWords</strong><br>
                Dominio: doubleclick.net<br>
                Recoge información sobre navegación y productos visitados para ofrecer anuncios personalizados.
                </li>

                <li><strong>Google Cookies</strong><br>
                Dominio: google.com<br>
                Recuerda preferencias de idioma, región y apariencia del sitio (como tamaño de texto o fuente).
                </li>

                <li><strong>Facebook Cookies</strong><br>
                Dominio: facebook.com<br>
                Se usan para ofrecer productos, servicios y publicidad personalizada.
                </li>

                <li><strong>Twitter Cookies</strong><br>
                Dominio: twitter.com<br>
                Recopilan datos adicionales sobre uso del sitio web y mejoran los servicios ofrecidos.
                </li>
            </ul>

            <p>
                <strong>Consentimiento:</strong> El usuario, al aceptar las condiciones anteriores, consiente el uso de las cookies mencionadas. 
                Puede deshabilitarlas en su navegador, pero al hacerlo no podrá realizar compras en la web.
            </p>

            <h3 class="font-semibold mt-6 mb-2">Gestionar las cookies en tu navegador:</h3>

            <ul class="list-disc ml-8 space-y-1 text-blue-600 underline">
                <li><a href="http://support.mozilla.org/es/products/firefox/cookies" target="_blank">Firefox</a></li>
                <li><a href="http://support.google.com/chrome/bin/answer.py?hl=es&answer=95647" target="_blank">Chrome</a></li>
                <li><a href="http://windows.microsoft.com/es-es/windows7/how-to-manage-cookies-in-internet-explorer-9" target="_blank">Explorer</a></li>
                <li><a href="http://support.apple.com/kb/ph5042" target="_blank">Safari</a></li>
                <li><a href="http://help.opera.com/Windows/11.50/es-ES/cookies.html" target="_blank">Opera</a></li>
                <li><a href="http://support.apple.com/kb/HT1677?viewlocale=es_ES" target="_blank">iOS (iPhone, iPad, iPod)</a></li>
                <li><a href="http://support.google.com/android/bin/answer.py?hl=es&answer=1722193" target="_blank">Android</a></li>
            </ul>
        </div>

    </div>

  <!-- Newsletter -->
    <div class="bg-[#ffa3c2] py-6">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 text-white">
                    <i class="far fa-envelope text-5xl"></i>
                    <div>
                        <h3 class="text-xl font-bold">Boletín de Novedades</h3>
                        <p class="text-sm">Suscríbete y estarás al tanto de nuestras novedades</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <input type="email" placeholder="Email" class="px-4 py-2 w-80 rounded focus:outline-none">
                    <button class="bg-blue-900 text-white px-6 py-2 rounded hover:bg-blue-950">Suscribir</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-4 gap-8 mb-8">
                <!-- Servicio al Cliente -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">SERVICIO AL CLIENTE</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Mis Pedidos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Mis Favoritos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Mis Direcciones</a></li>
                    </ul>
                </div>

                <!-- Políticas de la Tienda -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">POLÍTICAS DE LA TIENDA</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Sobre Nosotros</a></li>
                        <li><a href="#" class="hover:text-sky-400">Aviso de privacidad</a></li>
                        <li><a href="#" class="hover:text-sky-400">Políticas de envío</a></li>
                        <li><a href="#" class="hover:text-sky-400">Política de venta en línea</a></li>
                    </ul>
                </div>

                <!-- Soporte al Cliente -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">SOPORTE AL CLIENTE</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Ayuda</a></li>
                        <li><a href="#" class="hover:text-sky-400">Contáctenos</a></li>
                        <li><a href="#" class="hover:text-sky-400">Nuestras librerías</a></li>
                        <li><a href="#" class="hover:text-sky-400">Política de dinero electrónico</a></li>
                    </ul>
                </div>

                <!-- Facturación -->
                <div class="text-center">
                    <div class="bg-sky-500 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-invoice text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-4">FACTURACIÓN</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-sky-400">Facturación electrónica</a></li>
                    </ul>
                </div>
            </div>

            <!-- Métodos de Pago -->
             <div class="border-t border-gray-700 pt-8">
                <div class="flex justify-center items-center gap-6 mb-6">
                    <img src="img/Paypal.png" alt="PayPal" class="h-10">
                    <img src="img/MP.png" alt="Mercado Pago" class="h-10">
                    <img src="img/AE.png" alt="American Express" class="h-10">
                    <img src="img/VISA.png" alt="Visa" class="h-10">
                    <img src="img/MC.png" alt="Mastercard" class="h-10">
                </div>

                <!-- Copyright -->
                <div class="text-center text-sm text-gray-400 space-y-2">
                    <p>Librerías Gonvill S.A. de C.V. Todos los Derechos Reservados.</p>
                    <p>Los precios y la disponibilidad de los productos están sujetos a cambio sin previo aviso y solo se aplican para ventas en línea.</p>
                    <p>Los precios mostrados incluyen IVA.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>