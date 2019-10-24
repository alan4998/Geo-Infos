$.getJSON("https://restcountries.eu/rest/v2/name/france", function(data) {
  console.log(data);

  var country = data[0].name;
  var capital = data[0].capital;
  var region = data[0].region;
  
  $(".region").append(region);

  $(".capital").append(capital);
  $(".country").append(country);
});
