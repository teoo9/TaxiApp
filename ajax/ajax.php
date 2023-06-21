<?php
require_once '../classes.php';

$db=mysqli_connect("localhost", "root", "", "taksi");
$db1=mysqli_connect("localhost", "root", "", "taksi");
mysqli_query($db, "SET NAMES utf8");
$funkcija=$_GET['funkcija'];
if($funkcija=="registracija"){
   $odgovor="";
   if(isset($_POST['ime']) && isset($_POST['prezime']) && isset($_POST['email']) && isset($_POST['telefon']) && isset($_POST['password'])){
    $ime=$_POST['ime'];
    $prezime=$_POST['prezime'];
    $email=$_POST['email'];
    $telefon=$_POST['telefon'];
    $password=$_POST['password'];
    $qry="CALL proveraJedinstvenogEmaila('{$email}')";
    $rez=mysqli_query($db,$qry);
    if(mysqli_num_rows($rez)==0){
        $upit="CALL kreiranjeKorisnika('{$ime}','{$prezime}','{$email}','{$telefon}','{$password}')";
        $novirez=mysqli_query($db1,$upit);
        if(mysqli_affected_rows($db1)==1){
            $odgovor="Uspesno ste se registrovali!<a href='prijava.php'>Prijavite se</a>.";
        }
        else{
            $odgovor="NIJE UPISAN";
        }
        
    }
    else{
        $odgovor="Korisnik sa unetim e-mail-om već postoji!";
    }
   }
   
}


if($funkcija=="rezervacija"){
    $db2=mysqli_connect("localhost", "root", "", "taksi");
    mysqli_query($db2, "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['id']) && isset($_POST['polazak']) && isset($_POST['odrediste']) && isset($_POST['vozac'])){
        $id=$_POST['id'];
        $polazak=$_POST['polazak'];
        $odrediste=$_POST['odrediste'];
        $vozac=$_POST['vozac'];
        $trenutno_vreme = time();
        $broj_sati = floor($trenutno_vreme / 3600);
        $formirano_vreme = date('H', $broj_sati * 3600);
        if($formirano_vreme>23 || $formirano_vreme<5){
            $tarifa=2;
        }
        else{
            $tarifa=1;
        }
        $upit2="CALL rezervisiVoznju({$id},{$vozac},{$tarifa},'{$polazak}','{$odrediste}')";
        $novirez2=mysqli_query($db2,$upit2);
        if(mysqli_affected_rows($db2)==1){
            $odgovor="Uspešno rezervisana vožnja. Sačekajte dok vozač prihvati vožnju...";
        }
        else{
            $odgovor="NIJE UPISAN";
        }
    }
}
if($funkcija=="potvrda"){
    $db3=mysqli_connect("localhost", "root", "", "taksi");
    mysqli_query($db3, "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['udaljenost']) && isset($_POST['datumPocetka']) && isset($_POST['id'])){
        $id=$_POST['id'];
        $udaljenost=$_POST['udaljenost'];
        $datumPocetka=$_POST['datumPocetka'];
        $upit3="CALL potvrdaVoznje({$udaljenost},'{$datumPocetka}',{$id})";
        $novirez3=mysqli_query($db3,$upit3);
        if(mysqli_affected_rows($db3)>0){
            $odgovor="Vožnja uspešno završena! Vratite se na <a href='taksistiPocetna.php'>spisak vožnji.</a>";
        }
        else{
            $odgovor="nije";
        }
    }
}

if($funkcija=="izmena"){
    $db4=mysqli_connect("localhost","root","","taksi");
    mysqli_query($db4, "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['id']) && isset($_POST['ime']) && isset($_POST['prezime']) && isset($_POST['email']) && isset($_POST['tel'])){
        $id=$_POST['id'];
        $ime=$_POST['ime'];
        $prezime=$_POST['prezime'];
        $email=$_POST['email'];
        $tel=$_POST['tel'];
        $upit4="CALL izmenaPodataka({$id},'{$ime}','{$prezime}','{$email}','{$tel}')";
        $novirez4=mysqli_query($db4,$upit4);
        if(mysqli_affected_rows($db4)>0){
            $odgovor="Uspešno ste izmenili podatke!";
        }
        else{
            $odgovor="Podaci nisu izmenjeni!";
        }
    }
}

