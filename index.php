<?php
require 'vendor/autoload.php';
//Variables
$body = '';

try{
    //Load .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    //Check that the variables exist
    $dotenv->required(['APP_KEY','DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

    //Load database connection
    include_once 'config/database.php';

    $database = new Database();
    $db = $database->getConnection();

    $body = '
            <h1>Bienvenido al lemon-api</h1>
            <h2>Estatus de la base de datos:</h2>';
        if($db['status'] > 0){
            $body .= '<p style="color: green;">'.$db['message'].'</p>';
        }else{
            $body .= '<p style="color: red;">'.$db['message'].'</p>';
        }
    $body .='
            <h2>Funciones del api sobre los usuarios</h2>
            <ol>
                <li><a href="users/">Usuarios</a></li>
                <li><a href="users/create.php">Crear</a></li>
                <li><a href="users/update.php">Actualizar</a></li>
                <li><a href="users/delete.php">Eliminar</a></li>
            </ol>
            ';
}catch(Exception $ex){
    $body = '<p>'.$ex->getMessage().'</p>';
}finally{
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        '.$body.'
    </body>
    </html>
    ';
}