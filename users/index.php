<?php
require __DIR__ . '/../vendor/autoload.php';
include_once '../helpers/security.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if(!check_credentials()){
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
        $users = new User($db['value']);
        $users->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : 0;
        $result = $users->read();
        $usersRecords = array();
        if ($result->num_rows > 0) {
            
            $usersRecords["users"] = array();
            while ($userRecord = $result->fetch_assoc()) {
                extract($userRecord);
                $userDetails = array(
                    "id" => $id,
                    "name" => $name,
                    "lastName" => $lastName,
                    "price" => $email,
                    "createdAt" => $createdAt,
                    "updatedAt" => $updatedAt
                );
                array_push($usersRecords["users"], $userDetails);
            }
        }
        
        $response['data'] = $usersRecords;
        $response['message'] = "Total de registros: ". count($usersRecords["users"]);
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
    if($response['status'] == "error")
        http_response_code(500);
    else
        http_response_code(200);
    echo json_encode($response);
}