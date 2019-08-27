<!DOCTYPE html>
<html>
  <head>
    <title>Žanrovi</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="../javascript/bootstrap.min.js"></script>
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
      <div id="ispisGreske" style="background-color: red; color: white"></div>
      <h2 class="text-center">Žanrovi</h2><hr>
      <form class="form-inline text-center">
        <div id="dugmici" class="form-group">
          <input type="text" id="zanr" class="form-control">
          <input type="submit" id="dgmDodaj" class="btn btn-success" value="Dodaj žanr">
        </div>
      </form><br>
      <table class="table table-bordered table-striped" style="width: auto; margin: auto">
        <thead>
          <tr>
            <th style="text-align: center">Žanr</th>
            <td colspan="2" style="text-align: center">Izmeni / Obriši</td>
          </tr>
        </thead>
        <tbody id="listaZanrova"></tbody>
      </table>
    </div>
    <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
    <script type="text/javascript">
      // funkcija koja prilikom otvaranja stranice izlistava žanrove
      function listing() {
        $.getJSON("../baza/upiti.php", {
          upit: "zanr_listing"
        }, function(podaci) {
          $.each(podaci, function(kljuc, vrednost) {
            $('#listaZanrova').append('<tr><td>'+vrednost.zanr+'</td><td class="text-center"><a href="#" onclick="izmenaZanra('+vrednost.id+')" class="btn btn-xs btn-default">&#128393;</a></td><td class="text-center"><a href="#" onclick="brisanjeZanra('+vrednost.id+')" class="btn btn-xs btn-default">&#128465;</a></td></tr>');
          });
        });
      }

      // funkcija koja klikom na dugme (id = "dgmDodaj") dodaje žanr u bazu
      $('#dgmDodaj').click(function(event) {
        event.preventDefault();
        if($('#zanr').val() == "") {
          $('#ispisGreske').html("Niste uneli žanr.");
        } else {
            $('#ispisGreske').empty();
            $.getJSON("../baza/upiti.php", {
              upit: "zanr_dodavanje",
              zanr: $('#zanr').val()
            }, function(povratnaPoruka) {
                if(povratnaPoruka.poruka == "ok") {
                  $('#zanr').val("");
                  $('#listaZanrova').empty();
                  listing();
                } else if(povratnaPoruka.poruka == "duplikat") {
                    $('#ispisGreske').html("Žanr '" +$('#zanr').val()+ "' već postoji u bazi.");
                }
            });
          }
      });

      // funkcija za brisanje žanra
      function brisanjeZanra(idZanra) {
        if(confirm("Da li ste sigurni?")) {
          $.getJSON("../baza/upiti.php", {
            upit: "zanr_brisanje",
            id: idZanra
          }, function(povratnaPoruka) {
              $('#ispisGreske').empty();
              $('#zanr').val("");
              $('#listaZanrova').empty();
              listing();
          });
        }
      }

      // funkcija za izmenu žanra
      function izmenaZanra(idZanra) {
        $('#ispisGreske').empty();
        $('#dugmici').empty();
        $('#dugmici').append('<input type="text" id="zanr" class="form-control" style="margin-right: 4px"><input type="submit" id="dgmIzmeni" class="btn btn-success" value="Izmeni žanr" style="margin-right: 4px"><a href="zanrovi.php"><input type="button" class="btn btn-default" value="Odustani"></a>');
        $.getJSON("../baza/upiti.php", {
          upit: "zanr_listingID",
          id: idZanra
        }, function(podaci) {
          $('#zanr').val(podaci[0].zanr);
          $('#dgmIzmeni').click(function(event) {
            event.preventDefault();
            if($('#zanr').val() == "") {
              $('#ispisGreske').html("Niste uneli žanr.");
            } else {
                $('#ispisGreske').empty();
                $.getJSON("../baza/upiti.php", {
                  upit: "zanr_izmena",
                  id: idZanra,
                  zanr: $('#zanr').val()
                }, function(povratnaPoruka) {
                  if(povratnaPoruka.poruka == "ok") {
                    alert("Podaci su uspešno promenjeni.");
                    $('#zanr').val("");
                    $('#listaZanrova').empty();
                    listing();
                  } else if(povratnaPoruka.poruka == "duplikat") {
                      $('#ispisGreske').html("Žanr '" +$('#zanr').val()+ "' već postoji u bazi.");
                  }
                });
              }
          });
        });
      }
    </script>
  </body>
</html>
