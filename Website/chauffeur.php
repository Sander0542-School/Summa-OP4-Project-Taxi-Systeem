<?php
$pageTitle = "Chauffeurs";
include_once "assets/head.php";

if ($CORE->is_logged_in() && U_DATA["chauffeurID"] != null) {
  echo '
    <form method="POST">
      <div class="container">
        <div class="row">
          <div class="col"></div>
          <div class="col-9">
            <h1>Uw gegevens</h1><br/>
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
                <input type="submit" value="Update!" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
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
                <input type="submit" value="Registreer!" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
              </div>
            </div>
          </div>
          <div class="col"></div>
        </div>
      </div>
    </form>';
}
?>

    <div class="container bg-white">
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