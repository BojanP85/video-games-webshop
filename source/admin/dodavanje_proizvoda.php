<!DOCTYPE html>
<html>
  <head>
    <title>Dodavanje proizvoda</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <link rel="stylesheet" href="../stilovi/bootstrap.css">
    <link rel="stylesheet" href="../stilovi/stilovi.css">
  </head>
  <body onload="listing()">
    <?php
      require_once("../baza/konekcija.php");
      if(!korisnik_prijavljen()) {
        prijava_greska();
      }
    ?>
    <div class="container-fluid">
      <div id="ispisGreske" style="background-color: red; color: white"></div>
      <h2 class="text-center">Dodaj proizvod</h2><hr>
      <form enctype="multipart/form-data">
        <!-- Unos naziva -->
        <div class="form-group col-md-3">
          <label for="naziv">Naziv*:</label>
          <input type="text" id="naziv" class="form-control praznoPolje" name="naziv">
        </div>

        <!-- Odabir žanra -->
        <div class="form-group col-md-3">
          <label for="zanr">Žanr*:</label>
          <select id="zanr_listanje" class="form-control" name="zanr"></select>
        </div>

        <!-- Odabir top-kategorije -->
        <div class="form-group col-md-3">
          <label for="top_kategorija">Top-kategorija*:</label>
          <select id="tk_listanje" class="form-control" name="top_kategorija"></select>
        </div>

        <!-- Odabir pod-kategorije -->
        <div class="form-group col-md-3">
          <label for="pod_kategorija">Pod-kategorija*:</label>
          <select id="pk_listanje" class="form-control" name="pod_kategorija"></select>
        </div>

        <!-- Unos cene -->
        <div class="forum-group col-md-3">
          <label for="cena">Cena*:</label>
          <input type="number" id="cena" class="form-control praznoPolje" name="cena">
        </div>

        <!-- Unos stare cene -->
        <div class="form-group col-md-3">
          <label for="stara_cena">Stara cena:</label>
          <input type="number" id="stara_cena" class="form-control praznoPolje" name="stara_cena">
        </div>

        <!-- Unos opisa -->
        <div class="form-group col-md-6">
          <label for="opis">Opis:</label>
          <textarea type="text" id="opis" class="form-control praznoPolje" name="opis" rows="6"></textarea>
        </div>
      </form>

      <!-- Dugmići za dodavanje i odustajanje -->
      <div class="col-md-1 pull-right" style="margin-top: 20px">
        <input type="button" id="dgmDodaj" class="form-control btn btn-success" value="Dodaj">
        <a href="proizvodi.php" style="margin-top: 5px" class="form-control btn btn-default">Odustani</a>
      </div>
    </div>
    <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
    <script type="text/javascript">
      // funkcija koja prilikom otvaranja stranice izlistava žanrove i top-kategorije
      function listing() {
        $('#zanr_listanje').append('<option value="-"></option>');
        $.getJSON("../baza/upiti.php", {
          upit: "zanr_listing"
        }, function(podaci) {
          $.each(podaci, function(kljuc, vrednost) {
            $('#zanr_listanje').append('<option value="'+vrednost.id+'">'+vrednost.zanr+'</option>');
          });
        });

        $('#tk_listanje').append('<option value="-"></option>');
        $.getJSON("../baza/upiti.php", {
          upit: "tk_listing"
        }, function(podaci) {
          $.each(podaci, function(kljuc, vrednost) {
            $('#tk_listanje').append('<option value="'+vrednost.id+'">'+vrednost.kategorija+'</option>');
          });
        });
      }

      // funkcija koja zavisno od odabrane top-kategorije izlistava odgovarajuće pod-kategorije
      $('#tk_listanje').change(function() {
        $('#pk_listanje').empty();
        if($('#tk_listanje').val() != "-") {
          $.getJSON("../baza/upiti.php", {
            upit: "pk_listing",
            id: $('#tk_listanje').val()
          }, function(podaci) {
            $.each(podaci, function(kljuc, vrednost) {
              $('#pk_listanje').append('<option value="'+vrednost.id+'">'+vrednost.kategorija+'</option>');
            });
          });
        }
      });

      // funkcija koja klikom na dugme (id = "dgmDodaj") dodaje proizvod u bazu
      $('#dgmDodaj').click(function() {
        if($('#naziv').val() == "" || $('#zanr_listanje').val() == "" || $('#pk_listanje').val() == "" || $('#cena').val() == "") {
          $('#ispisGreske').html("* Obavezno polje.");
        } else {
            $('#ispisGreske').empty();
            $.getJSON("../baza/upiti.php", {
              upit: "proizvod_dodavanje",
              naziv: $('#naziv').val(),
              cena: $('#cena').val(),
              stara_cena: ($('#stara_cena').val() == null ? "" : $('#stara_cena').val()),
              zanr: $('#zanr_listanje').val(),
              kategorija: $('#pk_listanje').val(),
              opis: $('#opis').val()
            }, function(povratnaPoruka) {
                if(povratnaPoruka.poruka == "ok") {
                  alert("Uspešno dodat nov proizvod.");
                  $('#zanr_listanje').empty();
                  $('#tk_listanje').empty();
                  $('#pk_listanje').empty();
                  $('.praznoPolje').val("");
                  listing();
                } else if(povratnaPoruka.poruka == "duplikat") {
                  $('#ispisGreske').html("Proizvod '" +$('#naziv').val()+ "' već postoji u bazi.");
                }
            });
          }
      });
    </script>
  </body>
</html>
