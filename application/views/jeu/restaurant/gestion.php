<?php
if(isset($retour)) { echo $retour; }
?>

<div class="row mt">
    <div class="col-lg-6">
        <h4>Restaurant <em>&laquo;<?= $nom;?>&raquo;</em></h4>
        <p>Bienvenue sur le page de gestion de votre restaurant.<br/>
            Vous pouvez accéder aux ordres suivants :</p>
        <!-- Ouverture -->
        <div class="col-xs-12 col-sm-6 col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Ouverture du restaurant
                </div>
                <div class="panel-body">
                    <p>
                        Ouvrez votre restaurant pour recevoir vos clients.<br/>
                        Vous pouvez ouvrir votre restaurant encore <strong><?= $open . ' / ' . $openMax;?> fois</strong>.
                    </p>
                </div>
                <div class="panel-footer">
                    <a href="<?= site_url('jeu/restaurant/ouvrir');?>">
                        <button class="btn btn-success" type="button">
                            <i class="fa fa-bullhorn"></i> Vers l'ouverture <i class="fa fa-angle-double-right"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <!-- /Ouverture -->
        <!-- Ingrédients -->
        <div class="col-xs-12 col-sm-6 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <p>
                        Achetez les ingrédients pour réaliser les
                        recettes de vos pizzas.
                    </p>
                </div>
                <div class="panel-footer">
                    <a href="<?= site_url('jeu/marche/ingredients');?>">
                        <button class="btn btn-success" type="button">
                            <i class="fa fa-shopping-cart"></i> Marché des ingrédients <i class="fa fa-angle-double-right"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>        
        <!-- /Ingrédients -->
        <!-- Recettes -->
        <div class="col-xs-12 col-sm-6 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <p>
                        Gérez les recettes à pizzas de votre restaurant.<br/><br/>
                    </p>
                </div>
                <div class="panel-footer">
                    <a href="<?= site_url('jeu/recettes');?>">
                        <button class="btn btn-success" type="button">
                            <i class="fa fa-cutlery"></i> Mes recettes <i class="fa fa-angle-double-right"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <!-- /Recettes -->
        <!-- Emploi -->
        <div class="col-xs-12 col-sm-6 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <p>
                        Recrutez des salariés pour améliorer votre restaurant.<br/><br/>
                    </p>
                </div>
                <div class="panel-footer">
                    <a href="<?= site_url('jeu/emploi');?>">
                        <button class="btn btn-success" type="button">
                            <i class="fa fa-users"></i> Recruter <i class="fa fa-angle-double-right"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <!-- /Emploi -->
        <!-- Emploi -->
        <div class="col-xs-12 col-sm-6 col-lg-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <p>
                        Envoyez des voyous casser la concurrence.<br/><br/>
                    </p>
                </div>
                <div class="panel-footer">
                    <a href="<?= site_url('jeu/attaquer');?>">
                        <button class="btn btn-success" type="button">
                            <i class="fa fa-bomb"></i> Vandalisme <i class="fa fa-angle-double-right"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <!-- /Emploi -->
    </div><!-- /colg-lg-6 -->

    <?php
        $pourcentageSante = ($etat * 100) / $etatMax;
        $pourcentageStock = ($stockOccupe * 100) / $stock;
    ?>

    <div class="col-lg-6">
        <h4>Etat :</h4>
        <p>
            Argent en caisse : <?= number_format($argent, 0, ',', ' ');?> <?= pizzaMoney();?> -
            Capacité maximum : <?= $capacite;?> couverts.
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#agrandir">
                <i class="fa fa-arrows"></i> Agrandir
            </button>
        </p>
        <p>Santé du bâtiment - (<?= $etat.' / '.$etatMax; ?>)
            <div class="progress">
                <div style="width: <?= $pourcentageSante;?>%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?= $pourcentageSante;?>" role="progressbar" class="progress-bar progress-bar-theme">
                    <span class="sr-only"><?= $pourcentageSante; ?></span>
                </div>
            </div>
            <!-- Button trigger modal -->
            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#ameliorer">
                <i class="fa fa-ambulance"></i> Améliorer
            </button>
             <?= ($etat < $etatMax) ? '
            <!-- Button trigger modal -->
            <button class="btn btn-danger btn-lg" data-toggle="modal" data-target="#reparer">
              <i class="fa fa-wrench"></i> Réparer
            </button>' : '';?>
        </p>
        <p>&Eacute;tat du stock - (<?= $stockOccupe.' / '.$stock; ?>)
            <div class="progress">
                <div style="width: <?= $pourcentageStock;?>%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?= $pourcentageStock;?>" role="progressbar" class="progress-bar progress-bar-theme">
                    <span class="sr-only"><?= $pourcentageStock; ?></span>
                </div>
            </div>
            <!-- Button trigger modal -->
            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#augmenterStock">
                <i class="fa fa-cubes"></i> Augmenter
            </button>
            <button class="btn btn-success btn-lg" data-toggle="modal" data-target="#voirStock">
                <i class="fa fa-eye"></i> Voir le stock
            </button>
        </p>

        <p>
            Notoriété : <?= $notoriete;?> (<?= $experience;?> / <?= $xpRestant;?> XP)

            <div class="progress">
                <div style="width: <?= $pourcentageNotoriete;?>%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?= $pourcentageNotoriete;?>" role="progressbar" class="progress-bar progress-bar-theme">
                    <span class="sr-only"><?= $pourcentageNotoriete; ?></span>
                </div>
            </div>
        </p>


    </div><!-- /col-lg-6 -->
</div>

