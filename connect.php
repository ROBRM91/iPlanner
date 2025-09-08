<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe los datos del formulario POST
    $servername = $_POST['server_name'];
    $username = $_POST['user_name'];
    $password = $_POST['password'];
    $dbname = $_POST['db_name'];
    $serverport = $_POST['server_port'];

    // Crea una conexión usando MySQLi
    $conn = new mysqli($servername, $username, $password, $dbname, $serverport);

    // Verifica si la conexión fue exitosa
    if ($conn->connect_error) {
        // Preparamos un mensaje de error
        $error_message = "Error de conexión: " . $conn->connect_error;
        
        // Redirigimos de vuelta al formulario con el mensaje de error
        header("Location: index.html?error=" . urlencode($error_message));
        exit();
    } else {
        // Cerramos la conexión
        $conn->close();
        
        // Redirige al usuario a la página de catálogos
        header("Location: iplanner-catalogos.html");
        exit();
    }
} else {
    // Si no es una solicitud POST, redirigir al index
    header("Location: index.html?error=" . urlencode("Método no permitido"));
    exit();
}
?>