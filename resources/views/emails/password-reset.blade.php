<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #ffa3c2 0%, #ff8fb3 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .token-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #ffa3c2;
            margin: 20px 0;
            font-family: monospace;
            font-size: 18px;
            letter-spacing: 2px;
            word-break: break-all;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 Librería Gonvill</h1>
        <p>Restablecer Contraseña</p>
    </div>
    
    <div class="content">
        <p>Hola <strong>{{ $name }}</strong>,</p>
        
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta.</p>
        
        <p>Usa el siguiente código en la aplicación:</p>
        
        <div class="token-box">
            {{ $token }}
        </div>
        
        <p><strong>⏰ Este código expira en 60 minutos.</strong></p>
        
        <p>Si no solicitaste este cambio, puedes ignorar este correo de forma segura.</p>
        
        <hr>
        
        <p style="color: #666; font-size: 14px;">
            Por seguridad, nunca compartas este código con nadie.
        </p>
    </div>
    
    <div class="footer">
        <p>© 2025 Librería Gonvill - Todos los derechos reservados</p>
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
    </div>
</body>
</html>