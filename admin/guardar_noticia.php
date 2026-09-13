<?php

require_once "auth.php";
proteger_admin();

require_once "../includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: noticias.php");
    exit;
}

if (!comprobar_csrf($_POST["csrf_token"] ?? "")) {
    die("Token de seguridad inválido.");
}

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
$titulo = trim($_POST["titulo"] ?? "");
$resumen = trim($_POST["resumen"] ?? "");
$contenido = trim($_POST["contenido"] ?? "");
$imagen_actual = trim($_POST["imagen_actual"] ?? "");
$orden = isset($_POST["orden"]) ? (int) $_POST["orden"] : 0;
$activo = isset($_POST["activo"]) ? 1 : 0;

if ($titulo === "" || $contenido === "") {
    $error = urlencode("El título y el contenido son obligatorios.");
    header("Location: noticias.php?error=$error");
    exit;
}

$imagen = $imagen_actual;

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
        $error = urlencode("No se ha podido subir la imagen.");
        header("Location: noticias.php?error=$error");
        exit;
    }

    $extension = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
    $extensiones_permitidas = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($extension, $extensiones_permitidas, true)) {
        $error = urlencode("La imagen debe ser JPG, PNG o WEBP.");
        header("Location: noticias.php?error=$error");
        exit;
    }

    $directorio = __DIR__ . "/../assets/img/noticias/";

    if (!is_dir($directorio)) {
        mkdir($directorio, 0775, true);
    }

    $nombre_archivo = "noticia-" . time() . "-" . bin2hex(random_bytes(4)) . "." . $extension;
    $ruta_destino = $directorio . $nombre_archivo;

    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_destino)) {
        $error = urlencode("No se ha podido guardar la imagen en el servidor.");
        header("Location: noticias.php?error=$error");
        exit;
    }

    $imagen = "assets/img/noticias/" . $nombre_archivo;
}

try {
    if ($id > 0) {
        $sql = "UPDATE noticias 
                SET titulo = :titulo,
                    resumen = :resumen,
                    contenido = :contenido,
                    imagen = :imagen,
                    orden = :orden,
                    activo = :activo
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":titulo" => $titulo,
            ":resumen" => $resumen,
            ":contenido" => $contenido,
            ":imagen" => $imagen,
            ":orden" => $orden,
            ":activo" => $activo,
            ":id" => $id
        ]);
    } else {
        $sql = "INSERT INTO noticias
                (titulo, resumen, contenido, imagen, orden, activo)
                VALUES
                (:titulo, :resumen, :contenido, :imagen, :orden, :activo)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":titulo" => $titulo,
            ":resumen" => $resumen,
            ":contenido" => $contenido,
            ":imagen" => $imagen,
            ":orden" => $orden,
            ":activo" => $activo
        ]);
    }

    header("Location: noticias.php?ok=1");
    exit;

} catch (PDOException $e) {
    $error = urlencode("No se ha podido guardar la noticia.");
    header("Location: noticias.php?error=$error");
    exit;
}
?>