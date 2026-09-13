<?php

function proteger_etiquetas_basicas($texto)
{
    $texto = $texto ?? "";

    $reemplazos = [
        "<strong>" => "___STRONG_OPEN___",
        "</strong>" => "___STRONG_CLOSE___",
        "<b>" => "___STRONG_OPEN___",
        "</b>" => "___STRONG_CLOSE___",
        "<em>" => "___EM_OPEN___",
        "</em>" => "___EM_CLOSE___",
        "<i>" => "___EM_OPEN___",
        "</i>" => "___EM_CLOSE___",
        "<br>" => "___BR___",
        "<br/>" => "___BR___",
        "<br />" => "___BR___"
    ];

    $texto = str_ireplace(array_keys($reemplazos), array_values($reemplazos), $texto);

    $texto = htmlspecialchars($texto, ENT_QUOTES, "UTF-8");

    $texto = str_replace(
        [
            "___STRONG_OPEN___",
            "___STRONG_CLOSE___",
            "___EM_OPEN___",
            "___EM_CLOSE___",
            "___BR___"
        ],
        [
            "<strong>",
            "</strong>",
            "<em>",
            "</em>",
            "<br>"
        ],
        $texto
    );

    return $texto;
}

function formato_texto_basico($texto)
{
    $texto = proteger_etiquetas_basicas($texto);

    return nl2br($texto);
}

function obtener_parrafos_formateados($texto)
{
    $texto = $texto ?? "";

    /*
        Normalizamos saltos de línea.
    */
    $texto = str_replace(["\r\n", "\r"], "\n", $texto);

    /*
        Quitamos espacios extra al principio y al final.
    */
    $texto = trim($texto);

    if ($texto === "") {
        return [];
    }

    /*
        Separamos párrafos solo cuando hay una línea en blanco.
        Es decir:
        - 1 Enter dentro de un texto se convertirá en espacio.
        - 2 Enter crearán un nuevo párrafo.
    */
    $bloques = preg_split("/\n\s*\n+/", $texto);

    $parrafos = [];

    foreach ($bloques as $bloque) {
        $bloque = trim($bloque);

        if ($bloque === "") {
            continue;
        }

        /*
            Los saltos de línea simples dentro del mismo párrafo
            se convierten en espacios para evitar frases cortadas.
        */
        $bloque = preg_replace("/[ \t]*\n[ \t]*/", " ", $bloque);

        /*
            Limpiamos espacios duplicados.
        */
        $bloque = preg_replace("/[ \t]{2,}/", " ", $bloque);

        $parrafos[] = proteger_etiquetas_basicas($bloque);
    }

    return $parrafos;
}
?>