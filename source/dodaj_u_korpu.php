<?php
  session_start();

  $ukupno_cena = 0;
  $ukupno_proizvodi = 0;

  // počinjemo iscrtavanje tabele
  $iscrtavanje = '
   <div class="table-responsive" id="order_table">
    <table class="table table-bordered table-striped">
     <tr>
       <th class="text-center" width="40%">Proizvod</th>
       <th class="text-center" width="5%">Količina</th>
       <th class="text-center" width="12%">Cena</th>
       <th class="text-center" width="12%">Ukupno</th>
       <th width="5%"></th>
     </tr>
  ';

  if(!empty($_SESSION["korpa"])) { // proveravamo da li varijabla $_SESSION["korpa"] ima vrednost, odnosno da li u korpi ima proizvoda
   foreach($_SESSION["korpa"] as $kljuc => $vrednost) {
    $iscrtavanje .= '
     <tr>
      <td>'.$vrednost["proizvod_naziv"].'</td>
      <td class="text-center"><button name="umanji" class="btn btn-default btn-xs umanji" id="'. $vrednost["proizvod_id"].'">-</button><span>&nbsp;</span>'.$vrednost["proizvod_kolicina"].'<span>&nbsp;</span><button name="uvecaj" class="btn btn-default btn-xs uvecaj" id="'. $vrednost["proizvod_id"].'">+</button></td>
      <td class="text-right">'.$vrednost["proizvod_cena"].'</td>
      <td class="text-right">'.number_format($vrednost["proizvod_kolicina"] * $vrednost["proizvod_cena"], 2).'</td>
      <td class="text-center"><button name="ukloni" class="btn btn-danger btn-xs ukloni" id="'. $vrednost["proizvod_id"].'">Ukloni</button></td>
     </tr>
    ';
    $ukupno_cena = $ukupno_cena + ($vrednost["proizvod_kolicina"] * $vrednost["proizvod_cena"]); // ukupan iznos korpe smeštamo unutar varijable $ukupno_cena
    $ukupno_proizvodi = $ukupno_proizvodi + 1; // varijabla $ukupno_proizvodi biva uvećana za jedan svaki put kada dodamo novi proizvod u korpu
   }
   $iscrtavanje .= '
     <tr>
      <td colspan="3" align="right"><b>Ukupno</b></td>
      <td class="text-right">'.number_format($ukupno_cena, 2).'</td>
      <td></td>
     </tr>
   ';
  } else { // kod unutar ELSE bloka se izvršava ukoliko je korpa prazna
   $iscrtavanje .= '
     <tr>
      <td colspan="5" class="text-center">
       Korpa je prazna!
      </td>
     </tr>
   ';
  }
  // završavamo iscrtavanje tabele
  $iscrtavanje .= '</table></div>';

  // skupljamo podatke koje ćemo u obliku "json" zapisa proslediti indeksnoj stranici
  $data = array(
   'korpa_detalji' => $iscrtavanje,
   'ukupno_cena' => number_format($ukupno_cena, 2). ' din',
   'ukupno_proizvodi' => $ukupno_proizvodi
  );
  echo json_encode($data);
?>
