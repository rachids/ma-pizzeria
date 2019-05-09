<h3 xmlns="http://www.w3.org/1999/html">Panneau d'administration</h3>

<p><a href="<?= site_url('config/addNews');?>">Ajouter une news</a><br/>
<a href="<?= site_url('cron/lafameusemiseajour');?>">Déclencher une mise à jour</a></p>

<p>Statistiques</p>
    <ul>
        <li><a href="<?= site_url('config/users')?>"><?= $nbUser;?></a> utilisateurs</li>
        <li><a href="<?= site_url('config/restaurants')?>"><?= $nbRestau;?></a> restaurants</li>
    </ul>