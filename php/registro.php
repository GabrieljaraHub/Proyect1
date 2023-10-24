<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Se a registrado correctamente</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $usuario = $_POST["usuario"];

    echo "<p>Hola! <strong> $nombre </strong>   Bienvenido </p>";
    echo '<p style="display: inline;">Bienvenido <h4 style="display: inline;">' . $usuario . '</h4></p>';
    }
?>

</body>
</html>