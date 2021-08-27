<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Pit Boss</title>

  <!-- Bootstrap core CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<style>
    body{
        background-color:#000;
    }
    #dealer-section{
        height:100%;
    }
.crossfade > figure {
  animation: imageAnimation 75s linear infinite 0s;
  backface-visibility: hidden;
  background-size: cover;
  background-position: center center;
  color: transparent;
  left: 0px;
  opacity: 0;
  position: absolute;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: -9999;
}
.crossfade > figure:nth-child(1) { background-image: url('https://images.pitboss-grills.com/catalog/dealers/backgrounds/IMG_0844.jpg'); }
.crossfade > figure:nth-child(2) {
  animation-delay: 10s;
  background-image: url('https://images.pitboss-grills.com/catalog/dealers/backgrounds/IMG_8008.jpg');
}
.crossfade > figure:nth-child(3) {
  animation-delay: 25s;
  background-image: url('https://images.pitboss-grills.com/catalog/dealers/backgrounds/IMG_7593edit.jpg');
}
    /* LG IMAGE
.crossfade > figure:nth-child(5) {
  animation-delay: 45s;
  background-image: url('https://images.pitboss-grills.com/catalog/dealers/backgrounds/IMG_5806.jpg');
}*/
@keyframes 
imageAnimation {  
    0% {
        animation-timing-function: ease-in;
        opacity: 0;
    }
    5% {
        opacity: 1;
    }
    75% {
        animation-timing-function: ease-out;
        opacity:.80;
    }
    80%{
        opacity:0;
    }
}
</style>
        <div class="crossfade">
	<figure></figure>
	<figure></figure>
    <figure></figure>
	<figure></figure>
	<figure></figure>
	<figure></figure>
	<figure></figure>
</div>

</head>

<body><br><br>
<div class="container">
    <div class="row">
        <div class="col-lg-3 center-text"><center>
            <img src="https://images.pitboss-grills.com/catalog/logos/bigger-logo-no-shadow-01-31-2019.png" alt="Pit Boss Grills">
            <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Pick a Country
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
              <a class="dropdown-item" href="https://pitboss-grills.com">United States</a>
              <a class="dropdown-item" href="https://pitboss-grills.ca">Canada</a>
              <a class="dropdown-item" href="https://uk.pitboss-grills.com">United Kingdom</a>
              <a class="dropdown-item" href="https://cz.pitboss-grills.com">Czech Republic</a>
              <a class="dropdown-item" href="https://il.pitboss-grills.com">Israel</a>
              <a class="dropdown-item" href="https://ch.pitboss-grills.com">Switzerland</a>
              <a class="dropdown-item" href="https://pl.pitboss-grills.com">Poland</a>
              <a class="dropdown-item" href="https://ro.pitboss-grills.com">Romania</a>
              <a class="dropdown-item" href="https://gr.pitboss-grills.com">Germany</a>
              <a class="dropdown-item" href="https://hg.pitboss-grills.com">Hungary</a>
              <a class="dropdown-item" href="https://ml.pitboss-grills.com">Malta</a>
              <a class="dropdown-item" href="https://es.pitboss-grills.com">Spain</a>
              <a class="dropdown-item" href="https://ee.pitboss-grills.com">Estonia</a>
              <a class="dropdown-item" href="https://la.pitboss-grills.com">Latvia</a>
              <a class="dropdown-item" href="https://li.pitboss-grills.com">Lithuania</a>
              <a class="dropdown-item" href="https://fl.pitboss-grills.com">Finland</a>
              <a class="dropdown-item" href="https://de.pitboss-grills.com">Denmark</a>
              <a class="dropdown-item" href="https://sw.pitboss-grills.com">Sweden</a>
              <a class="dropdown-item" href="https://fr.pitboss-grills.com">France</a>
              <a class="dropdown-item" href="https://it.pitboss-grills.com">Italy</a>
            </div>
          </div>
            </center>
        </div>
        <div class="col-lg-3">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Dansons Europe</h3>
            <p class="font-weight-light mb-0">11 Rue Galvani <br>
                Paris, France 75017
            </p><br><br>
        </section>
        </div>
        <div class="col-lg-3">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Auchan<br><br></h3>
            <p class="font-weight-light mb-0">200 rue de la Recherche<br>
            Villeneuve d'Ascq, France 59491<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.auchan.fr/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-3">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Rue du commerce<br><br></h3>
            <p class="font-weight-light mb-0">44-50 avenue du Capitaine Glarner<br>
            Saint Ouen, France 93400<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.rueducommerce.fr/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
</div><br>
    <div class="row">
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Darty </h3>
            <p class="font-weight-light mb-0">9 Rue des Bateaux-Lavoirs<br>
            Ivry sur Seine, France 94200<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.darty.com/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>La Redoute</h3>
            <p class="font-weight-light mb-0">110 Rue de Blanchemaille<br>
            Roubaix, France 59100<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.laredoute.fr/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Leroy Merlin<br><br></h3>
            <p class="font-weight-light mb-0">Rue Chanzy <br>
            Lille, France 59712<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.leroymerlin.fr/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Boulanger </h3>
            <p class="font-weight-light mb-0">Avenue de la Motte <br>
            Lesquin, France 59810<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.boulanger.com/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Leclerc</h3>
            <p class="font-weight-light mb-0">Route de Paris<br>
            Lisieux, France 14100<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.e-leclerc.com/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Barbecue and Co<br><br></h3>
            <p class="font-weight-light mb-0">ZA La Briqueterie<br>
            Feucherolles, France 78810<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://barbecue-co.com/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Esprit Barbecue</h3>
            <p class="font-weight-light mb-0">Rue de Menin<br>
            Marquette-lez-Lille, France 59520<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.esprit-barbecue.fr/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Barbecue-France</h3>
            <p class="font-weight-light mb-0">Rue de l'Église<br>
            Gouffern en Auge, France 61310<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.barbecue-france.fr/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Cuisine Barbecue Concept<br><br></h3>
            <p class="font-weight-light mb-0">1195 Avenue Edouard Herriot<br>
            Villefrance sur Saone, France 69400<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.cuisinebarbecueconcept.fr/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Costco France</h3>
            <p class="font-weight-light mb-0">3 Avenue de Bréhat<br>
            Villebon-sur-Yvette, France 91140<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.costco.fr/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Plancha Bordeaux</h3>
            <p class="font-weight-light mb-0">34 Avenue de l'Argonn<br>
            Mérignac, France 33700<br>
            </p><br><br>
            <a class="btn btn-dark" href="http://plancha-bordeaux.fr/barbecue-and-co.php">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>France Pellets<br><br></h3>
            <p class="font-weight-light mb-0">Rue de l'Eglise, Silly en Gouffern<br>
            GOUFFERN EN AUGE, France 61310<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.barbecue-France.fr">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>La Maison du Barbecue</h3>
            <p class="font-weight-light mb-0">14 rue du Commandant Charcot, Le Forum<br>
            44700 ORVAULT, France<br>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.lamaisondubarbecue.com/">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-12 center-text">
            <p class="text-muted small mb-4 mb-lg-0">&copy; Pit Boss 2020. All Rights Reserved.</p>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>