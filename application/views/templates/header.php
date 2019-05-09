<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ma Pizzeria</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url("assets/css/bootstrap.css");?>" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="<?php echo base_url("assets/css/main.css");?>" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <link rel="icon" href="<?= base_url("assets/img/favicon.ico");?>">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <?php
        if(isset($css)) {
            echo $css;
        }
    ?>
  </head>

  <body>
    <!-- Static navbar -->
    <div class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo site_url();?>">Ma Pizzeria [Alpha test]</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?= site_url();?>">Accueil</a></li>
            <li><a href="<?= site_url('forum');?>">Forum</a></li>
            <li><a href="<?= site_url('pages/regles');?>">Règles du jeu</a></li>
<?php
if($this->session->userdata('logged_in')) {
  if($this->session->userdata('hasRestaurant')) {
    echo '<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Votre Restau <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                <li><a href="'.site_url('jeu').'"><strong>'.$this->session->userdata('nomRestaurant').'</strong></a></li>
                <li class="divider"></li>
                <li><a href="'.site_url('jeu').'">Gestion du restau</a></li>
                <li><a href="'.site_url('jeu/recettes').'">Gestion des pizzas</a></li>
                <li class="divider"></li>
                <li><a href="'.site_url('jeu/marche/ingredients').'">Acheter des ingrédients</a></li>
                <li><a href="'.site_url('jeu/emploi').'">Marché de l\'emploi</a></li>
                <li class="divider"></li>
                <li><a href="'.site_url('jeu/journal/prive').'">Rapport</a></li>
                <li><a href="'.site_url('jeu/journal/public').'">Journal public</a></li>
              </ul>
            </li>';
  } else {
    echo '<li><a href="'.site_url('jeu').'">Jouer</a></li>';    
  }
?>
            <li><a href="<?= site_url('user');?>">Mon Profil</a></li>
            <li><a href="<?= site_url('user/deconnexion');?>">Déconnexion</a></li>
<?php
} else {
?>
            <li><a href="<?= site_url('user/connexion');?>">Connexion</a></li>
            <li><a href="<?= site_url('user/inscription');?>">Inscription</a></li>
<?php
}
?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
<?php if($this->session->flashdata('flashmessage')){
echo '<!--Flashmessage-->
<div class="alert alert-info alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <span class="glyphicon glyphicon-info-sign"></span> '. $this->session->flashdata('flashmessage') .'
</div>
<!-Fin fashmessage-->';
}
?>
    <!-- +++++ Post +++++ -->
    <div id="white">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">