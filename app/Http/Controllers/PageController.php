<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Muestra la página de inicio
     */
    public function inicio()
    {
        // Aquí puedes cargar libros destacados, promociones, etc.
        return view('inicio');
    }

    /**
     * Muestra la página de impresión bajo demanda
     */
    public function impresionBajoDemanda()
    {
        return view('impresion-bajo-demanda');
    }

    /**
     * Muestra la página sobre nosotros
     */
    public function sobreNosotros()
    {
        return view('sobre-nosotros');
    }

    /**
     * Muestra la página de nuestras librerías
     */
    public function nuestrasLibrerias()
    {
        // Aquí puedes cargar las ubicaciones de las librerías desde la BD
        $librerias = [
            [
                'nombre' => 'Gonvill Centro',
                'direccion' => 'Dirección ejemplo',
                'telefono' => '123-456-7890',
                'horario' => 'Lun-Vie: 9:00-20:00'
            ],
            // Más librerías...
        ];
        
        return view('nuestras-librerias', compact('librerias'));
    }

    /**
     * Muestra la página de bolsa de trabajo
     */
    public function bolsaTrabajo()
    {
        return view('bolsa-trabajo');
    }

    /**
     * Muestra la página de ayuda
     */
    public function ayuda()
    {
        return view('ayuda');
    }

    /**
     * Muestra la página de SchoolShop
     */
    public function schoolShop()
    {
        return view('schoolshop');
    }

    /**
     * Muestra la página de contacto
     */
    public function contacto()
    {
        return view('contacto');
    }

        /**
     * Muestra la página de micuenta
     */
    public function micuenta()
    {
        return view('micuenta');
    }



}