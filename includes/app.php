<?php 

require __DIR__ . '/../vendor/autoload.php';
$dotenv=Dotenv\Dotenv::createImmutable(__DIR__); //Define referencia a nuestro archivo .env que contiene nuestra variable globales que serán ocultas al momento de efectuar el deployment
$dotenv->safeLoad();

require 'funciones.php';
require 'database.php';



// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setDB($db);