if($funkcija=="produziReg"){
    $db5=mysqli_connect("localhost","root","","taksi");
    mysqli_query($db5, "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['id'])){
        $id=$_POST['id'];
        $upit5="CALL produziReg({$id})";
        $novirez5=mysqli_query($db5,$upit5);
        if(mysqli_affected_rows($db5)>0){
            $odgovor="Uspešno ste produžili registraciju!";
        }
        else{
            $odgovor="Podaci nisu izmenjeni!";
        }
    }
}

if($funkcija=="mesecniIzvestaj"){
    $db6=mysqli_connect("localhost","root","","taksi");
    mysqli_query($db6, "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['broj_meseca']) && isset($_POST['id'])){
        $id=$_POST['id'];
        $broj_meseca=$_POST['broj_meseca'];
        $upit6="CALL mesecniIzvestaj({$broj_meseca},{$id})";
        $novirez6=mysqli_query($db6,$upit6);
        if(mysqli_num_rows($novirez6)>0){
        while($row=mysqli_fetch_assoc($novirez6)){
                 $odgovor.="<div class='voznje1'>Polazak: {$row['POLAZAK']};<br> odredište: {$row['ODREDISTE']};<br> udaljenost: {$row['UDALJENOST']} km;<br> vreme početka: {$row['VREMEPOCETKA']};<br> vreme završetka: {$row['VREMEZAVRSETKA']};<br> status: {$row['STATUS']};<br>";

                 $db7=mysqli_connect("localhost","root","","taksi");
                 $idVoznje=$row['IDVOZNJE'];
                 $upit7="SELECT cenaVoznje({$idVoznje}) as cena";
        $rez7=mysqli_query($db7,$upit7);
        $row2=mysqli_fetch_assoc($rez7);
        $suma=$row2['cena'];
        $odgovor.="Cena: {$suma}</div>";
            }
        }
            else{
                $odgovor="Nema podataka za uneti mesec!";
            }
       
        
    }
}

if($funkcija=="mesecniIzvestaj1"){
    $db11=mysqli_connect("localhost","root","","taksi");
    mysqli_query($db11, "SET NAMES utf8");
    $odgovor="";
    $ukupnaZarada=0;
    if(isset($_POST['broj_meseca'])){
        $broj_meseca=$_POST['broj_meseca'];
        $upit11="SELECT * FROM voznja WHERE month(VREMEPOCETKA)={$broj_meseca}";
        $novirez11=mysqli_query($db11,$upit11);
        if(mysqli_num_rows($novirez11)>0){
        while($row11=mysqli_fetch_assoc($novirez11)){
                 $odgovor.="<div class='voznje1'>Polazak: {$row11['POLAZAK']};<br> odredište: {$row11['ODREDISTE']};<br> udaljenost: {$row11['UDALJENOST']} km;<br> vreme početka: {$row11['VREMEPOCETKA']};<br> vreme završetka: {$row11['VREMEZAVRSETKA']};<br> status: {$row11['STATUS']};<br>";

                 $db12=mysqli_connect("localhost","root","","taksi");
                 $idVoznje=$row11['IDVOZNJE'];
                 $upit12="SELECT cenaVoznje({$idVoznje}) as cena";
        $rez12=mysqli_query($db12,$upit12);
        $row12=mysqli_fetch_assoc($rez12);
        $suma=$row12['cena'];
        $ukupnaZarada=$ukupnaZarada+$suma;
        $odgovor.="Cena: {$suma}<hr></div>";
            }
            $odgovor.="<b>UKUPNA ZARADA: {$ukupnaZarada}</b>";
        }
            else{
                $odgovor="Nema podataka za uneti mesec!";
            }
       
        
    }
}

