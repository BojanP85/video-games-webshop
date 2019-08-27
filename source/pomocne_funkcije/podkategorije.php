<?php
  require_once("../baza/konekcija.php");
  $tk_id = $_POST["id"]; // "id" se odnosi na parametar "id" koji prosleđujemo unutar ajax-a (fajl: kategorije.php; linija: 199)
  $pk_rezultat = mysqli_query($link, "SELECT * FROM kategorije WHERE kategorije.top_kategorija = $tk_id");
  ob_start();
?>
  <option value=""></option>
<?php while($pk_red = mysqli_fetch_assoc($pk_rezultat)) { ?>
  <option value="<?php echo $pk_red["id"]; ?>"><?php echo $pk_red["kategorija"]; ?></option>
<?php } ?>
<?php echo ob_get_clean(); ?>
