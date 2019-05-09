<h3>Supprimer une recette</h3>

<p>Êtes-vous sûr de vouloir supprimer votre recette "<strong><em><?= $pizza->nom;?></em></strong>"</p>

<?php 
echo form_open(current_url());
echo form_hidden('idPizza', $this->uri->segment(4));
?>
<div class="pull-right">
    <a href="<?= site_url('jeu/recettes');?>">
        <button type="button" class="btn btn-default" data-dismiss="modal">Non, je garde ma recette !</button>
    </a>
    <button type="submit" class="btn btn-danger">Oui, supprimer la recette.</button>
</div>
<?=form_close();?>