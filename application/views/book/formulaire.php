<h3>Signer le livre d'or</h3>

<?php if(validation_errors()) {
    echo validation_errors();
}
?>

<?php echo form_open('book/index');?>

<label for="pseudo">Pseudonyme:</label>
<input type="input" name="pseudo"/><br/>

<label for="message">Texte:</label>
<textarea name="message"></textarea><br/>

<button type="submit">Ajouter</button>
</form>