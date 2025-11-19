@extends('layouts.app')

@section('title', 'Bolsa de Trabajo')

@section('content')

<!-- ******************* AQUI PONER CODIGO ***********************************-->

<!--PESTAÑA SOBRE NOSOTROS-->

<main class="bg-[#fef0f5] py-12">
    <div class="container mx-auto px-16 md:px-20 lg:px-24">
        <!-- Título -->
        <h1 class="text-center text-4xl font-bold text-[#ff6392] mb-8 uppercase tracking-wide">Sobre Nosotros</h1>

        <!-- Introducción (Por si se ocupa)
        <p class="text-center text-gray-700 max-w-3xl mx-auto mb-12 leading-relaxed">
            En <strong class="text-[#ff6392]">Librerías Gonvill</strong> fomentamos la lectura, la educación y el amor por los libros desde hace más de medio siglo,
            ofreciendo siempre un espacio de conocimiento y cultura para todos.
        </p>-->

        <!-- Imagen -->
        <div class="relative mb-16">
            <img src="{{ asset('img/010-es-banner-nosotros2.jpg') }}" 
                 alt="Librería Gonvill Sobre Nosotros" 
                 class="w-full rounded-2xl shadow-lg border border-[#ffd7e3]">
            <div class="absolute inset-0 bg-[#ffa3c2]/20 rounded-2xl"></div>
        </div> 

        <!-- Contenido principal TODO -->
        <div> <!--Ponerlo en modo de columna:  class="grid md:grid-cols-2 gap-10 text-gray-700 leading-relaxed"-->
            <div class="space-y-6">
                <!-- AQUÍ INICIA "NUESTRA HISTORIA" -->
                <div class="space-y-2">
                    <h2 class="text-2xl font-semibold italic text-[#ff6392] mb-2">Nuestra Historia</h2>
                    <p class="text-justify"> LIBRERIAS GONVILL abre su primera librería en Guadalajara, Jal. en 1967.
                    </p>
                    <p class="text-justify">
                        A través de un desarrollo y evolución constante, 
                        en la actualidad somos una cadena de 31 librerías en las ciudades de Guadalajara, 
                        Puerto Vallarta, León, Aguascalientes, San Luis Potosí, Querétaro, Torreón, Monterrey, 
                        Chihuahua, Culiacán y Mazatlán.
                    </p>
                    <p class="text-justify">
                        La librería virtual <a href="http://www.gonvill.com.mx/">www.gonvill.com.mx</a> 
                        brinda servicio nacional e internacionalmente las 24 hrs. de los 7 días de la semana.
                    </p>
                    <p class="text-justify">
                        Adicionalmente, el Centro de Distribución Nacional de LIBRERIAS GONVILL en Guadalajara 
                        atiende pedidos de mayoreo de escuelas, universidades, empresas, entidades 
                        gubernamentales, etc. y  participa en licitaciones en todo México. 
                    </p>
                    <p class="text-justify">
                        Nuestras librerías ofrecen un servicio rápido y eficiente, y tienen el más amplio 
                        surtido en libros de interés general, profesionales, académicos y de texto para todos 
                        los niveles educativos en español, inglés y otros idiomas, así como libros de lectura 
                        para niños, jóvenes y adultos en inglés y en español.
                    </p>
                </div>
                <!-- AQUÍ INICIA "MISIÓN" -->
                <div>
                    <h2 class="text-2xl font-semibold italic text-[#ff6392] mb-2">Misión</h2>
                    <p class="text-justify">
                        Apoyar el fomento a la lectura en nuestro país, brindando espacios dignos 
                        y agradables al libro para su adecuada exhibición y comercialización, y 
                        hacerlo llegar a todo el territorio nacional en tiempos razonables y a precios 
                        justos.
                    </p>
                </div>
            </div>
                <!-- AQUÍ INICIA "VISIÓN" -->
            <div class="space-y-6">
                <div>
                    <br><h2 class="text-2xl font-semibold text-[#ff6392] mb-2">Visión</h2>
                    <p class="text-justify">
                        Participar activamente en el desarrollo cultural y educativo de México, 
                        como una empresa líder y confiable para los distintos segmentos de mercado a los que llegamos. 
                        <br>
                        Apoyar y participar en la evolución de la industria editorial hacia 
                        nuevas formas de distribución y comercialización de contenidos en 
                        formato electrónico e impreso.
                    </p>
                </div>

            <!-- VALORES-->
                <div>
                    <h2 class="text-2xl font-semibold italic text-[#ff6392] mb-2">Valores</h2>
                    <ul class="list-disc pl-6">
                        <li>Honestidad, ética y compromiso con nuestra actividad.</li>
                        <li>Respeto a nuestros clientes, proveedores, colaboradores y empleados, instituciones públicas, y en general a la comunidad a la que servimos.</li>
                        <li>EL LIBRO en sus diversos formatos ocupa un lugar especial en el desarrollo de la humanidad, por ser transmisor de la historia y de la cultura de generación en generación. </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- AQUÍ INICIA "FRASE" -->
        <div class="mt-16 text-center bg-[#ffd7e3] py-10 px-6 rounded-2xl shadow-inner">
            <p class="text-xl italic text-gray-700 max-w-2xl mx-auto">
                “HEMOS RECORRIDO UN LARGO CAMINO. . . MUCHO MÁS NOS QUEDA POR ALCANZAR”
            </p>
        </div>
    </div>
</main>

<!--FIN PESTAÑA SOBRE NOSOTROS-->

@endsection