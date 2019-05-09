<h3><?= $topic->title;?></h3>
<?= $msg;?>
<?= validation_errors('<div class="alert alert-danger">', '</div>');?>
<ul class="breadcrumb">
    <li>
        <a href="<?php echo site_url('forum'); ?>">Forum</a>
    </li>
    <li>
        <a href="<?php echo site_url('forum/categorie/'.$categorie->slug); ?>"><?php echo $categorie->name; ?></a>
    </li>
    <li>
        <?= $topic->title;?>
    </li>
</ul>
<?php
foreach($posts as $post){
    $dateCrea = new DateTime($post->date_add);
    ?>
<div class="col-lg-12">
    <div class="panel panel-success">
        <div class="panel-heading">
            <i class="fa fa-comment"></i>
            Posté le <?= $dateCrea->format('d/m/y à H:i')?>
            <?php
            $user = $this->user_model->getMember($post->author_id);
            $dateModif = new DateTime($post->date_edit);
            if($dateModif->format('Y') > 2013){
                echo '<span class="pull-right">Ce message a été modifié le '.$dateModif->format('d/m à H:i').'</span>';
            }
            ?>
        </div>
        <div class="panel-body">
            <div class="col-md-2">
                <a href="<?= site_url('user/profil/'.$post->user_id);?>">
                    <img src="<?= $user->avatar?>" alt="Avatar" title="Avatar" class="pull-left img-responsive"/>
                    &nbsp;<?= $post->pseudo?>
                </a>
            </div>
            <div class="col-md-10">
                <p><?= nl2br($post->post);?></p>
            </div>
        </div>
        <?php if($post->user_id === $this->session->userdata('id')):
        ?>
        <div class="panel-footer text-right">
            <a href="<?= site_url('forum/editer/'.$post->id)?>" class="btn btn-xs btn-info">Modifier</a>
        </div>
        <?php
        endif;
    ?>
    </div>
</div>
<?php
}

if($showForm) {
    $param = array('role' => 'form');
    echo form_open(current_url(),$param);
    ?>
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <i class="fa fa-comment"></i>
            Ajouter une réponse
        </div>
        <div class="panel-body">
            <div class="form-group">
                <textarea id="textpost" name="message"
                          required placeholder="Mon super message." class="form-control"><?= set_value('message');?></textarea>
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