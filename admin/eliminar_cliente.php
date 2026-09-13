<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: clientes.php");
    exit;
}

if (!comprobar_csrf($_POST["csrf_token"] ?? "")) {
    die("Token de seguridad inválido.");
}

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
    $stmt->execute([":id" => $id]);
}

header("Location: clientes.php?ok=1");
exit;
?>
