<?php
  session_start();

  if(isset($_POST["operacija"])) {
   // proveravamo da li je odabrana opcija za dodavanje proizvoda u korpu
   if($_POST["operacija"] == "dodaj") {
    // proveravamo da li varijabla $_SESSION["korpa"] postoji, odnosno da li ima vrednost različitu od NULL. u tom slučaju isset() funkcija vraća "true".
    if(isset($_SESSION["korpa"])) {
     $dostupnost = 0; // vrednost varijable $dostupnost će se povećavati ukoliko u korpu dodajemo isti proizvod više puta
     foreach($_SESSION["korpa"] as $kljuc => $vrednost) {
      if($_SESSION["korpa"][$kljuc]['proizvod_id'] == $_POST["proizvod_id"]) { // proveravamo da li se proizvod koji dodajemo u korpu već nalazi u njoj
       $dostupnost++; // ukoliko je prethodni uslov ispunjen, ne želimo da ponovo dodajemo isti proizvod (pod posebnom stavkom)...
       $_SESSION["korpa"][$kljuc]['proizvod_kolicina'] = $_SESSION["korpa"][$kljuc]['proizvod_kolicina'] + $_POST["proizvod_kolicina"]; // ...već samo da povećamo njegovu količinu u korpi
      }
     }
     if($dostupnost == 0) { // proveravamo da li je proizvod koji dodajemo različit od onih koji se već nalaze u korpi
      $proizvod_niz = array( // ukoliko je prethodni uslov ispunjen, smeštamo podatke o novom proizvodu unutar varijable $proizvod_niz koja predstavlja asocijativan niz
       'proizvod_id' => $_POST["proizvod_id"],
       'proizvod_naziv' => $_POST["proizvod_naziv"],
       'proizvod_cena' => $_POST["proizvod_cena"],
       'proizvod_kolicina' => $_POST["proizvod_kolicina"]
      );
      $_SESSION["korpa"][] = $proizvod_niz; // podatke unutar varijable $proizvod_niz smeštamo u varijablu $_SESSION["korpa"]
     }
   } else { // kod unutar ELSE bloka se izvršava ukoliko isset() funkcija vrati "false". u tom slučaju korpi dodajemo prvi proizvod, čime rezultat isset($_SESSION["korpa"]) funkcije postaje "true".
      $proizvod_niz = array( // smeštamo podatke o proizvodu unutar varijable $proizvod_niz koja predstavlja asocijativan niz
        'proizvod_id' => $_POST["proizvod_id"],
        'proizvod_naziv' => $_POST["proizvod_naziv"],
        'proizvod_cena' => $_POST["proizvod_cena"],
        'proizvod_kolicina' => $_POST["proizvod_kolicina"]
       );
      $_SESSION["korpa"][] = $proizvod_niz; // podatke unutar varijable $proizvod_niz smeštamo u varijablu $_SESSION["korpa"]
    }
   }

   // proveravamo da li je odabrana opcija za brisanje pojedinačnog proizvoda iz korpe
   if($_POST["operacija"] == 'obrisi') {
     foreach($_SESSION["korpa"] as $kljuc => $vrednost) {
       if($vrednost["proizvod_id"] == $_POST["proizvod_id"]) { // proveravamo da li se "id" proizvoda koji se nalazi u korpi poklapa sa "id"-em proizvoda koji brišemo
         unset($_SESSION["korpa"][$kljuc]); // ukoliko je prethodni uslov ispunjen, brišemo pojedinačan proizvod iz korpe
       }
     }
   }

   // proveravamo da li je odabrana opcija za umanjivanje količine pojedinačnog proizvoda u korpi
   if($_POST["operacija"] == 'umanji') {
     foreach($_SESSION["korpa"] as $kljuc => $vrednost) {
       if($vrednost["proizvod_id"] == $_POST["proizvod_id"]) { // proveravamo da li se "id" proizvoda koji se nalazi u korpi poklapa sa "id"-em proizvoda čiju količinu umanjujemo
         $_SESSION["korpa"][$kljuc]['proizvod_kolicina'] = $_SESSION["korpa"][$kljuc]['proizvod_kolicina'] - 1;
         if($_SESSION["korpa"][$kljuc]['proizvod_kolicina'] == 0) {
           unset($_SESSION["korpa"][$kljuc]); // ukoliko količina pojedinačnog proizvoda dođe do nule, brišemo taj proizvod iz korpe
         }
       }
     }
   }

   // proveravamo da li je odabrana opcija za uvećavanje količine pojedinačnog proizvoda u korpi
   if($_POST["operacija"] == 'uvecaj') {
     foreach($_SESSION["korpa"] as $kljuc => $vrednost) {
       if($vrednost["proizvod_id"] == $_POST["proizvod_id"]) { // proveravamo da li se "id" proizvoda koji se nalazi u korpi poklapa sa "id"-em proizvoda čiju količinu uvećavamo
         $_SESSION["korpa"][$kljuc]['proizvod_kolicina'] = $_SESSION["korpa"][$kljuc]['proizvod_kolicina'] + 1;
       }
     }
   }

   // proveravamo da li je odabrana opcija za pražnjenje korpe
   if($_POST["operacija"] == 'isprazni') {
     unset($_SESSION["korpa"]); // praznimo kompletnu korpu
   }
  }
?>
