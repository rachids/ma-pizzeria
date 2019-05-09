<?php
if($this->session->flashdata('flashforum')){
    echo $this->session->flashdata('flashforum');
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Forum
    </div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>Catégorie</th>
                <th><span class="pull-right">Dernier message</span></th>
            </tr>
            <?php
            foreach($categories as $categorie){

                if(isset($categorie['lastPost']->date_last_post)) {
                    $dt = new DateTime($categorie['lastPost']->date_last_post);

                    $date = $dt->format('d/m (H:i)');

                    $lastPost = '<a href="'.site_url('forum/topic/'.$categorie['lastPost']->slug).'">
                            '.$categorie['lastPost']->title.'
                        </a><br/>
                        '.$date;
                } else {
                    $lastPost = 'Aucun message';
                }

                echo '<tr>
                    <td>
                        <a href="'.site_url('forum/categorie/'.$categorie['slug']).'">'.$categorie['name'].'</a><br/>
                        '.$categorie['description'].'
                    </td>
                    <td>
                    <span class="pull-right text-right">
                        '.$lastPost.'
                    </span></td>
                </tr>';
            }
            ?>
        </table>
    </div>
</div>