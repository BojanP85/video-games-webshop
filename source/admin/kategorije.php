<!DOCTYPE html>
<html>
  <head>
    <title>Kategorije</title>
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
      <?php
        /* $tk - top-kategorija (Playstation, XBOX, Nintendo, PC)
           $pk - pod-kategorija (Igre, Konzole, Dodatna oprema, akcije) */
        require_once("../baza/konekcija.php");
        require_once("../pomocne_funkcije/greske.php");

        $tk_rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE kategorije.top_kategorija = 0");
        $top_kategorija_odabir = "";

        // izmena kategorije
        if(isset($_GET["edit"]) && !empty($_GET["edit"])) { // proveravamo da li je odabrana opcija za izmenu (klikom na "edit" dugme), kao i da li "edit" (iz URL-a) ima odgovarajući id za svoju vrednost
          $izmena_id = $_GET["edit"];
          $rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE kategorije.id = $izmena_id");
          $izmena_red = mysqli_fetch_assoc($rezultat);
        }

        // brisanje kategorije
        if(isset($_GET["delete"]) && !empty($_GET["delete"])) { // proveravamo da li je odabrana opcija za brisanje (klikom na "delete" dugme), kao i da li "delete" (iz URL-a) ima odgovarajući id za svoju vrednost
            $brisanje_id = $_GET["delete"];
            $rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE kategorije.id = $brisanje_id");
            $brisanje_red = mysqli_fetch_assoc($rezultat);

            if($brisanje_red["top_kategorija"] == 0) { // ovaj uslov osigurava da brisanjem top-kategorije brišemo i sve pripadajuće pod-kategorije
              mysqli_query($link, "DELETE FROM kategorije WHERE kategorije.top_kategorija = $brisanje_id");
            }
            mysqli_query($link, "DELETE FROM kategorije WHERE kategorije.id = $brisanje_id");
            header('Location: kategorije.php');
        }

        // prosleđivanje forme:
        $greska = array();
        if(isset($_POST) && !empty($_POST)) {
          $top_kategorija_odabir = $_POST["top_kategorija"];
          $kategorija = $_POST["kategorija"];
          // proveravamo slučaj "praznog unosa":
          if($kategorija == "") {
            $greska[] = "Niste uneli kategoriju.";
          }
          // proveravamo da li kategorija već postoji u bazi
          $listing = "SELECT * FROM kategorije WHERE kategorije.kategorija = '$kategorija' AND kategorije.top_kategorija = '$top_kategorija_odabir'";
          if(isset($_GET["edit"])) { // ovaj uslov osigurava da program ne izbacuje grešku ukoliko, na primer, kategoriju "Igre" menjamo u "Igre"
            //$id = $izmena_red["id"];
            $listing = "SELECT * FROM kategorije WHERE kategorije.kategorija = '$kategorija' AND kategorije.top_kategorija = '$top_kategorija_odabir' AND kategorije.id != $izmena_id";
          }
          $provera_duplikata = mysqli_query($link, $listing);
          $brojac_redova = mysqli_num_rows($provera_duplikata);
          if($brojac_redova > 0) {
            $greska[] = 'Kategorija "'.$kategorija.'" već postoji u bazi.';
          }

          if(!empty($greska)) {
            // štampanje grešaka
            echo prikaz_greske($greska); // funkcija prikaz_greske() se nalazi u fajlu pomocne_funkcije.php
          } else {
            // dodavanje kategorije u bazu
            $dodaj_izmeni = "INSERT INTO kategorije (kategorija, top_kategorija) VALUES ('$kategorija', $top_kategorija_odabir)";
            // izmena kategorije
            if(isset($_GET["edit"])) {
              $dodaj_izmeni = "UPDATE kategorije SET kategorije.kategorija = '$kategorija', kategorije.top_kategorija = '$top_kategorija_odabir' WHERE kategorije.id = $izmena_id";
            }
            mysqli_query($link, $dodaj_izmeni);
            header('Location: kategorije.php');
          }
        }

        // kod koji obezbeđuje da u input polju (kao njegova vrednost) bude ispisano ime kategorije koju menjamo
        $izmena_id_vrednost = "";
        $top_kategorija_id = 0;
        if(isset($_GET["edit"])) {
          $izmena_id_vrednost = $izmena_red["kategorija"];
          $top_kategorija_id = $izmena_red["top_kategorija"];
        } else { // "else" blok obezbeđuje da odabir pod-kategorije koju menjamo povlači i odabir odgovarajuće top-kategorije
          if(isset($_POST)) {
            $top_kategorija_id = $top_kategorija_odabir;
          }
        }
      ?>
      <h2 class="text-center">Kategorije</h2><hr>
      <div class="row">
        <!-- Forma za unos kategorija -->
        <div class="col-md-6">
          <!-- Ukoliko radimo izmenu, neophodno je da URL sadrzi informaciju o izmeni, odnosno informaciju o tome koji član menjamo.
               Na primer, u slučaju URL-a "http://localhost/php_sql_vezbe/gameshop/source/admin/kategorije.php?edit=5" vidimo da je odabrana opcija "edit" za člana čiji je id = 5. -->
          <form class="form" action="kategorije.php<?php if(isset($_GET["edit"])) {echo "?edit=".$izmena_id;} else {echo "";} ?>" method="post">
            <legend><?php if(isset($_GET["edit"])) {echo "Izmeni ";} else {echo "Dodaj ";} ?> kategoriju</legend>
            <div class="form-group">
              <label for="top_kategorija">Top-kategorija</label>
              <select class="form-control" name="top_kategorija" id="top_kategorija">
                <option value="0"<?php if($top_kategorija_id == 0) {echo " selected='selected'";} else {echo "";} ?>>Top-kategorija</option> <!-- Top-kategorije u bazi (Playstation, XBOX, Nintendo, PC) imaju vrednost 0 -->
                <?php while ($tk_red = mysqli_fetch_assoc($tk_rezultat)) { ?>
                  <option value="<?php echo $tk_red["id"]; ?>"<?php if($top_kategorija_id == $tk_red["id"]) {echo " selected='selected'";} else {echo "";} ?>><?php echo $tk_red["kategorija"]; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="kategorija">Kategorija</label>
              <input type="text" class="form-control" id="kategorija" name="kategorija" value="<?php echo $izmena_id_vrednost; ?>">
            </div>
            <div class="form-group">
              <input type="submit" value="<?php if(isset($_GET["edit"])) {echo "Izmeni ";} else {echo "Dodaj ";} ?> kategoriju" class="btn btn-success">
            </div>
          </form>
        </div>

        <!-- Tabela sa kategorijama -->
        <div class="col-md-6">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th style="text-align: center">Kategorija</th>
                <th style="text-align: center">Top-kategorija</th>
                <td colspan="2" style="text-align: center; width: 50px">Izmeni / Obriši</td>
              </tr>
            </thead>
            <tbody>
              <?php
                $tk_rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE top_kategorija = 0");
                while ($tk_red = mysqli_fetch_assoc($tk_rezultat)) {
                $tk_id = $tk_red["id"];
                $pk_rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE top_kategorija = $tk_id");
              ?>
                <tr class="bg-primary">
                  <td><?php echo $tk_red["kategorija"]; ?></td>
                  <td>Top-kategorija</td>
                  <td style="text-align: center">
                    <a href="kategorije.php?edit=<?php echo $tk_red["id"] ?>" class="btn btn-xs btn-default">&#128393;</a>
                  </td>
                  <td style="text-align: center">
                    <a href="kategorije.php?delete=<?php echo $tk_red["id"] ?>" class="btn btn-xs btn-default">&#128465;</a>
                  </td>
                </tr>
                <?php while ($pk_red = mysqli_fetch_assoc($pk_rezultat)) { ?>
                  <tr class="bg-info">
                    <td><?php echo $pk_red["kategorija"]; ?></td>
                    <td><?php echo $tk_red["kategorija"]; ?></td>
                    <td style="text-align: center">
                      <a href="kategorije.php?edit=<?php echo $pk_red["id"] ?>" class="btn btn-xs btn-default">&#128393;</a>
                    </td>
                    <td style="text-align: center">
                      <a href="kategorije.php?delete=<?php echo $pk_red["id"] ?>" class="btn btn-xs btn-default">&#128465;</a>
                    </td>
                  </tr>
                <?php } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
    <script type="text/javascript">
      function podkategorije() {
        var tk_id = $('#top_kategorija').val();
        $.ajax({
          url: "../pomocne_funkcije/podkategorije.php",
          type: "post",
          data: {
            id: tk_id
          },
          success: function(data){
            $('#pod_kategorija').html(data);
          },
          error: function(){
            alert("Došlo je do greške.");
          }
        });
      }
    </script>
  </body>
</html>
