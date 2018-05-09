<?php
session_start();
require_once("assets/classes/core.php");
$CORE = new CORE();
$CORE->logout();
$CORE->redirect("home");
?>