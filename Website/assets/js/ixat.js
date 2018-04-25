function openProfiel(page){
  console.log(page.value);
  if (page.value == 1){
    window.location.href = "/klant-profiel";
  }
  else if (page.value == 2){
    window.location.href = "/chauffeur-profiel";
    console.log(2);
  }
}