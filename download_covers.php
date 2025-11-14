<?php

/**
 * Script para descargar portadas de libros desde Gonvill ( si no encuentra crea solo placeholders)
 * 
 * INSTRUCCIONES:
 * 1. Ejecuta desde terminal: php download_covers.php
 * 3. Las imágenes se guardarán en storage/app/public/covers/
 */

// Configuración
$coversPath = __DIR__ . '/storage/app/public/covers/';
$placeholderColor = '#2c3e50'; // Color de fondo para placeholders
$textColor = '#ecf0f1'; // Color del texto

// Crear directorio si no existe
if (!file_exists($coversPath)) {
    mkdir($coversPath, 0755, true);
    echo "✓ Directorio creado: {$coversPath}\n\n";
}

// Lista de libros que necesitan portada (del seeder)
$books = [
    // Libros para todos
    ['isbn' => '9786074007520', 'filename' => 'cien-anos-soledad.jpeg', 'title' => 'CIEN AÑOS DE SOLEDAD'],
    ['isbn' => '9788466331579', 'filename' => 'casa-espiritus.jpeg', 'title' => 'LA CASA DE LOS ESPÍRITUS'],
    ['isbn' => '9788408163282', 'filename' => 'juego-angel.jpeg', 'title' => 'EL JUEGO DEL ÁNGEL'],
    
    // Novedades
    ['isbn' => '9789403834252', 'filename' => 'dios-enamorado.jpeg', 'title' => 'DIOS ESTÁ ENAMORADO DE TI'],
    ['isbn' => '9788412931570', 'filename' => 'perro-mundo.jpeg', 'title' => 'PERRO MUNDO'],
    
    // Terror
    ['isbn' => '9788414017265', 'filename' => 'cuentos-macabros.jpeg', 'title' => 'CUENTOS MACABROS VOL.II'],
    ['isbn' => '9786071437068', 'filename' => 'historias-terror-miedo.jpeg', 'title' => 'HISTORIAS DE TERROR PARA SUPERAR EL MIEDO'],
    ['isbn' => '9788491648277', 'filename' => 'historias-terror-1.jpeg', 'title' => 'HISTORIAS DE TERROR 1'],
    ['isbn' => '9780307743664', 'filename' => 'it-eso.jpeg', 'title' => 'IT (ESO)'],
    ['isbn' => '9780385121675', 'filename' => 'resplandor.jpeg', 'title' => 'EL RESPLANDOR'],
    
    // Juveniles
    ['isbn' => '9786070118654', 'filename' => 'relatos-terror-cine.jpeg', 'title' => 'LOS MEJORES RELATOS DE TERROR'],
    ['isbn' => '9789584508959', 'filename' => 'cuentos-terror-tio.jpeg', 'title' => 'CUENTOS DE TERROR DE MI TÍO'],
    ['isbn' => '9788498387087', 'filename' => 'harry-potter-1.jpeg', 'title' => 'HARRY POTTER Y LA PIEDRA FILOSOFAL'],
    ['isbn' => '9780439023481', 'filename' => 'juegos-hambre.jpeg', 'title' => 'LOS JUEGOS DEL HAMBRE'],
    ['isbn' => '9781423101499', 'filename' => 'percy-jackson-1.jpeg', 'title' => 'PERCY JACKSON Y EL LADRÓN DEL RAYO'],
    
    // Infantiles
    ['isbn' => '9786077942665', 'filename' => 'cuentos-terror-ninos.jpeg', 'title' => 'CUENTOS DE TERROR PARA NIÑOS'],
    ['isbn' => '9786071413017', 'filename' => 'cuentos-terror-ninos-nueva.jpeg', 'title' => 'CUENTOS DE TERROR PARA NIÑOS'],
    ['isbn' => '9786075326160', 'filename' => 'libro-esferico-mundo.jpeg', 'title' => 'LIBRO INFANTIL ESFÉRICO'],
    ['isbn' => '9786075324876', 'filename' => 'pop-up-espacio.jpeg', 'title' => 'INCREÍBLE EN 3D POP UP: EL ESPACIO'],
    ['isbn' => '9780394800011', 'filename' => 'gato-sombrero.jpeg', 'title' => 'EL GATO EN EL SOMBRERO'],
    ['isbn' => '9780399257865', 'filename' => 'oruga-hambrienta.jpeg', 'title' => 'LA ORUGA MUY HAMBRIENTA'],
    ['isbn' => '9780064431781', 'filename' => 'donde-viven-monstruos.jpeg', 'title' => 'DONDE VIVEN LOS MONSTRUOS'],
    ['isbn' => '9780723247708', 'filename' => 'perico-conejo.jpeg', 'title' => 'EL CUENTO DE PERICO'],
    
    // Textos escolares
    ['isbn' => '9786078421152', 'filename' => 'ingles-1-bach.jpeg', 'title' => 'INGLÉS 1 BACHILLERATO'],
    ['isbn' => '9786079917562', 'filename' => 'vida-saludable.jpeg', 'title' => 'VIDA SALUDABLE'],
    ['isbn' => '9786078326754', 'filename' => 'matematicas-1-sec.jpeg', 'title' => 'MATEMÁTICAS 1 SECUNDARIA'],
    ['isbn' => '9786075266756', 'filename' => 'quimica-1-bach.jpeg', 'title' => 'QUÍMICA 1 BACHILLERATO'],
    ['isbn' => '9786073267441', 'filename' => 'fisica-1-bach.jpeg', 'title' => 'FÍSICA 1 BACHILLERATO'],
    ['isbn' => '9786073268158', 'filename' => 'biologia-1-bach.jpeg', 'title' => 'BIOLOGÍA 1 BACHILLERATO'],
    ['isbn' => '9786078421176', 'filename' => 'historia-mexico-1.jpeg', 'title' => 'HISTORIA DE MÉXICO 1'],
    ['isbn' => '9786075267449', 'filename' => 'calculo-diferencial.jpeg', 'title' => 'CÁLCULO DIFERENCIAL'],
    ['isbn' => '9786073268943', 'filename' => 'literatura-1-bach.jpeg', 'title' => 'LITERATURA 1 BACHILLERATO'],
    
    // Literatura
    ['isbn' => '9788420412146', 'filename' => 'don-quijote.jpeg', 'title' => 'DON QUIJOTE DE LA MANCHA'],
    ['isbn' => '9788437610757', 'filename' => 'celestina.jpeg', 'title' => 'LA CELESTINA'],
    ['isbn' => '9788467034141', 'filename' => 'veinte-poemas.jpeg', 'title' => 'VEINTE POEMAS DE AMOR'],
    ['isbn' => '9788437604947', 'filename' => 'bodas-sangre.jpeg', 'title' => 'BODAS DE SANGRE'],
    ['isbn' => '9788437608839', 'filename' => 'vida-sueno.jpeg', 'title' => 'LA VIDA ES SUEÑO'],
    
    // Arte y Diseño
    ['isbn' => '9788425228742', 'filename' => 'historia-arte.jpeg', 'title' => 'LA HISTORIA DEL ARTE'],
    ['isbn' => '9788425229534', 'filename' => 'arte-color.jpeg', 'title' => 'EL ARTE DEL COLOR'],
    ['isbn' => '9788416504862', 'filename' => 'fundamentos-diseno.jpeg', 'title' => 'FUNDAMENTOS DEL DISEÑO GRÁFICO'],
    ['isbn' => '9788425227882', 'filename' => 'anatomia-artistas.jpeg', 'title' => 'ANATOMÍA PARA ARTISTAS'],
    ['isbn' => '9788425230356', 'filename' => 'psicologia-color.jpeg', 'title' => 'PSICOLOGÍA DEL COLOR'],
];