if($funkcija=="otpusti"){
    $db8=mysqli_connect("localhost","root","","taksi");
    mysqli_query($db8, "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['id'])){
        $id=$_POST['id'];
        $upit8="CALL otpusti({$id})";
        $novirez8=mysqli_query($db8,$upit8);
        if(mysqli_affected_rows($db8)>0){
            $odgovor="Taksista je otpušten!";
        }
        else{
            $odgovor="Nije moguće otpuštanje!";
        }
    }
}

if($funkcija=="dodajTaksistu"){
    $db9=mysqli_connect("localhost","root","","taksi");
    mysqli_query($db9, "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['ime']) && isset($_POST['prezime']) && isset($_POST['telefon']) && isset($_POST['adresa']) && isset($_POST['pol']) && isset($_POST['brDozvole']) && isset($_POST['email']) && isset($_POST['lozinka'])){
        $ime=$_POST['ime'];
        $prezime=$_POST['prezime'];
        $telefon=$_POST['telefon'];
        $adresa=$_POST['adresa'];
        $pol=$_POST['pol'];
        $brDozvole=$_POST['brDozvole'];
        $email=$_POST['email'];
        $lozinka=$_POST['lozinka'];
        $upit9="SELECT max(IDVOZACA) as 'id' from vozac ";
        $novirez9=mysqli_query($db9,$upit9);
        $row9=mysqli_fetch_assoc($novirez9);
        $id=$row9['id']+100;
        $db10=mysqli_connect("localhost","root","","taksi");
        $upit10="CALL dodajTaksistu({$id},'{$ime}','{$prezime}','{$telefon}','{$adresa}','{$pol}','{$brDozvole}','{$email}','{$lozinka}')";
        $novirez10=mysqli_query($db10,$upit10);
        if(mysqli_affected_rows($db10)>0){
            $odgovor="Uspešno ste dodali taksistu!";
        }
        else{
            $odgovor="Nije dodat!";
        }
    }
}

if($funkcija=="dodajVozilo"){
    $db14=mysqli_connect("localhost","root","","taksi");
    mysqli_query($db14, "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['marka']) && isset($_POST['model']) && isset($_POST['regbr']) && isset($_POST['godProizvodnje']) && isset($_POST['datumReg']) && isset($_POST['idvozaca'])){
        $marka=$_POST['marka'];
        $model=$_POST['model'];
        $regbr=$_POST['regbr'];
        $godProizvodnje=$_POST['godProizvodnje'];
        $datumReg=$_POST['datumReg'];
        $vozac=$_POST['idvozaca'];

        $upit14="CALL dodajVozilo({$vozac},'{$marka}','{$model}','{$regbr}',{$godProizvodnje},'{$datumReg}')";
        $novirez14=mysqli_query($db14,$upit14);
        if(mysqli_affected_rows($db14)>0){
            $odgovor="Uspešno ste dodali vozilo!";
        }
        else{
            $odgovor="Nije dodato!";
        }
    }
}

if($funkcija=="obrisiVozilo"){
    $db15=new Database();
    mysqli_query($db15->connect(), "SET NAMES utf8");
    $odgovor="";
    if(isset($_POST['idVozila'])){
        $idVozila=$_POST['idVozila'];
        $db16=mysqli_connect("localhost","root","","taksi");
        $upit15="CALL obrisiVozilo({$idVozila})";
        mysqli_query($db16,$upit15);
        if(mysqli_affected_rows($db16)>0){
            $odgovor="Uspešno ste obrisali vozilo!";
        }
        else{
            $odgovor="Vozilo se ne može izbrisati!";
        }
    }
}
echo $odgovor;
?>