<h3>Journal des évènements</h3>

<?php if($visibilite == 'prive'):?>
<p>Voici un récapitulatif de vos dernières actions.<br/>
<strong>ATTENTION:</strong> Le rapport privé est nettoyé de messages vieux de + de 3 jours tous les soirs à la mise à jour de minuit.</p>
<?php else:?>
<p>Contient tout ce qui s'est passé dans le monde des Pizzaiolos.</p>
<?php endif;?>
<div class="panel panel-info">
    <div class="panel-heading">
        Journal d'activité
        <?php
            $nb = count($messages);
            if($nb > 0 && isset($visibilite) && $visibilite == 'prive') {
                echo '<div class="pull-right">
                    <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#vider"><i class="fa fa-trash-o"></i> Vider</button>
                </div>';
            }
        ?>
    </div>
      <div class="panel-body">
        <?php
        echo $paginationLink;

        if($nb > 0){
        ?>
        <table class="table table-striped">
        <?php

            $day = null;

            foreach ($messages as $msg) {
                $fullDate = date('H:i:s', $msg['date']);

                if($day != date('d/m/Y', $msg['date'])){
                    echo '<tr>
                        <td colspan="2" class="text-center"><h5>'.date('d/m/Y', $msg['date']).'</h5></td>
                    </tr>';
                }

                echo '<tr>
                        <td>'.$fullDate.'</td>
                        <td>'.$msg['message'].'</td>
                    </tr>';
                $day = date('d/m/Y', $msg['date']);
            }
        } else {
            echo '<p>Pas de nouvelles.</p>';
        }
        ?>
        </table>
      </div>
</div>
<?php
if(!isset($visibilite) || $visibilite == 'prive') {
?>
<!-- Vider -->
<div class="modal fade" id="vider" tabindex="-1" role="dialog" aria-labelledby="ModalVider" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
        <h4 class="modal-title" id="ModalVider">Vider votre journal</h4>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir vider votre rapport d'activités ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
        <a href="<?= site_url('jeu/journal/vider');?>"><button type="button" class="btn btn-primary">Oui</button>
      </div>
    </div>
  </div>
</div>
<?php
}