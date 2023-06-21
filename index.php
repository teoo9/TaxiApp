<?php
require_once("classes.php");
session_start();
if(!isset($_SESSION['idkorisnika']) && !isset($_SESSION['statuskorisnika'])){
  //echo "Nemate pristup ovoj stranici! Vratite se na <a href='prijava.php'>prijavu</a>.";
  //exit();
  header('Location:prijava.php');
  exit();
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
    <title>Početna</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styleVoznje.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
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

<?php
echo "<div class='my-data '><h1>MOJI PODACI</h1><br>";
$database=new Database();
$qry="SELECT * from korisnik WHERE IDKORISNIKA={$_SESSION['idkorisnika']}";
$result=mysqli_query($database->connect(),$qry);
$row=mysqli_fetch_assoc($result);
echo "Ime i prezime: {$row['IMEKORISNIKA']} {$row['PREZIMEKORISNIKA']}<br>Email:{$row['EMAILKORISNIKA']}<br>Telefon: {$row['TELKORISNIKA']}<br><button class='btn-reserve' data-bs-toggle='modal' data-bs-target='#modal2'>Izmeni</button></div>";
?>
<div class="container">
<div class="row justify-content-md-center">
<div class="col col-lg-2"><button class="btn-reserve" data-bs-toggle="modal" data-bs-target="#modal">Rezerviši vožnju<i class="fas fa-car"></i></button></div>
<div class="col-md-auto"></div>
<div class="col col-lg-2"><button class="btn-reserve" data-bs-toggle="modal" data-bs-target="#modal1">Moje vožnje<br> <i class="fas fa-car"></i></button></div>
</div>
</div>

<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="rezervisiVoznju" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rezervisiVoznju">Rezerviši vožnju</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">    
    <form method="post" class="forma1" name="forma" id="forma" onsubmit="return false;"> 
       <br>
       <div class="mb-3"><input type="text" id="polazak" name="polazak" placeholder="Unesite polaznu lokaciju" required></div>
       <div class="mb-3"><input type="text" id="odrediste" name="odrediste" placeholder="Unesite odredišnu lokaciju" required></div>
       <div class="mb-3">


 <select name="vozac" id="vozac">
  <option value="0">--Izaberite vozača--</option>
  <?php
  $db=mysqli_connect("localhost","root","","taksi");
        $upit="SELECT * FROM vozac WHERE zaposlen=true";
        $rez=mysqli_query($db,$upit);
        if(mysqli_num_rows($rez)!=0){
            //$red=mysqli_fetch_assoc($rez);
            while($red=mysqli_fetch_assoc($rez)){
                echo "<option value='{$red['IDVOZACA']}'>{$red['IMEVOZACA']} {$red['PREZIMEVOZACA']} </option>";
            }
          }
    ?>

 </select>
 
<?php
echo "<input type='text' id='id' name='id' hidden value='{$_SESSION['idkorisnika']}'>";
?>
</div>

  <div class="form-buttons"><input type="submit" value="Rezerviši" onclick="rezervacija()"></div>
  <div id="odgovor"></div>
</form>
</div>
</div>
</div>
  </div>




  <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="mojeVoznje" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rezervisiVoznju">Moje vožnje</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">    
        <?php
$db=new Database();
$upit="CALL vožnjeKorisnika({$_SESSION['idkorisnika']})";
$rez=mysqli_query($db->connect(),$upit);
if(mysqli_num_rows($rez)!=0){
 //echo "<div class='modal-dialog modal-dialog-scrollable'>";
    //$row=mysqli_fetch_assoc($rez);
    while($row=mysqli_fetch_assoc($rez)){
        echo "<div class='voznje'>Polazak: {$row['POLAZAK']};<br> odredište: {$row['ODREDISTE']};<br> udaljenost: {$row['UDALJENOST']} km;<br> vreme početka: {$row['VREMEPOCETKA']};<br> vreme završetka: {$row['VREMEZAVRSETKA']};<br> status: {$row['STATUS']};<br>";
        $db1=new Database();
        $upit1="CALL taksistaPodaci({$row['IDVOZACA']})";
        $rez1=mysqli_query($db1->connect(),$upit1);
        $row1=mysqli_fetch_assoc($rez1);
        echo "Vozač: {$row1['IMEVOZACA']} {$row1['PREZIMEVOZACA']}<br>";
        $db2=new Database();
        $upit2="SELECT * from tarifa";
        $rez2=mysqli_query($db2->connect(),$upit2);
        $row2=mysqli_fetch_assoc($rez2);
        //$suma=$row['UDALJENOST']*$row2['CENAPOKM']+$row2['ULAZNACENA'];
        $db3=new Database();
        $upit3="SELECT cenaVoznje({$row['IDVOZNJE']}) as cena";
        $rez3=mysqli_query($db3->connect(),$upit3);
        $row3=mysqli_fetch_assoc($rez3);
        $suma=$row3['cena'];
        echo "Cena: {$suma} din</div>";
    }
    //echo "</div>";
}
?>
</div>
</div>
</div>
  </div>


  <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="izmena" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rezervisiVoznju">Izmeni podatke</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">    
        <?php
  
$dataB=new Database();
$upit4="SELECT * FROM korisnik WHERE IDKORISNIKA={$_SESSION['idkorisnika']}";
$rez4=mysqli_query($dataB->connect(),$upit4);
$row4=mysqli_fetch_assoc($rez4);
  echo "<form method='post' class='forma1' name='forma' id='forma' onsubmit='return false;'><div class='mb-3'><input type='text' id='ime' name='ime' value='{$row4['IMEKORISNIKA']}' required></div>
  <div class='mb-3'><input type='text' id='prezime' name='prezime' value='{$row4['PREZIMEKORISNIKA']}' required></div><div class='mb-3'><input type='text' id='email' name='email' value='{$row4['EMAILKORISNIKA']}' required></div><div class='mb-3'><input type='text' id='tel' name='tel' value='{$row4['TELKORISNIKA']}' required></div>";
  echo "<div class='form-buttons'><input type='submit' value='Izmeni' onclick='izmena({$_SESSION['idkorisnika']})'></div></form>";
  echo "<div id='odgovor1'></div>";
?>
</div>
</div>
</div>
  </div>

    <script>
      function rezervacija(){
        let polazak=$("#polazak").val();
        let odrediste=$("#odrediste").val();
        let vozac=$("#vozac").val();
        if(vozac=="0"){
          $("#odgovor").html("Izaberite vozaca!");
            return false;
        }
        let id=$("#id").val();
        $.post("ajax/ajax.php?funkcija=rezervacija", {id:id,polazak:polazak,odrediste:odrediste,vozac:vozac}, function(response){
             $("#odgovor").html(response);
         })
      }

      function izmena(id){
        let ime=$("#ime").val();
        let prezime=$("#prezime").val();
        let email=$("#email").val();
        let tel=$("#tel").val();
        $.post("ajax/ajax.php?funkcija=izmena", {id:id,ime:ime,prezime:prezime,email:email,tel:tel}, function(response){
             $("#odgovor1").html(response);
             window.location="index.php";
         })
      }
    </script>

<script src="js/script.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/all.min.js"></script>
  <script src="js/jQuery.js"></script>
</body>
</html>