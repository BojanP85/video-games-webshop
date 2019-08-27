<!DOCTYPE html>
<html>
  <head>
    <title>Galerija</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="../javascript/jquery-ui-1.12.1.custom/external/jquery/jquery.js"></script>
    <script src="../javascript/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/overcast/jquery-ui.css">
    <link rel="stylesheet" href="../stilovi/stilovi.css">
  </head>
  <body onload="listingProizvoda()">
    <?php
      require_once("../baza/konekcija.php");
      if(!korisnik_prijavljen()) {
        prijava_greska();
      }
    ?>
    <div class="container-fluid"><br>
      <h2 class="text-center" style="font-size: 30px; font-family: sans-serif; margin-top: -5px; margin-bottom: 18px">Galerija</h2><hr style="margin-bottom: 20px">
      <center>
        <div class="col-md-5 text-center">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <label class="input-group-text" for="spisakProizvoda">Proizvod</label>
            </div>
            <select class="custom-select" id="spisakProizvoda"></select>
          </div>
          <div id="album"></div><br>
          <div>
            <button id="dgmDodavanjeSlike">Dodaj / Izmeni sliku</button><br><br>
            <a href="proizvodi.php"><button id="povratak">Povratak na proizvode</button></a>
          </div>
        </div>
      </center>
    </div>

    <!-- Dijalog za dodavanje slike -->
    <div id="dlgDodavanjeSlike" title="Odaberi sliku">
      <form id="frmDodavanjeSlike" enctype="multipart/form-data">
        <input type="file" id="imeSlike" name="imeSlike">
        <input  type="hidden" id="idProizvoda" name="idProizvoda">
      </form>
    </div>
    <footer class="text-center" style="margin-top: 80px; font-size: 14px; font-family: sans-serif">&copy; Copyright 2015-2018 GameShop</footer>
    <script type="text/javascript">
      // funkcija koja prilikom otvaranja stranice izlistava proizvode unutar "select" polja
      function listingProizvoda() {
        $("#spisakProizvoda").append('<option value="-">Izaberite proizvod</option>')
        $.getJSON("../baza/upiti.php", {
          upit: "proizvod_listing"
        }, function(podaci) {
          $.each(podaci, function(kljuc, vrednost) {
            $('#spisakProizvoda').append('<option value="'+vrednost.id+'">'+vrednost.naziv+'</option>');
          });
        });
      }

      // funkcija koja prati promenu vrednosti "select" polja (id proizvoda) i prikazuje sliku za odabrani proizvod
      $('#spisakProizvoda').change(function() {
        listingSlika();
      });

      function listingSlika() {
        $('#album').empty();
        $.getJSON("../baza/upiti.php", {
          upit: "proizvod_listingSlika",
          id: $('#spisakProizvoda').val()
        }, function(podaci) {
          $.each(podaci, function(kljuc, vrednost) {
            $('#album').append('<div><img src="../slike/'+vrednost.slika+'"></div>');
          });
        });
      }

      // dugmići
      $( "#dgmDodavanjeSlike" ).button();
      $( "#povratak" ).button();

      // dugme koje pokreće dijalog za dodavanje slike
      $( "#dgmDodavanjeSlike" ).click(function( event ) {
        if($('#spisakProizvoda').val() == "-") {
          alert("Niste odabrali proizvod.");
        } else {
            $('#idProizvoda').val($('#spisakProizvoda').val());
            $( "#dlgDodavanjeSlike" ).dialog( "open" );
            event.preventDefault();
        }
      });

      // dijalog za dodavanje slike
      $( "#dlgDodavanjeSlike" ).dialog({
        autoOpen: false,
        width: 400,
        buttons: [
          {
            text: "Potvrdi",
            click: function() {
              $('#frmDodavanjeSlike').submit();
              $( this ).dialog( "close" );
            }
          },
          {
            text: "Odustani",
            click: function() {
              $( this ).dialog( "close" );
            }
          }
        ]
      });

      // funkcija za "upload" slike
			$('#frmDodavanjeSlike').on('submit',(function(e) {
				e.preventDefault();
				var formData = new FormData(this);
				$.ajax({
					type:'POST',
					url: '../pomocne_funkcije/dodavanje_slike.php',
					data:formData,
					cache:false,
					contentType: false,
					processData: false,
					success:function(data){
						listingSlika();
					},
					error: function(data){
						console.log("error");
						console.log(data);
					}
				});
	    }));
    </script>
  </body>
</html>
