<?php
class Database
{
  private $host = "vps.sanderjochems.com";
  private $db_name = "ixat_taxis";
  private $username = "ixat";
  private $password = "QzA4AWAFBgdhS3FZ";
  
  public $conn;

  public function dbConnection()
	{
    $this->conn = null;
    try {
      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name.";charset=utf8mb4;", $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $exception) {
      exit("Connection Error: " . $exception->getMessage());
    }
    return $this->conn;
  }
}
?>