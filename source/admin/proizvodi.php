<!DOCTYPE html>
<html>
  <head>
    <title>Proizvodi</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="../javascript/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../stilovi/bootstrap.css">
    <link rel="stylesheet" href="../stilovi/stilovi.css">
  </head>
  <body>
    <?php
      require_once("../baza/konekcija.php");
      if(!korisnik_prijavljen()) {
        prijava_greska();
      }
      $proizvod_rezultat = mysqli_query($link, "SELECT * FROM proizvodi ORDER BY proizvodi.naziv ASC");
    ?>
    <nav class="navbar navbar-default navbar-fixed-top" style="background: linear-gradient(to bottom, #003399 0%, #009933 100%)">
      <div class="container">
        <a href="index.php" class="navbar-brand" style="color: white">GameShop Admin</a>
        <ul class="nav navbar-nav">
          <li><a href="zanrovi.php" style="color: white">Žanrovi</a></li>
          <li><a href="kategorije.php" style="color: white">Kategorije</a></li>
          <li><a href="proizvodi.php" style="color: white">Proizvodi</a></li>
          <?php if(ovlascenje("admin")) { ?>
            <li><a href="korisnici.php" style="color: white">Korisnici</a></li>
          <?php } ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: white"><?php echo $korisnik_podaci["ime"]; ?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="promena_lozinke.php">Promeni lozinku</a></li>
              <li><a href="odjava.php">Izloguj se</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container-fluid">
      <h2 class="text-center">Proizvodi</h2><hr>
      <a href="dodavanje_proizvoda.php" id="dodaj_proizvod" class="btn btn-success">Dodaj proizvod</a>
      <br><br><br>
      <table class="table table-bordered table-condensed table-hover">
        <thead>
          <tr>
            <th style="text-align: center; width: 250px">Naziv</th>
            <th style="text-align: center; width: 150px">Žanr</th>
            <th style="text-align: center; width: 150px">Kategorija</th>
            <th style="text-align: center; width: 600px;">Opis</th>
            <th style="text-align: center; width: 90px">Slika</th>
            <th style="text-align: center; width: 230px">Izdvajamo</th>
            <th style="text-align: center; width: 90px">Stara cena</th>
            <th style="text-align: center; width: 90px">Cena</th>
            <td colspan="2" style="text-align: center; width: 50px">Izmeni / Obriši</td>
          </tr>
        </thead>
        <tbody>
          <?php
            /* tk - top-kategorija (Playstation, XBOX, Nintendo, PC)
               pk - pod-kategorija (Igre, Konzole, Dodatna oprema, Akcije) */
            while($proizvod_red = mysqli_fetch_assoc($proizvod_rezultat)) {

            // kod za dinamičko ispisivanje kategorije
            $pk_id = $proizvod_red["kategorije"];
            $pk_rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE kategorije.id = $pk_id");
            $pod_kategorija = mysqli_fetch_assoc($pk_rezultat);
            $tk_id = $pod_kategorija["top_kategorija"];
            $tk_rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE kategorije.id = $tk_id");
            $top_kategorija = mysqli_fetch_assoc($tk_rezultat);
            $ispis_kategorije = $top_kategorija["kategorija"]." - ".$pod_kategorija["kategorija"];

            // kod za dinamičko ispisivanje žanra
            $zanr_id = $proizvod_red["zanr"];
            $zanr_rezultat = mysqli_query($link, "SELECT * FROM zanr WHERE zanr.id = $zanr_id");
            $zanr = mysqli_fetch_assoc($zanr_rezultat);

            // kod za promenu vrednosti kolone "izdvojeno" u tabeli "proizvodi"
            if(isset($_GET["izdvojeno"])) { // proveravamo da li je kliknuto na dugme za promenu (indeks "izdvojeno" dobijamo iz url-a "proizvodi.php?izdvojeno=")
              $id = $_GET["id"];
              $izdvojeno = $_GET["izdvojeno"];
              $izdvojeno_rezultat = mysqli_query($link, "UPDATE proizvodi SET proizvodi.izdvojeno = $izdvojeno WHERE proizvodi.id = $id");
              header('Location: proizvodi.php');
            }
          ?>
          <tr>
            <td style="vertical-align: middle"><?php echo $proizvod_red["naziv"]; ?></td>
            <td style="vertical-align: middle"><?php echo $zanr["zanr"]; ?></td>
            <td style="vertical-align: middle"><?php echo $ispis_kategorije; ?></td>
            <td style="vertical-align: middle"><div class="poljeOpis"><?php echo $proizvod_red["opis"]; ?></div></td>
            <td><input type="button" onclick="dlgSlika(<?php echo $proizvod_red["id"]; ?>)" class="form-control btn btn-success" value="Prikaži"></td>
            <td style="vertical-align: middle">
              <a href="proizvodi.php?izdvojeno=<?= (($proizvod_red['izdvojeno'] == 0)?'1':'0'); ?>&id=<?= $proizvod_red['id']; ?>" class="btn btn-xs btn-default">
                <span class="glyphicon glyphicon-<?= (($proizvod_red['izdvojeno'] == 1)?'minus':'plus'); ?>"></span>
              </a>&nbsp; <?php if($proizvod_red["izdvojeno"] == 1) {echo "Ukloniti iz izdvojenih proizvoda";} else {echo "Dodati u izdvojene proizvode";} ?>
            </td>
            <td style="text-align: right; vertical-align: middle"><?php echo $proizvod_red["stara_cena"]; ?></td>
            <td style="text-align: right; vertical-align: middle"><?php echo $proizvod_red["cena"]; ?></td>
            <td style="text-align: center; vertical-align: middle"><a href="#" onclick="dlgIzmena(<?php echo $proizvod_red["id"]; ?>)" class="btn btn-xs btn-default">&#128393;</a></td>
            <td style="text-align: center; vertical-align: middle"><a href="#" onclick="brisanjeProizvoda(<?php echo $proizvod_red["id"]; ?>)" class="btn btn-xs btn-default">&#128465;</a></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Dijalog za izmenu -->
    <div class="modal fade details-1" id="detalji" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg"  style="width: 60%">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" onclick="zatvaranje()" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title text-center">Izmeni proizvod</h4>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div id="ispisGreske" style="background-color: red; color: white"></div><br>
              <form enctype="multipart/form-data">
                <!-- Skriveni ID -->
                <input type="hidden" id="skriveniID">

                <!-- Izmena naziva -->
                <div class="form-group col-md-3">
                  <label for="naziv">Naziv*:</label>
                  <input type="text" id="naziv" class="form-control praznoPolje" name="naziv">
                </div>

                <!-- Izmena žanra -->
                <div class="form-group col-md-3">
                  <label for="zanr">Žanr*:</label>
                  <select id="zanr_listanje" class="form-control" name="zanr"></select>
                </div>

                <!-- Izmena top-kategorije -->
                <div class="form-group col-md-3">
                  <label for="top_kategorija">Top-kategorija*:</label>
                  <select id="tk_listanje" class="form-control" name="top_kategorija"></select>
                </div>

                <!-- Izmena pod-kategorije -->
                <div class="form-group col-md-3">
                  <label for="pod_kategorija">Pod-kategorija*:</label>
                  <select id="pk_listanje" class="form-control" name="pod_kategorija"></select>
                </div>

                <!-- Izmena cene -->
                <div class="forum-group col-md-3">
                  <label for="cena">Cena*:</label>
                  <input type="number" id="cena" class="form-control praznoPolje" name="cena">
                </div>

                <!-- Izmena stare cene -->
                <div class="form-group col-md-3">
                  <label for="stara_cena">Stara cena:</label>
                  <input type="number" id="stara_cena" class="form-control praznoPolje" name="stara_cena">
                </div>

                <!-- Izmena opisa -->
                <div class="form-group col-md-6">
                  <label for="opis">Opis:</label>
                  <textarea type="text" id="opis" class="form-control praznoPolje" name="opis" rows="6"></textarea>
                </div>
              </form>
            </div>
          </div>

          <!-- Dugmići za izmenu i odustajanje -->
          <div class="modal-footer">
            <input type="button" id="dgmIzmeni" class="form-control btn btn-success" value="Izmeni" style="width: 120px">
            <a href="#" onclick="zatvaranje()" style="width: 120px" class="form-control btn btn-default">Odustani</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Dijalog za prikaz slike -->
    <div class="modal fade details-1" id="slikaProizvoda" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg" style="width: 40%">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" onclick="zatvaranjeDlgSlike()" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title text-center">Slika proizvoda</h4>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
                <div id="album"></div>
            </div>
          </div>

          <!-- Dugmići za izmenu i odustajanje -->
          <div class="modal-footer">
            <a href="galerija.php"><input type="button" class="form-control btn btn-success" value="Dodaj / Izmeni" style="width: 120px"></a>
            <a href="#" onclick="zatvaranjeDlgSlike()" style="width: 120px" class="form-control btn btn-default">Odustani</a>
          </div>
        </div>
      </div>
    </div>
    <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
    <script type="text/javascript">
      $(document).ready(function() {
        listing();
      });

      // funkcija koja izlistava žanrove i top-kategorije (unutar dijaloga za izmenu)
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

      // funkcija koja zavisno od odabrane top-kategorije izlistava odgovarajuće pod-kategorije (unutar dijaloga za izmenu)
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

      // funkcija za pozivanje dijaloga za izmenu
      function dlgIzmena(idProizvoda) {
        $('#ispisGreske').empty();
        $("#detalji").modal("toggle");
        $.getJSON("../baza/upiti.php", {
          upit: "proizvod_listingID",
          id: idProizvoda
        }, function(podaci) {
          $('#skriveniID').val(podaci[0].id);
          $('#naziv').val(podaci[0].naziv);
          $('#cena').val(podaci[0].cena);
          $('#stara_cena').val(podaci[0].stara_cena);
          $('#zanr_listanje').val(podaci[0].zanr);
          $('#opis').val(podaci[0].opis);
        });
      }

      // funkcija za izmenu podataka o proizvodu
      $('#dgmIzmeni').click(function() {
        if($('#naziv').val() == "" || $('#zanr_listanje').val() == "-" || $('#tk_listanje').val() == "-" || $('#cena').val() == "") {
          $('#ispisGreske').html("* Obavezno polje.");
        } else {
            $('#ispisGreske').empty();
            $.getJSON("../baza/upiti.php", {
              upit: "proizvod_izmena",
              id: $('#skriveniID').val(),
              naziv: $('#naziv').val(),
              cena: $('#cena').val(),
              stara_cena: ($('#stara_cena').val() == null ? "" : $('#stara_cena').val()),
              zanr: $('#zanr_listanje').val(),
              kategorija: $('#pk_listanje').val(),
              opis: $('#opis').val()
            }, function(povratnaPoruka) {
              if(povratnaPoruka.poruka == "ok") {
                alert("Podaci su uspešno promenjeni.");
                location.reload();
              } else if(povratnaPoruka.poruka == "duplikat") {
                  $('#ispisGreske').html("Proizvod '" +$('#naziv').val()+ "' već postoji u bazi.");
              }
            });
          }
      });

      // funkcija za zatvaranje dijaloga za izmenu
      function zatvaranje() {
        $('#zanr_listanje').empty();
        $('#pk_listanje').empty();
        $('#tk_listanje').empty();
        listing();
        $("#detalji").modal("hide");
        $('#tk_listanje').val("");
      }

      // funkcija za brisanje proizvoda
      function brisanjeProizvoda(idProizvoda) {
        if(confirm("Da li ste sigurni?")) {
          $.getJSON("../baza/upiti.php", {
            upit: "proizvod_brisanje",
            id: idProizvoda
          }, function(povratnaPoruka) {
              location.reload();
          });
        }
      }

      // funkcija za pozivanje dijaloga za slike
      function dlgSlika(idProizvoda_slika) {
        $("#slikaProizvoda").modal("toggle");
        listanjeSlika(idProizvoda_slika);
      }

      //funkcija za listanje slika
      function listanjeSlika(idProizvoda_slika) {
        $('#album').empty();
        $.getJSON("../baza/upiti.php", {
          upit: "proizvod_listingID",
          id: idProizvoda_slika
        }, function(podaci) {
          $.each(podaci, function(kljuc, vrednost) {
            $('#album').append('<div class="text-center"><img src="../slike/'+vrednost.slika+'"></div>');
          });
        });
      }

      // funkcija za zatvaranje dijaloga za slike
      function zatvaranjeDlgSlike() {
        $("#slikaProizvoda").modal("hide");
      }
    </script>
  </body>
</html>
