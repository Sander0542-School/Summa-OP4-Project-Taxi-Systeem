var map;
var marker = null;
var klantProfielChoosed = false;

function openProfiel(page) {
  if (page.value == 1) {
    window.location.href = "/klant-profiel";
  } else if (page.value == 2) {
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

  map.addListener('click', function (e) {
    placeMarker(e.latLng, map, true);
  });

  navigator.geolocation.getCurrentPosition(function (position) {
    if (!klantProfielChoosed) {
      placeMarker(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), map, true);
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

  navigator.geolocation.getCurrentPosition(function (position) {
    document.getElementById("user-lat").value = position.coords.latitude;
    document.getElementById("user-lng").value = position.coords.longitude;
  });
}

function placeMarker(position, map, updateForm) {
  if (marker != null) {
    marker.setMap(null);
  }
  marker = new google.maps.Marker({
    position: position,
    map: map
  });
  map.panTo(position);

  if (updateForm) {
    document.getElementById("user-lat").value = position.lat();
    document.getElementById("user-lng").value = position.lng();
  
    document.getElementById("submit-button").value = "Verzoek indienen!";
    document.getElementById("submit-button").disabled = false;

    klantProfielChoosed = true;
  }
}

function mapsPanTo(lat, lng) {
  var latLng = new google.maps.LatLng(lat, lng);
  map.panTo(latLng);
}

function loadData(aanvraagID, passagiers, laadruimte, mobile, date, time, email, latitude, longitude) {
  document.getElementById("aanvraagID").value = aanvraagID;
  document.getElementById("passagiers").value = passagiers;
  document.getElementById("laadruimte").value = laadruimte;
  document.getElementById("mobiel").value = mobile;
  document.getElementById("datum").value = date;
  document.getElementById("tijd").value = time;
  document.getElementById("email").value = email;
  mapsPanTo(latitude, longitude);
  placeMarker(new google.maps.LatLng(latitude, longitude), map, false);
}