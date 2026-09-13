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
$nombre = trim($_POST["nombre"] ?? "");
$logo = trim($_POST["logo"] ?? "");
$web = trim($_POST["web"] ?? "");
$orden = isset($_POST["orden"]) ? (int) $_POST["orden"] : 0;
$activo = isset($_POST["activo"]) ? 1 : 0;

if ($nombre === "") {
    $error = urlencode("El nombre del cliente es obligatorio.");
    header("Location: clientes.php?error=$error");
    exit;
}

if ($web !== "" && !filter_var($web, FILTER_VALIDATE_URL)) {
    $error = urlencode("La web del cliente no es válida. Debe empezar por http:// o https://");
    header("Location: clientes.php?error=$error");
    exit;
}

try {
    if ($id > 0) {
        $sql = "UPDATE clientes 
                SET nombre = :nombre,
                    logo = :logo,
                    web = :web,
                    orden = :orden,
                    activo = :activo
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nombre" => $nombre,
            ":logo" => $logo,
            ":web" => $web,
            ":orden" => $orden,
            ":activo" => $activo,
            ":id" => $id
        ]);
    } else {
        $sql = "INSERT INTO clientes 
                (nombre, logo, web, orden, activo)
                VALUES
                (:nombre, :logo, :web, :orden, :activo)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nombre" => $nombre,
            ":logo" => $logo,
            ":web" => $web,
            ":orden" => $orden,
            ":activo" => $activo
        ]);
    }

    header("Location: clientes.php?ok=1");
    exit;

} catch (PDOException $e) {
    $error = urlencode("No se han podido guardar los cambios.");
    header("Location: clientes.php?error=$error");
    exit;
}
?>