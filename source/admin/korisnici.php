<!DOCTYPE html>
<html>
  <head>
    <title>Korisnici</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="../javascript/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../stilovi/bootstrap.css">
    <link rel="stylesheet" href="../stilovi/stilovi.css">
  </head>
  <body>
    <div class="container-fluid">
      <?php
        require_once("../baza/konekcija.php");
        require_once("../pomocne_funkcije/greske.php");
        require_once("../pomocne_funkcije/datum_format.php");
        if(!korisnik_prijavljen()) {
          prijava_greska();
        }
        if(!ovlascenje("admin")) { // uslov koji određuje da samo admin može pristupiti stranici sa korisnicima
          ovlascenje_greska('index.php');
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
      <?php
        // brisanje korisnika
        if(isset($_GET["delete"])) {
          $brisanje_id = $_GET["delete"];
          mysqli_query($link, "DELETE FROM korisnici WHERE korisnici.id = $brisanje_id");
          header('Location: korisnici.php');
        }

        // dodavanje korisnika
        if(isset($_GET["add"])) { // deo koda unutar "if" bloka biće izvršen ukoliko kliknemo na dugme "Dodaj korisnika"
          // indeksne vrednosti (ime_prezime, email, password, potvrda, ovlascenje) odgovaraju vrednostima "name" atributa unutar forme za dodavanje korisnika
          $ime_prezime = ((isset($_POST["ime_prezime"])) ? $_POST["ime_prezime"] : "");
          $email = ((isset($_POST["email"])) ? $_POST["email"] : "");
          $password = ((isset($_POST["password"])) ? $_POST["password"] : "");
          $potvrda = ((isset($_POST["potvrda"])) ? $_POST["potvrda"] : "");
          $ovlascenje = ((isset($_POST["ovlascenje"])) ? $_POST["ovlascenje"] : "");

          // prosleđivanje forme:
          $greska = array();
          if($_POST) {
            // proveravamo slučaj "praznog unosa":
            if(empty($_POST["ime_prezime"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["potvrda"]) || empty($_POST["ovlascenje"])) {
              $greska[] = "* Obavezno polje.";
            } else {
              $rezultat = mysqli_query($link, "SELECT * from korisnici WHERE korisnici.email = '$email'");
              $brojac_email = mysqli_num_rows($rezultat);
              // proveravamo validnost email-a:
              if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $greska[] = "Morate uneti ispravan e-mail.";
              } else {
                // proveravamo da li email već postoji u bazi:
                if($brojac_email != 0) {
                  $greska[] = "Uneti e-mail već postoji u bazi.";
                }
              }
              // proveravamo da li je lozinka duža od 6 karaktera:
              if(strlen($password) < 6) {
                $greska[] = "Lozinka mora biti duža od 6 karaktera.";
              } else {
                // proveravamo "poklapanje" lozinke sa lozinkom unetom u polje za potvrdu
                if($password != $potvrda) {
                  $greska[] = "Lozinka i potvrda lozinke se ne poklapaju.";
                }
              }
            }

            if(!empty($greska)) {
              // štampanje grešaka
              echo prikaz_greske($greska); // funkcija prikaz_greske() se nalazi u folderu pomocne_funkcije (fajl: greske.php)
            } else {
              // dodavanje korisnika
              $hash_password = password_hash($password, PASSWORD_DEFAULT); // funkcija pomoću koje lozinku zapisujemo u kriptovanom formatu u bazu
              mysqli_query($link, "INSERT INTO `korisnici` (`ime_prezime`, `email`, `password`, `ovlascenje`) VALUES ('$ime_prezime', '$email', '$hash_password', '$ovlascenje')");
              header('Location: korisnici.php');
            }
          }
        ?>
          <h2 class="text-center">Dodaj korisnika</h2><hr>
          <form action="korisnici.php?add=1" method="post">
            <div class="form-group col-md-2">
              <label for="ime_prezime">Ime i prezime*:</label>
              <input type="text" id="ime_prezime" class="form-control" name="ime_prezime" value="<?php echo $ime_prezime; ?>">
            </div>
            <div class="form-group col-md-2">
              <label for="email">E-mail*:</label>
              <input type="email" id="email" class="form-control" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group col-md-2">
              <label for="password">Lozinka*:</label>
              <input type="password" id="password" class="form-control" name="password" value="<?php echo $password; ?>">
            </div>
            <div class="form-group col-md-2">
              <label for="potvrda">Potvrdi lozinku*:</label>
              <input type="password" id="potvrda" class="form-control" name="potvrda" value="<?php echo $potvrda; ?>">
            </div>
            <div class="form-group col-md-2">
              <label for="ovlascenje">Ovlašćenje*:</label>
              <select class="form-control" name="ovlascenje">
                <option value=""<?php if($ovlascenje == "") {echo " selected";} else {echo "";} ?>></option>
                <option value="urednik"<?php if($ovlascenje == "urednik") {echo " selected";} else {echo "";} ?>>Urednik</option>
                <option value="admin,urednik"<?php if($ovlascenje == "admin,urednik") {echo " selected";} else {echo "";} ?>>Admin</option>
              </select>
            </div>
            <div class="form-group pull-right">
              <input type="submit" class="btn btn-success" value="Dodaj" style="margin-top: 25px">
              <a href="korisnici.php" class="btn btn-default" style="margin-top: 25px">Odustani</a>
            </div>
          </form>
          <?php
        } else {
        $rezultat = mysqli_query($link, "SELECT * FROM korisnici ORDER BY korisnici.ime_prezime ASC");
      ?>
      <h2 class="text-center">Korisnici</h2><hr>
      <a href="korisnici.php?add=1" class="btn btn-success">Dodaj korisnika</a>
      <br><br><br>
      <table class="table table-bordered table-striped table-condensed">
        <thead>
          <th style="text-align: center">Ime i prezime</th>
          <th style="text-align: center">E-mail</th>
          <th style="text-align: center">Datum registracije</th>
          <th style="text-align: center">Ovlašćenje</th>
          <td style="text-align: center">Obriši</td>
        </thead>
        <tbody>
          <?php while($korisnik = mysqli_fetch_assoc($rezultat)) { ?>
            <tr>
              <td><?php echo $korisnik["ime_prezime"]; ?></td>
              <td><?php echo $korisnik["email"]; ?></td>
              <td style="text-align: center"><?php echo datum_format($korisnik["datum_registracije"]); ?></td>
              <td style="text-align: center"><?php echo $korisnik["ovlascenje"]; ?></td>
              <td style="text-align: center">
                <?php if($korisnik["ovlascenje"] != "admin,urednik") { // ostavljamo mogućnost brisanja samo onih korisnika koji nemaju ovlašćenje "admin,urednik" ?>
                  <a href="korisnici.php?delete=<?php echo $korisnik["id"]; ?>" class="btn btn-default btn-xs">&#128465;</a>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } ?>
    </div>
    <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
  </body>
</html>
