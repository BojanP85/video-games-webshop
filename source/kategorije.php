<!DOCTYPE html>
<html>
  <head>
    <title>GameShop</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="javascript/bootstrap.min.js"></script>
    <link rel="stylesheet" href="stilovi/bootstrap.css">
    <link rel="stylesheet" href="stilovi/stilovi.css">
    <style type="text/css">
      .popover {
        width: 100%;
        max-width: 800px;
      }
      body {
        background-image: url("slike/header/pozadina-index.jpg");
        background-repeat: no-repeat;
        background-size: auto;
        background-attachment: fixed;
      }
    </style>
  </head>
  <body>
    <?php
      require_once("baza/index_konekcija.php");
      require_once("pomocne_funkcije/ispis_kategorije.php");

      if(isset($_GET["kat"])) {
        $kategorija_id = $_GET["kat"];
      } else {
        $kategorija_id = "";
      }
      $proizvodi_rezultat = mysqli_query($link, "SELECT * FROM proizvodi WHERE kategorije = $kategorija_id");
      $kategorija = ispis_kategorije($kategorija_id);

      /* $tk - top-kategorija (Playstation, XBOX, Nintendo, PC)
         $pk - pod-kategorija (Igre, Konzole, Dodatna oprema, akcije) */
    	$tk_rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE top_kategorija = 0");
    ?>

    <!-- Navigacija -->
    <nav class="navbar navbar-default navbar-fixed-top" style="background: linear-gradient(to bottom, #003399 0%, #009933 100%)">
      <div class="container">
        <a href="index.php" class="navbar-brand" style="color: white">GameShop</a>
        <ul class="nav navbar-nav">
          <?php while ($tk_red = mysqli_fetch_assoc($tk_rezultat)) { ?>
            <?php
              $tk_id = $tk_red["id"]; /* Pri svakom prolasku kroz WHILE petlju (41. red), u promenljivu $tk_id smeštamo id vrednost trenutne top-kategorije
                                         (za "Playstation" id ima vrednost 1, za "XBOX" id ima vrednost 2 itd.)
                                         Ovo nam je bitno kako bismo u novoj WHILE petlji (51. red) omogućili pristup pod-kategorijama. */
              $pk_rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE top_kategorija = '$tk_id'");
            ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: white"><?php echo $tk_red["kategorija"]; ?><span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <?php while ($pk_red = mysqli_fetch_assoc($pk_rezultat)) { ?>
                  <li><a href="kategorije.php?kat=<?php echo $pk_red["id"]; ?>"><?php echo $pk_red["kategorija"]; ?></a></li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
        </ul>
        <div class="form-inline pull-right" style="margin-top: 8px">
          <input class="form-control mr-sm-2" type="text" id="poljePretraga" placeholder="Nađi proizvod..." aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" id="dgmPretraga">Traži</button>
        </div>
      </div>
    </nav>

    <!-- Korpa -->
    <div class="container" style="width: 300px; margin-right: 0">
      <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
         <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Menu</span>
            <span class="glyphicon glyphicon-menu-hamburger"></span>
          </button>
          <a class="navbar-brand">Korpa</a>
         </div>
         <div id="navbar-cart" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
           <li>
            <a id="cart-popover" class="btn" data-placement="bottom">
             <span class="badge"></span>
             <span class="ukupno_cena">0.00</span>
            </a>
           </li>
          </ul>
         </div>
        </div>
      </nav>
      <div id="popover_sadrzaj" style="display: none">
        <span id="korpa_detalji"></span>
        <div align="right">
         <a href="#" class="btn btn-primary" id="check_out_cart">Poruči</a>
         <a href="#" class="btn btn-default" id="dgmIsprazni">Isprazni korpu</a>
        </div>
      </div>
    </div>

    <!-- Glavni sadrzaj -->
    <div class="container-fluid">
      <!-- Filteri -->
      <div class="col-md-2 filteri_okvir">
        <?php
          include("filteri.php");
        ?>
      </div>
      <div class="col-md-8 text-center glavni_okvir">
        <div class="row"><br>
          <h2 class="text-center" style="margin-top: -35px"><?php echo $kategorija["top_kat"]. " - " .$kategorija["pod_kat"]; ?></h2><br><br>
          <?php while ($proizvod = mysqli_fetch_assoc($proizvodi_rezultat) ) { ?>
           <div class="col-md-4">
            <h4 style="margin-top: 35px"><?php echo $proizvod["naziv"]; ?></h4>
            <img src="slike/<?php echo $proizvod["slika"]; ?>" alt="<?php echo $proizvod["naziv"]; ?>"/>
            <p class="stara-cena text-danger">Stara cena: <s><?php echo $proizvod["stara_cena"]; ?>din</s></p>
            <p class="nova-cena">Cena: <?php echo $proizvod["cena"]; ?>din</p>
            <button type="button" class="btn btn-sm btn-success" onclick="detalji(<?php echo $proizvod["id"]; ?>)">Detalji</button>
           </div>
          <?php } ?>
        </div>
      </div>
      <div class="col-md-2"></div>
    </div>
    <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
    <script type="text/javascript">
      $(document).ready(function() {
        osvezi_korpu();
        $('#cart-popover').popover({
          html : true,
          container: 'body',
          content:function(){
           return $('#popover_sadrzaj').html();
          }
        });

        // funkcija koja klikom na dugme (class = "ukloni") briše pojedinačan proizvod iz korpe
        $(document).on('click', '.ukloni', function() {
          var proizvod_id = $(this).attr("id");
          var operacija = 'obrisi';
          if(confirm("Da li ste sigurni?")) {
           $.ajax({
            url: "operacija.php",
            method: "POST",
            data: {
              proizvod_id: proizvod_id,
              operacija: operacija
            },
            success: function() {
              osvezi_korpu();
              $('#cart-popover').popover('hide');
              alert("Proizvod je uklonjen iz korpe.");
            }
           });
          } else {
           return false;
          }
       });

       // funkcija koja klikom na dugme (class = "umanji") umanjuje količinu pojedinačnog proizvoda u korpi
       $(document).on('click', '.umanji', function() {
         var proizvod_id = $(this).attr("id");
         var operacija = 'umanji';
        $.ajax({
         url: "operacija.php",
         method: "POST",
         data: {
           proizvod_id: proizvod_id,
           operacija: operacija
         },
         success: function() {
           osvezi_korpu();
           $('#cart-popover').popover('hide');
         }
        });
       });

       // funkcija koja klikom na dugme (class = "uvecaj") uvećava količinu pojedinačnog proizvoda u korpi
       $(document).on('click', '.uvecaj', function() {
         var proizvod_id = $(this).attr("id");
         var operacija = 'uvecaj';
        $.ajax({
         url: "operacija.php",
         method: "POST",
         data: {
           proizvod_id: proizvod_id,
           operacija: operacija
         },
         success: function() {
           osvezi_korpu();
           $('#cart-popover').popover('hide');
         }
        });
       });

       // funkcija koja klikom na dugme (id = "dgmIsprazni") briše kompletan sadržaj korpe
       $(document).on('click', '#dgmIsprazni', function() {
          var operacija = 'isprazni';
          if(confirm("Da li ste sigurni?")) {
            $.ajax({
             url: "operacija.php",
             method: "POST",
             data: {
               operacija: operacija
             },
             success: function() {
              osvezi_korpu();
              $('#cart-popover').popover('hide');
              alert("Korpa je ispražnjena.");
             }
            });
          } else {
            return false;
          }
       });
      });

      // funkcija koja osvežava sadržaj korpe
      function osvezi_korpu() {
        $.ajax({
         url: "dodaj_u_korpu.php",
         method: "POST",
         dataType: "json",
         success: function(data) {
          $('#korpa_detalji').html(data.korpa_detalji);
          $('.ukupno_cena').text(data.ukupno_cena);
          $('.badge').text(data.ukupno_proizvodi);
         }
        });
      }

      // funkcija koja otvara dijalog sa detaljima proizvoda
      function detalji(id) {
        var podatak = {"id" : id}
        $.ajax({
            url: "detalji_proizvoda.php",
            method: "POST",
            data: podatak,
            success: function(data){
              $("body").append(data);
              $("#detalji").modal("toggle");
            },
            error: function(){
              alert("Došlo je do greške.");
            }
        });
      }

      // funkcija koja klikom na dugme (id = "dgmPretraga") pretražuje proizvode
      $('#dgmPretraga').click(function() {
        if($('#poljePretraga').val() != "") {
          $('.glavni_okvir').empty();
          $('.glavni_okvir').append('<div class="row"><br><h2 class="text-center" style="margin-top: -35px">Rezultati pretrage</h2><br><br></div>');
          $.getJSON("baza/upiti.php", {
            upit: "proizvod_pretraga",
            pretraga_naziv: $('#poljePretraga').val()
          }, function(podaci) {
            $.each(podaci, function(kljuc, vrednost) {
              $('.glavni_okvir').append('<div class="col-md-4"><h4 style="margin-top: 35px">'+vrednost.naziv+'</h4><img src="slike/'+vrednost.slika+'" alt="'+vrednost.naziv+'"/><p class="stara-cena text-danger">Stara cena: <s>'+vrednost.stara_cena+'din</s></p><p class="nova-cena">Cena: '+vrednost.cena+'din</p><button type="button" class="btn btn-sm btn-success" onclick="detalji('+vrednost.id+')">Detalji</button></div>');
              $('#poljePretraga').val("");
            });
          });
        }
      });
    </script>
  </body>
</html>
