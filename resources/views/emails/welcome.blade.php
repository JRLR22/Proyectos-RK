<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #4CAF50;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            margin: 20px 0;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Bienvenido a Gonvill, {{ $user->first_name }}!</h1>
        
        <p>Gracias por registrarte en nuestra librería online.</p>
        
        <p>Ahora puedes:</p>
        <ul>
            <li>Explorar nuestro catálogo de libros</li>
            <li>Realizar compras online</li>
            <li>Recibir recomendaciones personalizadas</li>
        </ul>
        
        <a href="{{ config('app.url') }}" class="button">
            Explorar Catálogo
        </a>
        
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
        
        <p>¡Feliz lectura!</p>
        
        <hr>
        <small style="color: #888;">
            Este es un email automático de Gonvill Bookstore
        </small>
    </div>
</body>
</html>