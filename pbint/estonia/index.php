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
            <h3>Nordic BBQ<br><br></h3>
            <p class="font-weight-light mb-0">Kauri põik 2<br>
            Tallinn<br>
            Estonia 76403<br>
              <i class="icon-phone"> 37256288355</i><br>
              <i class="icon-envelope-open"> <span style="font-size:.9rem;">info@nordicbbq.ee</span></i>
            </p><br><br>
            <a class="btn btn-dark" href="http://www.nordicbbq.ee">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-3">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Homefy <br><br></h3>
            <p class="font-weight-light mb-0"> Sõpruse pst 255<br>
            Tallinn<br>
                Estonia 13414<br>
              <i class="icon-phone"> +372 58 444 644</i><br>
              <i class="icon-envelope-open"> <span style="font-size:.9rem;">tallinn@homefy.ee</span></i>
            </p><br><br>
            <a class="btn btn-dark" href="https://homefy.ee/?s=pit+boss&post_type=product">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-3">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Homefy <br><br></h3>
            <p class="font-weight-light mb-0">Ringtee 4<br>
            Tartu<br>
            Estonia 51013<br>
              <i class="icon-phone"> +372 56 861 884</i><br>
              <i class="icon-envelope-open"> <span style="font-size:.9rem;"> tartu@homefy.ee</span></i>
            </p><br><br>
            <a class="btn btn-dark" href="https://homefy.ee/?s=pit+boss&post_type=product">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
</div><br><br>
<div class="row">
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Grilli Guru </h3>
            <p class="font-weight-light mb-0">Allika tee 2<br>
            Peetri küla Harjumaa<br>
                Estonia 75312<br>
              <i class="icon-phone"> 3,725,104,855</i><br>
              <i class="icon-envelope-open"> <span style="font-size:.9rem;">alvar@grilliguru.ee</span></i>
            </p><br><br>
            <a class="btn btn-dark" href="https://grilliguru.ee/?s=pit+boss&post_type=product">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Veltekspert</h3>
            <p class="font-weight-light mb-0">Pärnu mnt 139e/11<br>
            Tallinn<br>
                Estonia 11317<br>
              <i class="icon-phone">+372 56 488 805</i><br>
              <i class="icon-envelope-open"> <span style="font-size:.9rem;"> info@veltekspert.ee</span></i>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.veltekspert.ee/otsing?q=pit+boss">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <!--<div class="col-lg-4">-->
        <!--<section class="features-icons bg-light text-center" id="dealer-section">-->
        <!--    <br>-->
        <!--    <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">-->
        <!--    <h3>HT Motors</h3>-->
        <!--    <p class="font-weight-light mb-0">Turu 41 a<br>-->
        <!--    Tartu<br>-->
        <!--        Estonia 50106<br>-->
        <!--      <i class="icon-phone">+372 51 999 034</i><br>-->
        <!--      <i class="icon-envelope-open"> <span style="font-size:.9rem;">tartu@veltmotocenter.ee</span></i>-->
        <!--    </p><br><br>-->
        <!--    <a class="btn btn-dark" href="http://www.veltmotocenter.ee">Shop Pit Boss Products</a><br><br>-->
        <!--</section>-->
        <!--</div>-->
</div><br><br>
<div class="row">
        <!--<div class="col-lg-4">-->
        <!--<section class="features-icons bg-light text-center" id="dealer-section">-->
        <!--    <br>-->
        <!--    <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">-->
        <!--    <h3>Espak  </h3>-->
        <!--    <p class="font-weight-light mb-0">Viadukti 42<br>-->
        <!--    Tallinn<br>-->
        <!--        Estonia 11313<br>-->
        <!--      <i class="icon-phone">3,726,512,301</i><br>-->
        <!--      <i class="icon-envelope-open"> <span style="font-size:.9rem;">info@espak.ee</span></i>-->
        <!--    </p><br><br>-->
        <!--    <a class="btn btn-dark" href="http://www.espak.ee">Shop Pit Boss Products</a><br><br>-->
        <!--</section>-->
        <!--</div>-->
        <div class="col-lg-4">
        <section class="features-icons bg-light text-center" id="dealer-section">
            <br>
            <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">
            <h3>Hobby Hall GROUP OÜ</h3>
            <p class="font-weight-light mb-0">Luite 19A<br>
            Tallinn<br>
                Estonia 11313<br>
              <i class="icon-phone"> 3,726,115,511</i><br>
              <i class="icon-envelope-open"> <span style="font-size:.9rem;">info@hansapost.ee</span></i>
            </p><br><br>
            <a class="btn btn-dark" href="https://www.hansapost.ee/search/?sn.q=pit%20boss">Shop Pit Boss Products</a><br><br>
        </section>
        </div>
        <!--<div class="col-lg-4">-->
        <!--<section class="features-icons bg-light text-center" id="dealer-section">-->
        <!--    <br>-->
        <!--    <img src='https://images.pitboss-grills.com/catalog/icons/mapicon.png' style="max-width:40px;">-->
        <!--    <h3>BBQ entertainment</h3>-->
        <!--    <p class="font-weight-light mb-0">Tartu mnt 80<br>-->
        <!--    Tallinn<br>-->
        <!--    Estonia 10112<br>-->
        <!--      <i class="icon-phone">3725225981 </i><br>-->
        <!--      <i class="icon-envelope-open"> <span style="font-size:.9rem;">enn@pull.ee</span></i>-->
        <!--    </p><br><br>-->
        <!--    <a class="btn btn-dark" href="http://www.bbqentertainment.com">Shop Pit Boss Products</a><br><br>-->
        <!--</section>-->
        <!--</div>-->
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