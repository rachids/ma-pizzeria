<h3>Créez votre restaurant !</h3>

<?php if(validation_errors()) {
    ?>
    <div class="alert alert-warning alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>Erreur!</strong> <?= validation_errors();?>
    </div>
<?php 
}
?>

<p>Votre restaurant sera créé avec les ressources par défaut (voir règles).</p>

<?php echo form_open('jeu/restaurant/creer');?>
<div class="form-group">
<label for="nom">Nom de votre restaurant:</label>
<input type="input" class="form-control" name="nom" required value="<?= set_value('nom');?>" placeholder="Chez Pizza-YoLo"/>
</div>

<button type="submit" class="btn btn-success">Je créé mon restaurant !</button>
</form>