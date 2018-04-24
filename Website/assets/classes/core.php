<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'database.php';

class CORE
{

	private $conn;

	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
  }

	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function lastID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}

	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE id=:id");
			$stmt->execute(array(":id"=>$_SESSION['userSession']));
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($stmt->rowCount() == 1) {
				return true;
			} else {
				$this->logout();
				return false;
			}
		} else {
			return false;
		}
	}

	public function redirect($url)
	{
		header("Location: $url");
	}

	public function logout()
	{
		session_unset();
		session_destroy();
		$_SESSION['userSession'] = false;
  }
  
  public function register($username, $email, $password, $firstname, $lastname) 
  {
		try {
			$stmt = $this->conn->prepare("INSERT INTO users (username, email, password, firstname, lastname, signup_ip, login_ip) VALUES (:username, :email, :password, :firstname, :lastname, :ip, :ip);");
			$stmt->bindparam(":username",$username);
			$stmt->bindparam(":email",$email);
			$stmt->bindparam(":password",$password);
			$stmt->bindparam(":firstname",$firstname);
			$stmt->bindparam(":lastname",$lastname);
			$stmt->bindparam(":ip",$_SERVER["REMOTE_ADDR"]);
			$stmt->execute();

			return true;
		} catch (PDOException $ex) {
			return false;
		}
  }

	public function login($email, $password)
	{
		$stmt = $this->conn->prepare("SELECT id FROM users WHERE (email=:email OR username=:email) AND password=:password;");
		$stmt->bindparam(":email",$email);
		$stmt->bindparam(":password",$password);
		$stmt->execute();
		$PS = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() != 1) {
			return false;
		} else {
      $_SESSION['userSession'] = $PS[0]["id"];
			return true;
		}
	}
  
  public function create_game() 
  {
		try {
			$stmt = $this->conn->prepare("INSERT INTO games (creator) VALUES (:id);");
			$stmt->bindparam(":id",$_SESSION['userSession']);
			$stmt->execute();

			return $this->lastID;
		} catch (PDOException $ex) {
			return false;
		}
  }
  
  public function leave_game() 
  {
		unset($_SESSION["currentGame"]);
  }

	public function game_exists($gameID)
	{
		$stmt = $this->conn->prepare("SELECT games.id, games.time_started, users.username as creator FROM games INNER JOIN users ON games.creator = users.id WHERE games.id=:gameID;");
		$stmt->bindparam(":gameID",$gameID);
		$stmt->execute();
		$PS = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			return $PS[0];
		} else {
			return false;
		}
	}

	public function get_game_cards($gameID)
	{
		$stmt = $this->conn->prepare("SELECT * FROM cards WHERE gameID=:gameID;");
		$stmt->bindparam(":gameID",$gameID);
		$stmt->execute();
		$PS = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			return $PS[0];
		} else {
			return false;
		}
	}

	public function join_game($gameID)
	{
		$stmt = $this->conn->prepare("SELECT id FROM games WHERE id=:gameID;");
		$stmt->bindparam(":gameID",$gameID);
		$stmt->execute();
		$PS = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			$_SESSION["currentGame"] = $PS[0]["id"];
			return true;
		} else {
			return false;
		}
	}

	function numbers_to_card($number, $card) {
		switch ($number) {
			case 1:
				$number = "jack";
				break;
			case 2:
				$number = "queen";
				break;
			case 3:
				$number = "king";
				break;
			case 4:
				$number = "ace";
				break;
			case 5:
				$number = "7";
				break;
			case 6:
				$number = "8";
				break;
			case 7:
				$number = "9";
				break;
			case 8:
				$number = "10";
				break;
			default:
				$number = "jack";
				break;
		}
		switch ($card) {
			case 1:
				$type = "clubs";
				break;
			case 2:
				$type = "diamonds";
				break;
			case 3:
				$type = "hearts";
				break;
			case 4:
				$type = "spades";
				break;
			default:
				$type = "clubs";
				break;
		}
		$cardName = $number."_of_".$type.".png";
		return $cardName;
	}
}

?>