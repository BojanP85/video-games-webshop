<?php
  function prikaz_greske($greska) {
    $prikaz = '<ul style="background-color: red">';
    foreach($greska as $greske) {
      $prikaz.='<li style="color: white">'.$greske.'</li>';
    }
    $prikaz.='</ul>';
    return $prikaz;
  }
?>
