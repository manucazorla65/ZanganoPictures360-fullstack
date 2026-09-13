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

function limpiar_html_admin_servicio($texto)
{
    $texto = trim($texto ?? "");
    return strip_tags($texto, "<strong><b><em><i><br>");
}

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
$titulo = trim($_POST["titulo"] ?? "");

/*
    La descripción corta NO acepta HTML.
    Así evitamos que se vea <strong> escrito en tarjetas.
*/
$descripcion_corta = trim(strip_tags($_POST["descripcion_corta"] ?? ""));

/*
    La descripción larga SÍ permite negrita, cursiva y saltos de línea.
*/
$descripcion_larga = limpiar_html_admin_servicio($_POST["descripcion_larga"] ?? "");

$imagen = trim($_POST["imagen"] ?? "");
$orden = isset($_POST["orden"]) ? (int) $_POST["orden"] : 0;
$activo = isset($_POST["activo"]) ? 1 : 0;

if ($titulo === "" || $descripcion_corta === "" || $descripcion_larga === "" || $imagen === "") {
    $error = urlencode("Todos los campos principales son obligatorios.");
    header("Location: servicios.php?error=$error");
    exit;
}

try {
    if ($id > 0) {
        $sql = "UPDATE servicios 
                SET titulo = :titulo,
                    descripcion_corta = :descripcion_corta,
                    descripcion_larga = :descripcion_larga,
                    imagen = :imagen,
                    orden = :orden,
                    activo = :activo
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":titulo" => $titulo,
            ":descripcion_corta" => $descripcion_corta,
            ":descripcion_larga" => $descripcion_larga,
            ":imagen" => $imagen,
            ":orden" => $orden,
            ":activo" => $activo,
            ":id" => $id
        ]);
    } else {
        $sql = "INSERT INTO servicios
                (titulo, descripcion_corta, descripcion_larga, imagen, orden, activo)
                VALUES
                (:titulo, :descripcion_corta, :descripcion_larga, :imagen, :orden, :activo)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":titulo" => $titulo,
            ":descripcion_corta" => $descripcion_corta,
            ":descripcion_larga" => $descripcion_larga,
            ":imagen" => $imagen,
            ":orden" => $orden,
            ":activo" => $activo
        ]);
    }

    header("Location: servicios.php?ok=1");
    exit;

} catch (PDOException $e) {
    $error = urlencode("No se ha podido guardar el servicio. Motivo: " . $e->getMessage());
    header("Location: servicios.php?error=$error");
    exit;
}
?>