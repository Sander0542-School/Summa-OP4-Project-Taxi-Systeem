var map;
var marker = null;
var klantProfielChoosed = false;

function openProfiel(page){
  if (page.value == 1){
    window.location.href = "/klant-profiel";
  }
  else if (page.value == 2){
    window.location.href = "/chauffeur-profiel";
  }
}

function googleMapsKlantProfiel() {
  var mapProp = {
    center: new google.maps.LatLng(51.466448, 5.4964),
    zoom: 17,
    mapTypeControl: false,
    streetViewControl: false,
    rotateControl: false
  };
  map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

  map.addListener('click', function(e) {
    placeMarker(e.latLng, map);
  });

  navigator.geolocation.getCurrentPosition(function(position){
    if (!klantProfielChoosed) {
      placeMarker(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), map);
    }
  });
}

function googleMapsChauffeurProfiel() {
  var mapProp = {
    center: new google.maps.LatLng(51.466448, 5.4964),
    zoom: 17,
    mapTypeControl: false,
    streetViewControl: false,
    rotateControl: false
  };
  map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

  navigator.geolocation.getCurrentPosition(function(position){
    document.getElementById("user-lat").value = position.coords.latitude;
    document.getElementById("user-lng").value = position.coords.longitude;
  });
}
 
function placeMarker(position, map) {
  if (marker != null) {
    marker.setMap(null);
  }
  marker = new google.maps.Marker({
    position: position,
    map: map
  });
  map.panTo(position);

  document.getElementById("user-lat").value = position.lat();
  document.getElementById("user-lng").value = position.lng();

  document.getElementById("submit-button").value = "Verzoek indienen!";
  document.getElementById("submit-button").disabled = false;

  klantProfielChoosed = true;
}

function mapsPanTo(lat, lng) {
  var latLng = new google.maps.LatLng(lat, lng);
  map.panTo(latLng);
}