<?php
  // funkcija koja obavlja prijavu korisnika
  function prijava($korisnik_id) {
    $_SESSION["GSKorisnik"] = $korisnik_id;
    $_SESSION["poruka_uspeh"] = "Uspešno ste se prijavili.";
    header('Location: index.php');
  }

  // funkcija koja proverava da li je korisnik prijavljen ili ne
  function korisnik_prijavljen() {
    if(isset($_SESSION["GSKorisnik"]) && $_SESSION["GSKorisnik"] > 0) {
      return true;
    } else {
      return false;
    }
  }

  // ova funkcija je usko povezana sa funkcijom korisnik_prijavljen(), budući da ispisuje grešku ukoliko korisnik nije prijavljen
  function prijava_greska($adresa = "prijava.php") {
    $_SESSION["poruka_greska"] = "Morate biti prijavljeni da biste pristupili stranici.";
    header('Location: '.$adresa);
  }

  // funkcija koja omogućuje pristup određenim stranicama isključivo ukoliko korisnik ima ovlašćenje "admin"
  function ovlascenje($ovlascenje = "admin") {
    global $korisnik_podaci;
    $ovlascenja = explode(',', $korisnik_podaci["ovlascenje"]);
    if(in_array($ovlascenje, $ovlascenja, true)) {
      return true;
    } else {
      return false;
    }
  }

  // ova funkcija je usko povezana sa funkcijom ovlascenje(), budući da ispisuje grešku ukoliko korisnik nema ovlašćenje
  function ovlascenje_greska($adresa = "prijava.php") {
    $_SESSION["poruka_greska"] = "Nemate ovlašćenje za pristup stranici.";
    header('Location: '.$adresa);
  }
?>
