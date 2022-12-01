<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Función que determina si un elemento del arreglo de JOINS provisto por la base de datos es el último o no
function esUltimo(string $actual,string $proximo):bool{
    if($actual !== $proximo){
        return true;
    }

    return false;
}

//Función que revisa que el usuario está autenticado

function isAuth():void{
    if(!isset($_SESSION['login'])){
        header('Location:/');
    }
}

function isAdmin():void{
    if(!isset($_SESSION['admin'])){
        header('Location:/');
    }
}