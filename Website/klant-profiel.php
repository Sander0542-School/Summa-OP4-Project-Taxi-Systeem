<?php
$pageTitle = "Klant Profiel";
include_once "assets/head.php";

if ($CORE->is_logged_in() && isset($_POST["passagiers"]) && isset($_POST["laadruimte"]) && isset($_POST["lat"]) && isset($_POST["lng"])) {
	$taxiAanvraag = $CORE->requestRide($_POST["passagiers"],$_POST["laadruimte"],$_POST["lat"],$_POST["lng"]);
	switch ($taxiAanvraag) {
		case 0:
		echo $CORE->showAlert("Verzoek voor een taxi ingediend");
		break;
		case 1:
			echo $CORE->showAlert("Kon uw verzoek niet indienen", "warning");
			break;
	}
}

if ($CORE->is_logged_in()) {
	echo '
		<div class="container">
			<div class="row">
				<div class="col-40px"></div>
				<div class="col-5">
					<div class="row">
						<div class="col">
							<select class="custom-select" onchange="openProfiel(this)">
								<option value="1" selected>Klant</option>
								<option value="2">Chauffeur</option>
							</select>
						</div>
						<div class="col">
							<h3>Uw rithistorie</h3>
							<ul>'; 
							
	$ritten = $CORE->getRideHistory();

	if ($ritten) {
		foreach ($ritten as $rit) {
			$arrContextOptions=array(
					"ssl"=>array(
							"verify_peer"=>false,
							"verify_peer_name"=>false,
					),
			); 
			$placeAPI = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$rit["latitude"].",".$rit["longitude"]."&key=AIzaSyDhUaFv5qwATzUG_DlgxbNCH1wXBa-B-PQ", false, stream_context_create($arrContextOptions)), true);
			echo '<li>'.$rit["datum"].' '.$placeAPI["results"][0]["address_components"][3]["long_name"].'</li>';
		}
	} else {
		echo "<li>U heeft nog geen ritten</li>";
	}
	
	echo '
							</ul>
						</div>
					</div>
				</div>
				<div class="col-40px"></div>
				<div class="col">
					<div id="googleMap" class="margin-bottom-25px" style="width:100%;height:350px;"></div>
					<div class="row">
						<div class="col d-flex flex-column">
							<form method="POST">
								<input name="passagiers" type="text" class="form-control" placeholder="Aantal Passagiers" required>
								<br/>
								<input name="laadruimte" type="tel" class="form-control" placeholder="Laadruimte" required>
								<br/>
								<input type="tel" class="form-control" placeholder="Mobiel Nummer" value="'.$U_DATA["mobiel"].'"disabled required>
								<br/>
								<input type="hidden" name="lat" id="user-lat">
								<input type="hidden" name="lng" id="user-lng">
								<input type="submit" value="Wachten op locatie" id="submit-button" class="btn btn-block btn-dark mt-auto margin-bottom-25px" disabled>
							</form>
						</div>
						<div class="col-40px"></div>
						<div class="col">

						</div>
					</div>
				</div>
				<div class="col-40px"></div>
			</div>
		</div>';
} else {
	echo '
		<div class="container">
			<div class="row">
				<div class="col"></div>
				<div class="col-8">
					<h1>U moet <a href="/inloggen">inloggen</a> om deze pagina te bekijken</h1>
				</div>
				<div class="col"></div>
			</div>
		</div>';
}
?>


		<div id="footer" class="container bg-white">
			<div class="row">
				<div class="col-2"></div>
				<div class="col-8">
					<br/>
					<h1>Aanpassen van uw gegevens</h1>

					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis
						natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque
						eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, f ringilla vel, aliquet nec, vulputate eget,
						arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer
						tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor
						eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus.
					</p>
				</div>
			</div>
		</div>

		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhUaFv5qwATzUG_DlgxbNCH1wXBa-B-PQ&callback=googleMapsKlantProfiel"></script>

<?php
include_once "assets/footer.php";
?>