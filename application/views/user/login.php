<h3>Connexion à votre compte</h3>

<?php if(validation_errors()) {
    ?>
    <div class="alert alert-warning alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>Erreur!</strong> <?= validation_errors();?>
    </div>
    <?php 
} elseif(isset($error_credentials)) {
    echo '<div class="alert alert-warning alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>Erreur!</strong> '.$error_credentials.'
    </div>';
}
?>

<div class="row">
    <div class="col-lg-6">
        <?php echo form_open('user/connexion');?>
        <div class="form-group">
            <label for="username">Login:</label>
            <input type="input" class="form-control" name="username" required value="<?= set_value('username');?>" placeholder="Adresse email."/>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" name="password" required value="<?= set_value('password');?>" placeholder="Mot de passe."/>
        </div>

        <button type="submit" class="btn btn-success">Se connecter</button>
        </form>
        <p>Pas de compte ? <a href="<?= site_url('user/inscription');?>">Inscrivez vous gratuitement ici</a>.</p>
    </div>
    <div class="col-lg-6">
        <a href="<?= site_url('user/loginFB')?>">
            <button type="button" class="btn btn-primary btn-lg"><i class="fa fa-facebook"></i> Connexion via Facebook</button>
        </a>
    </div>
</div>