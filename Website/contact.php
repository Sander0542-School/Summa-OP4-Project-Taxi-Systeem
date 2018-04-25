<?php
$pageTitle = "Contact";
include_once "assets/head.php";
?>
<div class="container">
  <div class="row">
    <div class="col"></div>
    <div class="col-6">
      <form>
        <div class="form-group">
          <label for="exampleFormControlInput1">Naam</label>
          <input type="text" class="form-control" placeholder="Naam"><br/>
          <label for="exampleFormControlInput1">Telefoon</label>
          <input type="tel" class="form-control" placeholder="Telefoon"><br/>
          <label for="exampleFormControlInput1">Emailaddress</label>
          <input type="email" class="form-control" placeholder="E-mailadress">
        </div>
        <div class="form-group">
          <label for="message">Bericht</label>
          <textarea class="form-control" rows="3" placeholder="Bericht"></textarea>
        </div>
        <input type="submit" value="Verzenden" class="btn btn-block btn-dark mt-auto margin-bottom-25px">
      </form>
    </div>
    <div class="col"></div>
  </div>
</div>

<?php
include_once "assets/footer.php";
?>