<?php

require_once "includes/config.php";
require_once "includes/conexion.php";

/* =========================================================
   FUNCIÓN PARA REDIRIGIR
   ========================================================= */

function volver_contacto($tipo)
{
    header("Location: contacto.php?" . $tipo);
    exit;
}

/* =========================================================
   VERIFICAR CLOUDFLARE TURNSTILE
   ========================================================= */

function verificar_turnstile($token, $secret_key)
{
    if (empty($token) || empty($secret_key)) {
        return false;
    }

    $datos = [
        "secret" => $secret_key,
        "response" => $token,
        "remoteip" => $_SERVER["REMOTE_ADDR"] ?? ""
    ];

    $ch = curl_init("https://challenges.cloudflare.com/turnstile/v0/siteverify");

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $respuesta = curl_exec($ch);
    $error_curl = curl_error($ch);

    curl_close($ch);

    if (!$respuesta || $error_curl) {
        return false;
    }

    $resultado = json_decode($respuesta, true);

    return isset($resultado["success"]) && $resultado["success"] === true;
}

/* =========================================================
   SOLO ACEPTAR POST
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    volver_contacto("error=metodo");
}

/* =========================================================
   CAMPO TRAMPA ANTIBOTS
   Si este campo viene relleno, casi seguro es un bot.
   ========================================================= */

if (!empty($_POST["empresa_web"] ?? "")) {
    volver_contacto("error=spam");
}

/* =========================================================
   TIEMPO MÍNIMO DE ENVÍO
   Si se manda en menos de 4 segundos, probablemente es bot.
   ========================================================= */

$form_inicio = isset($_POST["form_inicio"]) ? (int) $_POST["form_inicio"] : 0;
$tiempo_transcurrido = time() - $form_inicio;

if ($form_inicio <= 0 || $tiempo_transcurrido < 4) {
    volver_contacto("error=spam");
}

/* =========================================================
   VERIFICAR TURNSTILE
   ========================================================= */

$token_turnstile = $_POST["cf-turnstile-response"] ?? "";

if (!isset($turnstile_secret_key) || !verificar_turnstile($token_turnstile, $turnstile_secret_key)) {
    volver_contacto("error= Es necesario marcar el captcha para enviar el formulario");
}

/* =========================================================
   RECOGER Y LIMPIAR DATOS
   ========================================================= */

$nombre = trim($_POST["nombre"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$servicio = trim($_POST["servicio"] ?? "");
$mensaje = trim($_POST["mensaje"] ?? "");
$privacidad = isset($_POST["privacidad"]);

if ($servicio === "") {
    $servicio = "Consulta desde página principal";
}

/* =========================================================
   VALIDACIONES BÁSICAS
   ========================================================= */

if ($nombre === "" || $email === "" || $mensaje === "") {
    volver_contacto("error=campos");
}

if (!$privacidad) {
    volver_contacto("error=privacidad");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    volver_contacto("error=email");
}

if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 80) {
    volver_contacto("error=nombre");
}

if (mb_strlen($mensaje) < 10 || mb_strlen($mensaje) > 2000) {
    volver_contacto("error=mensaje");
}

if ($telefono !== "" && !preg_match("/^[0-9+\s()-]{6,20}$/", $telefono)) {
    volver_contacto("error=telefono");
}

/* =========================================================
   FILTRO ANTISPAM BÁSICO
   ========================================================= */

$texto_spam = strtolower($nombre . " " . $email . " " . $telefono . " " . $servicio . " " . $mensaje);

$palabras_bloqueadas = [
    "graph.org",
    "balance",
    "withdraw",
    "transfer",
    "dollars",
    "bitcoin",
    "crypto",
    "wallet",
    "investment",
    "loan",
    "casino",
    "viagra",
    "porn",
    "sex",
    "xxx",
    "telegram",
    "whatsapp group",
    "free money",
    "earn money",
    "click here",
    "http://",
    "https://"
];

foreach ($palabras_bloqueadas as $palabra) {
    if (strpos($texto_spam, $palabra) !== false) {
        volver_contacto("error=spam");
    }
}

/* =========================================================
   EVITAR MUCHOS ENLACES O TEXTO RARO
   ========================================================= */

$numero_urls = preg_match_all("/https?:\/\/|www\./i", $mensaje);

if ($numero_urls > 0) {
    volver_contacto("error=spam");
}

if (preg_match("/[\x{0400}-\x{04FF}]/u", $mensaje)) {
    volver_contacto("error=spam");
}

/* =========================================================
   GUARDAR EN BASE DE DATOS
   ========================================================= */

try {
    $stmt = $pdo->prepare("
        INSERT INTO mensajes 
            (nombre, email, telefono, servicio, mensaje, estado, fecha_creacion)
        VALUES 
            (:nombre, :email, :telefono, :servicio, :mensaje, 'nuevo', NOW())
    ");

    $stmt->execute([
        ":nombre" => $nombre,
        ":email" => $email,
        ":telefono" => $telefono,
        ":servicio" => $servicio,
        ":mensaje" => $mensaje
    ]);

    volver_contacto("ok=1");

} catch (PDOException $e) {
    volver_contacto("error=bd");
}
?>