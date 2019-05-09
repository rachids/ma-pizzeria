<h3>Marché de l'emploi</h3>
<?php
if(validation_errors()) {
    echo '<div class="alert alert-danger">'.validation_errors().'</div>';
}elseif($message) {
    echo '<div class="alert alert-success">'.$message.'</div>';
}
?>
<p>
    Recruter ici vos employés. Ils permettent d'améliorer une partie de votre restaurant selon leurs compétences et
    vos besoins.
</p>

<hr/>
<?php
foreach($employes as $employe) :
    $caracs = json_decode($employe['caracteristique'], true);
    $niveau = ($employe['niveau'] === null) ? 0 : $employe['niveau'];
    $carriereMax = count($caracs['niveau']);
    $niveauMax = ($niveau == $carriereMax) ? $carriereMax : $niveau + 1;

    $descNiveau = '';
    for($i = 1; $i <= $niveauMax; $i++){
        if($niveau == $carriereMax && $i == $carriereMax) {
            $descNiveau .= '<span class="text-success"><strong>Niveau MAX</strong> : ';
        }elseif($i > $niveau) {
            $descNiveau .= '<span class="text-primary"><strong>Prochain niveau '.$i.'</strong> : ';
        } else {
            $descNiveau .= '<span class="text-success"><strong>Niveau '.$i.'</strong> : ';
        }
        $descNiveau .= $caracs['niveau'][$i].'</span><br/>';
    }
?>
<div class="row">
    <div class="col-lg-2">
        <h4><?= $employe['nom'];?></h4>
    </div>
    <div class="col-lg-7">
        <p>
            <?= $employe['description'];?>
        </p>
        <p>
            <?= $descNiveau;?>
        </p>
    </div>
    <div class="col-lg-3">
        <p>
            Salaire Actuel : <?= $employe['salaire']*$niveau;?>$/jour
        </p>
<?php
    if($niveau == $carriereMax) {
        echo '<p>Votre '.$employe['nom'].' a atteint le niveau maximum.</p>
        <p>
            <a href="'.site_url('jeu/emploi/licencier/'.$employe['id']).'"class="btn btn-danger">Licencier</a>
        </p>';
    } else {
?>
        <?= form_open();?>
        <?= form_hidden('emploi', $employe['id']);?>
<?php
        if($niveau === 0) {
            echo 'Aucun '.$employe['nom'];
            $label = 'Recruter';
            $licencier = '';
        } else {
            echo 'Votre '.$employe['nom'].' est niveau '.$niveau;
            $label = 'Promouvoir';
            $licencier = '<a href="'.site_url('jeu/emploi/licencier/'.$employe['id']).'" class="btn btn-danger">
            Licencier</a>';
        }
?>
        <p>
            Prochain Salaire : <?= $employe['salaire']*($niveau+1);?>$/jour
        </p>
        <button type="submit" class="btn btn-success"><?= $label;?></button> <?= $licencier;?>
        <?= form_close();?>
<?php
    }
?>

    </div>
</div>
<hr/>
<?php
endforeach;
?>