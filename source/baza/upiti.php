<?php
  require_once("konekcija.php");
  $niz = array();

  switch ($_GET["upit"]) {
    case "tk_listing":
      $rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE kategorije.top_kategorija = 0");
      while ($red = mysqli_fetch_assoc($rezultat)) {
        $niz[] = $red;
      }
      break;

    case "zanr_listing":
      $rezultat = mysqli_query($link, "SELECT * FROM zanr ORDER BY zanr.zanr ASC");
      while ($red = mysqli_fetch_assoc($rezultat)) {
        $niz[] = $red;
      }
      break;

    case "zanr_listingID":
      $id = $_GET["id"];
      $rezultat = mysqli_query($link, "SELECT * FROM zanr WHERE zanr.id = $id");
      while ($red = mysqli_fetch_assoc($rezultat)) {
        $niz[] = $red;
      }
      break;

    case "zanr_dodavanje":
      $zanr = $_GET["zanr"];
      $rezultat = mysqli_query($link, "SELECT zanr.id FROM zanr WHERE zanr.zanr = '$zanr'");
      if (mysqli_num_rows($rezultat) != 0) {
        $niz = array("poruka" => "duplikat");
      } else {
        mysqli_query($link, "INSERT INTO `zanr` (`zanr`) VALUES ('$zanr')");
        $niz = array("poruka" => "ok");
      }
      break;

    case "zanr_brisanje":
      $id = $_GET["id"];
      mysqli_query($link, "DELETE FROM zanr WHERE zanr.id = $id");
      $niz = array("poruka" => "ok");
      break;

    case "zanr_izmena":
      $id = $_GET["id"];
      $zanr = $_GET["zanr"];
      $rezultat = mysqli_query($link, "SELECT zanr.id FROM zanr WHERE zanr.zanr = '$zanr' AND zanr.id <> $id");
      if(mysqli_num_rows($rezultat) != 0) {
  			$niz = array("poruka" => "duplikat");
  		} else {
        mysqli_query($link, "UPDATE zanr SET zanr.zanr = '$zanr' WHERE zanr.id = $id");
        $niz = array("poruka" => "ok");
  		}
      break;

    case "pk_listing":
      $id = $_GET["id"];
      $rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE kategorije.top_kategorija = $id");
      while ($red = mysqli_fetch_assoc($rezultat)) {
        $niz[] = $red;
      }
      break;

    case "proizvod_listing":
      $rezultat = mysqli_query($link, "SELECT proizvodi.id, proizvodi.naziv FROM proizvodi ORDER BY proizvodi.naziv ASC");
      while ($red = mysqli_fetch_assoc($rezultat)) {
        $niz[] = $red;
      }
      break;

    case "proizvod_listingID":
      $id = $_GET["id"];
      $rezultat = mysqli_query($link, "SELECT * FROM proizvodi WHERE proizvodi.id = $id");
      while ($red = mysqli_fetch_assoc($rezultat)) {
        $niz[] = $red;
      }
      break;

    case "proizvod_listingSlika":
      $id = $_GET["id"];
      $rezultat = mysqli_query($link, "SELECT proizvodi.slika FROM proizvodi WHERE proizvodi.id = $id");
      while ($red = mysqli_fetch_assoc($rezultat)) {
        $niz[] = $red;
      }
      break;

    case "proizvod_dodavanje":
      $naziv = mysqli_real_escape_string($link, $_GET["naziv"]);
      $cena = $_GET["cena"];
      $stara_cena = $_GET["stara_cena"];
      if ($stara_cena == "") {
        $stara_cena = "NULL";
      }
      $zanr = $_GET["zanr"];
      $kategorija = $_GET["kategorija"];
      $opis = $_GET["opis"];
      $slika = null;
      $rezultat = mysqli_query($link, "SELECT proizvodi.id FROM proizvodi WHERE proizvodi.naziv = '$naziv'");
      if (mysqli_num_rows($rezultat) != 0) {
        $niz = array("poruka" => "duplikat");
      } else {
        mysqli_query($link, "INSERT INTO `proizvodi` (`naziv`, `cena`, `stara_cena`, `zanr`, `kategorije`, `slika`, `opis`) VALUES ('$naziv', $cena, $stara_cena, $zanr, $kategorija, '$slika', '$opis')");
        $niz = array("poruka" => "ok");
      }
      break;

    case "proizvod_brisanje":
      $id = $_GET["id"];
      mysqli_query($link, "DELETE FROM proizvodi WHERE proizvodi.id = $id");
      $niz = array("poruka" => "ok");
      break;

    case "proizvod_izmena":
      $id = $_GET["id"];
      $naziv = mysqli_real_escape_string($link, $_GET["naziv"]);
      $cena = $_GET["cena"];
      $stara_cena = $_GET["stara_cena"];
      if ($stara_cena == "") {
        $stara_cena = "NULL";
      }
      $zanr = $_GET["zanr"];
      $kategorija = $_GET["kategorija"];
      $opis = $_GET["opis"];
      $rezultat = mysqli_query($link, "SELECT proizvodi.id FROM proizvodi WHERE proizvodi.naziv = '$naziv' AND proizvodi.id <> $id");
      if(mysqli_num_rows($rezultat) != 0) {
  			$niz = array("poruka" => "duplikat");
  		} else {
        mysqli_query($link, "UPDATE proizvodi SET proizvodi.naziv = '$naziv' WHERE proizvodi.id = $id");
        mysqli_query($link, "UPDATE proizvodi SET proizvodi.cena = $cena WHERE proizvodi.id = $id");
        mysqli_query($link, "UPDATE proizvodi SET proizvodi.stara_cena = $stara_cena WHERE proizvodi.id = $id");
        mysqli_query($link, "UPDATE proizvodi SET proizvodi.zanr = $zanr WHERE proizvodi.id = $id");
        mysqli_query($link, "UPDATE proizvodi SET proizvodi.kategorije = $kategorija WHERE proizvodi.id = $id");
        mysqli_query($link, "UPDATE proizvodi SET proizvodi.opis = '$opis' WHERE proizvodi.id = $id");
        $niz = array("poruka" => "ok");
      }
      break;

    case "proizvod_pretraga":
      $pretraga_naziv = $_GET["pretraga_naziv"];
      $rezultat = mysqli_query($link, "SELECT * from proizvodi WHERE proizvodi.naziv LIKE '%$pretraga_naziv%'");
      while ($red = mysqli_fetch_assoc($rezultat)) {
        $niz[] = $red;
      }
      break;
  }
  echo json_encode($niz);
?>
