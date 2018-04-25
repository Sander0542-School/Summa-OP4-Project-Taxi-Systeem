function openProfiel(page){
  if (page.value == 1){
    window.location.href = "/klant-profiel";
  }
  else if (page.value == 2){
    window.location.href = "/chauffeur-profiel";
  }
}

function myMap() {
  var mapProp = {
    center: new google.maps.LatLng(51.508742, -0.120850),
    zoom: 5,
  };
  var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
}