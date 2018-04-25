<?php
$pageTitle = "Klant Profiel";
include_once "assets/head.php";
?>

	<div class="container">
		<div class="row">
			<div class="col-40px"></div>
			<div class="col-5">
				<div class="row">
					<div class="col">
						<select class="custom-select" onchange="openProfiel(this)">
							<option value="1" selected>Klant</option>
							<option value="2">Chauffeur</option>
						</select>
					</div>
					<div class="col">
						<h3>Uw rithistorie</h3>
						<ul>
							<li>25/03/2018 Eindhoven</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-40px"></div>
			<div class="col">
				
			</div>
			<div class="col-40px"></div>
		</div>
	</div>

	<div id="footer" class="container bg-white">
		<div class="row">
			<div class="col-2"></div>
			<div class="col-8">
				<br/>
				<h1>Aanpassen van uw gegevens</h1>

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
