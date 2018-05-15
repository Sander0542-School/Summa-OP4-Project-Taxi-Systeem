<?php
$pageTitle = "Chauffeurs";
include_once "assets/head.php";

if (isset($_POST["type"])) {
  $type = $_POST["type"];
  if ($type == "newCustomer") {
    if (isset($_POST["naam"]) && isset($_POST["mobiel"]) && isset($_POST["email"]) && isset($_POST["automerk"]) && isset($_POST["autotype"]) && isset($_POST["kenteken"]) && isset($_POST["passagiers"]) && isset($_POST["laadruimte"]) && isset($_POST["schadevrij"]) && isset($_POST["gebruikersnaam"]) && isset($_POST["wachtwoord"]) && isset($_POST["wachtwoord2"])) {
      $result = $CORE->registerDriver($_POST["gebruikersnaam"],$_POST["wachtwoord"],$_POST["wachtwoord2"],$_POST["naam"],$_POST["mobiel"],$_POST["email"],$_POST["automerk"],$_POST["autotype"],$_POST["kenteken"],$_POST["passagiers"],$_POST["laadruimte"],$_POST["schadevrij"]);
      switch ($result) {
        case 0:
          echo $CORE->showAlert("Account gemaakt en uw aanvraag voor chauffeur is ingediend");
          break;
        case 1:
          echo $CORE->showAlert("Kon uw account niet maken", "danger");
          break;
        case 2:
          echo $CORE->showAlert("Account gemaakt, maar er kon geen aanvraag voor chauffeur worden ingediend", "warning");
          break;
        case 3:
          echo $CORE->showAlert("Uw wachtwoorden komen niet overeen", "warning");
          break;
        case 4:
          echo $CORE->showAlert("Aanvraag voor chauffeur ingediend");
          break;
      }
    }
  } elseif ($type == "newDriver" && $CORE->is_logged_in()) {
    if (isset($_POST["automerk"]) && isset($_POST["autotype"]) && isset($_POST["kenteken"]) && isset($_POST["passagiers"]) && isset($_POST["laadruimte"]) && isset($_POST["schadevrij"])) {
      $result = $CORE->requestDriver($U_DATA["id"], $_POST["automerk"],$_POST["autotype"],$_POST["kenteken"],$_POST["passagiers"],$_POST["laadruimte"],$_POST["schadevrij"]);
      switch ($result) {
        case 0:
          echo $CORE->showAlert("Aanvraag voor chauffeur ingediend");
          break;
        case 1:
          echo $CORE->showAlert("Uw aanvraag kon niet worden ingediend", "danger");
          break;
      }
    }
  } elseif ($type == "updateDriver" && $CORE->is_logged_in()) {
    if (isset($_POST["automerk"]) && isset($_POST["autotype"]) && isset($_POST["kenteken"]) && isset($_POST["passagiers"]) && isset($_POST["laadruimte"]) && isset($_POST["schadevrij"])) {
      $result = $CORE->updateChauffeur($UC_DATA["id"], $_POST["automerk"],$_POST["autotype"],$_POST["kenteken"],$_POST["passagiers"],$_POST["laadruimte"],$_POST["schadevrij"]);
      switch ($result) {
        case 0:
          echo $CORE->showAlert("Uw gegevens zijn bijgewerkt");
          break;
        case 1:
          echo $CORE->showAlert("Uw gegevens konden niet worden bijgewerkt", "danger");
          break;
      }
    }
  }
}

