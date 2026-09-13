<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function proteger_admin()
{
    if (!isset($_SESSION["admin_logueado"]) || $_SESSION["admin_logueado"] !== true) {
        header("Location: login.php");
        exit;
    }
}

function limpiar($valor)
{
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

function comprobar_csrf($token)
{
    return isset($_SESSION["csrf_token"]) && hash_equals($_SESSION["csrf_token"], $token ?? "");
}
?>