<?php
require __DIR__ . '/../vendor/autoload.php';

//Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

class Database{
	
	private $host;
    private $user;
    private $password;
    private $database; 

    public function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->user  = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
        $this->database = $_ENV['DB_NAME'];
    }
    
    public function getConnection(){		
		$conn = new mysqli($this->host, $this->user, $this->password, $this->database);
		if($conn->connect_error){
			return [
                "message" => "Error failed to connect to MySQL: " . $conn->connect_error,
                "value" => null,
                "status" => -1
            ];
		} else {
            return [
                "message" => "Conexión exitosa a la base de datos",
                "value" => $conn,
                "status" => 1
            ];
		}
    }
}
?>