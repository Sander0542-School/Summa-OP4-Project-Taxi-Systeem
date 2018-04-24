<?php
$pageTitle = "Klanten";
include_once "assets/head.php";
?>


<form method="POST">
    <div class="container">
    <div class="row">
      <div class="col"></div>
      <div class="col-9">
        <h1>Registreer je nu als klant!</h1><br/>
        <div class="row chauffeur">
          <div class="col">
            <input type="text" class="form-control" placeholder="Naam" required><br/>
            <input type="tel" class="form-control" placeholder="Mobiel Nummer" required><br/>
            <input type="email" class="form-control" placeholder="E-mailadres" required><br/>
          </div>
          <div class="col-40px"></div>
          <div class="col">
            <input type="text" class="form-control" placeholder="Gebruikersnaam" required><br/>
            <input type="password" class="form-control" placeholder="Wachtwoord" required><br/>
            <input type="password" class="form-control" placeholder="Herhaal Wachtwoord" required><br/>
            <input type="submit" value="Registreer!" class="btn btn-block btn-dark">
          </div>
          <div class="col-40px"></div>
          <div class="col">
            <img src="../image/kech.png" alt="kech">
          </div>
        </div>
      </div>
      <div class="col"></div>
    </div>
  </div>
  </form>
          


<?php
include_once "assets/footer.php";
?>