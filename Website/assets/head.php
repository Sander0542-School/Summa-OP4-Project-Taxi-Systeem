<?php
session_start();
require_once "classes/core.php";
$CORE = new CORE();
$U_DATA = $CORE->load_user_data();
if ($U_DATA["chauffeurID"] != null) {
  $UC_DATA = $CORE->load_chauffeur_data($U_DATA["chauffeurID"]);
}

include "head/header.php";
?>