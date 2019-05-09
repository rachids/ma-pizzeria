<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 16/07/14
 * Time: 16:52
 * To change this template use File | Settings | File Templates.
 */
echo validation_errors('<div class="alert alert-danger">','</div>');
$param = array('role' => 'form');
echo form_open(current_url(),$param);
?>
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <i class="fa fa-comment"></i>
            Modifier son message
        </div>
        <div class="panel-body">
            <div class="form-group">
                <textarea id="textpost" name="message"
                          required placeholder="Mon super message." class="form-control"><?= set_value('message',$post->post);?></textarea>
                <span class="help-block">Le message doit comporter entre 5 et 255 caractères.</span>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-success">Modifier</button>
            <a href="<?= site_url($redirection)?>" class="btn btn-default">Retour</a>
        </div>
    </div>
</div>
<?php
echo form_close();