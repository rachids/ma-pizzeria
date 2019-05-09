<h3>Vandalisme</h3>
<?php
if($this->session->flashdata('flashassaut')){
    echo $this->session->flashdata('flashassaut');
}
?>
<p>Si vous trouvez la compétition trop performante à votre goût, le recours aux vandales vous permettrait de ralentir
ces compétiteurs.</p>
<strong>A savoir :</strong>
<ul>
    <li>Si les vandales échouent, ils gardent l'argent.</li>
    <li>Si les vandales sont arrêtés par la police, il y a de fortes chances qu'ils vous dénoncent</li>
    <li>Les vandales agissent à la mise à jour de minuit.</li>
    <li>Si plusieurs vandales attaquent le même restaurant, le premier vandalisme aura lieu <em>(et les autres attaques
        ne seront pas remboursées)</em></li>
    <li>Les missions sont payables à l'avance et non remboursables peu importe le succès ou l'échec.</li>
    <li>Si le restaurant cible bénéficie d'une protection policière, vous ne le saurez qu'au moment de l'assaut
    (et l'assaut n'aura donc pas lieu.)</li>
</ul>

<div class="row">
    <div class="col-lg-4">
        <h4><span class="badge">1</span> Choisissez la cible</h4>
        <p>Sélectionnez le restaurant que vous souhaitez attaquer.</p>

<?php

        echo form_open('jeu/attaquer/index');

        $options = array();

        foreach($restaurants as $restaurant){
            $options[$restaurant['id']] = $restaurant['nom'];
        }

        echo form_dropdown('restaurant', $options);
?>
    </div>

    <div class="col-lg-4">
        <h4><span class="badge">2</span> Détail de l'attaque</h4>
        <p>Sélectionnez la mission des vandales</p>

        <div class="radio">
            <label>
                <input type="radio" name="mission" id="cambriolage" value="cambriolage" required>
                Les vandales pénètrent par effraction dans le restaurant cible et s'emparent d'une partie de la caisse.
                (Entre 10 et 30% de son contenu)<br/>
                Coût : <?= $this->config->item('pizza_cambriolage_cout');?> <?= pizzaMoney();?> -
                Chance de réussite : <?= $this->config->item('pizza_cambriolage_chance');?>%
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="mission" id="graffiti" value="graffiti" required>
                Les vandales taguent des mots pas cool sur les murs du restaurant.<br/>
                Coût : <?= $this->config->item('pizza_graffiti_cout');?> <?= pizzaMoney();?> -
                Chance de réussite : <?= $this->config->item('pizza_graffiti_chance');?>%
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="mission" id="casse" value="casse" required>
                Les vandales détruisent la devanture et démolissent tables et chaises.<br/>
                Coût : <?= $this->config->item('pizza_casse_cout');?> <?= pizzaMoney();?> -
                Chance de réussite : <?= $this->config->item('pizza_casse_chance');?>%
            </label>
        </div>
    </div>

    <div class="col-lg-4">
        <h4><span class="badge">3</span> Programmer l'assaut</h4>
        <button type="submit" class="btn btn-warning btn-lg">Attaquer !</button>
        <?= form_close();?>
    </div>
</div>


