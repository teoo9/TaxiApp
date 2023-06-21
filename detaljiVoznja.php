<?php
require_once("classes.php");
session_start();
if(!isset($_SESSION['idvozaca']) ){
  echo "Nemate pristup ovoj stranici! Vratite se na <a href='prijava.php'>prijavu</a>.";
  exit();
  //header('Location:prijava.php');
}


if(isset($_POST['odjava'])){
  session_unset();
  session_destroy();
  header("Location: prijava.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>    
    <title>Panel za taksiste</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styleVoznje.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<!-- NAVIGACIJA -->
<nav class="navbar navbar-expand-lg navbar-light moj_navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img class="logo" src="img/logo1.png" alt="Logo"></a>
    <div class="navbar-text mx-auto text-center" style="flex-grow: 1;">
      <span style="text-transform: uppercase; font-size: 1.5rem;">TAXICO</span>
    </div>
    <form action="index.php" method="post" class="ms-auto">
      <button name="odjava" class="btn btn-link"><i class="fas fa-sign-out-alt"></i></button>
    </form>
  </div>
</nav>

<form method="post" class="forma" id="forma" onsubmit="return false;">
        <label for="broj">Unesite pređenu kilometražu:</label>
        <input type="number" id="udaljenost" name="udaljenost" step="0.01" required><br>

        <label for="datum">Unesite datum i vreme početka vožnje:</label>
        <input type="datetime-local" id="datumPocetka" name="datumPocetka" required><br>

        <input type="number" name="id" id="id" value="<?php echo $_GET['id']?>" hidden>
        <input type="submit" value="Potvrdi" onclick="potvrdaVoznje()">
        <div id="odgovorPotvrde"></div>
    </form>
    

   <script>
    function potvrdaVoznje(){
        let udaljenost=$("#udaljenost").val();
    let datumPocetka=$("#datumPocetka").val();
    let id=$("#id").val();
    $.post("ajax/ajax.php?funkcija=potvrda", {udaljenost:udaljenost,datumPocetka:datumPocetka,id:id}, function(response){
             $("#odgovorPotvrde").html(response);
         })
    }
    
    </script>

<script src="js/script.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/all.min.js"></script>
</body>
</html>