if ($CORE->is_logged_in() && $U_DATA["chauffeurID"] != null) {
  echo '
    <form method="POST">
      <input type="hidden" name="type" value="updateDriver" required>
      <div class="container">
        <div class="row">
          <div class="col"></div>
          <div class="col-9">
            <h1>Uw gegevens</h1><br/>
            <div class="row chauffeur">
              <div class="col">
                <input name="naam" type="text" class="form-control" value="'.$U_DATA["naam"].'" required readonly><br/>
                <input name="mobiel" type="tel" class="form-control" value="'.$U_DATA["mobiel"].'" required readonly><br/>
                <input name="email" type="email" class="form-control" value="'.$U_DATA["email"].'" required readonly><br/>
              </div>
              <div class="col-40px"></div>
              <div class="col">
                <input name="automerk" type="text" class="form-control" value="'.$UC_DATA["automerk"].'" required><br/>
                <input name="autotype" type="text" class="form-control" value="'.$UC_DATA["autotype"].'" required><br/>
                <input name="kenteken" type="text" class="form-control" value="'.$UC_DATA["kenteken"].'" required><br/>
                <input name="passagiers" type="number" class="form-control" value="'.$UC_DATA["aantal_passagiers"].'" required><br/>
                <input name="laadruimte" type="number" class="form-control" value="'.$UC_DATA["laadruimte"].'" required><br/>
                <input name="schadevrij" type="number" class="form-control" value="'.$UC_DATA["schadevrije_jaren"].'" required><br/>
              </div>
              <div class="col-40px"></div>
              <div class="col d-flex flex-column">
                <input name="gebruikersnaam" type="hidden" value="'.$U_DATA["gebruikersnaam"].'" required><br/>
                <input name="wachtwoord" type="hidden" value="1" required><br/>
                <input name="wachtwoord2" type="hidden" value="1" required><br/>
                <input type="submit" value="Update!" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
              </div>
            </div>
          </div>
          <div class="col"></div>
        </div>
      </div>
    </form>';
} elseif ($CORE->is_logged_in()) {
  if ($CORE->hasRequest() && !isset($_POST["naam"])) {
    echo $CORE->showAlert("U heeft een al aan aanvraag voor chauffeur open staan");
  }
  echo '
    <form method="POST">
      <input type="hidden" name="type" value="newDriver" required>
      <div class="container">
        <div class="row">
          <div class="col"></div>
          <div class="col-9">
            <h1>Meld je nu aan!</h1><br/>
            <div class="row chauffeur">
              <div class="col">
                <input name="naam" type="text" class="form-control" value="'.$U_DATA["naam"].'" required readonly><br/>
                <input name="mobiel" type="tel" class="form-control" value="'.$U_DATA["mobiel"].'" required readonly><br/>
                <input name="email" type="email" class="form-control" value="'.$U_DATA["email"].'" required readonly><br/>
              </div>
              <div class="col-40px"></div>
              <div class="col">
                <input name="automerk" type="text" class="form-control" placeholder="Merk Auto" required><br/>
                <input name="autotype" type="text" class="form-control" placeholder="Type Auto" required><br/>
                <input name="kenteken" type="text" class="form-control" placeholder="Kenteken" required><br/>
                <input name="passagiers" type="number" class="form-control" placeholder="Aantal Passagiers" required><br/>
                <input name="laadruimte" type="number" class="form-control" placeholder="Laadruimte (in liters)" required><br/>
                <input name="schadevrij" type="number" class="form-control" placeholder="Schadevrije jaren" required><br/>
              </div>
              <div class="col-40px"></div>
              <div class="col d-flex flex-column">
                <input name="gebruikersnaam" type="text" class="form-control" value="'.$U_DATA["gebruikersnaam"].'" required readonly><br/>
                <input name="wachtwoord" type="password" class="form-control" value="Wachtwoord" required readonly><br/>
                <input name="wachtwoord2" type="password" class="form-control" value="Wachtwoord" required readonly><br/>
                <input type="submit" value="Registreer!" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
              </div>
            </div>
          </div>
          <div class="col"></div>
        </div>
      </div>
    </form>';
} else {
  echo '
    <form method="POST">
      <input type="hidden" name="type" value="newCustomer" required>
      <div class="container">
        <div class="row">
          <div class="col"></div>
          <div class="col-9">
            <h1>Meld je nu aan!</h1><br/>
            <div class="row chauffeur">
              <div class="col">
                <input name="naam" type="text" class="form-control" placeholder="Naam" required><br/>
                <input name="mobiel" type="tel" class="form-control" placeholder="Mobiel Nummer" required><br/>
                <input name="email" type="email" class="form-control" placeholder="E-mailadres" required><br/>
              </div>
              <div class="col-40px"></div>
              <div class="col">
                <input name="automerk" type="text" class="form-control" placeholder="Merk Auto" required><br/>
                <input name="autotype" type="text" class="form-control" placeholder="Type Auto" required><br/>
                <input name="kenteken" type="text" class="form-control" placeholder="Kenteken" required><br/>
                <input name="passagiers" type="number" class="form-control" placeholder="Aantal Passagiers" required><br/>
                <input name="laadruimte" type="number" class="form-control" placeholder="Laadruimte (in liters)" required><br/>
                <input name="schadevrij" type="number" class="form-control" placeholder="Schadevrije jaren" required><br/>
              </div>
              <div class="col-40px"></div>
              <div class="col d-flex flex-column">
                <input name="gebruikersnaam" type="text" class="form-control" placeholder="Gebruikersnaam" required><br/>
                <input name="wachtwoord" type="password" class="form-control" placeholder="Wachtwoord" required><br/>
                <input name="wachtwoord2" type="password" class="form-control" placeholder="Herhaal Wachtwoord" required><br/>
                <input type="submit" value="Registreer!" class="btn btn-block btn-dark mt-auto margin-bottom-25px"/>
              </div>
            </div>
          </div>
          <div class="col"></div>
        </div>
      </div>
    </form>';
}
?>

    <div id="footer" class="container bg-white">
      <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
          <br/>
          <h1>Chauffeursreglement</h1>

          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis
            natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque
            eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, f ringilla vel, aliquet nec, vulputate
            eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
            Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula,
            porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus.
          </p>
        </div>
      </div>
    </div>

<?php
include_once "assets/footer.php";
?>