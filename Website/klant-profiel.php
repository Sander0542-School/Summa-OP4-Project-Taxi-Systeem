<?php
$pageTitle = "Klant Profiel";
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
	<div id="googleMap" style="width:100%;height:400px;"></div>

			</div>
			<div class="col-40px"></div>
		</div>
	</div>

<script>
function myMap() {
var mapProp= {
    center:new google.maps.LatLng(51.508742,-0.120850),
    zoom:5,
};
var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhUaFv5qwATzUG_DlgxbNCH1wXBa-B-PQ&callback=myMap"></script>


<?php
include_once "assets/footer.php";
?>