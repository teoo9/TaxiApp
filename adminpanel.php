<?php
require_once("classes.php");
session_start();
if(!isset($_SESSION['idkorisnika']) || !isset($_SESSION['statuskorisnika']) || $_SESSION['statuskorisnika']!="admin"){
  //echo "Nemate pristup ovoj stranici! Vratite se na <a href='prijava.php'>prijavu</a>.";
  
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
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>    
    <title>Admin panel</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href=css/styleAdmin.css>
    
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



 <div class="container-fluid">  
<div class="row justify-content-between">
<div class="col-2 meni">
    <table>
      <tr>
        <td><button class="btn-reserve btnAdmin" data-bs-toggle="modal" data-bs-target="#modal5">Dodaj taksistu</button> </td>
      </tr>
      <tr>
        <td><button class="btn-reserve btnAdmin" data-bs-toggle="modal" data-bs-target="#modal6">Izveštaj</button> </td>
      </tr>
      <tr>
        <td><button class="btn-reserve btnAdmin" data-bs-toggle="modal" data-bs-target="#modal7">Dodaj vozilo</button> </td>
      </tr>
      <tr>
        <td><button class="btn-reserve btnAdmin" data-bs-toggle="modal" data-bs-target="#modal8">Obriši vozilo</button> </td>
      </tr>
    </table>
  </div>
<div class="col-9 glavniMeni">
  <h4 class='text-center display-4'>TAKSISTI</h4>
<?php
$db=new Database();
$upit="SELECT * from vozac";
$rez=mysqli_query($db->connect(),$upit);
if(mysqli_num_rows($rez)!=0){
  while($row=mysqli_fetch_assoc($rez)){
echo "<div class='my-data'>Ime i prezime: {$row['IMEVOZACA']} {$row['PREZIMEVOZACA']}<br>Broj telefona:{$row['TELVOZACA']}<br>Adresa: {$row['ADRESAVOZACA']}<br>Broj dozvole:{$row['BRDOZVOLE']}<br>Datum zaposlenja:{$row['DATUMZAP']}<br>";
if($row['ZAPOSLEN']==1){
  echo " <button class='btn-reserve' onclick='otpusti({$row['IDVOZACA']})'>Otpusti</button>";
  echo "<div id='odgovorOtpust'></div>";
}
echo "</div>";
}
}

?>
</div>

</div>
 </div>  


 <!-- MODAL ZA DODAVANJE TAKSISTE  -->
 <div class="modal fade" id="modal5" tabindex="-1" aria-labelledby="dodajTaksistu" aria-hidden="true">
    <div id="dodajTaksistuModal" class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mojAuto">Dodaj taksistu </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">    
        
<form action="adminpanel.php" method="post" onsubmit="return false;">
<label for="ime">Unesite ime vozača</label>
<div class="mb-2"><input type="text" id="ime" name="ime"  required></div>
<label for="prezime">Unesite prezime vozača</label>
<div class="mb-2"><input type="text" id="prezime" name="prezime"  required></div>
<label for="telefon">Unesite broj telefona vozača</label>
<div class="mb-2"><input type="text" id="telefon" name="telefon" required></div>
<label for="adresa">Unesite adresu vozača</label>
<div class="mb-2"><input type="text" id="adresa" name="adresa" required></div>
<label for="pol">Unesite pol vozača</label>
<div class="mb-2"><input type="text" id="pol" name="pol" ></div>
<label for="pol">Unesite broj dozvole vozača</label>
<div class="mb-2"><input type="text" id="brDozvole" name="brDozvole" required></div>
<hr>
<h4>Informacije za nalog taksiste</h4>
<label for="email">Unesite e-mail adresu vozača</label>
<div class="mb-2"><input type="text" id="email" name="email" required></div>
<label for="lozinka">Unesite lozinku za vozača</label>
<div class="mb-2"><input type="password" id="lozinka" name="lozinka" required></div>
<div class="form-buttons"><input type="submit" value="Dodaj" onclick="dodajTaksistu()"></div>
</form>
<div id="odgovor"></div>
</div>
</div>
</div>
  </div>

<!-- MODAL ZA IZVEŠTAJE -->
<div class="modal fade" id="modal6" tabindex="-1" aria-labelledby="izvestaj" aria-hidden="true">
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
  <?php echo"  <button type='submit'  onclick='mesecniIzvestaj()' ><i class='far fa-paper-plane'></i></button> "?>
  </form>
</div>
<div class="svevoznje"></div>
<div class="greska"></div>
</div>
</div>
</div>
  </div>

  <!-- MODAL ZA DODAVANJE VOZILA -->
  <div class="modal fade" id="modal7" tabindex="-1" aria-labelledby="dodavanjeVozila" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rezervisiVoznju">Dodaj vozilo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">    
        <div class="container mt-5">
        <form action="adminpanel.php" method="post" onsubmit="return false;">
<label for="marka">Unesite marku</label>
<div class="mb-2"><input type="text" id="marka" name="marka"  required></div>
<label for="model">Unesite model</label>
<div class="mb-2"><input type="text" id="model" name="model"  required></div>
<label for="regbr">Unesite registracioni broj</label>
<div class="mb-2"><input type="text" id="regbr" name="regbr" required></div>
<label for="godProizvodnje">Unesite godinu proizvodnje</label>
<div class="mb-2"><input type="text" id="godProizvodnje" name="godProizvodnje" required></div>
<label for="datumReg">Unesite datum registracije</label>
<div class="mb-2"><input type="text" id="datumReg" name="datumReg" ></div>

<select name="vozac" id="vozac">
  <option value="0">--Izaberite vozača--</option>
  <?php
  $database=mysqli_connect("localhost","root","","taksi");
        $upitBaze="SELECT * FROM vozac WHERE zaposlen=true";
        $rezBaze=mysqli_query($database,$upitBaze);
        if(mysqli_num_rows($rezBaze)!=0){
            //$red=mysqli_fetch_assoc($rez);
            while($redBaze=mysqli_fetch_assoc($rezBaze)){
                echo "<option value='{$redBaze['IDVOZACA']}'>{$redBaze['IMEVOZACA']} {$redBaze['PREZIMEVOZACA']} </option>";
            }
          }
    ?>

 </select>
<div class="form-buttons"><input type="submit" value="Dodaj" onclick="dodajVozilo()"></div>
</form>
<div id="odgovor1"></div>
</div>

</div>
</div>
</div>
  </div>

<!-- MODAL ZA BRISANJE VOZILA -->
<div class="modal fade" id="modal8" tabindex="-1" aria-labelledby="brisanjeVozila" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rezervisiVoznju">Obriši vozilo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        <div class="modal-body">    
        <div >
        <form action="adminpanel.php" method="post" onsubmit="return false;">

<select name="vozilo" id="vozilo">
  <option value="0">--Izaberite vozilo za brisanje--</option>
  <?php
  $database1=mysqli_connect("localhost","root","","taksi");
        $upitBaze1="SELECT * FROM vozilo";
        $rezBaze1=mysqli_query($database1,$upitBaze1);
        if(mysqli_num_rows($rezBaze1)!=0){
            //$red=mysqli_fetch_assoc($rez);
            while($redBaze1=mysqli_fetch_assoc($rezBaze1)){
                echo "<option value='{$redBaze1['IDVOZILA']}'>{$redBaze1['MARKA']} {$redBaze1['MODEL']} {$redBaze1['REG_BR']} </option>";
            }
          }
    ?>

 </select>
<div class="form-buttons"><input type="submit" value="Obriši" onclick="obrisiVozilo()"></div>
</form>
<div id="odgovor2"></div>
</div>

</div>
</div>
</div>
  </div>


<script>
  function otpusti(id){
    let confirmed=confirm("Da li ste sigurni?");
    if(confirmed){
      $.post("ajax/ajax.php?funkcija=otpusti", {id:id}, function(response){
             $("#odgovorOtpust").html(response);
         })
    }
  }

  function dodajTaksistu(){
    let ime=$("#ime").val();
    let prezime=$("#prezime").val();
    let telefon=$("#telefon").val();
    let adresa=$("#adresa").val();
    let pol=$("#pol").val();
    let brDozvole=$("#brDozvole").val();
    let email=$("#email").val();
    let lozinka=$("#lozinka").val();
    $.post("ajax/ajax.php?funkcija=dodajTaksistu", {ime:ime,prezime:prezime,telefon:telefon,adresa:adresa,pol:pol,brDozvole:brDozvole,email:email,lozinka:lozinka}, function(response){
             $("#odgovor").html(response);
         })
  }

  function mesecniIzvestaj(){
      let broj_meseca=$("#broj_meseca").val();
      $.post("ajax/ajax.php?funkcija=mesecniIzvestaj1", {broj_meseca:broj_meseca}, function(response){
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

    function dodajVozilo(){
      let marka=$("#marka").val();
      let model=$("#model").val();
      let regbr=$("#regbr").val();
      let godProizvodnje=$("#godProizvodnje").val();
      let datumReg=$("#datumReg").val();
      let idvozaca=$("#vozac").val();
      $.post("ajax/ajax.php?funkcija=dodajVozilo", {marka:marka,model:model,regbr:regbr,godProizvodnje:godProizvodnje,datumReg:datumReg,idvozaca:idvozaca}, function(response){
             $("#odgovor1").html(response);
         })
    }

    function obrisiVozilo(){
      let idVozila=parseInt($("#vozilo").val());
      console.log(jQuery.type(idVozila));
      $.post("ajax/ajax.php?funkcija=obrisiVozilo", {idVozila:idVozila}, function(response){
             $("#odgovor2").html(response);
         })
    }
</script>
   

<script src="js/script.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/all.min.js"></script>
</body>
</html>