<?php
$pageTitle = "Profiel Chauffeur";
include_once "assets/head.php";

if ($CORE->is_logged_in()) {
  if ($U_DATA["chauffeurID"] != null) {
    if (isset($_POST["aanvraagID"])) {
      if (isset($_POST["accepteren"])) {
        $RESULT = $CORE->accepteerRit($_POST["aanvraagID"], $UC_DATA["id"]);
        switch ($RESULT) {
          case 0:
            echo $CORE->showAlert("U heeft de aanvraag geaccepteerd");
            break;
          case 1:
            echo $CORE->showAlert("Kon de aanvraag niet accepteren", "warning");
            break;
        }
      } else if (isset($_POST["weigeren"])) {
        $RESULT = $CORE->weigerRit($_POST["aanvraagID"], $UC_DATA["id"]);
        switch ($RESULT) {
          case 0:
            echo $CORE->showAlert("U heeft de aanvraag afgewezen");
            break;
          case 1:
            echo $CORE->showAlert("Kon de aanvraag niet afwijzen", "warning");
            break;
        }
      }
    }
    echo '
    <div class="container">
      <div class="row">
        <div class="col-40px"></div>
        <div class="col">
          <div class="row">
            <div class="col">
              <select class="custom-select" onchange="openProfiel(this)">
                <option value="1">Klant</option>
                <option value="2" selected>Chauffeur</option>
              </select>
            </div>
            <div class="col">
              <h4>Openstaande aanvragen</h4>
              <ul>';
            
  $aanvragen =  $CORE->getOpenRequests($UC_DATA["id"]);
  if ($aanvragen) {
    foreach ($aanvragen as $naam) {
      echo '<li onclick="loadData(\''.$naam["aanvraagID"].'\', \''.$naam["passagiers"].'\', \''.$naam["minimale_laadruimte"].'\', \''.$naam["mobiel"].'\', \''.$naam["datum"].'\', \''.$naam["tijd"].'\', \''.$naam["email"].'\',  \''.$naam["latitude"].'\',  \''.$naam["longitude"].'\');">'.$naam["naam"].'</li>';
    }
  } else {
    echo "<li>Er zijn geen aanvragen</li>";
  }

  echo '
              </ul>
            </div>
          </div>
        </div>
        <div class="col-40px"></div>
        <div class="col">
          <div id="googleMap" class="margin-bottom-25px" style="width:100%;height:350px;"></div>
          <form method="POST">
            <div class="row">
              <div class="col">
                <input id="aanvraagID" type="hidden" name="aanvraagID" value="0" required>
                <input id="passagiers" type="text" class="form-control" placeholder="Aantal Passagiers" disabled>
                <br/>
                <input id="laadruimte" type="tel" class="form-control" placeholder="Laadruimte" disabled>
                <br/>
                <input id="mobiel" type="tel" class="form-control" placeholder="Mobiel Nummer" disabled>
                <br/>
                <input type="submit" name="accepteren" id="accepteer" value="Accepteren!" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
              </div>
              <div class="col-40px"></div>
              <div class="col">
                <input id="datum" type="text" class="form-control" placeholder="Datum" disabled>
                <br/>
                <input id="tijd" type="tel" class="form-control" placeholder="Tijd" disabled>
                <br/>
                <input id="email" type="tel" class="form-control" placeholder="E-mailadress" disabled>
                <br/>
                
                <input type="submit" name="weigeren" id="weigeren" value="Weigeren!" class="btn btn-block btn-danger mt-auto margin-bottom-25px">
              </div>
            </div>
          </form>
        </div>
        <div class="col-40px"></div>
      </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhUaFv5qwATzUG_DlgxbNCH1wXBa-B-PQ&callback=googleMapsChauffeurProfiel"></script>';
  } else {
	  echo '
		<div class="container">
			<div class="row">
				<div class="col"></div>
				<div class="col-8">
					<h3>U moet een <a href="/chauffeur">chauffeur</a> zijn om deze pagina te bekijken</h3>
				</div>
				<div class="col"></div>
			</div>
		</div>';
  }
} else {
	echo '
		<div class="container">
			<div class="row">
				<div class="col"></div>
				<div class="col-8">
					<h3>U moet <a href="/inloggen">inloggen</a> om deze pagina te bekijken</h3>
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
          eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, f ringilla vel, aliquet nec, vulputate
          eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis
          pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean
          leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat
          a, tellus.
          <br>
          <br> Klik dan
          <a href="/chauffeur">hier</a>
        </p>
      </div>
    </div>
  </div>
  <?php
include_once "assets/footer.php";
?>