<h3><?= $restaurant->nom?></h3>

<p>La pizzeria <em><?= $restaurant->nom?></em> peut accueillir jusqu'à
    <strong><?= $restaurant->capacite?></strong> clients en un service !</p>

<p>Sa notoriété est de <strong><?= $restaurant->notoriete?></strong> (<?= $restaurant->experience?> XP).<br/>
<em><?= $restaurant->nom?></em> a servi pas moins de <strong><?= $restaurant->affluenceTotale?></strong> clients (dont
<?= $restaurant->affluenceDay?> aujourd'hui)</p>