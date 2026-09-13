<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: servicios.php");
    exit;
}

if (!comprobar_csrf($_POST["csrf_token"] ?? "")) {
    die("Token de seguridad inválido.");
}

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

if ($id <= 0) {
    $error = urlencode("Servicio no válido.");
    header("Location: servicios.php?error=$error");
    exit;
}

try {
    $pdo->beginTransaction();

    /*
        Si existe la tabla servicio_media, borramos primero
        los elementos multimedia relacionados con el servicio.
        Si no existe, no hacemos nada y seguimos.
    */
    $stmt_tabla = $pdo->prepare("SHOW TABLES LIKE 'servicio_media'");
    $stmt_tabla->execute();
    $existe_tabla_media = $stmt_tabla->fetchColumn();

    if ($existe_tabla_media) {
        $stmt_media = $pdo->prepare("DELETE FROM servicio_media WHERE servicio_id = :id");
        $stmt_media->execute([
            ":id" => $id
        ]);
    }

    /*
        Ahora borramos el servicio.
    */
    $stmt_servicio = $pdo->prepare("DELETE FROM servicios WHERE id = :id");
    $stmt_servicio->execute([
        ":id" => $id
    ]);

    if ($stmt_servicio->rowCount() === 0) {
        $pdo->rollBack();

        $error = urlencode("No se ha encontrado el servicio que quieres eliminar.");
        header("Location: servicios.php?error=$error");
        exit;
    }

    $pdo->commit();

    header("Location: servicios.php?ok=1");
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    /*
        En local mostramos el motivo real para poder corregirlo.
        Cuando la web esté online, podemos volver a dejar un mensaje genérico.
    */
    $error = urlencode("No se ha podido eliminar el servicio. Motivo: " . $e->getMessage());
    header("Location: servicios.php?error=$error");
    exit;
}
?>