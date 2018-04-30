<?php
session_start();
require_once "classes/core.php";
$CORE = new CORE();
if ($CORE->is_logged_in())  {
  $stmt = $CORE->runQuery("SELECT * FROM klant WHERE gebruikersnaam=:gebruikersnaam");
  $stmt->execute(array(":gebruikersnaam"=>$_SESSION['userSession']));
  $U_DATA = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($U_DATA["chauffeurID"] != null) {
    $stmt = $CORE->runQuery("SELECT * FROM chauffeur WHERE id=:cID");
    $stmt->execute(array(":cID"=>$U_DATA["chauffeurID"]));
    $UC_DATA = $stmt->fetch(PDO::FETCH_ASSOC);
  }
}

include "head/header.php";
?>