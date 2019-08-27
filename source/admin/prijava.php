<!DOCTYPE html>
<html>
  <head>
    <title>Prijava</title>
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

      // indeksne vrednosti (email, password) odgovaraju vrednostima "name" atributa unutar forme za prijavu korisnika
      $email = ((isset($_POST["email"])) ? $_POST["email"] : "");
      $password = ((isset($_POST["password"])) ? $_POST["password"] : "");
    ?>
    <div class="container-fluid">
    <div id="prijava_okvir">
      <div>
        <?php
          // prosleđivanje forme:
          $greska = array();
          if($_POST) {
            // proveravamo slučaj "praznog unosa":
            if(empty($_POST["email"]) || empty($_POST["password"])) {
              $greska[] = "* Obavezno polje.";
            } else {
              $rezultat = mysqli_query($link, "SELECT * from korisnici WHERE korisnici.email = '$email'");
              $korisnik = mysqli_fetch_assoc($rezultat);
              $brojac_korisnika = mysqli_num_rows($rezultat);
              // proveravamo validnost email-a:
              if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $greska[] = "Morate uneti ispravan e-mail.";
              } else {
                // proveravamo da li email postoji u bazi:
                if($brojac_korisnika < 1) {
                  $greska[] = "Uneti e-mail ne postoji u bazi.";
                }
              }
              // proveravamo da li je lozinka duža od 6 karaktera:
              if(strlen($password) < 6) {
                $greska[] = "Lozinka mora biti duža od 6 karaktera.";
              } else {
                // proveravamo "poklapanje" unete lozinke sa lozinkom koja postoji u bazi
                if(!password_verify($password, $korisnik["password"])) {
                  $greska[] = "Pogrešna lozinka.";
                }
              }
            }

            if(!empty($greska)) {
              // štampanje grešaka
              echo prikaz_greske($greska); // funkcija prikaz_greske() se nalazi u folderu pomocne_funkcije (fajl: greske.php)
            } else {
              // prijava korisnika
              $korisnik_id = $korisnik["id"];
              prijava($korisnik_id);
            }
          }
        ?>
      </div>
      <h2 class="text-center">Prijava</h2><hr>
      <form action="prijava.php" method="post">
        <div class="form-group">
          <label for="email">Email*:</label>
          <input type="email" id="email" class="form-control" name="email" value="<?php echo $email; ?>">
        </div>
        <div class="form-group">
          <label for="password">Lozinka*:</label>
          <input type="password" id="password" class="form-control" name="password" value="<?php echo $password; ?>">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Prijavi se">
        </div>
      </form>
      <p class="text-right"><a href="../index.php" alt="home">GameShop</a></p>
    </div>
    <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
  </body>
</html>
