<?php
  $link = mysqli_connect("localhost", "root", "", "projekat");
  if (!$link) {
    echo "Došlo je do greške u povezivanju sa bazom." .PHP_EOL;
    exit;
  }

  session_start();

  //require_once($_SERVER['DOCUMENT_ROOT']."/php_sql_vezbe/gameshop/source/pomocne_funkcije/logovanje.php");
  require_once("../pomocne_funkcije/logovanje.php");

  // proveravamo da li varijabla $_SESSION["GSKorisnik"] postoji, odnosno da li ima vrednost različitu od NULL. u tom slučaju isset() funkcija vraća "true".
  if(isset($_SESSION["GSKorisnik"])) {
    $korisnik_id = $_SESSION["GSKorisnik"];
    $rezultat = mysqli_query($link, "SELECT * from korisnici WHERE korisnici.id = $korisnik_id");
    $korisnik_podaci = mysqli_fetch_assoc($rezultat);
    $imePrezime = explode(' ', $korisnik_podaci["ime_prezime"]); // ime i prezime korisnika pretvaramo u niz pomoću funkcije explode(), kako bismo imali pristup...
    $korisnik_podaci["ime"] = $imePrezime[0]; // ...posebno imenu i...
    $korisnik_podaci["prezime"] = $imePrezime[1]; // ...posebno prezimenu korisnika
  }

  /* proveravamo da li varijable $_SESSION["poruka_uspeh"] i $_SESSION["poruka_greska"] imaju vrednost različitu od NULL.
     ukoliko imaju, ispisujemo odgovarajuću poruku. poruke su definisane u fajlu "logovanje.php" (folder: pomocne_funkcije). */
  if(isset($_SESSION["poruka_uspeh"])) {
    echo '<br><div class="bg-success"><p class="text-success text-center">'.$_SESSION["poruka_uspeh"].'</p></div>';
    unset($_SESSION["poruka_uspeh"]);
  }
  if(isset($_SESSION["poruka_greska"])) {
    echo '<br><div class="bg-danger"><p class="text-danger text-center">'.$_SESSION["poruka_greska"].'</p></div>';
    unset($_SESSION["poruka_greska"]);
  }
?>
