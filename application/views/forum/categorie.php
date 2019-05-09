<ul class="breadcrumb">
    <li>
        <a href="<?php echo site_url('forum'); ?>">Forum</a>
    </li>
    <li>
        <a href="<?php echo site_url('forum/categorie/'.$categorie->slug); ?>"><?php echo $categorie->name; ?></a>
    </li>
</ul>
<?= $msg;?>
<?= validation_errors('<div class="alert alert-danger">', '</div>');?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo $categorie->name; ?>
        <?php if($showForm){
            echo '<a href="#" data-toggle="modal" data-target="#newTopic" class="btn btn-success btn-xs pull-right">
            Ajouter une discussion
        </a>';
        }?>

    </div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>Sujet</th>
                <th>Créé le</th>
                <th>Dernier post</th>
            </tr>
            <?php
            foreach($topics as $topic){
                $dateCrea = new DateTime($topic->date_add);
                $dateLastPost = new DateTime($topic->date_last_post);
                echo '<tr>
                    <td>
                        <a href="'.site_url('forum/topic/'.$topic->slug).'">'.$topic->title.'</a>
                    </td>
                    <td>'.$dateCrea->format('d/m/y (H:i)').'</td>
                    <td>'.$dateLastPost->format('d/m/y (H:i)').'</td>
                </tr>';
            }
            ?>
        </table>
    </div>
</div>
<?php if($showForm):?>
<!-- NewTopic -->
<div class="modal fade" id="newTopic" tabindex="-1" role="dialog" aria-labelledby="NouveauSujet" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
                <h4 class="modal-title" id="NouveauSujet">Ajouter une nouvelle discussion</h4>
            </div>
            <div class="modal-body">
                <?php
                echo form_open(current_url(),'class="form-horizontal" role="form"');
                ?>
                <div class="form-group">
                    <label for="titre" class="col-sm-2 control-label">Titre</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du sujet" value="<?= set_value('titre')?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-2 control-label">Message</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="message" name="message" placeholder="Votre message"><?= set_value('message')?></textarea>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Envoyer</button><?= form_close();?>
            </div>
        </div>
    </div>
</div>
<!-- Fin NewTopic -->
<?php endif;?>