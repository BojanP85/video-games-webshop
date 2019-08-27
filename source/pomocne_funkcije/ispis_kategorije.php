<?php
  function ispis_kategorije($pk_id) {
    global $link;
    // ovim upitom pravimo novu tabelu pridruživanjem elemenata iz već postojeće tabele "kategorije"
    $nova_tabela = "SELECT tk.id AS 'tk_id', tk.kategorija AS 'top_kat', pk.id AS 'pk_id', pk.kategorija AS 'pod_kat'
             FROM kategorije pk
             INNER JOIN kategorije tk
             ON pk.top_kategorija = tk.id
             WHERE pk.id = $pk_id";
    $rezultat = mysqli_query($link, $nova_tabela);
    $kategorija = mysqli_fetch_assoc($rezultat);
    return $kategorija;
  }
?>
