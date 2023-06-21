<?php
session_start();
if(isset($_SESSION['idkorisnika']) && isset($_SESSION['statuskorisnika']) && $_SESSION['statuskorisnika']=="korisnik"){
    header('Location:index.php');
    exit();
}
else if(isset($_SESSION['idkorisnika']) && isset($_SESSION['statuskorisnika']) && $_SESSION['statuskorisnika']=="admin"){
    header('Location:adminpanel.php');
    exit();
  }

else if(isset($_SESSION['idvozaca']) && isset($_SESSION['statuskorisnika']) && $_SESSION['statuskorisnika']=="taksista"){
  header('Location:taksistiPocetna.php');
  exit();
}
require_once("classes.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
  <div class="row">
    <div class="col-md-12">
    <form action="prijava.php" method="post" class="forma"> 
        <header class="zaglavljeForme">
            <img src="img/logo.png" alt="Logo Taxico">
            <p>TAXICO - NA KLIK OD VAS</p>
        </header>
       <br>
      <h1>Prijava</h1>

      <label for="email">E-mail:</label>
      <input type="text" id="email" name="email" placeholder="Unesite Vaš email" required>

      <label for="password">Lozinka:</label>
      <input type="password" id="password" name="password" placeholder="Unesite Vašu lozinku" required>
      <p>Nemate nalog? <a href="registracija.php">Registrujte se</a>.</p>

      <div class="form-buttons"><input type="submit" value="Prijavi se"></div>
      
      <?php
    if(isset($_POST['email']) && isset($_POST['password'])){
      $email=$_POST['email'];
      $password=$_POST['password'];
      $db=new Database();
      $query="CALL proveriKorisnika('{$email}','{$password}')";
      $query1="CALL proveriVozaca('{$email}','{$password}')";
      $rez=mysqli_query($db->connect(),$query);
      $rez1=mysqli_query($db->connect(),$query1);
      if(mysqli_num_rows($rez)==1){
        $row=mysqli_fetch_array($rez,MYSQLI_ASSOC);
        $_SESSION['idkorisnika']=$row['IDKORISNIKA'];
        if($row['STATUSKORISNIKA']=='korisnik'){
          $_SESSION['statuskorisnika']="korisnik";
          header('Location: index.php');
        }
        else if($row['STATUSKORISNIKA']=='admin'){
          $_SESSION['statuskorisnika']="admin";
          header('Location:adminpanel.php');
        }
        
      }
      else if(mysqli_num_rows($rez1)==1){
        $row=mysqli_fetch_array($rez1,MYSQLI_ASSOC);
        $_SESSION['idvozaca']=$row['IDVOZACA'];
        $_SESSION['statuskorisnika']="taksista";
        header('Location:taksistiPocetna.php');
      }
      else{
        echo " <div class='alert alert-danger'>Pogrešni podaci za prijavu!</div>";
      }
    }
    ?>
    </form>

    
  </div>
  </div>

  <script src="js/bootstrap.min.js"></script>
  </body>
</html>


