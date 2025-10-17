<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gonvill</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4b6cb7, #182848);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
            width: 350px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #444;
        }
        input {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #4b6cb7;
        }
        .btn {
            width: 100%;
            background: #4b6cb7;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #182848;
        }
        .extra-links {
            text-align: center;
            margin-top: 10px;
        }
        .extra-links a {
            color: #4b6cb7;
            text-decoration: none;
        }
        .extra-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="#">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="Ingresa tu correo" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            </div>
            <button type="submit" class="btn">Entrar</button>
        </form>
        <div class="extra-links">
            <p><a href="#">¿Olvidaste tu contraseña?</a></p>
            <p><a href="#">Crear nueva cuenta</a></p>
        </div>
    </div>
</body>
</html>