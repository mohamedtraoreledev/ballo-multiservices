
// var btnVoirProduit = document.querySelector("#voircommande");
// var detailsCommande = document.querySelector(".detailspanier");
//     if(btnVoirProduit){
//         btnVoirProduit.addEventListener("click",(e)=>{
//             e.preventDefault();
//             detailsCommande.classList.toggle("active");
// });
//     }


// OUVERTURE MENU CACHER, AFFICHER L'URL SANS RELOAD DE LA PAGE
// document.addEventListener("DOMContentLoaded", ()=>{
//     var boutons = document.querySelectorAll(".voirproduitadmin");
//     // console.log("boutons : ",boutons);
//     if(boutons){
//         boutons.forEach((btn)=>{
//         btn.addEventListener("click",(e)=>{
//             e.preventDefault();
//         var parent = btn.closest(".produit");
//         var content = parent.querySelector(".contentproduit");
//         content.classList.toggle("activeadmin");
        
//         var url = new URL(btn.href);
//         var idgamme = url.searchParams.get("idgamme");
//         window.location.href =  "?idgamme=" + idgamme;

//         // document.querySelectorAll(".contentproduit").forEach(c => {
//         //         c.classList.remove("activeadmin");
//         //     });

//         //     content.classList.toggle("activeadmin");

//         });
//     });

// }
// });
    



var couleurs = document.querySelectorAll(".couleur");

if(couleurs){
        couleurs.forEach((c)=>{
        c.addEventListener("click", ()=>{
            var parent = c.closest(".allproduit");
            var image = parent.querySelector(".imageProduit");
            var nouvelleImage = c.getAttribute("data-img");
            image.src = nouvelleImage;
            var couleurUser = c.getAttribute("data-couleur");
            var lienDetail =  parent.querySelector(".detail");
            if(lienDetail){
            var urlDetail = new URL(lienDetail.href);
            urlDetail.searchParams.set("couleur",couleurUser);
            lienDetail.href = urlDetail.toString();
            }
        });
    });
}

// var couleurs = document.querySelectorAll(".couleur");
// var lien = parent.querySelector(".SuppCoul");
    


    if(couleurs){

        // if(lienModif){
        //                 var urlmodif = new URL(lienModif.href);
        //                 lienModif.addEventListener("click",(e)=>{
        //                     e.preventDefault();
        //                     if(confirm("Vous allez modifier ce produit ?")){
        //                         window.location.href = lienModif.href;
        //                     }
        //                 })
        //             }
            couleurs.forEach((c)=>{
                c.addEventListener("click",()=>{
                    var parent = c.closest(".allproduitmin");
                    var image = parent.querySelector(".imageProduit");
                    var nouvelleImage = c.getAttribute("data-img");
                    image.src = nouvelleImage;
                    var stock = parent.querySelector("strong");
                    var couleur = parent.querySelector(".coul");
                    var lien = parent.querySelector(".SuppCoul");
                    var lienModel = parent.querySelector(".suppModel");
                    var lienModif = parent.querySelector(".ModifModel");
                    var affstock = c.getAttribute("data-stock");
                    var affcouleur = c.getAttribute("data-couleur");
                    stock.innerHTML = affstock;
                    couleur.innerHTML = affcouleur;
                    if(lien){
                    var url = new URL(lien.href);
                    url.searchParams.set("couleur",affcouleur);
                    lien.href = url.toString();
                    console.log("APRÈS :", lien.href);
                    lien.addEventListener("click",(e)=>{
                        e.preventDefault();
                        if(confirm("Voulez-vous vraiment supprimer cette couleur ? ")){
                            window.location.href = lien.href;
                        }
                    });
                    }
                    if(lienModel){
                        var urlmod = new URL(lienModel.href);
                        // lienModel.href = url.toString();
                        console.log("model = "+lienModel.href);
                        lienModel.addEventListener("click",(e)=>{
                            e.preventDefault();
                            if (confirm("Voulez-vous supprimer ce model ?")){
                                window.location.href = lienModel.href;
                            }
                        })
                    }
                    if(lienModif){
                        var urlmodif = new URL(lienModif.href);
                        urlmodif.searchParams.set("couleur",affcouleur);
                        lienModif.href = urlmodif.toString();
                        lienModif.addEventListener("click",(e)=>{
                            e.preventDefault();
                            if(confirm("Vous allez modifier ce produit ?")){
                                window.location.href = lienModif.href;
                            }
                        })
                    }
                });

                
            });
        }



var form = document.querySelector(".formajoutproduit");
var closebtn = document.querySelector("#closebtn");
var ajouterbtn = document.querySelector(".espaceajoutproduit");
var page = document.querySelector(".pageadmin");
if(ajouterbtn){
    ajouterbtn.addEventListener("click",()=>{
    form.classList.toggle("activeform");
    })
    closebtn.addEventListener("click",()=>{
        form.classList.remove("activeform");
    })

}

var password = document.getElementById("mdp");

if(password){
    var voirpass = document.getElementById("btnvoir");
    var passvalue = password.value;
    var form = document.querySelector(".form");
    var msg = document.getElementById("msg");
    var res1 = /[A-Z]+/;
    var res2 = /[0-9]+/;
password.addEventListener("input",()=>{
    voirpass.innerHTML = "visibility";
})
voirpass.addEventListener("click",()=>{
    if(password.type==="password"){
        password.type="text";
        voirpass.innerHTML = "visibility_off";
    }else{
        password.type = "password";
        voirpass.innerHTML = "visibility";
    }
})
form.addEventListener("submit",(e)=>{
    var passvalue = password.value;
    if(passvalue.length<8){
        msg.innerHTML = "Votre mot de passe doit contenir au moins 8 caractères";
        msg.style.color = "red";
        e.preventDefault();
    }else if(res1.test(passvalue)==false|| res2.test(passvalue)==false){
        msg.innerHTML = "Votre mot de passe doit avoir une majuscule et un chiffre";
        msg.style.color = "red";
        e.preventDefault();
    }
    else{
        msg.innerHTML = "ok";
        msg.style.color = "green";
    }

    
})

password.addEventListener("input",()=>{
    var passvalue = password.value;
    if(res1.test(passvalue)==true){
        msg.innerHTML = "Majuscule ok";
        msg.style.color = "green";
    }else if(res2.test(passvalue)==true){
        msg.innerHTML = "Chiffre ok";
        msg.style.color = "green";
    }
})
}


function ajouterCouleur(){
    var div = document.createElement("div");
    div.classList.add("variante");

    div.innerHTML = `<label for="couleur">Couleur</label><br>
                            <input type="text" name="couleur[]" id="couleur" placeholder="rouge"><br>

                            <label for="stock">Stock disponible</label><br>
                            <input type="text" name="stock[]" id="stock" placeholder="7"><br>

                            <label for="image">Image du modèl</label><br>
                            <input type="file" name="image[]" id="image"><br> `

    document.querySelector(".varianteconteneur").appendChild(div);
}
   
