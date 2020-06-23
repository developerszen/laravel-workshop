<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <div>
        <h3>Recuperación de contraseña!!</h3>
        <p>Usuario: {{ $user->name }}</p>
        <p>Por favor siga el siguiente enlace: </p>

        <a href="http://laravel-workshop.test/password-recovery?reset_token={{ $user->reset_token }}">
            Recuperar contraseña
        </a>
    </div>
</body>
</html>