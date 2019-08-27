<?php
  require_once("../baza/konekcija.php");
  unset($_SESSION["GSKorisnik"]); // trenutno prijavljen korisnik biva odjavljen...
  header('Location: prijava.php'); // ...i preusmeren na stranicu za prijavu
?>
