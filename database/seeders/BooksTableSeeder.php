<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    public function run()
    {
        //----------------- Aquí empieza el agregado de categorías y autores plebes ---------------------//

        // 1. Crear categoría Libros para todos (sin duplicar)
        DB::table('categories')->updateOrInsert(
            ['category_id' => 1], // Primera categoría insertada, lleva el id 1
            [
                'name' => 'Libros para todos',  //Nombre que llevara la categoría
                'description' => 'Libros para todos', //Descripción de la categoría
                'parent_category_id' => null, 
                'updated_at' => now(),
            ]
        );

        // 2. Crear autores de Libros para todos (sin duplicar)
        DB::table('authors')->updateOrInsert(
            ['author_id' => 1], //Primer autor registrado, lleva el id 1
            [
                'first_name' => 'MILLAND', // Primer nombre del autor
                'last_name' => 'LIS',  //Segundo nombre del autor
                'updated_at' => now(),
            ]
        );

        // 2. Crear autores de Libros para todos (sin duplicar)
        DB::table('authors')->updateOrInsert(
            ['author_id' => 2], //Primer autor registrado, lleva el id 1
            [
                'first_name' => 'MILLAND', // Primer nombre del autor
                'last_name' => 'LIS',  //Segundo nombre del autor
                'updated_at' => now(),
            ]
        );

        // 1. Crear categoría para Novedades (sin duplicar)
        DB::table('categories')->updateOrInsert(
            ['category_id' => 2], // Segunda categoría insertada, lleva el id 2
            [
                'name' => 'Novedades', //Nombre de la segunda categoría
                'description' => 'Libros nuevos', //Descripción de la categoría
                'parent_category_id' => null,
                'updated_at' => now(),
            ]
        );
        // 2. Crear autores para Novedades (sin duplicar)
        DB::table('authors')->updateOrInsert(
            ['author_id' => 3], //Segundo autor registado, lleva el id 2
            [
                'first_name' => 'Varios', // Primer nombre
                'last_name' => 'Autores', // Segundo nombre
                'updated_at' => now(),
            ]
        );

        // 1. Crear categoría para Terror (sin duplicar)
        DB::table('categories')->updateOrInsert(
            ['category_id' => 3], // condición
            [
                'name' => 'Terror',
                'description' => 'Libros de terror',
                'parent_category_id' => null,
                'updated_at' => now(),
            ]
        );
        // 2. Crear autores para Terror (sin duplicar)
        DB::table('authors')->updateOrInsert(
            ['author_id' => 4],
            [
                'first_name' => 'Antonio',
                'last_name' => 'Morales',
                'updated_at' => now(),
            ]
        );

        // 1. Crear categoría para Juveniles (sin duplicar)
        DB::table('categories')->updateOrInsert(
            ['category_id' => 4], // condición
            [
                'name' => 'Juveniles',
                'description' => 'Libros para jóvenes',
                'parent_category_id' => null,
                'updated_at' => now(),
            ]
        );
        // 2. Crear autores para Juveniles (sin duplicar)
        DB::table('authors')->updateOrInsert(
            ['author_id' => 5],
            [
                'first_name' => 'Michel',
                'last_name' => 'Foucault',
                'updated_at' => now(),
            ]
        );

        // 1. Crear categoría infentiles (sin duplicar)
        DB::table('categories')->updateOrInsert(
            ['category_id' => 5], // condición
            [
                'name' => 'Infantiles',
                'description' => 'Libros para niños',
                'parent_category_id' => null,
                'updated_at' => now(),
            ]
        );
        // 2. Crear autores para infantil (sin duplicar)
        DB::table('authors')->updateOrInsert(
            ['author_id' => 6],
            [
                'first_name' => 'Leonardo',
                'last_name' => 'DiCaprio',
                'updated_at' => now(),
            ]
        );

         // 1. Crear categoría para Textos escolares (sin duplicar)
        DB::table('categories')->updateOrInsert(
            ['category_id' => 6], // condición
            [
                'name' => 'Textos escolares',
                'description' => 'Libros de textos escolares y universitarios',
                'parent_category_id' => null,
                'updated_at' => now(),
            ]
        );
        // 2. Crear autores para Textos escolares (sin duplicar)
        DB::table('authors')->updateOrInsert(
            ['author_id' => 7],
            [
                'first_name' => 'Disney',
                'last_name' => 'Press',
                'updated_at' => now(),
            ]
        );
                // 2. Crear autores para Textos escolares (sin duplicar)
        DB::table('authors')->updateOrInsert(
            ['author_id' => 8],
            [
                'first_name' => 'Tiger',
                'last_name' => 'Tales',
                'updated_at' => now(),
            ]
        );


        //----------------- Aquí termina el agregado de categorías y autores plebes ---------------------//

        //----------------- Aquí inicia el agregado de los libros plebes ---------------------//

        // 3. Crear libros (sin duplicar)
        DB::table('books')->updateOrInsert(
            ['book_id' => 1], //Primer libro agregado, tiene el id 1
            [
                'isbn' => '9780789926708', //Entre las comillas el número ISBN que va tener
                'gonvill_code' => 'GON03990254', //Entre comillas el código gonvill
                'title' => 'UNA MUJER COMO TÚ', //Entre comillas el título del libro
                'subtitle' => null, //Aquí pueden ponerle entre comillas algún subtítulo si quieren
                'publisher' => 'Editorial unilit', //Entre comillas el Editorial
                'publication_year' => 2014, //Fecha de publicación del libro
                'price' => 420.00,  //Precio que tendrá el libro
                'stock_quantity' => 15, //Cantidad en stock que tendremos del libro
                'category_id' => 1, //El id de la categoría, este primer libro pertenece a la primera categoría (Libros para todos)
                'status' => 'En stock', //Estado del libro ( si está en stock o no)
                'type' => 'Papel', //Tipo: En este caso papel
                'cover_image' => 'covers/UNA MUJER COMO TÚ.jpeg', //La imagen que va llevar el libro
                'updated_at' => now(),
            ]
        );
        
        DB::table('books')->updateOrInsert(
            ['book_id' => 2],
            [
                'isbn' => '9786075328881',
                'gonvill_code' => 'GON02011950',
                'title' => 'NO MIRES ADENTRO ¡CUIDADO! LOS ANIMALES VAN MANEJANDO',
                'subtitle' => null,
                'publisher' => 'Silver Dolphin',
                'publication_year' => 2018,
                'price' => 300.00,
                'stock_quantity' => 30,
                'category_id' => 1, //Este libro y el anterior pertenecen a la misma categoría (Libros para todos)
                'status' => 'En stock',
                'type' => 'Papel',
                'cover_image' => 'covers/NO MIRES ADENTRO ¡CUIDADO! LOS ANIMALES VAN MANEJANDO.jpeg',
                'updated_at' => now(),
            ]
        );

        DB::table('books')->updateOrInsert(
            ['book_id' => 3],
            [
                'isbn' => '9796075328881',
                'gonvill_code' => 'GON02012050',
                'title' => 'MAUDIT KARMA',
                'subtitle' => null,
                'publisher' => 'Planeta cómic',
                'publication_year' => 2012,
                'price' => 120.00,
                'stock_quantity' => 12,
                'category_id' => 2, 
                'status' => 'En stock',
                'type' => 'Papel',
                'cover_image' => 'covers/MAUDIT KARMA.jpeg',
                'updated_at' => now(),
            ]
        );


        DB::table('books')->updateOrInsert(
            ['book_id' => 4],
            [
                'isbn' => '9296075328881',
                'gonvill_code' => 'GON12012050',
                'title' => 'CÓDIGO BESTSELLER',
                'subtitle' => null,
                'publisher' => 'Berenice Ensayo',
                'publication_year' => 2013,
                'price' => 220.50,
                'stock_quantity' => 1,
                'category_id' => 2, 
                'status' => 'En stock',
                'type' => 'Papel',
                'cover_image' => 'covers/CÓDIGO BESTSELLER.jpeg',
                'updated_at' => now(),
            ]
        );


              DB::table('books')->updateOrInsert(
            ['book_id' => 5],
            [
                'isbn' => '9786075328891',
                'gonvill_code' => 'GON02011960',
                'title' => 'Creepy',
                'subtitle' => null,
                'publisher' => 'Planeta cómic',
                'publication_year' => 2018,
                'price' => 300.00,
                'stock_quantity' => 30,
                'category_id' => 3,
                'status' => 'En stock',
                'type' => 'Papel',
                'cover_image' => 'covers/creepy.png',
                'updated_at' => now(),
            ]
        );

            DB::table('books')->updateOrInsert(
            ['book_id' => 6],
            [
                'isbn' => '9786075328991',
                'gonvill_code' => 'GON02019960',
                'title' => 'Historia de la sexualidad',
                'subtitle' => null,
                'publisher' => 'Siglo veintiuno',
                'publication_year' => 2008,
                'price' => 500.00,
                'stock_quantity' => 19,
                'category_id' => 4,
                'status' => 'En stock',
                'type' => 'Papel',
                'cover_image' => 'covers/historia_sexualidad.jpeg',
                'updated_at' => now(),
            ]
        );

            DB::table('books')->updateOrInsert(
            ['book_id' => 7],
            [
                'isbn' => '9786095328991',
                'gonvill_code' => 'GON02149960',
                'title' => 'THE LEGEND OF SLEEPY HOLLOW',
                'subtitle' => null,
                'publisher' => 'Random House',
                'publication_year' => 2008,
                'price' => 200.00,
                'stock_quantity' => 29,
                'category_id' => 5,
                'status' => 'En stock',
                'type' => 'Papel',
                'cover_image' => 'covers/THE LEGEND OF SLEEPY HOLLOW.jpeg',
                'updated_at' => now(),
            ]
        );

            DB::table('books')->updateOrInsert(
            ['book_id' => 8],
            [
                'isbn' => '9786095328101',
                'gonvill_code' => 'GON01239960',
                'title' => 'TTEN TINY DINOSAURS -COLORFUL COUNTDOWN FUN!-',
                'subtitle' => null,
                'publisher' => 'Random House',
                'publication_year' => 2017,
                'price' => 240.50,
                'stock_quantity' => 2,
                'category_id' => 5,
                'status' => 'En stock',
                'type' => 'Papel',
                'cover_image' => 'covers/TEN TINY DINOSAURS -COLORFUL COUNTDOWN FUN!-.jpeg',
                'updated_at' => now(),
            ]
        );

            DB::table('books')->updateOrInsert(
            ['book_id' => 9],
            [
                'isbn' => '1786095328101',
                'gonvill_code' => 'GON12470960',
                'title' => 'ANÁLISIS DE RIESGO LOGÍSTICO -EL REPRESENTANTE LEGAL-',
                'subtitle' => null,
                'publisher' => 'Random House',
                'publication_year' => 2022,
                'price' => 240.00,
                'stock_quantity' => 2,
                'category_id' => 6,
                'status' => 'En stock',
                'type' => 'Papel',
                'cover_image' => 'covers/ANÁLISIS DE RIESGO LOGÍSTICO -EL REPRESENTANTE LEGAL-.jpeg',
                'updated_at' => now(),
            ]
        );

        //----------------- Aquí termina el agregado de los libros plebes ---------------------//

        // 4. Relacionar libros con autores (sin duplicar)
        DB::table('book_authors')->updateOrInsert(
            ['book_id' => 1, 'author_id' => 1],
            [
                'author_order' => 1,
                'updated_at' => now(),
            ]
        );

        DB::table('book_authors')->updateOrInsert(
            ['book_id' => 2, 'author_id' => 2],
            [
                'author_order' => 2,
                'updated_at' => now(),
            ]
        );

            DB::table('book_authors')->updateOrInsert(
            ['book_id' => 3, 'author_id' => 3],
            [
                'author_order' => 3,
                'updated_at' => now(),
            ]
        );
        
            DB::table('book_authors')->updateOrInsert(
            ['book_id' => 4, 'author_id' => 4],
            [
                'author_order' => 4,
                'updated_at' => now(),
            ]
        );
                    DB::table('book_authors')->updateOrInsert(
            ['book_id' => 5, 'author_id' => 5],
            [
                'author_order' => 5,
                'updated_at' => now(),
            ]
        );
            DB::table('book_authors')->updateOrInsert(
            ['book_id' => 6, 'author_id' => 6],
            [
                'author_order' => 6,
                'updated_at' => now(),
            ]
        );

            DB::table('book_authors')->updateOrInsert(
            ['book_id' => 7, 'author_id' => 7],
            [
                'author_order' => 7,
                'updated_at' => now(),
            ]
        );
            DB::table('book_authors')->updateOrInsert(
            ['book_id' => 8, 'author_id' => 8],
            [
                'author_order' => 8,
                'updated_at' => now(),
            ]
        );
            DB::table('book_authors')->updateOrInsert(
            ['book_id' => 9, 'author_id' => 8],
            [
                'author_order' => 9,
                'updated_at' => now(),
            ]
        );
    }
}
