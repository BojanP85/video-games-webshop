<?php
	require_once("../baza/konekcija.php");
	$id = $_POST["idProizvoda"];
	$slikaNaziv = $id."-".$_FILES["imeSlike"]["name"];
	$slikaPrivremenNaziv = $_FILES['imeSlike']['tmp_name'];

	if(move_uploaded_file($slikaPrivremenNaziv, "../slike/".$slikaNaziv)) {
		mysqli_query($link, "UPDATE proizvodi SET proizvodi.slika = '$slikaNaziv' WHERE proizvodi.id = $id");
		require_once("obrada_slike.php");
		obradaSlike($slikaNaziv);
	}
	$niz = array("poruka" => "ok");
	echo json_encode($niz);
?>
