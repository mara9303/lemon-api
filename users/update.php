<?php
require __DIR__ . '/../vendor/autoload.php';
include_once '../helpers/security.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (!check_credentials()) {
    $response = array(
        "status" => "fail",
        "data" => null,
        "message" => "Credenciales inválidas"
    );
    http_response_code(403);
    echo json_encode($response);
    die;
}

include_once '../config/database.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$response = array(
    "status" => "success",
    "data" => null,
    "message" => ""
);

try {

    if ($db['status'] > 0) {
        $user = new User($db['value']);
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id) && !empty($data->name) &&!empty($data->lastName) && !empty($data->email) &&!empty($data->pass)) {

            $user->id = $data->id;
            $user->name = $data->name;
            $user->lastName = $data->lastName;
            $user->email = $data->email;
            $user->pass = md5($data->pass);

            if ($user->update()) {
                $response['message'] = "Usuario actualizado con éxito";
            }
            else {
                $response['status'] = "fail";
                $response['message'] = "No se pudo actualizar el usuario";
            }
        }else{
            $response['status'] = "fail";
            $response['message'] = "No se pudo actualizar el usuario. Parámetros insuficientes";        
        }
    }
    else {
        $response['status'] = "fail";
        $response['message'] = "La conexión a la base de datos falló";
    }
}
catch (Exception $ex) {
    $response['status'] = "error";
    $response['message'] = $ex->getMessage();
}
finally {
    if ($response['status'] == "error")
        http_response_code(500);
    else
        http_response_code(200);
    echo json_encode($response);
}