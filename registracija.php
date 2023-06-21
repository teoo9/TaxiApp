<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>    
    <title>Registracija</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
  <div class="row">
    <div class="col-md-12">
    <form method="post" class="forma" id="forma" onsubmit="return false;"> 
        <header class="zaglavljeForme">
            <img src="img/logo.png" alt="Logo Taxico">
            <p>TAXICO - NA KLIK OD VAS</p>
        </header>
       <br>
      <h1>Registracija</h1><br>
      <label for="ime">Ime:</label>
  <input type="text" id="ime" name="ime" placeholder="Unesite Vaše ime" required><br>

  <label for="prezime">Prezime:</label>
  <input type="text" id="prezime" name="prezime" placeholder="Unesite Vaše prezime" required><br>

  <label for="email">E-mail:</label>
  <input type="email" id="email" name="email" placeholder="example@gmail.com" required>

  <label for="telefon">Broj telefona:</label>
  <input type="tel" id="telefon" name="telefon" placeholder="Unesite Vaš broj telefona" required><br>

  <label for="password">Lozinka:</label>
  <input type="password" id="password" name="password" placeholder="Unesite Vašu lozinku" required><br>

  <div class="form-buttons"><input type="submit" value="Registruj se" onclick="proveraPodatakaZaRegistraciju(event);registracija()"></div>
  <div id="div-greska" class="alert alert-danger" style="display: none;"></div>
  <div id="odgovor"></div>
</form>
</div>
  </div>



  
  <script>
    function registracija(){
      let ispravniPodaci=proveraPodatakaZaRegistraciju();
      if(ispravniPodaci){
        let ime=$("#ime").val();
      let prezime=$("#prezime").val();
      let email=$("#email").val();
      let telefon=$("#telefon").val();
      let password=$("#password").val();
       $.post("ajax/ajax.php?funkcija=registracija", {ime:ime,prezime:prezime,email:email,telefon:telefon, password:password}, function(response){
             $("#odgovor").html(response);
         })
      }
      }
      
  </script>
  <script src="js/script.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

  
  </body>
</html>
