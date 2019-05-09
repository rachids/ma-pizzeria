<h3>Modifier mon profil</h3>

<?php
if(($this->session->flashdata('errorMsg'))){
    echo $this->session->flashdata('errorMsg');
} elseif($msg) {
    echo $msg;
}

$attr = array('class' => 'form-horizontal', 'role' => 'form');
echo form_open('user/update');
?>
<div class="col-lg-6">
    <div class="form-group">
        <label for="inputEmail" class="col-sm-3 control-label">Email</label>
        <div class="col-sm-9">
            <input type="email" class="form-control" id="inputEmail" name="email" value="<?= $membre->email;?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="col-sm-3 control-label">Mot de passe</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Obligatoire">
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword3" class="col-sm-3 control-label">Mot de passe (vérification)</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" id="inputPassword3" name="password-check"
                   placeholder="A remplir uniquement si modification du mot de passe" value="<?=set_value('password-check');?>">
            <span class="help-block">Si vous ne souhaitez pas modifier le mot de passe, laissez ce champ vide.</span>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group">
        <label for="sexe" class="col-sm-3 control-label">Sexe</label>
        <div class="col-sm-9">
            <?php
                $options = $this->config->item('sexe');
                echo form_dropdown('sexe', $options, set_value('sexe',$membre->sexe), 'class="form-control" id="sexe"');
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Avatar</label>
        <div class="col-sm-9" align="center">
            <a href="#" data-toggle="modal" data-target="#uploadAvatar">
                <img alt="User Pic" src="<?= $membre->avatar;?>" class="img-responsive">
                Modifier
            </a>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="form-group pull-right">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-info">Modifier</button>
        </div>
    </div>
<?= form_close();?>
</div>

<!-- UploadAvatar -->
<div class="modal fade" id="uploadAvatar" tabindex="-1" role="dialog" aria-labelledby="Avatar" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
                <h4 class="modal-title" id="Avatar">Envoyer un avatar</h4>
            </div>
            <div class="modal-body">
                Modifier votre avatar en renseignant le champs suivant :
                <?php
                echo form_open_multipart('user/upload');
                echo form_upload('image',null,'class="form-control"');
?>
                Le jeu utilise les services d'<a href="http://www.avatars.io">AvatarsIO</a>.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Envoyer</button><?= form_close();?>
            </div>
        </div>
    </div>
</div>
<!-- FinUpload -->