<div class="row">
    <div class="col-lg-4">
        <h3>Bienvenue sur Ma Pizzeria !</h3>
        <p>Dans ce jeu, vous allez pouvoir lancer votre propre pizzeria et concocter des recettes de votre création.</p>
    </div>
    <div class="col-lg-4">
        <h3>Dernière news</h3>
        <div class="panel panel-success">
            <div class="panel-heading">
                <i class="fa fa-thumb-tack"></i>
                <a href="<?= site_url('news/lire/'.$news->slug);?>">
                    <?= $news->title;?>
                </a>
            </div>
            <div class="panel-body">
                <p><?= truncateMessage(nl2br($news->text));?></p>
            </div>
            <div class="panel-footer">
                Posté le <span title="<?= $fullDateNews?>"><?= $dateNews;?></span> -
                <a href="<?= site_url('news/lire/'.$news->slug);?>">
                    <?= isPluriel($nbComments, 'commentaire', 'commentaires');?>
                </a>
            </div>
        </div>
        <p class="pull-right"><a href="<?= site_url('news');?>">Voir toutes les news.</a></p>
    </div>
    <div class="col-lg-4">
        <h3>Top 3</h3>
        <?php
        $i = 1;
        foreach($topRestau as $restaurant) {
            echo '<p><span class="badge">'.$i.'</span> - <strong>'.$restaurant['nom'].'</strong>
            <a href="'.site_url('user/stats/'.$restaurant['id_joueur']).'" class="btn btn-xs btn-info">
            <i class="fa fa-search"></i> Voir</a></p>';
            $i++;
        }
?>
        <p class="pull-right"><a href="<?= site_url('pages/classement');?>">Autre Classements.</a></p>
    </div>
</div>