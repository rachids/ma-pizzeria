<?php
if(isset($retour)) { echo $retour; }
?>
<div class="row">
    <h3>Ouvrir le restaurant</h3>

    <p><strong>Attention :</strong> En cliquant sur le bouton d'ouverture, les clients vont venir dans votre restaurant
    (le nombre est détérminé par votre Notoriété). <strong>Vérifiez bien que tout est prêt pour recevoir les
    clients</strong> (ingrédients, recettes, santé du batiment etc.)</p>

    <div class="col-lg-4 col-lg-offset-5">
        <a href="<?= site_url('jeu/restaurant/ouvrir/go')?>">
            <button type="button" class="btn btn-success btn-lg <?= ($disabled === true) ? 'disabled' : ''?>">
                Ouverture du restaurant (<?= $open . ' / ' . $openMax;?>)
            </button>
        </a>
        <?= ($disabled === true) ? '<p>Vous ne pouvez plus recevoir de clients pour aujourd\'hui.</p>' : ''?>
    </div>
</div>

<hr/>
<div class="row">
    <h3>Privatiser le restaurant</h3>

    <p>Vous pouvez privatiser votre restaurant pour <?= $this->config->item('restaurant_coutPrivatisation');?> <?= pizzaMoney();?>.
        Très utile si votre caisse est dans le négatif ou si vous n'avez plus assez d'argent pour acheter des
        ingrédients.</p>

    <div class="col-lg-4 col-lg-offset-5">
        <a href="<?= site_url('jeu/restaurant/ouvrir/privatiser')?>">
            <button type="button" class="btn btn-success btn-lg <?= ($disabled === true) ? 'disabled' : ''?>">
                Privatiser le restaurant (<?= $open . ' / ' . $openMax;?>)
            </button>
        </a>
        <?= ($disabled === true) ? '<p>Vous ne pouvez plus privatiser votre établissement aujourd\'hui.</p>' : ''?>
    </div>
</div>