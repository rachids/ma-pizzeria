<h3>Ajouter une news</h3>

<?php if(validation_errors()) {
    echo validation_errors();
} ?>

<?php echo form_open(current_url());?>

<label for="title">Titre:</label>
<input type="input" name="title" class="form-control" id="title"/><br/>

<label for="text">Texte:</label>
<textarea name="text" class="form-control" id="text"></textarea><br/>

<button type="submit">Ajouter</button>
</form>