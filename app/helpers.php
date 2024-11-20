<?php

if (!function_exists('generateRandomCode')) {
    function generateRandomCode($numDigits, $numLetters)
    {
        $code = '';

        // Générer des chiffres
        for ($i = 0; $i < $numDigits; $i++) {
            $code .= rand(0, 9);
        }

        // Générer des lettres
        for ($i = 0; $i < $numLetters; $i++) {
            $code .= chr(rand(65, 90)); // Utiliser 65-90 pour les majuscules (ASCII)
            // Pour les minuscules, vous pouvez utiliser chr(rand(97, 122))
        }

        return $code;
    }
}


if (!function_exists('formaterCodeBon')) {

    function formaterCodeBon($numeroFacture, $longueur = 6)
    {
        return 'BL' . str_pad($numeroFacture, $longueur, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('formaterCode')) {

    function formaterCode($numero, $longueur = 6)
    {
        return str_pad($numero, $longueur, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('premiereLettre') ) {
    function premiereLettre($chaine) {
        return substr($chaine, 0, 1);
    }
}

if (!function_exists('derniereLettre') ) {
    function derniereLettre($chaine) {
        return substr($chaine, -1);
    }
}
