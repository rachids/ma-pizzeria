<p>Inscrivez vous gratuitement sur Ma Pizzeria et commencez dès à présent à gérer votre propre pizzeria !</p>

<h3>Inscription :</h3>
<?php if(validation_errors()) {
    ?>
<div class="alert alert-warning alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Erreur!</strong> <?= validation_errors();?>
</div>
<?php
}
?>
<div class="row">
    <div class="col-lg-6">
        <?php echo form_open('user/inscription');?>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required placeholder="Votre adresse email" value="<?= set_value('email');?>"/>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" name="password" required placeholder="Mot de passe" value="<?= set_value('password');?>"/>
        </div>
        <div class="form-group">
            <label for="password-check">Retapez votre mot de passe:</label>
            <input type="password" class="form-control" name="password-check" required placeholder="Vérification du mot de passe" value="<?= set_value('password-check');?>"/>
        </div>
        <div class="form-group">
            <label for="pseudo">Pseudo:</label>
            <input type="input" class="form-control" name="pseudo" required placeholder="Pseudo" value="<?= set_value('pseudo');?>"/>
        </div>

        <button type="submit" class="btn btn-success">S'inscrire</button>
        </form>

        <p>Vous avez déjà un compte ? Connectez-vous <a href="<?= site_url('user/connexion');?>">ici</a>.</p>
    </div>
    <div class="col-lg-6">
        <a href="<?= site_url('user/loginFB')?>">
            <button type="button" class="btn btn-primary btn-lg"><i class="fa fa-facebook"></i> Connexion via Facebook</button>
        </a>
    </div>
</div>