<!-- Amélioration -->
<div class="modal fade" id="ameliorer" tabindex="-1" role="dialog" aria-labelledby="ModalAmeliorer" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
        <h4 class="modal-title" id="ModalAmeliorer">Améliorer la santé du bâtiment</h4>
      </div>
      <div class="modal-body">
        Prévoyez et anticipez !<br/>
        Améliorer l'infrastructure de votre restaurant pour vous permettre de voir large.<br/>
        Vous ne pouvez pas ajouter plus de 100 points d'un coup.<br/>

        <?php 
        $attribute = array('class' => 'form-inline', 'role' => 'form');
        echo form_open('jeu/restaurant/gestion', $attribute);?>        
          <div class="form-group">
            <label class="sr-only" for="nombre">Combien souhaitez vous en ajouter ?</label>
            <input type="number" class="form-control inputAmeliorer" id="nombre" name="nombre" placeholder="10" required>
            <p class="text-info">Ceci vous coûtera <span id="costAmeliorateHealth" class="text-warning">0</span> <?= pizzaMoney();?> !</p>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary" name="action" value="ameliorer">
            <i class="fa fa-ambulance"></i> Améliorer
        </button><?= form_close();?>
      </div>
    </div>
  </div>
</div>


<!-- Réparation -->
<div class="modal fade" id="reparer" tabindex="-1" role="dialog" aria-labelledby="ModalReparer" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
        <h4 class="modal-title" id="ModalReparer">Réparer le bâtiment</h4>
      </div>
      <div class="modal-body">
        Faites intervenir un réparateur pour ne pas vous retrouver bloqué et dans l'impossibilité de servir vos clients.<br/>
        Vous ne pouvez pas ajouter plus de 100 points d'un coup.<br/>
        <?php
        if($etatMax-$etat > 0) {

            ?>
        Vous pouvez réparer entre 1 et <?= $etatMax-$etat;?> points de santé.
        <?php 
        $attribute = array('class' => 'form-inline', 'role' => 'form');
        echo form_open('jeu/restaurant/gestion', $attribute);?>        
          <div class="form-group">
            <label class="sr-only" for="nombre">Combien souhaitez vous en ajouter ?</label>
            <input type="number" class="form-control inputReparer" id="nombre" name="nombre" placeholder="10" required>
            <p class="text-info">Ceci vous coûtera <span id="costRepairHealth" class="text-warning">0</span> <?= pizzaMoney();?> !</p>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary" name="action" value="reparer">
            <i class="fa fa-wrench"></i> Réparer
        </button><?= form_close();?>
        <?php
    } else {
        ?>
        Aucune réparation nécessaire. Le bâtiment se porte à merveille !
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        <?php
    }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Augmentation du stock -->
<div class="modal fade" id="augmenterStock" tabindex="-1" role="dialog" aria-labelledby="ModalAugmenterStock" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
        <h4 class="modal-title" id="ModalAugmenterStock">Augmenter votre stock</h4>
      </div>
      <div class="modal-body">
        Acheter des entrepôts pour stocker plus de marchandises et servir plus de pizzas !<br/>
        Vous ne pouvez pas ajouter plus de 100 points de stock d'un coup.<br/>

        <?php 
        $attribute = array('class' => 'form-inline', 'role' => 'form');
        echo form_open('jeu/restaurant/gestion', $attribute);?>        
          <div class="form-group">
            <label class="sr-only" for="nombre">Combien souhaitez vous en ajouter ?</label>
            <input type="number" class="form-control inputAugmenter" id="nombre" name="nombre" placeholder="10" required>
            <p class="text-info">Ceci vous coûtera <span id="costAmeliorateStock" class="text-warning">0</span> <?= pizzaMoney();?> !</p>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        <button type="submit" class="btn btn-primary" name="action" value="augmenterStock">
            <i class="fa fa-cubes"></i> Augmenter
        </button><?= form_close();?>
      </div>
    </div>
  </div>
</div>

<!-- Voir le stock -->
<div class="modal fade" id="voirStock" tabindex="-1" role="dialog" aria-labelledby="ModalVoirStock" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
        <h4 class="modal-title" id="ModalVoirStock">Contenu de votre stock</h4>
      </div>
      <div class="modal-body">
        <p>Voici ce que vous entreposez en ce moment :</p>
        <p>
          <?php foreach($stockListing as $item):?>
            <img src="<?= base_url($this->config->item('ingredient_image').$item['image'])?>" title="<?= $item['nom'];?>" alt="-"/>
            <span class="badge"><?= $item['quantite'];?></span> <?= $item['nom'];?><br/>
          <?php endforeach;?>
      </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Augmentation de la capacité -->
<div class="modal fade" id="agrandir" tabindex="-1" role="dialog" aria-labelledby="ModalAgrandir" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
                <h4 class="modal-title" id="ModalAgrandir">Agrandir votre restaurant</h4>
            </div>
            <div class="modal-body">
                Agrandir le restaurant vous permet d'augmenter sa capacité et de recevoir plus de clients par service.<br/>
                Vous ne pouvez pas ajouter plus de 100 points d'un coup.<br/>

                <?php
                $attribute = array('class' => 'form-inline', 'role' => 'form');
                echo form_open('jeu/restaurant/gestion', $attribute);?>
                <div class="form-group">
                    <label class="sr-only" for="nombre">Combien souhaitez vous en ajouter ?</label>
                    <input type="number" class="form-control inputCapacite" id="nombre" name="nombre" placeholder="10" required>
                    <p class="text-info">Ceci vous coûtera <span id="costAmeliorateCapacity" class="text-warning">0</span> <?= pizzaMoney();?> !</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" name="action" value="augmenterCapacite">
                    <i class="fa fa-arrows"></i> Augmenter
                </button><?= form_close();?>
            </div>
        </div>
    </div>
</div>