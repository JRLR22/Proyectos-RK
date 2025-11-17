<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    public function run()
    {
        // ==================== CATEGORÍAS ====================
        $this->seedCategories();
        
        // ==================== AUTORES ====================
        $this->seedAuthors();
        
        // ==================== LIBROS ====================
        $this->seedBooks();
        
        // ==================== RELACIÓN LIBROS-AUTORES ====================
        $this->seedBookAuthors();
    }

    /**
     * Crear categorías
     */
    private function seedCategories()
    {
        $categories = [
            ['category_id' => 1, 'name' => 'Libros para todos', 'description' => 'Libros para todos'],
            ['category_id' => 2, 'name' => 'Novedades', 'description' => 'Libros nuevos'],
            ['category_id' => 3, 'name' => 'Terror', 'description' => 'Libros de terror'],
            ['category_id' => 4, 'name' => 'Juveniles', 'description' => 'Libros para jóvenes'],
            ['category_id' => 5, 'name' => 'Infantiles', 'description' => 'Libros para niños'],
            ['category_id' => 6, 'name' => 'Textos escolares', 'description' => 'Libros de textos escolares y universitarios'],
            ['category_id' => 7, 'name' => 'Literatura', 'description' => 'Obras literarias clásicas y contemporáneas'],
            ['category_id' => 8, 'name' => 'Arte y Diseño', 'description' => 'Libros sobre arte, diseño y creatividad'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['category_id' => $category['category_id']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'parent_category_id' => null,
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Crear autores
     */
    private function seedAuthors()
    {
        $authors = [
            // Autores existentes
            ['author_id' => 1, 'first_name' => 'MILLAND', 'last_name' => 'LIS'],
            ['author_id' => 2, 'first_name' => 'MILLAND', 'last_name' => 'LIS'],
            ['author_id' => 3, 'first_name' => 'Varios', 'last_name' => 'Autores'],
            ['author_id' => 4, 'first_name' => 'Antonio', 'last_name' => 'Morales'],
            ['author_id' => 5, 'first_name' => 'Michel', 'last_name' => 'Foucault'],
            ['author_id' => 6, 'first_name' => 'Leonardo', 'last_name' => 'DiCaprio'],
            ['author_id' => 7, 'first_name' => 'Disney', 'last_name' => 'Press'],
            ['author_id' => 8, 'first_name' => 'Tiger', 'last_name' => 'Tales'],
            
            // Nuevos autores de Gonvill
            ['author_id' => 9, 'first_name' => 'Edgar Allan', 'last_name' => 'Poe'],
            ['author_id' => 10, 'first_name' => 'Benjamin', 'last_name' => 'Lacombe'],
            ['author_id' => 11, 'first_name' => 'Sergio', 'last_name' => 'Gaspar'],
            ['author_id' => 12, 'first_name' => 'Juan José', 'last_name' => 'Plans'],
            ['author_id' => 13, 'first_name' => 'Tik Tak', 'last_name' => 'Draw'],
            ['author_id' => 14, 'first_name' => 'Delfín', 'last_name' => 'Editorial'],
            ['author_id' => 15, 'first_name' => 'Torre', 'last_name' => 'Amarilla'],
            ['author_id' => 16, 'first_name' => 'EMU', 'last_name' => 'Editorial'],
            ['author_id' => 17, 'first_name' => 'Ramírez', 'last_name' => 'Suárez'],
            ['author_id' => 18, 'first_name' => 'Rodríguez', 'last_name' => 'Lorenzo'],
            ['author_id' => 19, 'first_name' => 'Stephen', 'last_name' => 'King'],
            ['author_id' => 20, 'first_name' => 'Gabriel García', 'last_name' => 'Márquez'],
            ['author_id' => 21, 'first_name' => 'Isabel', 'last_name' => 'Allende'],
            ['author_id' => 22, 'first_name' => 'Carlos Ruiz', 'last_name' => 'Zafón'],
            ['author_id' => 23, 'first_name' => 'J.K.', 'last_name' => 'Rowling'],
            ['author_id' => 24, 'first_name' => 'Suzanne', 'last_name' => 'Collins'],
            ['author_id' => 25, 'first_name' => 'Rick', 'last_name' => 'Riordan'],
            ['author_id' => 26, 'first_name' => 'Roald', 'last_name' => 'Dahl'],
            ['author_id' => 27, 'first_name' => 'Dr.', 'last_name' => 'Seuss'],
            ['author_id' => 28, 'first_name' => 'Eric', 'last_name' => 'Carle'],
            ['author_id' => 29, 'first_name' => 'Maurice', 'last_name' => 'Sendak'],
            ['author_id' => 30, 'first_name' => 'Beatrix', 'last_name' => 'Potter'],
        ];

        foreach ($authors as $author) {
            DB::table('authors')->updateOrInsert(
                ['author_id' => $author['author_id']],
                [
                    'first_name' => $author['first_name'],
                    'last_name' => $author['last_name'],
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Crear libros
     */
    private function seedBooks()
    {
        $books = [
            // ==================== LIBROS PARA TODOS ====================
            [
                'book_id' => 1, 'isbn' => '9780789926708', 'gonvill_code' => 'GON03990254',
                'title' => 'UNA MUJER COMO TÚ', 'publisher' => 'Editorial unilit',
                'publication_year' => 2014, 'price' => 420.00, 'stock_quantity' => 0,
                'category_id' => 1, 'status' => 'No en stock', 'type' => 'Papel',
                'cover_image' => 'covers/UNA MUJER COMO TÚ.jpeg', 'description' => 'Libro para reflexionar'
            ],
            [
                'book_id' => 2, 'isbn' => '9786075328881', 'gonvill_code' => 'GON02011950',
                'title' => 'NO MIRES ADENTRO ¡CUIDADO! LOS ANIMALES VAN MANEJANDO',
                'publisher' => 'Silver Dolphin', 'publication_year' => 2018, 'price' => 300.00,
                'stock_quantity' => 30, 'category_id' => 1, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/NO MIRES ADENTRO ¡CUIDADO! LOS ANIMALES VAN MANEJANDO.jpeg'
            ],
            [
                'book_id' => 10, 'isbn' => '9786074007520', 'gonvill_code' => 'GON04007520',
                'title' => 'CIEN AÑOS DE SOLEDAD', 'publisher' => 'Diana',
                'publication_year' => 1967, 'price' => 350.00, 'stock_quantity' => 25,
                'category_id' => 1, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/cien-anos-soledad.jpeg'
            ],
            [
                'book_id' => 11, 'isbn' => '9788466331579', 'gonvill_code' => 'GON04331579',
                'title' => 'LA CASA DE LOS ESPÍRITUS', 'publisher' => 'Debolsillo',
                'publication_year' => 1982, 'price' => 280.00, 'stock_quantity' => 18,
                'category_id' => 1, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/casa-espiritus.jpeg'
            ],
            [
                'book_id' => 12, 'isbn' => '9788408163282', 'gonvill_code' => 'GON05163282',
                'title' => 'EL JUEGO DEL ÁNGEL', 'publisher' => 'Planeta',
                'publication_year' => 2008, 'price' => 420.00, 'stock_quantity' => 12,
                'category_id' => 1, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/juego-angel.jpeg'
            ],

            // ==================== NOVEDADES ====================
            [
                'book_id' => 3, 'isbn' => '9796075328881', 'gonvill_code' => 'GON02012050',
                'title' => 'MAUDIT KARMA', 'publisher' => 'Planeta cómic',
                'publication_year' => 2012, 'price' => 120.00, 'stock_quantity' => 12,
                'category_id' => 2, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/MAUDIT KARMA.jpeg'
            ],
            [
                'book_id' => 4, 'isbn' => '9296075328881', 'gonvill_code' => 'GON12012050',
                'title' => 'CÓDIGO BESTSELLER', 'publisher' => 'Berenice Ensayo',
                'publication_year' => 2013, 'price' => 220.50, 'stock_quantity' => 1,
                'category_id' => 2, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/CÓDIGO BESTSELLER.jpeg'
            ],
            [
                'book_id' => 13, 'isbn' => '9789403834252', 'gonvill_code' => 'GON06834252',
                'title' => 'DIOS ESTÁ ENAMORADO DE TI', 'publisher' => 'Casa Creación',
                'publication_year' => 2024, 'price' => 380.00, 'stock_quantity' => 15,
                'category_id' => 2, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/dios-enamorado.jpeg'
            ],
            [
                'book_id' => 14, 'isbn' => '9788412931570', 'gonvill_code' => 'GON07931570',
                'title' => 'PERRO MUNDO', 'publisher' => 'Indie Editorial',
                'publication_year' => 2024, 'price' => 290.00, 'stock_quantity' => 20,
                'category_id' => 2, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/perro-mundo.jpeg'
            ],

            // ==================== TERROR ====================
            [
                'book_id' => 5, 'isbn' => '9786075328891', 'gonvill_code' => 'GON02011960',
                'title' => 'Creepy', 'publisher' => 'Planeta cómic',
                'publication_year' => 2018, 'price' => 300.00, 'stock_quantity' => 30,
                'category_id' => 3, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/creepy.png'
            ],
            [
                'book_id' => 15, 'isbn' => '0987654321', 'gonvill_code' => '0987653421',
                'title' => 'La llorona', 'publisher' => 'Facultad de informatica Culiacan',
                'publication_year' => 2024, 'price' => 350.00, 'stock_quantity' => 22,
                'category_id' => 3, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/LALLORONA.jpeg', 'description' => 'Leyenda urbana'
            ],
            [
                'book_id' => 16, 'isbn' => '9788414017265', 'gonvill_code' => 'GON14017265',
                'title' => 'CUENTOS MACABROS VOL.II', 'publisher' => 'Edelvives',
                'publication_year' => 2021, 'price' => 801.00, 'stock_quantity' => 5,
                'category_id' => 3, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/cuentos-macabros.jpeg',
                'description' => 'Edgar Allan Poe ilustrado por Benjamin Lacombe'
            ],
            [
                'book_id' => 17, 'isbn' => '9786071437068', 'gonvill_code' => 'GON01806006',
                'title' => 'HISTORIAS DE TERROR PARA SUPERAR EL MIEDO', 'publisher' => 'EMU',
                'publication_year' => 2019, 'price' => 180.00, 'stock_quantity' => 15,
                'category_id' => 3, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/historias-terror-miedo.jpeg'
            ],
            [
                'book_id' => 18, 'isbn' => '9788491648277', 'gonvill_code' => 'GON09540152',
                'title' => 'HISTORIAS DE TERROR 1', 'publisher' => 'La esfera de los libros',
                'publication_year' => 2022, 'price' => 399.20, 'stock_quantity' => 8,
                'category_id' => 3, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/historias-terror-1.jpeg',
                'description' => '24 terroríficos relatos ilustrados'
            ],
            [
                'book_id' => 19, 'isbn' => '9780307743664', 'gonvill_code' => 'GON10743664',
                'title' => 'IT (ESO)', 'publisher' => 'Debolsillo',
                'publication_year' => 1986, 'price' => 450.00, 'stock_quantity' => 10,
                'category_id' => 3, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/it-eso.jpeg'
            ],
            [
                'book_id' => 20, 'isbn' => '9780385121675', 'gonvill_code' => 'GON11121675',
                'title' => 'EL RESPLANDOR', 'publisher' => 'Debolsillo',
                'publication_year' => 1977, 'price' => 380.00, 'stock_quantity' => 14,
                'category_id' => 3, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/resplandor.jpeg'
            ],

            // ==================== JUVENILES ====================
            [
                'book_id' => 6, 'isbn' => '9786075328991', 'gonvill_code' => 'GON02019960',
                'title' => 'Historia de la sexualidad', 'publisher' => 'Siglo veintiuno',
                'publication_year' => 2008, 'price' => 500.00, 'stock_quantity' => 19,
                'category_id' => 4, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/historia_sexualidad.jpeg'
            ],
            [
                'book_id' => 21, 'isbn' => '9786070118654', 'gonvill_code' => 'GON26801865',
                'title' => 'LOS MEJORES RELATOS DE TERROR LLEVADOS AL CINE',
                'publisher' => 'Alfaguara', 'publication_year' => 2018, 'price' => 150.00,
                'stock_quantity' => 12, 'category_id' => 4, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/relatos-terror-cine.jpeg'
            ],
            [
                'book_id' => 22, 'isbn' => '9789584508959', 'gonvill_code' => 'GON26602045',
                'title' => 'CUENTOS DE TERROR DE MI TÍO', 'publisher' => 'Norma',
                'publication_year' => 2017, 'price' => 145.00, 'stock_quantity' => 10,
                'category_id' => 4, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/cuentos-terror-tio.jpeg'
            ],
            [
                'book_id' => 23, 'isbn' => '9788498387087', 'gonvill_code' => 'GON12387087',
                'title' => 'HARRY POTTER Y LA PIEDRA FILOSOFAL', 'publisher' => 'Salamandra',
                'publication_year' => 1997, 'price' => 320.00, 'stock_quantity' => 25,
                'category_id' => 4, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/harry-potter-1.jpeg'
            ],
            [
                'book_id' => 24, 'isbn' => '9780439023481', 'gonvill_code' => 'GON13023481',
                'title' => 'LOS JUEGOS DEL HAMBRE', 'publisher' => 'RBA Molino',
                'publication_year' => 2008, 'price' => 280.00, 'stock_quantity' => 18,
                'category_id' => 4, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/juegos-hambre.jpeg'
            ],
            [
                'book_id' => 25, 'isbn' => '9781423101499', 'gonvill_code' => 'GON14101499',
                'title' => 'PERCY JACKSON Y EL LADRÓN DEL RAYO', 'publisher' => 'Montena',
                'publication_year' => 2005, 'price' => 295.00, 'stock_quantity' => 20,
                'category_id' => 4, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/percy-jackson-1.jpeg'
            ],

            // ==================== INFANTILES ====================
            [
                'book_id' => 7, 'isbn' => '9786095328991', 'gonvill_code' => 'GON02149960',
                'title' => 'THE LEGEND OF SLEEPY HOLLOW', 'publisher' => 'Random House',
                'publication_year' => 2008, 'price' => 200.00, 'stock_quantity' => 29,
                'category_id' => 5, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/THE LEGEND OF SLEEPY HOLLOW.jpeg'
            ],
            [
                'book_id' => 8, 'isbn' => '9786095328101', 'gonvill_code' => 'GON01239960',
                'title' => 'TEN TINY DINOSAURS -COLORFUL COUNTDOWN FUN!-',
                'publisher' => 'Random House', 'publication_year' => 2017, 'price' => 240.50,
                'stock_quantity' => 2, 'category_id' => 5, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/TEN TINY DINOSAURS -COLORFUL COUNTDOWN FUN!-.jpeg'
            ],
            [
                'book_id' => 26, 'isbn' => '9786077942665', 'gonvill_code' => 'GON37660086',
                'title' => 'CUENTOS DE TERROR PARA NIÑOS', 'publisher' => 'Delfín Editorial',
                'publication_year' => 2020, 'price' => 28.50, 'stock_quantity' => 35,
                'category_id' => 5, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/cuentos-terror-ninos.jpeg'
            ],
            [
                'book_id' => 27, 'isbn' => '9786071413017', 'gonvill_code' => 'GON01805607',
                'title' => 'CUENTOS DE TERROR PARA NIÑOS -NUEVA EDICIÓN-',
                'publisher' => 'EMU', 'publication_year' => 2021, 'price' => 36.00,
                'stock_quantity' => 40, 'category_id' => 5, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/cuentos-terror-ninos-nueva.jpeg'
            ],
            [
                'book_id' => 28, 'isbn' => '9786075326160', 'gonvill_code' => 'GON02011702',
                'title' => 'LIBRO INFANTIL ESFÉRICO: EL EXTRAORDINARIO MUNDO',
                'publisher' => 'Silver Dolphin', 'publication_year' => 2023, 'price' => 450.00,
                'stock_quantity' => 12, 'category_id' => 5, 'status' => 'En stock', 'type' => 'Impresión bajo demanda',
                'cover_image' => 'covers/libro-esferico-mundo.jpeg'
            ],
            [
                'book_id' => 29, 'isbn' => '9786075324876', 'gonvill_code' => 'GON02011608',
                'title' => 'INCREÍBLE EN 3D POP UP: EL ESPACIO', 'publisher' => 'Silver Dolphin',
                'publication_year' => 2023, 'price' => 279.00, 'stock_quantity' => 18,
                'category_id' => 5, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/pop-up-espacio.jpeg'
            ],
            [
                'book_id' => 30, 'isbn' => '9780394800011', 'gonvill_code' => 'GON15800011',
                'title' => 'EL GATO EN EL SOMBRERO', 'publisher' => 'Random House',
                'publication_year' => 1957, 'price' => 180.00, 'stock_quantity' => 25,
                'category_id' => 5, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/gato-sombrero.jpeg'
            ],
            [
                'book_id' => 31, 'isbn' => '9780399257865', 'gonvill_code' => 'GON16257865',
                'title' => 'LA ORUGA MUY HAMBRIENTA', 'publisher' => 'Penguin',
                'publication_year' => 1969, 'price' => 195.00, 'stock_quantity' => 30,
                'category_id' => 5, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/oruga-hambrienta.jpeg'
            ],
            [
                'book_id' => 32, 'isbn' => '9780064431781', 'gonvill_code' => 'GON17431781',
                'title' => 'DONDE VIVEN LOS MONSTRUOS', 'publisher' => 'HarperCollins',
                'publication_year' => 1963, 'price' => 210.00, 'stock_quantity' => 22,
                'category_id' => 5, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/donde-viven-monstruos.jpeg'
            ],
            [
                'book_id' => 33, 'isbn' => '9780723247708', 'gonvill_code' => 'GON18247708',
                'title' => 'EL CUENTO DE PERICO EL CONEJO TRAVIESO', 'publisher' => 'Penguin',
                'publication_year' => 1902, 'price' => 165.00, 'stock_quantity' => 28,
                'category_id' => 5, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/perico-conejo.jpeg'
            ],

            // ==================== TEXTOS ESCOLARES ====================
            [
                'book_id' => 9, 'isbn' => '1786095328101', 'gonvill_code' => 'GON12470960',
                'title' => 'ANÁLISIS DE RIESGO LOGÍSTICO -EL REPRESENTANTE LEGAL-',
                'publisher' => 'Random House', 'publication_year' => 2022, 'price' => 240.00,
                'stock_quantity' => 2, 'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/ANÁLISIS DE RIESGO LOGÍSTICO -EL REPRESENTANTE LEGAL-.jpeg'
            ],
            [
                'book_id' => 34, 'isbn' => '9786078421152', 'gonvill_code' => 'GON30220082',
                'title' => 'INGLÉS 1 -BACH.DGB/DGETI-', 'publisher' => 'Pearson',
                'publication_year' => 2023, 'price' => 240.00, 'stock_quantity' => 45,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/ingles-1-bach.jpeg'
            ],
            [
                'book_id' => 35, 'isbn' => '9786079917562', 'gonvill_code' => 'GON41160039',
                'title' => 'VIDA SALUDABLE -PARA SECUNDARIA-', 'publisher' => 'Santillana',
                'publication_year' => 2023, 'price' => 260.00, 'stock_quantity' => 38,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/vida-saludable.jpeg'
            ],
            [
                'book_id' => 36, 'isbn' => '9786078326754', 'gonvill_code' => 'GON19326754',
                'title' => 'MATEMÁTICAS 1 SECUNDARIA', 'publisher' => 'Pearson',
                'publication_year' => 2023, 'price' => 385.00, 'stock_quantity' => 50,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/matematicas-1-sec.jpeg'
            ],
            [
                'book_id' => 37, 'isbn' => '9786075266756', 'gonvill_code' => 'GON20266756',
                'title' => 'QUÍMICA 1 BACHILLERATO', 'publisher' => 'McGraw-Hill',
                'publication_year' => 2023, 'price' => 420.00, 'stock_quantity' => 35,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/quimica-1-bach.jpeg', 'subtitle' => null
            ],
            [
                'book_id' => 38, 'isbn' => '9786073267441', 'gonvill_code' => 'GON21267441',
                'title' => 'FÍSICA 1 BACHILLERATO', 'publisher' => 'Pearson',
                'publication_year' => 2023, 'price' => 395.00, 'stock_quantity' => 30,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/fisica-1-bach.jpeg'
            ],
            [
                'book_id' => 39, 'isbn' => '9786073268158', 'gonvill_code' => 'GON22268158',
                'title' => 'BIOLOGÍA 1 BACHILLERATO', 'publisher' => 'Pearson',
                'publication_year' => 2023, 'price' => 410.00, 'stock_quantity' => 28,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/biologia-1-bach.jpeg'
            ],
            [
                'book_id' => 40, 'isbn' => '9786078421176', 'gonvill_code' => 'GON23421176',
                'title' => 'HISTORIA DE MÉXICO 1', 'publisher' => 'Pearson',
                'publication_year' => 2023, 'price' => 370.00, 'stock_quantity' => 32,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/historia-mexico-1.jpeg'
            ],
            [
                'book_id' => 41, 'isbn' => '9786075267449', 'gonvill_code' => 'GON24267449',
                'title' => 'CÁLCULO DIFERENCIAL', 'publisher' => 'McGraw-Hill',
                'publication_year' => 2023, 'price' => 480.00, 'stock_quantity' => 25,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/calculo-diferencial.jpeg'
            ],
            [
                'book_id' => 42, 'isbn' => '9786073268943', 'gonvill_code' => 'GON25268943',
                'title' => 'LITERATURA 1 BACHILLERATO', 'publisher' => 'Pearson',
                'publication_year' => 2023, 'price' => 355.00, 'stock_quantity' => 40,
                'category_id' => 6, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/literatura-1-bach.jpeg'
            ],

            // ==================== LITERATURA ====================
            [
                'book_id' => 43, 'isbn' => '9788420412146', 'gonvill_code' => 'GON26412146',
                'title' => 'DON QUIJOTE DE LA MANCHA', 'publisher' => 'Alfaguara',
                'publication_year' => 1605, 'price' => 450.00, 'stock_quantity' => 20,
                'category_id' => 7, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/don-quijote.jpeg'
            ],
            [
                'book_id' => 44, 'isbn' => '9788437610757', 'gonvill_code' => 'GON27610757',
                'title' => 'LA CELESTINA', 'publisher' => 'Cátedra',
                'publication_year' => 1499, 'price' => 320.00, 'stock_quantity' => 15,
                'category_id' => 7, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/celestina.jpeg'
            ],
            [
                'book_id' => 45, 'isbn' => '9788467034141', 'gonvill_code' => 'GON28034141',
                'title' => 'VEINTE POEMAS DE AMOR Y UNA CANCIÓN DESESPERADA',
                'publisher' => 'Austral', 'publication_year' => 1924, 'price' => 180.00,
                'stock_quantity' => 35, 'category_id' => 7, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/veinte-poemas.jpeg'
            ],
            [
                'book_id' => 46, 'isbn' => '9788437604947', 'gonvill_code' => 'GON29604947',
                'title' => 'BODAS DE SANGRE', 'publisher' => 'Cátedra',
                'publication_year' => 1933, 'price' => 210.00, 'stock_quantity' => 18,
                'category_id' => 7, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/bodas-sangre.jpeg'
            ],
            [
                'book_id' => 47, 'isbn' => '9788437608839', 'gonvill_code' => 'GON30608839',
                'title' => 'LA VIDA ES SUEÑO', 'publisher' => 'Cátedra',
                'publication_year' => 1635, 'price' => 245.00, 'stock_quantity' => 22,
                'category_id' => 7, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/vida-sueno.jpeg'
            ],

            // ==================== ARTE Y DISEÑO ====================
            [
                'book_id' => 48, 'isbn' => '9788425228742', 'gonvill_code' => 'GON31228742',
                'title' => 'LA HISTORIA DEL ARTE', 'publisher' => 'Phaidon',
                'publication_year' => 2020, 'price' => 890.00, 'stock_quantity' => 8,
                'category_id' => 8, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/historia-arte.jpeg'
            ],
            [
                'book_id' => 49, 'isbn' => '9788425229534', 'gonvill_code' => 'GON32229534',
                'title' => 'EL ARTE DEL COLOR', 'publisher' => 'Gustavo Gili',
                'publication_year' => 2018, 'price' => 650.00, 'stock_quantity' => 12,
                'category_id' => 8, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/arte-color.jpeg'
            ],
            [
                'book_id' => 50, 'isbn' => '9788416504862', 'gonvill_code' => 'GON33504862',
                'title' => 'FUNDAMENTOS DEL DISEÑO GRÁFICO', 'publisher' => 'Promopress',
                'publication_year' => 2019, 'price' => 580.00, 'stock_quantity' => 15,
                'category_id' => 8, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/fundamentos-diseno.jpeg'
            ],
            [
                'book_id' => 51, 'isbn' => '9788425227882', 'gonvill_code' => 'GON34227882',
                'title' => 'ANATOMÍA PARA ARTISTAS', 'publisher' => 'Gustavo Gili',
                'publication_year' => 2017, 'price' => 720.00, 'stock_quantity' => 10,
                'category_id' => 8, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/anatomia-artistas.jpeg'
            ],
            [
                'book_id' => 52, 'isbn' => '9788425230356', 'gonvill_code' => 'GON35230356',
                'title' => 'PSICOLOGÍA DEL COLOR', 'publisher' => 'Gustavo Gili',
                'publication_year' => 2021, 'price' => 495.00, 'stock_quantity' => 18,
                'category_id' => 8, 'status' => 'En stock', 'type' => 'Papel',
                'cover_image' => 'covers/psicologia-color.jpeg'
            ],

            
        ]; 

        foreach ($books as $book) {
            $bookData = [
                'isbn' => $book['isbn'],
                'gonvill_code' => $book['gonvill_code'],
                'title' => $book['title'],
                'subtitle' => $book['subtitle'] ?? null,
                'publisher' => $book['publisher'],
                'publication_year' => $book['publication_year'],
                'price' => $book['price'],
                'stock_quantity' => $book['stock_quantity'],
                'category_id' => $book['category_id'],
                'status' => $book['status'],
                'type' => $book['type'],
                'cover_image' => $book['cover_image'],
                'description' => $book['description'] ?? null,
                'language' => 'Español',
                'updated_at' => now(),
            ];

            DB::table('books')->updateOrInsert(
                ['book_id' => $book['book_id']],
                $bookData
            );
        }
    }

    private function seedBookAuthors()
    {
        $bookAuthors = [
            ['book_id' => 1, 'author_id' => 1, 'author_order' => 1],
            ['book_id' => 2, 'author_id' => 2, 'author_order' => 1],
            ['book_id' => 10, 'author_id' => 20, 'author_order' => 1],
            ['book_id' => 11, 'author_id' => 21, 'author_order' => 1],
            ['book_id' => 12, 'author_id' => 22, 'author_order' => 1],
            ['book_id' => 3, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 4, 'author_id' => 4, 'author_order' => 1],
            ['book_id' => 13, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 14, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 5, 'author_id' => 5, 'author_order' => 1],
            ['book_id' => 15, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 16, 'author_id' => 9, 'author_order' => 1],
            ['book_id' => 17, 'author_id' => 11, 'author_order' => 1],
            ['book_id' => 18, 'author_id' => 12, 'author_order' => 1],
            ['book_id' => 19, 'author_id' => 19, 'author_order' => 1],
            ['book_id' => 20, 'author_id' => 19, 'author_order' => 1],
            ['book_id' => 6, 'author_id' => 5, 'author_order' => 1],
            ['book_id' => 21, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 22, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 23, 'author_id' => 23, 'author_order' => 1],
            ['book_id' => 24, 'author_id' => 24, 'author_order' => 1],
            ['book_id' => 25, 'author_id' => 25, 'author_order' => 1],
            ['book_id' => 7, 'author_id' => 7, 'author_order' => 1],
            ['book_id' => 8, 'author_id' => 8, 'author_order' => 1],
            ['book_id' => 26, 'author_id' => 14, 'author_order' => 1],
            ['book_id' => 27, 'author_id' => 16, 'author_order' => 1],
            ['book_id' => 28, 'author_id' => 8, 'author_order' => 1],
            ['book_id' => 29, 'author_id' => 8, 'author_order' => 1],
            ['book_id' => 30, 'author_id' => 27, 'author_order' => 1],
            ['book_id' => 31, 'author_id' => 28, 'author_order' => 1],
            ['book_id' => 32, 'author_id' => 29, 'author_order' => 1],
            ['book_id' => 33, 'author_id' => 30, 'author_order' => 1],
            ['book_id' => 9, 'author_id' => 8, 'author_order' => 1],
            ['book_id' => 34, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 35, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 36, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 37, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 38, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 39, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 40, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 41, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 42, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 43, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 44, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 45, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 46, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 47, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 48, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 49, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 50, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 51, 'author_id' => 3, 'author_order' => 1],
            ['book_id' => 52, 'author_id' => 3, 'author_order' => 1],
        ];

        foreach ($bookAuthors as $bookAuthor) {
            DB::table('book_authors')->updateOrInsert(
                ['book_id' => $bookAuthor['book_id'], 'author_id' => $bookAuthor['author_id']],
                ['author_order' => $bookAuthor['author_order'], 'updated_at' => now()]
            );
        }
    }
}