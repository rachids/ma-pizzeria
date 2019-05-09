<h3>Liste des restaurants</h3>

<div class="row">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Restaurants</h3>
            <div class="pull-right">
                <button class="btn btn-success btn-xs btn-filter"><i class="fa fa-filter"></i> Filtrer</button>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr class="filters">
                <th><input type="text" class="form-control" placeholder="id" disabled></th>
                <th><input type="text" class="form-control" placeholder="Nom" disabled></th>
                <th><input type="text" class="form-control" placeholder="Argent" disabled></th>
                <th><input type="text" class="form-control" placeholder="Capacité" disabled></th>
                <th><input type="text" class="form-control" placeholder="Stock" disabled></th>
                <th><input type="text" class="form-control" placeholder="Notoriété" disabled></th>
                <th><input type="text" class="form-control" placeholder="Etat" disabled></th>
                <th><input type="text" class="form-control" placeholder="Ouvertures" disabled></th>
                <th><input type="text" class="form-control" placeholder="Joueur" disabled></th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($restaurants as $restaurant) {
                echo '<tr>
                            <td>'.$restaurant['id'].'</td>
                            <td>'.$restaurant['nom'].'</td>
                            <td>'.$restaurant['argent'].' $</td>
                            <td>'.$restaurant['capacite'].'</td>
                            <td>'.$restaurant['stock'].'</td>
                            <td>'.$restaurant['notoriete'].'</td>
                            <td>'.$restaurant['etat'].' / '.$restaurant['etatMax'].'</td>
                            <td>'.$restaurant['open'].' / '.$restaurant['openMax'].'</td>
                            <td>'.$this->pizzalib->getPseudoByID($restaurant['id_joueur']).'
                            (id: '.$restaurant['id_joueur'].')</td>
                            <td>
                                <a href="'.site_url('config/modifierRestau/'.$restaurant['id']).'">
                                    <button class="btn btn-success">Modifier</button>
                                </a>
                            </td>
                        </tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>