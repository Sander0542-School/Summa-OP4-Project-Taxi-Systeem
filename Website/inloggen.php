<?php
$pageTitle = "Inloggen";
include_once "assets/head.php";

if (isset($_POST["username"]) && isset($_POST["password"])) {
  if ($CORE->login($_POST["username"], $_POST["password"])) {
    echo $CORE->showAlert("U bent successvol ingelogd");
    $CORE->redirect("klant-profiel");
  } else {
    echo $CORE->showAlert("De combinatie van uw gebruikersnaam en wachtwoord komen niet overeen", "danger");
  }
}
?>

    <div class="container">
      <div class="row">
        <div class="col"></div>
        <div class="col-3 margin-top-100px">
          <form method="POST">
            <input name="username" type="text" class="form-control" placeholder="Gebruikersnaam" required><br/>
            <input name="password" type="password" class="form-control" placeholder="Wachtwoord" required><br/>
            <input type="submit" value="Login" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
          </form>
        </div>
        <div class="col"></div>
      </div>
    </div>

<?php
include_once "assets/footer.php";
?>