// Contadores
$downloaded = 0;
$placeholders = 0;
$errors = 0;

echo " INICIANDO DESCARGA DE PORTADAS\n";
echo "==================================\n\n";

foreach ($books as $book) {
    $isbn = $book['isbn'];
    $filename = $book['filename'];
    $title = $book['title'];
    $filepath = $coversPath . $filename;
    
    // Si la imagen ya existe, saltar
    if (file_exists($filepath)) {
        echo "⏭  Ya existe: {$filename}\n";
        continue;
    }
    
    echo " Procesando: {$title}...\n";
    
    // Intentar diferentes URLs de Gonvill
    $possibleUrls = [
        // Patrón 1: imagenes/primeros-7-digitos/isbn-completo.JPG
        "https://www.gonvill.com.mx/imagenes/" . substr($isbn, 0, 7) . "/" . $isbn . ".JPG",
        "https://www.gonvill.com.mx/imagenes/" . substr($isbn, 0, 7) . "/" . $isbn . ".jpg",
        // Patrón 2: imagenes/primeros-4-digitos/isbn-completo.JPG
        "https://www.gonvill.com.mx/imagenes/" . substr($isbn, 0, 4) . "/" . $isbn . ".JPG",
        "https://www.gonvill.com.mx/imagenes/" . substr($isbn, 0, 4) . "/" . $isbn . ".jpg",
    ];
    
    $imageDownloaded = false;
    
    foreach ($possibleUrls as $url) {
        $imageData = @file_get_contents($url);
        
        if ($imageData !== false && strlen($imageData) > 1000) { // Mínimo 1KB para ser imagen válida
            file_put_contents($filepath, $imageData);
            echo "    Descargada desde Gonvill\n";
            $downloaded++;
            $imageDownloaded = true;
            break;
        }
    }
    
    // Si no se pudo descargar, crear placeholder
    if (!$imageDownloaded) {
        echo "   ⚠  No encontrada en Gonvill, creando placeholder...\n";
        
        if (createPlaceholder($filepath, $title, $placeholderColor, $textColor)) {
            echo "    Placeholder creado\n";
            $placeholders++;
        } else {
            echo "    Error al crear placeholder\n";
            $errors++;
        }
    }
    
    echo "\n";
}

