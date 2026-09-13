<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: panel.php");
    exit;
}

if (!comprobar_csrf($_POST["csrf_token"] ?? "")) {
    die("Token de seguridad inválido.");
}

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
$accion = $_POST["accion"] ?? "";

if ($id <= 0) {
    header("Location: panel.php");
    exit;
}

if ($accion === "eliminar") {
    $stmt = $pdo->prepare("DELETE FROM mensajes_contacto WHERE id = :id");
    $stmt->execute([":id" => $id]);

    header("Location: panel.php");
    exit;
}

$estados_permitidos = ["nuevo", "leido", "respondido"];

if (in_array($accion, $estados_permitidos, true)) {
    $stmt = $pdo->prepare("UPDATE mensajes_contacto SET estado = :estado WHERE id = :id");
    $stmt->execute([
        ":estado" => $accion,
        ":id" => $id
    ]);
}

header("Location: panel.php");
exit;
?>