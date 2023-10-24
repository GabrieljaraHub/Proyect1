<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>A Inicio correctamente su session</h1>
    <br>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST["usuario"];

        echo " <h2> Bienvenido Usuario: $nombre </h2>";
        
    }
    ?>
</body>
</html>