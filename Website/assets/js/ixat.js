var map;
var marker = null;

function openProfiel(page){
  if (page.value == 1){
    window.location.href = "/klant-profiel";
  }
  else if (page.value == 2){
    window.location.href = "/chauffeur-profiel";
  }
}

function googleMaps() {
  var mapProp = {
    center: new google.maps.LatLng(51.466448, 5.4964),
    zoom: 17,
    mapTypeControl: false,
    streetViewControl: false,
    rotateControl: false,
  };
  map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

  map.addListener('click', function(e) {
    placeMarker(e.latLng, map);
  });
function googleMapsChauffeurProfiel() {
  var mapProp = {
    center: new google.maps.LatLng(51.466448, 5.4964),
    zoom: 17,
    mapTypeControl: false,
    streetViewControl: false,
    rotateControl: false,
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
}

function mapsPanTo(lat, lng) {
  var latLng = new google.maps.LatLng(lat, lng);
  map.panTo(latLng);
}