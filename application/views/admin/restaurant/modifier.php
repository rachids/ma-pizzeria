<h3>Modification du restaurant</h3>
<?php
if(isset($message)){
    echo $message;
}
echo form_open(current_url(), array('role'=>'form'));
?>
<div class="form-group">
    <div class="form-group col-lg-4">
        <label for="nom">Nom du restaurant</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?= $restaurant->nom?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="argent">Argent en caisse</label>
        <input type="number" class="form-control" id="argent" name="argent" value="<?= $restaurant->argent?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="capacite">Capacité max.</label>
        <input type="number" class="form-control" id="capacite" name="capacite" value="<?= $restaurant->capacite?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="stock">Stock max</label>
        <input type="number" class="form-control" id="stock" name="stock" value="<?= $restaurant->stock?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="notoriete">Notoriété</label>
        <input type="number" class="form-control" id="notoriete" name="notoriete" value="<?= $restaurant->notoriete?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="experience">Expérience</label>
        <input type="number" class="form-control" id="experience" name="experience" value="<?= $restaurant->experience?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="etat">Etat actuel</label>
        <input type="number" class="form-control" id="etat" name="etat" value="<?= $restaurant->etat?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="etatMax">Etat max</label>
        <input type="number" class="form-control" id="etatMax" name="etatMax" value="<?= $restaurant->etatMax?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="open">Ouverture actuelle</label>
        <input type="number" class="form-control" id="open" name="open" value="<?= $restaurant->open?>">
    </div>

    <div class="form-group col-lg-4">
        <label for="openMax">Ouverture max</label>
        <input type="number" class="form-control" id="openMax" name="openMax" value="<?= $restaurant->openMax?>">
    </div>
</div>
    <button type="submit" class="btn btn-lg btn-success pull-right">Envoyer</button>
<?= form_close();?>