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
	
	public function load_user_data() {
		if ($this->is_logged_in()) {
			$stmt = $this->runQuery("SELECT * FROM klant WHERE gebruikersnaam=:gebruikersnaam");
			$stmt->execute(array(":gebruikersnaam"=>$_SESSION['userSession']));
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
	}

	public function load_chauffeur_data($chauffeurID) {
		$stmt = $this->runQuery("SELECT * FROM chauffeur WHERE id=:cID");
		$stmt->execute(array(":cID"=>$chauffeurID));
		return $stmt->fetch(PDO::FETCH_ASSOC);
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
			$stmt = $this->conn->prepare("SELECT * FROM klant WHERE gebruikersnaam=:user");
			$stmt->execute(array(":user"=>$_SESSION['userSession']));
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
  
  public function registerCustomer($gebruikersnaam, $wachtwoord, $repeatPassword, $naam, $mobiel, $email) 
  {
		try {
			$stmt = $this->conn->prepare("INSERT INTO klant (gebruikersnaam, wachtwoord, naam, mobiel, email) VALUES (:gebruikersnaam, :wachtwoord, :naam, :mobiel, :email);");
			$stmt->bindparam(":gebruikersnaam",$gebruikersnaam);
			$stmt->bindparam(":wachtwoord",$wachtwoord);
			$stmt->bindparam(":naam",$naam);
			$stmt->bindparam(":mobiel",$mobiel);
			$stmt->bindparam(":email",$email);
			$stmt->execute();

			return 0;
		} catch (PDOException $ex) {
			return 1;
		}
  }
  
  public function registerDriver($gebruikersnaam, $wachtwoord, $naam, $mobiel, $email, $autoMerk, $autoType, $kenteken, $passagiers, $laadruimte, $schadevrij) 
  {
		try {
			if (!$this->is_logged_in()) {
				$stmt = $this->conn->prepare("INSERT INTO klant (gebruikersnaam, wachtwoord, naam, mobiel, email) VALUES (:gebruikersnaam, :wachtwoord, :naam, :mobiel, :email);");
				$stmt->bindparam(":gebruikersnaam",$gebruikersnaam);
				$stmt->bindparam(":wachtwoord",$wachtwoord);
				$stmt->bindparam(":naam",$naam);
				$stmt->bindparam(":mobiel",$mobiel);
				$stmt->bindparam(":email",$email);
				$stmt->execute();
			}

			try {
				$stmt = $this->conn->prepare("INSERT INTO chauffeur_aanvraag (gebruikersnaam, automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren) VALUES (:gebruikersnaam, :automerk, :autotype, :kenteken, :aantal_passagiers, :laadruimte, :schadevrije_jaren);");
				$stmt->bindparam(":gebruikersnaam",$gebruikersnaam);
				$stmt->bindparam(":automerk",$autoMerk);
				$stmt->bindparam(":autotype",$autoType);
				$stmt->bindparam(":kenteken",$kenteken);
				$stmt->bindparam(":aantal_passagiers",$passagiers);
				$stmt->bindparam(":laadruimte",$laadruimte);
				$stmt->bindparam(":schadevrije_jaren",$schadevrij);
				$stmt->execute();
			} catch (PDOException $ex) {
				return 2;
			}

			return 0;
		} catch (PDOException $ex) {
			return 1;
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

	public function getRideHistory() {
		if ($this->is_logged_in()) {
			$stmt = $this->conn->prepare("SELECT taxi_aanvraag.*, klant.naam FROM taxi_aanvraag INNER JOIN klant ON taxi_aanvraag.gebruikersnaam = klant.gebruikersnaam WHERE gebruikersnaam=:user AND password=:password;");
			$stmt->bindparam(":user",$_SESSION['userSession']);
			$stmt->execute();
			$PS = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if ($stmt->rowCount() > 0) {
				return $PS;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function showAlert($message, $color = "success") {
		return '<div class="container"><div class="row margin-bottom-25px"><div class="col-2"></div><div class="col-8"><div class="alert alert-'.$color.' alert-dismissible fade show">'.$message.'</div></div></div></div>';
	}
}