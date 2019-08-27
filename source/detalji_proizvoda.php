<?php
  require_once("baza/index_konekcija.php");
  $id = $_POST["id"];
  $proizvod_rezultat = mysqli_query($link, "SELECT * FROM proizvodi WHERE id = $id");
  $proizvod = mysqli_fetch_assoc($proizvod_rezultat);
  $zanr_id = $proizvod["zanr"];
  $zanr_rezultat = mysqli_query($link, "SELECT zanr.zanr FROM zanr WHERE id = $zanr_id");
  $zanr = mysqli_fetch_assoc($zanr_rezultat);
?>

<?php ob_start(); ?>
<div class="modal fade details-1" id="detalji" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" onclick="zatvaranje()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title text-center"><?php echo $proizvod["naziv"]; ?></h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <div class="center-block">
                <img id="min_slika" class="details img-responsive" src="slike/<?php echo $proizvod["slika"]; ?>" alt="<?php echo $proizvod["naziv"]; ?>"/>
              </div>
            </div>
            <div class="col-sm-6">
              <h4>Opis</h4>
              <p><?php echo nl2br($proizvod["opis"]); ?></p> <!-- funkcija nl2br() osigurava da tekst ostane u onom formatu u kom se unosi u bazu -->
              <br><p><b>Žanr:</b> <?php echo $zanr["zanr"]; ?></p>
              <hr>
              <p><b>Cena:</b> <?php echo $proizvod["cena"]; ?>din</p>
                <div class="form-group">
                  <div class="col-xs-3">
                    <label for="kolicina">Količina:</label>
                    <input type="number" class="form-control" id="kolicina" name="kolicina" value="1" min="1" max="10">
                    <input type="hidden" id="skriveni_id" name="skriveni_id" value="<?php echo $proizvod["id"]; ?>">
                    <input type="hidden" id="skriveni_naziv" name="skriveni_naziv" value="<?php echo $proizvod["naziv"]; ?>">
                    <input type="hidden" id="skrivena_cena" name="skrivena_cena" value="<?php echo $proizvod["cena"]; ?>">
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="dodaj_u_korpu" class="btn btn-warning" type="submit" name="dodaj_u_korpu"><span>&#128722; &nbsp;</span>Dodaj u korpu</button>
        <button class="btn btn-default" onclick="zatvaranje()">Zatvori</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  // funkcija koja klikom na dugme (id = "dodaj_u_korpu") dodaje proizvod u korpu
  $('#dodaj_u_korpu').click(function() {
    var proizvod_id = $('#skriveni_id').val();
    var proizvod_naziv = $('#skriveni_naziv').val();
    var proizvod_cena = $('#skrivena_cena').val();
    var proizvod_kolicina = $('#kolicina').val();
    var operacija = "dodaj";
    if(proizvod_kolicina > 0) {
      $.ajax({
        url: "operacija.php",
        method: "POST",
        data: {
          proizvod_id: proizvod_id,
          proizvod_naziv: proizvod_naziv,
          proizvod_cena: proizvod_cena,
          proizvod_kolicina: proizvod_kolicina,
          operacija: operacija
        },
        success: function(data) {
          osvezi_korpu();
          alert("Proizvod je dodat u korpu.");
        }
      });
    } else {
      alert("Niste odabrali količinu.");
    }
  });

  // funkcija koja zatvara dijalog sa detaljima proizvoda
  function zatvaranje() {
    $("#detalji").modal("hide");
    setTimeout(function(){
      $("#detalji").remove();
    }, 500)
  }
</script>
<?php echo ob_get_clean(); ?>
