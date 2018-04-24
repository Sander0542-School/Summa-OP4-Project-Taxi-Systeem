<?php
require_once "classes/core.php";
$CORE = new CORE();
if ($CORE->is_logged_in())  {
  $stmt = $CORE->runQuery("SELECT * FROM klant WHERE gebruikersnaam=:gebruikersnaam");
  $stmt->execute(array(":gebruikersnaam"=>$_SESSION['userSession']));
  $U_DATA = $stmt->fetch(PDO::FETCH_ASSOC);
}

include "head/header.php";
?>