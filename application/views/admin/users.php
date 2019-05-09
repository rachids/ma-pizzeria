<h3>Liste des membres</h3>

<div class="row">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Ingrédients</h3>
            <div class="pull-right">
                <button class="btn btn-success btn-xs btn-filter"><i class="fa fa-filter"></i> Filtrer</button>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr class="filters">
                <th><input type="text" class="form-control" placeholder="id" disabled></th>
                <th><input type="text" class="form-control" placeholder="Pseudo" disabled></th>
                <th><input type="text" class="form-control" placeholder="Email" disabled></th>
                <th><input type="text" class="form-control" placeholder="ID Facebook" disabled></th>
                <th><input type="text" class="form-control" placeholder="Sexe" disabled></th>
                <th><input type="text" class="form-control" placeholder="Rôle" disabled></th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($membres as $membre) {
                echo '<tr>
                            <td>'.$membre['id'].'</td>
                            <td>'.$membre['pseudo'].'</td>
                            <td>'.$membre['email'].'</td>
                            <td>'.$membre['facebook'].'</td>
                            <td>'.$membre['sexe'].'</td>
                            <td>'.$membre['role'].'</td>
                            <td>Modifier - Supprimer</td>
                        </tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>