<!DOCTYPE html>
<html>
  <head>
    <title>Promena lozinke</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <link rel="stylesheet" href="../stilovi/bootstrap.css">
    <link rel="stylesheet" href="../stilovi/stilovi.css">
    <style type="text/css">
      #prijava_okvir {
        width: 50%;
        height: 60%;
        border: 2px solid;
        border-radius: 15px;
        box-shadow: 7px 7px 15px rgba(0, 0, 0, 0.6);
        margin: 6% auto;
        padding: 15px;
      }
    </style>
  </head>
  <body>
    <?php
      require_once("../baza/konekcija.php");
      require_once("../pomocne_funkcije/greske.php");
      if(!korisnik_prijavljen()) {
        prijava_greska();
      }
      $hash_password = $korisnik_podaci["password"]; // vrednost varijable $korisnik_podaci["password"] dobijamo iz fajla "konekcija.php"
      // indeksne vrednosti (stari_password, password, potvrda) odgovaraju vrednostima "name" atributa unutar forme za promenu lozinke
      $stari_password = ((isset($_POST["stari_password"])) ? $_POST["stari_password"] : "");
      $password = ((isset($_POST["password"])) ? $_POST["password"] : "");
      $potvrda = ((isset($_POST["potvrda"])) ? $_POST["potvrda"] : "");
      $novi_hash_password = password_hash($password, PASSWORD_DEFAULT); // funkcija pomoću koje lozinku zapisujemo u kriptovanom formatu u bazu
      $korisnik_id = $korisnik_podaci["id"]; // vrednost varijable $korisnik_podaci["id"] dobijamo iz fajla "konekcija.php"
    ?>
    <div class="container-fluid">
    <div id="prijava_okvir">
      <div>
        <?php
          // prosleđivanje forme:
          $greska = array();
          if($_POST) {
            // proveravamo slučaj "praznog unosa":
            if(empty($_POST["stari_password"]) || empty($_POST["password"]) || empty($_POST["potvrda"])) {
              $greska[] = "* Obavezno polje.";
            } else {
              // proveravamo da li je lozinka duža od 6 karaktera:
              if(strlen($password) < 6) {
                $greska[] = "Lozinka mora biti duža od 6 karaktera.";
              } else {
                // proveravamo "poklapanje" nove lozinke sa lozinkom unetom u polje za potvrdu
                if($password != $potvrda) {
                  $greska[] = "Nova lozinka i potvrda nove lozinke se ne poklapaju.";
                }
                // proveravamo "poklapanje" stare lozinke sa "hash" lozinkom
                if(!password_verify($stari_password, $hash_password)) {
                  $greska[] = "Pogrešna stara lozinka.";
                }
              }
            }

            if(!empty($greska)) {
              // štampanje grešaka
              echo prikaz_greske($greska); // funkcija prikaz_greske() se nalazi u folderu pomocne_funkcije (fajl: greske.php)
            } else {
              // promena lozinke
              mysqli_query($link, "UPDATE korisnici SET korisnici.password = '$novi_hash_password' WHERE korisnici.id = $korisnik_id");
              $_SESSION["poruka_uspeh"] = "Lozinka je uspešno promenjena.";
              header('Location: index.php');
            }
          }
        ?>
      </div>
      <h2 class="text-center">Promena lozinke</h2><hr>
      <form action="promena_lozinke.php" method="post">
        <div class="form-group">
          <label for="stari_password">Stara lozinka*:</label>
          <input type="password" id="stari_password" class="form-control" name="stari_password" value="<?php echo $stari_password; ?>">
        </div>
        <div class="form-group">
          <label for="password">Nova lozinka*:</label>
          <input type="password" id="password" class="form-control" name="password" value="<?php echo $password; ?>">
        </div>
        <div class="form-group">
          <label for="potvrda">Potvrdi novu lozinku*:</label>
          <input type="password" id="potvrda" class="form-control" name="potvrda" value="<?php echo $potvrda; ?>">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Potvrdi">
          <a href="index.php" class="btn btn-default">Odustani</a>
        </div>
      </form>
      <p class="text-right"><a href="../index.php" alt="home">GameShop</a></p>
    </div>
    <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
  </body>
</html>
