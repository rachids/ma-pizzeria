<?php
if(isset($retour)) { echo $retour; }
?>

<h3>Marché des ingrédients</h3>
<hr>
<p>Achetez les ingrédients dont vous avez besoin pour vos recettes.</p>
<?php
if($niveauControleur == 0) {
    echo '<p>Un Contrôleur de Gestion peut vous aider à prendre des décisions rapides et être plus efficace sur le
    marché. Recrutez le et formez le en vous rendant dans la page du
    <a href="'.site_url('jeu/emploi').'">marché de l\'emploi</a>.</p>';
} else {
    echo '<p>Votre Contrôleur de Gestion est au niveau '.$niveauControleur.'</p>';
}
?>
<p><strong>ATTENTION:</strong> De part la difficulté des grossistes à calculer exactement le nombre de produits qu'ils ont, les chiffres annoncés peuvent
être inexacts (il y a une marge d'erreur de 10%).</p>
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
                    <th><input type="text" class="form-control" placeholder="Nom" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Description" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Stock" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Prix" disabled></th>
                    <th><input type="text" class="form-control" placeholder="Achat" disabled></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    echo form_open('jeu/marche/ingredients/achat', array('class' => 'form-inline'));
                    foreach($ingredients as $ingredient) {
                        //Calcul des stocks (affichage uniquement des pourcentages)
                        $prctStock = $ingredient['stockDispo'] * 100 / $ingredient['stockMax'];

                        $stock = '<div class="progress">
                        <div style="width: '.$prctStock.'%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$prctStock.'" role="progressbar" class="progress-bar progress-bar-theme">
                            <span class="sr-only">'.$prctStock.'</span>
                        </div>
                        </div>';

                        $margeerreur = $ingredient['stockDispo'] * $this->config->item('ingredient_margeErreurStock');
                        $stockShow = mt_rand($ingredient['stockDispo'] - $margeerreur, $ingredient['stockDispo'] + $margeerreur);

                        if($ingredient['prixReel'] > $ingredient['prixNormalise']) {
                            $variance = '<i class="fa fa-chevron-up" title="Prix en hausse"></i>';
                        } elseif ($ingredient['prixReel'] < $ingredient['prixNormalise']) {
                            $variance = '<i class="fa fa-chevron-down" title="Prix en baisse"></i>';
                        } else {
                            $variance = '<span title="Prix stable">=</span>';
                        }

                        #Niveau 1 du Controleur de Gestion
                        if(in_array($ingredient['id'],$IngNecessaire)){
                            $nom = '<strong>'.$ingredient['nom'].'</strong>';
                        } else {
                            $nom = $ingredient['nom'];
                        }
                        $nbStock = '';
                        #Niveau 2 du Controleur de Gestion
                        if($niveauControleur >= 2) {
                            if(array_key_exists($ingredient['id'], $IngStock)){
                                $nbStock = '<em>('.$IngStock[$ingredient['id']].' en stock.)</em><br/>';
                            } else {
                                $nbStock = '<em>(0 en stock)</em><br/>';
                            }
                        }

                        $disabled = '';
                        $trDisabled = '';
                        $indicatifStock = '(Environ '.$stockShow.' produits restants)';
                        if($ingredient['stockDispo'] == 0 ){
                            $disabled = 'disabled';
                            $trDisabled = 'class="danger"';
                            $indicatifStock = '<strong>Rupture de stock !</strong>';
                        }

                        echo '<tr '.$trDisabled.'>
                            <td>
                                '.$nom.'<br/>
                                '.$nbStock.'
                                <img src="'.base_url($this->config->item('ingredient_image').$ingredient['image']).'" title="'.$ingredient['nom'].'" alt="Image indisponible"/>
                            </td>
                            <td>'.$ingredient['description'].'</td>
                            <td>'.$stock.'
                            '.$indicatifStock.'</td>
                            <td>'.$ingredient['prixReel'].' <img src="'.base_url($this->config->item('jeu_MoneySymbol')).'" alt="$"/>
                            '.$variance.'
                            </td>
                            <td>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control" data-prix="'.$ingredient['prixReel'].'"
                                    name="ing['.$ingredient['id'].']" value="0" required '.$disabled.'>
                                </div>
                            </td>
                        </tr>';
                    }
                ?>
            </tbody>
        </table>
        <div class="panel-footer recapAchat">

            <div class="row">
                <div class="col-md-9">
                    Coût : <span class="cost">0</span> <?= pizzaMoney();?><br/>
                    Stock requis : <span class="storage">0</span>
                </div>
                <div class="col-md-3 text-right">
                    <button class="btn btn-success btn-lg" type="submit" name="produit">
                        <i class="fa fa-shopping-cart"></i> Acheter
                    </button>
                    <?= form_close();?>
                </div>
            </div>
        </div>
    </div>
</div>