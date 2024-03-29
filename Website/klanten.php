<?php
$pageTitle = "Klanten";
include_once "assets/head.php";

if ($CORE->is_logged_in()) {
  if (isset($_POST["naam"]) && isset($_POST["mobiel"]) && isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"])) {
    $RESULT = $CORE->updateKlant($_POST["naam"],$_POST["email"],$_POST["mobiel"],$_POST["password"]);
    switch ($RESULT) {
      case 0:
        echo $CORE->showAlert("U heeft uw gegevens succesvol geupdate");
        break;
      case 1:
        echo $CORE->showAlert("Kon gegevens niet updaten", "warning");
        break;
      case 2:
        echo $CORE->showAlert("U moet inloggen om uw klant gegevens te kunnen updaten", "warning");
        break;
    }
    $U_DATA = $CORE->load_user_data();
  }
  echo '
    <form method="POST">
      <div class="container">
        <div class="row">
          <div class="col"></div>
          <div class="col-9">
            <h1>Wijzig uw gegevens!</h1>
            <br/>
            <div class="row chauffeur">
              <div class="col">
                <input name="naam" type="text" class="form-control" placeholder="Naam" value="'.$U_DATA["naam"].'" required><br/>
                <input name="mobiel" type="tel" class="form-control" placeholder="Mobiel Nummer" value="'.$U_DATA["mobiel"].'" required><br/>
                <input name="email" type="email" class="form-control" placeholder="E-mailadres" value="'.$U_DATA["email"].'" required><br/>
              </div>
              <div class="col-40px"></div>
              <div class="col d-flex flex-column">
                <input name="username" type="text" class="form-control" value="'.$U_DATA["gebruikersnaam"].'" placeholder="Gebruikersnaam" required readonly><br/>
                <input name="password" type="password" class="form-control" placeholder="Huidig Wachtwoord" required><br/>
                <input type="submit" value="Update!" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
              </div>
              <div class="col-20px"></div>
              <div class="col-5">
                <img src="/image/kech.png" class="margin-bottom-50px" alt="kech">
              </div>
            </div>
          </div>
          <div class="col"></div>
        </div>
      </div>
    </form>';
} else {
  if (isset($_POST["name"]) && isset($_POST["mobile"]) && isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["passwordRepeat"])) {
    $RESULT = $CORE->registerCustomer($_POST["username"],$_POST["password"],$_POST["passwordRepeat"],$_POST["name"],$_POST["mobile"], $_POST["email"]);
    switch ($RESULT) {
      case 0:
        echo $CORE->showAlert("Account aangemaakt. U kunt nu inloggen");
        break;
      case 1:
        echo $CORE->showAlert("Kon account niet aanmmaken", "warning");
        break;
    }
  }

  echo '
    <form method="POST">
      <div class="container">
        <div class="row">
          <div class="col"></div>
          <div class="col-9">
            <h1>Registreer je nu als klant!</h1>
            <br/>
            <div class="row chauffeur">
              <div class="col">
              <input name="name" type="text" class="form-control" placeholder="Naam" required><br/>
              <input name="mobile" type="tel" class="form-control" placeholder="Mobiel Nummer" required><br/>
              <input name="email" type="email" class="form-control" placeholder="E-mailadres" required><br/>
              </div>
              <div class="col-40px"></div>
              <div class="col d-flex flex-column">
                <input name="username" type="text" class="form-control" placeholder="Gebruikersnaam" required><br/>
                <input name="password" type="password" class="form-control" placeholder="Wachtwoord"><br/>
                <input name="passwordRepeat" type="password" class="form-control" placeholder="Herhaal Wachtwoord"><br/>
                <input type="submit" value="Registreer!" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
              </div>
              <div class="col-20px"></div>
              <div class="col-5">
                <img src="/image/kech.png" class="margin-bottom-50px" alt="kech">
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
          <h1>Voordelen van een klant worden</h1>

          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis
            natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque
            eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, f ringilla vel, aliquet nec, vulputate
            eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis
            pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean
            leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat
            a, tellus. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean
            leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat
            a, tellus. porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat
            a, tellus.
          </p>
        </div>
      </div>
    </div>

<?php
include_once "assets/footer.php";
?>