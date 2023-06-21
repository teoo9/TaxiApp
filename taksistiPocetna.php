<?php
require_once("classes.php");
session_start();
if(!isset($_SESSION['idvozaca']) ){
  echo "Nemate pristup ovoj stranici! Vratite se na <a href='prijava.php'>prijavu</a>.";
  exit();
 // header('Location:prijava.php');
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
  <style>
    
.voznje1{
    border: 1px solid #000 ; 
    background-color: rgba(255, 255, 255, 0.5); 
    border-radius: 10px; 
    padding: 15px;
    width: 80%; 
    max-width: 600px; 
    margin: 0 auto;
    margin-top: 10px;
    
    }

    .naslov {
  color:purple;
  margin:10px;
  padding:10px;
  font-weight:bold;
}
    
  </style>

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

<h3 class="text-center display-4 naslov">Dobro došli 
<?php
$db3=new Database();
$upit3="CALL taksistaPodaci({$_SESSION['idvozaca']})";
$rez3=mysqli_query($db3->connect(),$upit3);
if(mysqli_num_rows($rez3)!=0){
  $row3=mysqli_fetch_assoc($rez3);
  echo "{$row3['IMEVOZACA']} {$row3['PREZIMEVOZACA']} </h3>";
}
?>

<!-- SPISAK REZERVISANIH VOŽNJI -->
        <?php
$db=new Database();
$upit="CALL vožnjeVozačaPrihvati({$_SESSION['idvozaca']})";
$rez=mysqli_query($db->connect(),$upit);
if(mysqli_num_rows($rez)!=0){
 
    //$row=mysqli_fetch_assoc($rez);
    while($row=mysqli_fetch_assoc($rez)){
        echo "<div class='voznje'>Polazak: {$row['POLAZAK']};<br> odredište: {$row['ODREDISTE']};<br> udaljenost: {$row['UDALJENOST']} km;<br> vreme početka: {$row['VREMEPOCETKA']};<br> vreme završetka: {$row['VREMEZAVRSETKA']};<br> status: {$row['STATUS']};<br>";
        $idVoznje=$row['IDVOZNJE'];
        $db2=new Database();
        $upit2="SELECT cenaVoznje({$idVoznje}) as cena";
        $rez2=mysqli_query($db2->connect(),$upit2);
        $row2=mysqli_fetch_assoc($rez2);
        $suma=$row2['cena'];
        echo "Cena: {$suma} din";
        if($row['STATUS']=="na čekanju"){
          echo "<div style='display: flex; justify-content: space-between;'><div></div><a href='detaljiVoznja.php?id={$row['IDVOZNJE']}'><button class='btn btn-dark'>Prihvati vožnju</button></a></div>";
        }
        echo "</div>";
    }
    
}
?>

<!-- DUGMAD ZA MODALE -->
  <div class="text-center">
<table style="margin: 0 auto;">
  <tr>
    <td style="width: 33%;">
    <button class="btn-reserve" data-bs-toggle="modal" data-bs-target="#modal4">Moj auto <i class="fas fa-car"></i></button>
    </td>
    <td style="width: 33%;">
    <button class="btn-reserve" data-bs-toggle="modal" data-bs-target="#modal3">Izveštaj <i class="fas fa-car"></i></button>
    </td>
  </tr>
</table>
</div>

<!-- MODAL ZA IZVEŠTAJE -->
  <div class="modal fade" id="modal3" tabindex="-1" aria-labelledby="izvestaj" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rezervisiVoznju">Izveštaj</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">    
        <div class="container mt-5">
  <h2>Unesite broj meseca za koji želite izveštaj:</h2>
  <form action="taksistiPocetna.php" method="post" onsubmit="return false;">
    <input type="number" min="0" max="12" name="broj_meseca" id="broj_meseca" required>
  <?php echo"  <button type='submit'  onclick='mesecniIzvestaj({$_SESSION['idvozaca']})' ><i class='far fa-paper-plane'></i></button> "?>
  </form>
</div>

<?php
$db=new Database();
$upit="CALL vožnjeVozača({$_SESSION['idvozaca']})";
$rez=mysqli_query($db->connect(),$upit);
if(mysqli_num_rows($rez)!=0){
  echo "<div class='svevoznje'>";
    //$row=mysqli_fetch_assoc($rez);
    while($row=mysqli_fetch_assoc($rez)){
      if($row['STATUS']=="završena"){
        echo "<div id='voznje1' class='voznje1'>Polazak: {$row['POLAZAK']};<br> odredište: {$row['ODREDISTE']};<br> udaljenost: {$row['UDALJENOST']} km;<br> vreme početka: {$row['VREMEPOCETKA']};<br> vreme završetka: {$row['VREMEZAVRSETKA']};<br> status: {$row['STATUS']};<br>";
        $idVoznje=$row['IDVOZNJE'];
        $db2=new Database();
        $upit2="SELECT cenaVoznje({$idVoznje}) as cena";
        $rez2=mysqli_query($db2->connect(),$upit2);
        $row2=mysqli_fetch_assoc($rez2);
        $suma=$row2['cena'];
        echo "Cena: {$suma} din";
        // if($row['STATUS']=="na čekanju"){
        //   echo "<div style='display: flex; justify-content: space-between;'><div></div><a href='detaljiVoznja.php?id={$row['IDVOZNJE']}'><button class='btn btn-outline-primary'>Prihvati vožnju</button></a></div>";
        // }
        echo "</div>";
       
      }
    }
    
    echo "</div>";
    echo "<div id='greska'></div>";
}
?>

</div>
</div>
</div>
  </div>

<!-- MODAL ZA INFO O VOZILU -->
  <div class="modal fade" id="modal4" tabindex="-1" aria-labelledby="mojAuto" aria-hidden="true">
    <div id="mojAutoModal" class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mojAuto">Informacije o vozilu </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">    
        

<?php
$db1=new Database();
$upit1="CALL voziloInfo({$_SESSION['idvozaca']})";
$rez1=mysqli_query($db1->connect(),$upit1);
if(mysqli_num_rows($rez1)!=0){
  $row1=mysqli_fetch_assoc($rez1);
  if($row1['SLIKA']!=NULL){
    echo "<div class='text-center'><img src='img/{$row1['SLIKA']}'></img></div>";
  }
 echo "<div class='text-center'>Vozilo: {$row1['MARKA']} {$row1['MODEL']}<br>Registracioni broj: {$row1['REG_BR']}<br>Godina proizvodnje: {$row1['GODINAPROIZVODNJE']}<br>Datum registracije: {$row1['DATUMREG']}<br><button class='btn-reserve' onclick='produziReg({$row1['IDVOZILA']})'>Produži registraciju</button></div>";
    echo "<div id='odgovorReg'></div>";
}
?>

</div>
</div>
</div>
  </div>

  <script>
    function produziReg(id){
      $.post("ajax/ajax.php?funkcija=produziReg", {id:id}, function(response){
             $("#odgovorReg").html(response);
         })
    }

    function mesecniIzvestaj(id){
      let broj_meseca=$("#broj_meseca").val();
      $.post("ajax/ajax.php?funkcija=mesecniIzvestaj", {broj_meseca:broj_meseca,id:id}, function(response){
        if (response.includes("Nema podataka za uneti mesec!")) {
          $(".svevoznje").empty();  
          $(".svevoznje").css("border", "none");
          $(".voznje1").css("border", "none");
          $("#greska").html(response);
        }
        else{
          $("#greska").empty();
           $(".svevoznje").empty();    
        $(".svevoznje").html(response);
        }
       
         })
    }
  </script>
   

<script src="js/script.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/all.min.js"></script>
</body>
</html>