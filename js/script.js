function proveraPodatakaZaRegistraciju(event){
    var divGreska = document.getElementById("div-greska");
    divGreska.style.display = "none";
    var forma=document.getElementById("forma");
    var ime=document.getElementById("ime").value;
    var prezime=document.getElementById("prezime").value;
    var email=document.getElementById("email").value;
    var telefon=document.getElementById("telefon").value;
    var lozinka=document.getElementById("password").value;
    var reImePrez=/\d/;
    var reEmail = /\S+@\S+\.\S+/;
    var reBrojTelefona = /^\d{9,10}$/;
    // var divGreska=document.createElement("div")
        if(ime=="" || prezime=="" || email=="" || telefon=="" || lozinka==""){
            divGreska.style.display = "block";
            divGreska.innerHTML = "Svi podaci su obavezni!";
            event.preventDefault();
            return false;
        }
            else if(!reEmail.test(email)) {
                divGreska.style.display = "block";
                divGreska.innerHTML = "Email nije validan!";
                    //alert("Email nije validan!");
                    event.preventDefault();
                    return false;
                }
                else if(reImePrez.test(ime) || reImePrez.test(prezime)){
                    divGreska.style.display = "block";
                    divGreska.innerHTML = "Ime i prezime ne smeju sadržati cifre!";
                    event.preventDefault();
                    return false;
                }
                else if(!reBrojTelefona.test(telefon)){
                    divGreska.style.display = "block";
                    divGreska.innerHTML = "Broj telefona ne sme sadržati karaktere i može imati od 9 do 10 cifara!";
                    event.preventDefault();
                    return false;
                }
                else {
                    divGreska.style.display = "none";
                    return true;
                }
		
}
