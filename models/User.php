<?php
class User{   
    
    private $table = "lemon_users";      
    public $id;
    public $name;
    public $lastName;
    public $email;
    public $pass;   
    public $createdAt; 
	public $updatedAt; 
    private $conn;
	
    public function __construct($db){
        $this->conn = $db;
    }	
	
	function read(){	
		if(isset($this->id) && $this->id > 0) {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->table." WHERE id = ?");
			$stmt->bind_param("i", $this->id);					
		} else {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->table);		
		}		
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
	
	function create(){
		
		$stmt = $this->conn->prepare("
			INSERT INTO ".$this->table."(`name`, `lastName`, `email`, `pass`)
			VALUES(?,?,?,?)");
		
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->lastName = htmlspecialchars(strip_tags($this->lastName));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->pass = htmlspecialchars(strip_tags($this->pass));
		
		
		$stmt->bind_param("ssss", $this->name, $this->lastName, $this->email, $this->pass);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
		
	function update(){
	 
		$stmt = $this->conn->prepare("
			UPDATE ".$this->table." 
			SET name= ?, lastName = ?, email = ?, pass = ?
			WHERE id = ?");
	 
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->lastName = htmlspecialchars(strip_tags($this->lastName));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->pass = htmlspecialchars(strip_tags($this->pass));
	 
		$stmt->bind_param('ssssi',$this->name, $this->lastName, $this->email, $this->pass, $this->id);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	function delete(){
		
		$stmt = $this->conn->prepare("
			DELETE FROM ".$this->table." 
			WHERE id = ?");
			
		$this->id = htmlspecialchars(strip_tags($this->id));
	 
		$stmt->bind_param("i", $this->id);
	 
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
}
?>