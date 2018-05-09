<?php
$pageTitle = "Profiel Chauffeur";
include_once "assets/head.php";
?>

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
<?php
$aanvragen =  $CORE->getOpenRequests();
if ($aanvragen) {
  foreach ($aanvragen as $naam) {
    echo "<li>".$naam["naam"]."</li> <br>";
  }
} else {
  echo "Er zijn geen aanvragen";
}

?>
          </div>
        </div>
      </div>
      <div class="col-40px"></div>
      <div class="col">
        <div id="googleMap" class="margin-bottom-25px" style="width:100%;height:350px;"></div>
        <div class="row">
          <div class="col">
            <input name="passagiers" type="text" class="form-control" placeholder="Aantal Passagiers" required>
            <br/>
            <input name="laadruimte" type="tel" class="form-control" placeholder="Laadruimte" required>
            <br/>
            <input name="mobiel" type="tel" class="form-control" placeholder="Mobiel Nummer" required>
            <br/>
            <input type="submit" value="Accepteren!" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
          </div>
          <div class="col-40px"></div>
          <div class="col">
            <input name="datum" type="text" class="form-control" placeholder="Datum" required>
            <br/>
            <input name="tijd" type="tel" class="form-control" placeholder="Tijd" required>
            <br/>
            <input name="email" type="tel" class="form-control" placeholder="E-mailadress" required>
            <br/>
              <input type="submit" value="Weigeren!" class="btn btn-block btn-danger mt-auto margin-bottom-25px">
          </div>
        </div>
      </div>
      <div class="col-40px"></div>
    </div>

  </div>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhUaFv5qwATzUG_DlgxbNCH1wXBa-B-PQ&callback=googleMapsChauffeurProfiel"></script>
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