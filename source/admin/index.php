<!DOCTYPE html>
<html>
  <head>
    <title>Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="../javascript/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../stilovi/bootstrap.css">
    <link rel="stylesheet" href="../stilovi/stilovi.css">
  </head>
  <body>
    <div class="container-fluid">
      <?php
        require_once("../baza/konekcija.php");
        if(!korisnik_prijavljen()) {
          header('Location: prijava.php');
        }
      ?>
      <nav class="navbar navbar-default navbar-fixed-top" style="background: linear-gradient(to bottom, #003399 0%, #009933 100%)">
        <div class="container">
          <a href="index.php" class="navbar-brand" style="color: white">GameShop Admin</a>
          <ul class="nav navbar-nav">
            <li><a href="zanrovi.php" style="color: white">Žanrovi</a></li>
            <li><a href="kategorije.php" style="color: white">Kategorije</a></li>
            <li><a href="proizvodi.php" style="color: white">Proizvodi</a></li>
            <?php if(ovlascenje("admin")) { ?>
              <li><a href="korisnici.php" style="color: white">Korisnici</a></li>
            <?php } ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: white"><?php echo $korisnik_podaci["ime"]; ?>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="promena_lozinke.php">Promeni lozinku</a></li>
                <li><a href="odjava.php">Izloguj se</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
      <footer class="text-center" style="margin-top: 80px">&copy; Copyright 2015-2018 GameShop</footer>
    </div>
  </body>
</html>
