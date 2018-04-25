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
                <option selected>Maak een keuze</option>
                <option value="1">Klant</option>
                <option value="2">Chauffeur</option>
              </select>
            </div>
            <div class="col">
              <h4>Openstaande aanvragen</h4>
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
            </div>
            <div class="col-40px"></div>
            <div class="col">
              <input name="datum" type="text" class="form-control" placeholder="Datum" required>
              <br/>
              <input name="tijd" type="tel" class="form-control" placeholder="Tijd" required>
              <br/>
              <input name="email" type="tel" class="form-control" placeholder="E-mailadress" required>
              <br/>
            </div>
          </div>
        </div>
        <div class="col-40px"></div>
      </div>
    </div>

<?php
include_once "assets/footer.php";
?>