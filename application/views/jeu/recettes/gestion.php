<h3>Gérez vos recettes</h3>

<p>Vous trouverez ici vos recettes de Pizzas.</p>

<?php
if(count($pizzas) > 0) {
?>
<div class="row">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Vos recettes à pizzas</h3>
            <div class="pull-right">
                <button class="btn btn-success btn-xs btn-filter"><i class="fa fa-filter"></i> Filtrer</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr class="filters">
                    <th><input type="text" class="form-control" placeholder="Nom" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Note moyenne" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Prix" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Ingrédients" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Actions" disabled></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($pizzas as $pizza) {
                        echo '<tr>
                            <td>'.$pizza['nom'].'</td>
                            <td>'.$pizza['note'].'</td>
                            <td>'.$pizza['prix'].' '.pizzaMoney().'</td>
                            <td>'.$pizza['ingredients'].'</td>
                            <td><a href="'.site_url('jeu/recettes/modifier/'.$pizza['id']).'">
                               <button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Modifier</button>
                            </a>
                            <a href="'.site_url('jeu/recettes/supprimer/'.$pizza['id']).'">
                               <button class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Supprimer</button>
                            </a>
                            </td>
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
    if(count($pizzas) < $this->config->item('pizza_recetteMax')) {
    echo '<p>Vous pouvez encore étoffer votre menu. 
    <a href="'.site_url('jeu/recettes/creer').'"><button class="btn btn-success">Ajouter une recette <i class="fa fa-spoon fa-2x"></i></button>.</p>';
    } 
} else {
    echo '<p>Vous n\'avez pas encore créé de recettes !
    <a href="'.site_url('jeu/recettes/creer').'"><button class="btn btn-success">Faites le maintenant <i class="fa fa-spoon fa-2x"></i></button></p>';
}