<?php
require_once __DIR__ . "/../controllers/UsuarioController.php";
$metodo = $_SERVER["REQUEST_METHOD"];

if ($rota === "usuario"){
    switch($metodo){
        case "POST": 
            creatUsuario($con);
            break;
        case "GET":
            analisarUsuario($con);
            break; 
        case "UP": 
            updateUsuario($con);
            break; 
        case "DELETE": 
            deleteUsuario($con);
            break; 
    }
}