// Resumen
echo "\n==================================\n";
echo " RESUMEN\n";
echo "==================================\n";
echo " Descargadas de Gonvill: {$downloaded}\n";
echo " Placeholders creados: {$placeholders}\n";
echo " Errores: {$errors}\n";
echo " Total procesado: " . ($downloaded + $placeholders + $errors) . "\n";
echo "\n ¡Proceso completado!\n";

/**
 * Crear imagen placeholder con el título del libro
 */
function createPlaceholder($filepath, $title, $bgColor, $textColor) {
    $width = 400;
    $height = 600;
    
    // Crear imagen
    $image = imagecreatetruecolor($width, $height);
    if (!$image) return false;
    
    // Colores
    list($r, $g, $b) = sscanf($bgColor, "#%02x%02x%02x");
    $background = imagecolorallocate($image, $r, $g, $b);
    
    list($r, $g, $b) = sscanf($textColor, "#%02x%02x%02x");
    $foreground = imagecolorallocate($image, $r, $g, $b);
    
    // Llenar fondo
    imagefill($image, 0, 0, $background);
    
    // Agregar texto
    $fontSize = 5; // Tamaño de fuente incorporado
    $text = wordwrap($title, 30, "\n", true); // Dividir título en líneas
    $lines = explode("\n", $text);
    
    $y = ($height / 2) - (count($lines) * 20 / 2);
    
    foreach ($lines as $line) {
        $textWidth = imagefontwidth($fontSize) * strlen($line);
        $x = ($width - $textWidth) / 2;
        imagestring($image, $fontSize, $x, $y, $line, $foreground);
        $y += 20;
    }
    
    // Agregar icono de libro simple
    imagerectangle($image, $width/2 - 50, 50, $width/2 + 50, 150, $foreground);
    imageline($image, $width/2, 50, $width/2, 150, $foreground);
    
    // Guardar
    $result = imagejpeg($image, $filepath, 85);
    imagedestroy($image);
    
    return $result;
}