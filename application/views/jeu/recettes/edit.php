<h3>Modifiez votre recette</h3>

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

<?php echo form_open('jeu/recettes/modifier/'.$pizza->id);?>
<div class="form-group col-lg-3">
<label for="nom">Nom de votre pizza:</label>
<input type="input" class="form-control" name="nom" required value="<?= set_value('nom', $pizza->nom);?>" placeholder="La Pizza YOLO"/>
</div>
<div class="form-group col-lg-3">
<label for="prix">Prix de votre pizza:</label>
<input type="number" class="form-control" name="prix" required value="<?= set_value('prix', $pizza->prix);?>" placeholder="2"/>
</div>
<div class="col-lg-6">
<label>Listes des ingrédients:</label>
<div class="form-group">

    <?php
    $jsonPizza = json_decode($pizza->ingredients);
    foreach($ingredients as $ingredient):
    ?>
    <div class="col-xs-12 col-sm-4 col-lg-3">
        <p>

            <div class="checkbox">
              <label>
                <input type="checkbox" name="ingredients[]" value="<?= $ingredient['id'];?>" <?php
                    if(in_array($ingredient['id'],$jsonPizza)){
                        echo 'checked = "checked"';
                    } else {
                        echo set_checkbox('ingredients', $ingredient['id']);
                    }
                    ?>>
                <?= $ingredient['nom'];?>
              </label>
            </div>
        </p>
    </div>
    <?php endforeach; ?>
</div>


</div>
<button type="submit" class="btn btn-success">Valider les modifications</button>
</form>