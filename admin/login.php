<?php

require_once "auth.php";
require_once "../includes/conexion.php";

if (isset($_SESSION["admin_logueado"]) && $_SESSION["admin_logueado"] === true) {
    header("Location: panel.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($usuario === "" || $password === "") {
        $error = "Introduce usuario y contraseña.";
    } else {
        try {
            $stmt = $pdo->prepare("
                SELECT id, usuario, password_hash, nombre, activo 
                FROM admin_usuarios 
                WHERE usuario = :usuario 
                LIMIT 1
            ");

            $stmt->execute([
                ":usuario" => $usuario
            ]);

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$admin) {
                $error = "Ese usuario no existe en la base de datos.";
            } elseif ((int)$admin["activo"] !== 1) {
                $error = "Este usuario está desactivado.";
            } elseif (!password_verify($password, $admin["password_hash"])) {
                $error = "La contraseña no es correcta.";
            } else {
                session_regenerate_id(true);

                $_SESSION["admin_logueado"] = true;
                $_SESSION["admin_id"] = (int)$admin["id"];
                $_SESSION["admin_usuario"] = $admin["usuario"];
                $_SESSION["admin_nombre"] = $admin["nombre"];

                $stmt = $pdo->prepare("UPDATE admin_usuarios SET ultimo_acceso = NOW() WHERE id = :id");
                $stmt->execute([
                    ":id" => (int)$admin["id"]
                ]);

                header("Location: panel.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = "No se ha podido comprobar el acceso. Revisa la tabla admin_usuarios.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso administrador | Zángano Pictures 360</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/style.css?v=8">
</head>
<body>

<main class="admin-login-page">
    <div class="admin-login-card">
        <div class="admin-login-logo">
            <img src="../assets/img/logo.png?v=2" alt="Zángano Pictures 360">
        </div>

        <h1>Panel de administración</h1>
        <p>Accede para gestionar mensajes, servicios, noticias, clientes y reseñas.</p>

        <?php if ($error !== ""): ?>
            <div class="alert alert-error">
                <?= limpiar($error) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post" class="admin-form">
            <label>Usuario</label>
            <input type="text" name="usuario" placeholder="Usuario" required>

            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Contraseña" required>

            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>

        <a href="../index.php" class="admin-back-link">← Volver a la web</a>
    </div>
</main>

</body>
</html>