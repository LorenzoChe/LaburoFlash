<?php

include "connection.php"

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    $email = $conn->real_escape_string($email);
    $pass = $conn->real_escape_string($pass);

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND pass = '$pass'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        echo "¡Login exitoso!";
        // header("Location: index.html");
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}

?>