<?php
$pageTitle = "Profiel";
include_once "assets/head.php";
?>

	<div class="row">
		<div class="col-1"></div>
		<div class="col-8">
			<select class="custom-select" onchange="openProfiel(this)">
				<option selected>Maak een keuze</option>
				<option value="1">Klant</option>
				<option value="2">Chauffeur</option>
			</select>
		</div>
	</div>


<?php
include_once "assets/footer.php";
?>