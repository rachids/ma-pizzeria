<h2><?= $news['title']?></h2>
<p><?= nl2br($news['text'])?></p>

<hr/>
<h2>Commentaires</h2>
<?= $comment['msg'];?>
<?= validation_errors('<div class="alert alert-danger">', '</div>');?>


<div class="row">
    <div class="col-lg-6 col-lg-offset-3">
        <?php foreach($comment['listeComments'] as $comment){?>
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <i class="fa fa-comments"></i>
                    Posté par <?= $this->pizzalib->getPseudoByID($comment['id_user']);?>
                </div>
                <div class="panel-body">
                    <p>
                        <?= $comment['message'];?>
                    </p>
                </div>
                <div class="panel-footer">
                    Posté le <?= formatDate($comment['date'], 'd/m/Y (H:i)');?>
                </div>
            </div>
        </div>
        <?php }?>
<?php
if($showForm) {
    $param = array('role' => 'form');
    echo form_open(current_url(),$param);
    ?>
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-comment"></i>
                Posté par vous!
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <textarea id="commentForm" name="message"
                    required placeholder="Mon super com'." class="form-control"><?= set_value('message');?></textarea>
                    <span class="help-block">Le message doit comporter entre 5 et 255 caractères.</span>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-success right">Poster</button>
            </div>
        </div>
    </div>
    <?php
    echo form_close();
}
?>
    </div>
</div>

