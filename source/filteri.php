<?php
  $kategorija_id = ((isset($_REQUEST["kat"])) ? $_REQUEST["kat"] : "");
  $cena_filter = ((isset($_REQUEST["cena_filter"])) ? $_REQUEST["cena_filter"] : "");
  $min_cena = ((isset($_REQUEST["min_cena"])) ? $_REQUEST["min_cena"] : "");
  $max_cena = ((isset($_REQUEST["max_cena"])) ? $_REQUEST["max_cena"] : "");
  $proizvodi_akcija = ((isset($_REQUEST["proizvodi_akcija"])) ? $_REQUEST["proizvodi_akcija"] : "");
  $zanr_filter = ((isset($_REQUEST["zanr_filter"])) ? $_REQUEST["zanr_filter"] : "");
  $zanr_rezultat = mysqli_query($link, "SELECT * FROM zanr ORDER BY zanr.zanr ASC");
?>

<h3 class="text-center">Pretraži po:</h3><hr>
<!-- Pretraga po ceni -->
<h4 class="text-center">Cena:</h4>
<form action="pretraga.php" method="post">
  <input type="hidden" name="kat" value="<?php echo $kategorija_id; ?>">
  <!-- u slučaju da korisnik ne odabere ni "Rastuće" ni "Opadajuće" opciju, prosleđujemo nulu kao vrednost input-a (name = "cena_filter") -->
  <input type="hidden" name="cena_filter" value="0">
  <input type="radio" name="cena_filter" value="low"<?php if($cena_filter == "low") {echo " checked";} else {echo "";} ?>>Rastuće<br>
  <input type="radio" name="cena_filter" value="high"<?php if($cena_filter == "high") {echo " checked";} else {echo "";} ?>>Opadajuće<br><br>
  <input type="number" name="min_cena" style="width: 80px" placeholder="min" value="<?php echo $min_cena; ?>"> do
  <input type="number" name="max_cena" style="width: 80px" placeholder="max" value="<?php echo $max_cena; ?>"><br><br>
  <!-- u slučaju da korisnik ne odabere "Proizvodi na akciji" opciju, prosleđujemo nulu kao vrednost input-a (name = "proizvodi_akcija") -->
  <input type="hidden" name="proizvodi_akcija" value="0">
  <input type="radio" name="proizvodi_akcija" value="akcija"<?php if($proizvodi_akcija == "akcija") {echo " checked";} else {echo "";} ?>>Proizvodi na akciji<br><br>

  <!-- Pretraga po žanru -->
  <h4 class="text-center">Žanr:</h4>
  <input type="radio" name="zanr_filter" value=""<?php if($zanr_filter == "") {echo " checked";} else {echo "";} ?>>Svi žanrovi<br>
  <?php while($zanr_red = mysqli_fetch_assoc($zanr_rezultat)) { ?>
    <input type="radio" name="zanr_filter" value="<?php echo $zanr_red["id"]; ?>"<?php if($zanr_filter == $zanr_red["id"]) {echo " checked";} else {echo "";} ?>><?php echo $zanr_red["zanr"]; ?><br>
  <?php } ?>
  <hr><center><input style="margin-bottom: 15px" type="submit" class="btn btn-sm btn-primary" value="Traži"></center>
</form>
