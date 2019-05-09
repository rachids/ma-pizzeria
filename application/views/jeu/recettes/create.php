<h3>Créez votre recette</h3>

<?php if(validation_errors()) {
    ?>
    <div class="alert alert-warning alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>Erreur!</strong> <?= validation_errors();?>
    </div>
<?php 
}
?>

<p>Votre recette ne peut contenir qu'au maximum <?= $this->config->item('pizza_ingredientsMax');?> ingrédients.</p>

<?php echo form_open('jeu/recettes/creer');?>
<div class="form-group col-lg-3">
<label for="nom">Nom de votre pizza:</label>
<input type="input" class="form-control" name="nom" required value="<?= set_value('nom');?>" placeholder="La Pizza YOLO"/>
</div>
<div class="form-group col-lg-3">
<label for="prix">Prix de votre pizza:</label>
<input type="number" class="form-control" name="prix" required value="<?= set_value('prix');?>" placeholder="2"/>
</div>
<div class="col-lg-6">
<label>Listes des ingrédients:</label>
<div class="form-group">

    <?php foreach($ingredients as $ingredient):
    ?>
    <div class="col-xs-12 col-sm-4 col-lg-3">
        <p>

            <div class="checkbox">
              <label>
                <input type="checkbox" name="ingredients[]" value="<?= $ingredient['id'];?>" <?php echo set_checkbox('ingredients', $ingredient['id']);?>>
                <?= $ingredient['nom'];?>
              </label>
            </div>
        </p>
    </div>
    <?php endforeach; ?>
</div>


</div>
<button type="submit" class="btn btn-success">Je créé ma recette !</button>
</form>