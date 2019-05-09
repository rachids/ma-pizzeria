<h3>Classement complet</h3>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 26/07/14
 * Time: 13:32
 * To change this template use File | Settings | File Templates.
 */

echo $intro;
?>

<p class="text-center"> Classement par :
    <a href="<?= site_url('pages/classement/')?>">Notoriété</a> -
    <a href="<?= site_url('pages/classement/affluence')?>">Affluence</a> -
    <a href="<?= site_url('pages/classement/richesse')?>">Richesse</a> -
    <a href="<?= site_url('pages/classement/capacite')?>">Capacité</a>
</p>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Restaurant</th>
            <th><?= $valeur;?></th>
            <th>Lien</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
        foreach($topRestau as $restau){
           $i++;

            switch($valeur){
                case 'Notoriété':
                    $show = $restau['notoriete'].' (XP: '.$restau['experience'].')';
                    break;
                case 'Affluence totale':
                    $show = $restau['affluenceTotale'].' clients';
                    break;
                case 'Richesse':
                    $show = 'Donnée secrète';
                    break;
                case 'Capacité':
                    $show = $restau['capacite'];
                    break;
            }

            echo '<tr>
                    <td>'.$i.'</td>
                    <td>'.$restau['nom'].'</td>
                    <td>'.$show.'</td>
                    <td><a href="'.site_url('user/stats/'.$restau['id_joueur']).'" class="btn btn-xs btn-primary">
                        <i class="fa fa-search"></i> Statistiques
                         </a></td>
                </tr>';
        }
    ?>
    </tbody>
</table>