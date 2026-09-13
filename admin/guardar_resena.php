<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: resenas.php");
    exit;
}

if (!comprobar_csrf($_POST["csrf_token"] ?? "")) {
    die("Token de seguridad inválido.");
}

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
$titulo = trim($_POST["titulo"] ?? "");
$comentario = trim($_POST["comentario"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$cargo = trim($_POST["cargo"] ?? "");
$foto = trim($_POST["foto"] ?? "");
$estrellas = isset($_POST["estrellas"]) ? (int) $_POST["estrellas"] : 5;
$orden = isset($_POST["orden"]) ? (int) $_POST["orden"] : 0;
$activo = isset($_POST["activo"]) ? 1 : 0;

if ($titulo === "" || $comentario === "" || $nombre === "") {
    $error = urlencode("El título, el comentario y el nombre son obligatorios.");
    header("Location: resenas.php?error=$error");
    exit;
}

if ($estrellas < 1) {
    $estrellas = 1;
}

if ($estrellas > 5) {
    $estrellas = 5;
}

try {
    if ($id > 0) {
        $sql = "UPDATE resenas 
                SET titulo = :titulo,
                    comentario = :comentario,
                    nombre = :nombre,
                    cargo = :cargo,
                    foto = :foto,
                    estrellas = :estrellas,
                    orden = :orden,
                    activo = :activo
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":titulo" => $titulo,
            ":comentario" => $comentario,
            ":nombre" => $nombre,
            ":cargo" => $cargo,
            ":foto" => $foto,
            ":estrellas" => $estrellas,
            ":orden" => $orden,
            ":activo" => $activo,
            ":id" => $id
        ]);
    } else {
        $sql = "INSERT INTO resenas 
                (titulo, comentario, nombre, cargo, foto, estrellas, orden, activo)
                VALUES
                (:titulo, :comentario, :nombre, :cargo, :foto, :estrellas, :orden, :activo)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":titulo" => $titulo,
            ":comentario" => $comentario,
            ":nombre" => $nombre,
            ":cargo" => $cargo,
            ":foto" => $foto,
            ":estrellas" => $estrellas,
            ":orden" => $orden,
            ":activo" => $activo
        ]);
    }

    header("Location: resenas.php?ok=1");
    exit;

} catch (PDOException $e) {
    $error = urlencode("No se han podido guardar los cambios.");
    header("Location: resenas.php?error=$error");
    exit;
}
?>