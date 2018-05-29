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
  
  public function registerDriver($gebruikersnaam, $wachtwoord, $wachtwoord2, $naam, $mobiel, $email, $autoMerk, $autoType, $kenteken, $passagiers, $laadruimte, $schadevrij, $rijbewijzen) 
  {
		try {
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

			try {
				if ($klantID != 0) {
					$stmt = $this->conn->prepare("INSERT INTO chauffeur_aanvraag (klantID, automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren, rijbewijs) VALUES (:klantID, :automerk, :autotype, :kenteken, :aantal_passagiers, :laadruimte, :schadevrije_jaren, :rijbewijs);");
					$stmt->bindparam(":klantID",$klantID);
					$stmt->bindparam(":automerk",$autoMerk);
					$stmt->bindparam(":autotype",$autoType);
					$stmt->bindparam(":kenteken",$kenteken);
					$stmt->bindparam(":aantal_passagiers",$passagiers);
					$stmt->bindparam(":laadruimte",$laadruimte);
					$stmt->bindparam(":schadevrije_jaren",$schadevrij);
					$stmt->bindparam(":rijbewijs",implode(',', $rijbewijzen));
					$stmt->execute();
					return 4;
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
  
  public function requestDriver($klantID, $autoMerk, $autoType, $kenteken, $passagiers, $laadruimte, $schadevrij, $rijbewijzen) 
  {
		$rijbewijzen = implode(',', $rijbewijzen);
		try {
			$stmt = $this->conn->prepare("INSERT INTO chauffeur_aanvraag (klantID, automerk, autotype, kenteken, aantal_passagiers, laadruimte, schadevrije_jaren, rijbewijs) VALUES (:klantID, :automerk, :autotype, :kenteken, :aantal_passagiers, :laadruimte, :schadevrije_jaren, :rijbewijs);");
			$stmt->bindparam(":klantID",$klantID);
			$stmt->bindparam(":automerk",$autoMerk);
			$stmt->bindparam(":autotype",$autoType);
			$stmt->bindparam(":kenteken",$kenteken);
			$stmt->bindparam(":aantal_passagiers",$passagiers);
			$stmt->bindparam(":laadruimte",$laadruimte);
			$stmt->bindparam(":schadevrije_jaren",$schadevrij);
			$stmt->bindparam(":rijbewijs",$rijbewijzen);
			$stmt->execute();
			return 0;
		} catch (PDOException $ex) {
			return 1;
		}
  }

	public function updateKlant($name, $email, $mobile, $password) 
	{
		if ($this->is_logged_in()) {
			try {
				$stmt = $this->conn->prepare("SELECT gebruikersnaam FROM klant WHERE id=:klantID AND wachtwoord=:password;");
				$stmt->bindparam(":klantID",$_SESSION['userSession']);
				$stmt->bindparam(":password",$password);
					
				$stmt2 = $this->conn->prepare("UPDATE klant SET naam=:name, email=:email, mobiel=:mobile WHERE id=:klantID AND wachtwoord=:password;");
				$stmt2->bindparam(":klantID",$_SESSION['userSession']);
				$stmt2->bindparam(":name",$name);
				$stmt2->bindparam(":email",$email);
				$stmt2->bindparam(":mobile",$mobile);
				$stmt2->bindparam(":password",$password);
				$stmt2->execute();

				return 0;
			} catch (PDOException $ex) {
				return 1;
			}
		} else {
			return 2;
		}
	}

	public function updateChauffeur($chauffeurID, $autoMerk, $autoType, $kenteken, $passagiers, $laadruimte, $schadevrij, $rijbewijzen) 
	{
		$rijbewijzen = implode(',', $rijbewijzen);
		try {
			$stmt = $this->conn->prepare("UPDATE chauffeur SET automerk=:automerk, autotype=:autotype, kenteken=:kenteken, aantal_passagiers=:aantal_passagiers, laadruimte=:laadruimte, schadevrije_jaren=:schadevrije_jaren, rijbewijs=:rijbewijs WHERE id=:chauffeurID;");
			$stmt->bindparam(":chauffeurID",$chauffeurID);
			$stmt->bindparam(":automerk",$autoMerk);
			$stmt->bindparam(":autotype",$autoType);
			$stmt->bindparam(":kenteken",$kenteken);
			$stmt->bindparam(":aantal_passagiers",$passagiers);
			$stmt->bindparam(":laadruimte",$laadruimte);
			$stmt->bindparam(":schadevrije_jaren",$schadevrij);
			$stmt->bindparam(":rijbewijs",$rijbewijzen);
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

	public function requestRide($passagiers, $laadruimte, $latitude, $longitude) {
		try {
			$stmt = $this->conn->prepare("INSERT INTO taxi_aanvraag (klantID, minimale_laadruimte, passagiers, latitude, longitude) VALUES (:klantID, :laadruimte, :aantal_passagiers, :latitude, :longitude);");
			$stmt->bindparam(":aantal_passagiers",$passagiers);
			$stmt->bindparam(":laadruimte",$laadruimte);
			$stmt->bindparam(":klantID",$_SESSION["userSession"]);
			$stmt->bindparam(":latitude",$latitude);
			$stmt->bindparam(":longitude",$longitude);
			$stmt->execute();
			return 0;
		} catch (PDOException $ex) {
			return 1;
		}
	}

	public function getRideHistory() 
	{
		if ($this->is_logged_in()) {
			$stmt = $this->conn->prepare("SELECT CONCAT(DAY(datum_tijd),'-',MONTH(datum_tijd),'-',YEAR(datum_tijd)) as datum, latitude, longitude FROM taxi_aanvraag WHERE klantID=:klantID AND klaar='1';");
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

	public function accepteerRit($aanvraagID, $chauffeurID)
	{
		try {
			$stmt = $this->conn->prepare("UPDATE taxi_aanvraag SET chauffeurID=:chauffeurID, geaccepteerd='1' WHERE aanvraagID=:aanvraagID;");
			$stmt->bindparam(":chauffeurID",$chauffeurID);
			$stmt->bindparam(":aanvraagID",$aanvraagID);
			$stmt->execute();

			$stmt2 = $this->conn->prepare("UPDATE chauffeur SET vrij='0' WHERE id=:chauffeurID;");
			$stmt2->bindparam(":chauffeurID",$chauffeurID);
			$stmt2->execute();

			return 0;
		} catch (PDOException $ex) {
			return 1;
		}
	}

	public function weigerRit($aanvraagID, $chauffeurID) 
	{
		try {
			$stmt = $this->conn->prepare("UPDATE taxi_aanvraag SET chauffeurID= CASE WHEN chauffeurID = :chauffeurID THEN null ELSE chauffeurID END, geaccepteerd='0' WHERE aanvraagID=:aanvraagID;");
			$stmt->bindparam(":chauffeurID",$chauffeurID);
			$stmt->bindparam(":aanvraagID",$aanvraagID);
			$stmt->execute();

			return 0;
		} catch (PDOException $ex) {
			return 1;
		}
	}

	public function getOpenRequests($chauffeurID) 
	{
		$stmt = $this->conn->prepare("SELECT aanvraagID, naam, minimale_laadruimte, passagiers, TIME(datum_tijd) as tijd, CONCAT(DAY(datum_tijd),'-',MONTH(datum_tijd),'-',YEAR(datum_tijd)) as datum, email, mobiel, latitude, longitude FROM taxi_aanvraag INNER JOIN klant ON klant.id = taxi_aanvraag.klantID WHERE taxi_aanvraag.chauffeurID = :chauffeurID AND klaar = '0';");
		$stmt->bindparam(":chauffeurID",$chauffeurID);
		$stmt->execute();
		$PS = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($stmt->rowCount() > 0) {
			return $PS;
		} else {
			return false;
		}
	}

	public function showAlert($message, $color = "success") 
	{
		return '<div class="container"><div class="row margin-bottom-25px"><div class="col-2"></div><div class="col-8"><div class="alert alert-'.$color.' alert-dismissible fade show">'.$message.'</div></div></div></div>';
	}

}