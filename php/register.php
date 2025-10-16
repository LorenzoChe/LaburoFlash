<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $v_password = $_POST["v_password"];
    $phone_number = $_POST["phone_number"];

    // Verificacion de contraseñas.

    if ($password !== $v_password) {
        die("Las contraseñas no coinciden.");
    }

    // Verificacion de dominio.

    if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[a-zA-Z]{2,}$/", $email)) {
        die("El correo electrónico no es válido.");
    }

    $allowed_domains = ['com', 'net', 'org', 'edu', 'gov', 'ar', 'es'];
    $tld = strtolower(pathinfo($email, PATHINFO_EXTENSION));    

    if (!in_array($tld, $allowed_domains)) {
        die("Dominio de correo no permitido.");
    }

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Este correo ya está registrado.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, last_name, email, password, phone_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $last_name, $email, $hashed_password, $phone_number);

    if ($stmt->execute()) {
        echo "¡Registro exitoso!";
        // header("Location: login.html"); exit;
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
    $check->close();
    $conn->close();
} else {
    echo "Método no permitido.";
}
?>
