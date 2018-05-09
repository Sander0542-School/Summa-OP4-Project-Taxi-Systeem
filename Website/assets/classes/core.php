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
			$stmt = $this->runQuery("SELECT * FROM klant WHERE id=:klantID");
			$stmt->execute(array(":klantID"=>$_SESSION['userSession']));
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
			$stmt = $this->conn->prepare("SELECT * FROM klant WHERE id=:klantID");
			$stmt->execute(array(":klantID"=>$_SESSION['userSession']));
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
  
  public function registerDriver($gebruikersnaam, $wachtwoord, $wachtwoord2, $naam, $mobiel, $email, $autoMerk, $autoType, $kenteken, $passagiers, $laadruimte, $schadevrij, $klantID = 0) 
  {
		try {
			if (!$this->is_logged_in()) {
				if ($wachtwoord == $wachtwoord2) {
					$stmt = $this->conn->prepare("INSERT INTO klant (gebruikersnaam, wachtwoord, naam, mobiel, email) VALUES (:gebruikersnaam, :wachtwoord, :naam, :mobiel, :email);");
					$stmt->bindparam(":gebruikersnaam",$gebruikersnaam);
					$stmt->bindparam(":wachtwoord",$wachtwoord);
					$stmt->bindparam(":naam",$naam);
					$stmt->bindparam(":mobiel",$mobiel);
					$stmt->bindparam(":email",$email);
					$stmt->execute();
					$klantID = $this->lastID();
				} else {
					return 3;
				}
			}

			try {
				if ($klantID != 0) {
					$stmt = $this->conn->prepare("INSERT INTO chauffeur_aanvraag (klantID, automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren) VALUES (:klantID, :automerk, :autotype, :kenteken, :aantal_passagiers, :laadruimte, :schadevrije_jaren);");
					$stmt->bindparam(":klantID",$klantID);
					$stmt->bindparam(":automerk",$autoMerk);
					$stmt->bindparam(":autotype",$autoType);
					$stmt->bindparam(":kenteken",$kenteken);
					$stmt->bindparam(":aantal_passagiers",$passagiers);
					$stmt->bindparam(":laadruimte",$laadruimte);
					$stmt->bindparam(":schadevrije_jaren",$schadevrij);
					$stmt->execute();
					if ($gebruikersnaam == null) {
						return 4;
					}
				} else {
					return 2;
				}
			} catch (PDOException $ex) {
				return 2;
			}

			return 0;
		} catch (PDOException $ex) {
			return 1;
		}
  }
  
  public function requestDriver($klantID, $autoMerk, $autoType, $kenteken, $passagiers, $laadruimte, $schadevrij) 
  {
		try {
			$stmt = $this->conn->prepare("INSERT INTO chauffeur_aanvraag (klantID, automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren) VALUES (:klantID, :automerk, :autotype, :kenteken, :aantal_passagiers, :laadruimte, :schadevrije_jaren);");
			$stmt->bindparam(":klantID",$klantID);
			$stmt->bindparam(":automerk",$autoMerk);
			$stmt->bindparam(":autotype",$autoType);
			$stmt->bindparam(":kenteken",$kenteken);
			$stmt->bindparam(":aantal_passagiers",$passagiers);
			$stmt->bindparam(":laadruimte",$laadruimte);
			$stmt->bindparam(":schadevrije_jaren",$schadevrij);
			$stmt->execute();
			return 0;
		} catch (PDOException $ex) {
			return 1;
		}
  }

	public function updateKlant($name, $email, $mobile, $username, $password) {
		if ($CORE->is_logged_in()) {
			try {
				$stmt = $this->conn->prepare("UPDATE klant SET naam=:name, email=:email, mobiel=:mobile, gebruikersnaam=:username WHERE id=:klantID AND wachtwoord=:password;");
				$stmt->bindparam(":klantID",$_SESSION['userSession']);
				$stmt->bindparam(":name",$name);
				$stmt->bindparam(":email",$email);
				$stmt->bindparam(":mobile",$mobile);
				$stmt->bindparam(":username",$username);
				$stmt->bindparam(":password",$password);
				$stmt->execute();

				return 0;
			} catch (PDOException $ex) {
				return 1;
			}
		} else {
			return 2;
		}
	}

	public function updateChauffeur($chauffeurID, $autoMerk, $autoType, $kenteken, $passagiers, $laadruimte, $schadevrij) {
		try {
			$stmt = $this->conn->prepare("UPDATE chauffeur SET automerk=:automerk, autotype=:autotype, kenteken=:kenteken, aantal_passagiers=:aantal_passagiers, laadruimte:laadruimte, schadevrije_jaren:schadevrije_jaren WHERE id=:chauffeurID;");
			$stmt->bindparam(":chauffeurID",$chauffeurID);
			$stmt->bindparam(":automerk",$autoMerk);
			$stmt->bindparam(":autotype",$autoType);
			$stmt->bindparam(":kenteken",$kenteken);
			$stmt->bindparam(":aantal_passagiers",$passagiers);
			$stmt->bindparam(":laadruimte",$laadruimte);
			$stmt->bindparam(":schadevrije_jaren",$schadevrij);
			$stmt->execute();

			return 0;
		} catch (PDOException $ex) {
			return 1;
		}
	}

	public function login($username, $password)
	{
		$stmt = $this->conn->prepare("SELECT id FROM klant WHERE (email=:username OR gebruikersnaam=:username) AND wachtwoord=:password;");
		$stmt->bindparam(":username",$username);
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

	public function hasRequest()
	{
		$stmt = $this->conn->prepare("SELECT klantID FROM chauffeur_aanvraag WHERE klantID=:klantID;");
		$stmt->bindparam(":klantID",$_SESSION["userSession"]);
		$stmt->execute();
		$PS = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getRideHistory() {
		if ($this->is_logged_in()) {
			$stmt = $this->conn->prepare("SELECT taxi_aanvraag.*, klant.naam FROM taxi_aanvraag INNER JOIN klant ON taxi_aanvraag.gebruikersnaam = klant.gebruikersnaam WHERE id=:klantID AND password=:password;");
			$stmt->bindparam(":klantID",$_SESSION['userSession']);
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

	public function getOpenRequests(){
		$stmt = $this->conn->prepare("SELECT naam, minimale_laadruimte, passagiers, TIME(datum_tijd) as tijd, DATE(datum_tijd) as datum, email, mobiel, latitude, longitude FROM taxi_aanvraag INNER JOIN klant ON klant.id = taxi_aanvraag.klantID;");
		$stmt->execute();
		$PS = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			return $PS;
		} else {
			return false;
		}
	}
	
}