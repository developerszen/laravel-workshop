<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <div>
        <h3>Recuperación de contraseña</h3>
        <p>Por favor siga el siguiente enlace: </p>

        {{ $user->reset_token }}

        <a href="">
            Recuperar contraseña
        </a>
    </div>
</body>
</html>