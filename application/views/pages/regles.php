<h2>Règles du jeu</h2>

<p>Choisissez le chapitre que vous souhaitez lire.</p>

<div class="panel-group" id="accordion">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#butdujeu">
          But du jeu
        </a>
      </h4>
    </div>
    <div id="butdujeu" class="panel-collapse collapse in">
      <div class="panel-body">
        <p>Vous devez gérer votre restaurant. Gagnez de l'argent en vendant vos pizzas et en améliorant votre restau.<br/>
        Achetez les ingrédients, employez les meilleurs pizzaïolos, équipez vous du meilleur matos et s'il le faut,
        envoyez des voyous saccager la concurrence.</p>
      </div>
    </div>
  </div>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#lespizzas">
          Les Pizzas
        </a>
      </h4>
    </div>
    <div id="lespizzas" class="panel-collapse collapse">
      <div class="panel-body">
        Les Pizzas sont des recettes de votre création avec les ingrédients disponible. Une pizza doit contenir entre 1 et 5 ingrédients.<br/>
        Vous pouvez fixer le prix que vous souhaitez (jusqu'à un maximum de 30 <?= pizzaMoney();?>).<br/>
        Vous êtes limité à 3 recettes de pizza simultanément.
      </div>
    </div>
  </div>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#lesingredients">
          Les ingrédients
        </a>
      </h4>
    </div>
    <div id="lesingredients" class="panel-collapse collapse">
      <div class="panel-body">
        Les ingrédients qui composent vos pizzas doivent être achetés et stockés dans votre restaurant.<br/>
        Il est recommandé de recruter un Contrôleur de Gestion pour rendre la gestion des ingrédients plus facile.
      </div>
    </div>
  </div>

  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#lemarche">
                  Le marché
              </a>
          </h4>
      </div>
      <div id="lemarche" class="panel-collapse collapse">
          <div class="panel-body">
              Sur le marché, vous pouvez acheter vos ingrédients. Il y a une limite maximum que peut offrir le marché.
              Les marchands se réapprovisionnent plusieurs fois pendant la journée et recalculent leurs prix en conséquence.<br/>
              Plus un produit est acheté, plus son coût augmente. Certains produits sont très rares et il n'y en aura
              pas assez pour tous les restaurants.<br/>
              Les quantités maximales du marché dépendent du nombre de joueurs inscris.
          </div>
      </div>
  </div>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#lestock">
          Le stock
        </a>
      </h4>
    </div>
    <div id="lestock" class="panel-collapse collapse">
      <div class="panel-body">
        Votre restaurant dispose de base d'un espace de stockage de 10 ingrédients.<br/>
        C'est suffisant pour les premiers jours, mais bien vite, il faudra l'augmenter pour répondre à la demande croissante.
      </div>
    </div>
  </div>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#lesclients">
          Les clients
        </a>
      </h4>
    </div>
    <div id="lesclients" class="panel-collapse collapse">
      <div class="panel-body">
        Les clients sont tous différents et n'apprécieront pas toujours les mêmes choses !<br/>
        De ce fait, il ne peut exister de recette de pizza parfaite.<br/>
        Les clients jugeront votre pizza selon leur goût et lui attribueront une note (de 0 à 10).<br/>
        Il faut savoir que plus un client aura apprécié votre pizza, plus il sera enclin à payer le prix que vous avez fixé
        (même s'il est atrocement élevé, pourvu que le client s'est atrocement régalé). En revanche, lorsqu'un client n'aime
        pas votre pizza, il réfléchira à deux fois avant de payer. Il est même possible qu'il s'enfui en courant sans payer
        s'il trouve votre prix excessif ! 
      </div>
    </div>
  </div>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#perioderush">
          Ouvrir le restaurant
        </a>
      </h4>
    </div>
    <div id="perioderush" class="panel-collapse collapse">
      <div class="panel-body">
          Lorsque vous avez vos recettes et votre stock d'ingrédients, vous pouvez ouvrir votre restaurant et accueillir
          des clients.<br/>
          Les clients sont générés aléatoirement par le jeu et leur nombre dépend de votre notoriété.
      </div>
    </div>
  </div>

  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#privatiser">
                  Privatiser le restaurant
              </a>
          </h4>
      </div>
      <div id="privatiser" class="panel-collapse collapse">
          <div class="panel-body">
              Vous pouvez privatiser votre restaurant. L'établissement est alors loué en échange d'1 Ouverture.<br/>
              La privatisation rapporte une somme fixe. Elle est intéressante notamment lorsque votre caisse est dans
              le rouge ou que vous n'avez plus assez d'argent pour acheter des ingrédients.
          </div>
      </div>
  </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#notoriete">
                    La notoriété
                </a>
            </h4>
        </div>
        <div id="notoriete" class="panel-collapse collapse">
            <div class="panel-body">
                La notoriété est la réputation de votre restaurant. Plus la notoriété est haute et plus vous attirerez
                des clients.<br/>
                La notoriété dépend de votre expérience et des recettes de pizzas. Plus vous obtenez de bonnes notes par
                les clients et plus vous gagnerez d'expériences.
            </div>
        </div>
    </div>

  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#capacite">
                  La capacité
              </a>
          </h4>
      </div>
      <div id="capacite" class="panel-collapse collapse">
          <div class="panel-body">
              Votre restaurant a une capacité maximale par service qu'il ne peut dépasser.<br/>
              Si la notoriété est trop haute il faudra augmenter votre capacité d'accueil pour ne pas vous retrouver
              à renvoyer des clients faute de place. Agrandir le restaurant fait partie des aménagements les plus chers
              du jeu.
          </div>
      </div>
  </div>

  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#etat">
                  L'état du bâtiment
              </a>
          </h4>
      </div>
      <div id="etat" class="panel-collapse collapse">
          <div class="panel-body">
              Votre restaurant dispose d'une santé dont il faudra impérativement prendre soin. Si la barre de santé du
              bâtiment tombe en dessous d'un certain seuil, les services de sécurité de la ville vous interdisent toute
              exploitation.<br/>
              L'état du bâtiment peut être affecté par le temps, un événement spécial ou par le vandalisme.
          </div>
      </div>
  </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#employes">
                    Les employés
                </a>
            </h4>
        </div>
        <div id="employes" class="panel-collapse collapse">
            <div class="panel-body">
                Les employés peuvent être recrutés au marché de l'emploi. Chaque employé dispose de 3 compétences
                particulières. Pour avoir accès à toutes les compétences il faut promouvoir son employé pour le passer
                au niveau suivant. Son salaire augmentera et vous aurez débloqué sa nouvelle compétence.<br/>
                On ne peut promouvoir un employé qu'une fois toutes les 24 heures.<br/><br/>

                Si vous souhaitez licencier un employé, il faudra lui verser une indemnité correspondant à une journée
                de salaire. L'indémnité est prélevée entièrement, même si vous n'avez pas assez d'argent en caisse.<br/>
                Dans de tels cas, la caisse tombera en négatif.<br/>
                Si votre caisse est dans le négatif ou que vous n'ayez plus assez d'argent pour payer l'ensemble des
                employés, ils seront tous licenciés (en ayant touché une indemnité comme pour un licenciement normal)
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#vandalisme">
                    Le vandalisme
                </a>
            </h4>
        </div>
        <div id="vandalisme" class="panel-collapse collapse">
            <div class="panel-body">
                Le vandalisme est un moyen peu orthodoxe de ralentir ses compétiteurs. Illégal et immoral, avoir recours
                à ces méthodes peut s'avérer dangereux et le retour de bâton peut être terrible. Mais en cas de réussite,
                vos compétiteurs auront l'esprit ailleurs.<br/>
                Il existe plusieurs types de vandalisme différents, certains touchent à l'économie directe du restaurant
                (ex. cambriolage), d'autres sont plus dans la paralysie temporaire (ex. graffitis).<br/>
                Il y a un coût et un pourcentage de réussite pour chaque mission. Si la mission échoue, il y a 80% de chances
                que les vandales se fassent arrêter.<br/>
                Si les vandale sont arrêtés, il y a 95% de chances qu'ils dénoncent le commanditaire de l'attaque.
                Autrement dit, plus une mission est risquée et plus il y a de chance que votre nom apparaisse dans les
                rapports de police !<br/>
                Vous risquez dans ce cas une amende. L'Amende correspond à pourcentage du coût du vandalisme multiplié
                par votre notoriété ! <strong>Plus vous avez une grande notoriété, plus l'amende sera salée.</strong><br/>
                Si votre restaurant subit une attaque, il sera protégé par la Police.
            </div>
        </div>
    </div>

  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#police">
                  La Police
              </a>
          </h4>
      </div>
      <div id="police" class="panel-collapse collapse">
          <div class="panel-body">
              La Police vous protège contre le vandalisme en multipliant les patrouilles dans votre secteur.
              Lorsque vous vous inscrivez, vous bénéficiez d'une protection pendant quelques jours, le temps pour vous
              de prendre vos marques.<br/>
              Lorsque vous subissez une attaque, vous bénéficiez également d'une protection d'au moins 24 heures contre
              toute autre attaque.<br/>
              Attention toutefois, peu importe comment la protection a été obtenue (par nouvelle inscription ou après avoir
              subi une attaque), elle s'annule si vous lancez à votre tour une attaque avant la fin de son délai.<br/>
              Exemple: je suis protégé jusqu'à demain midi, je lance une attaque aujourd'hui à 16h, je perds ma protection.<br/>
              La protection est perdue dès lors que vous donnez l'ordre aux vandales d'aller attaquer un autre restau.
          </div>
      </div>
  </div>

  <div class="panel panel-primary">
      <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#maj">
                  La mise à jour
              </a>
          </h4>
      </div>
      <div id="maj" class="panel-collapse collapse">
          <div class="panel-body">
              La mise à jour a lieu tous les soirs à minuit (heure de Paris).<br/>
              Voici ce qu'elle réalise :<br/>
              <ul>
                  <li>Efface les messages vieux de plus de 3 jours dans les rapports personnels</li>
                  <li>Paie les salaires des employés (et vérifie le déficit pour les licenciements)</li>
                  <li>Exécute les compétences spéciales des employés (s'il y a lieu)</li>
                  <li>Gère les restaurants (remise à zéro des clients du jour, réinitialise les Ouvertures)</li>
                  <li>Gère les ordres de vandalisme</li>
              </ul>

              Une fois par semaine, elle recalcule également les stocks maximaux des marchands sur le marché des ingrédients.
          </div>
      </div>
  </div>

  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#jeuAlpha">
          Jeu en Alpha
        </a>
      </h4>
    </div>
    <div id="jeuAlpha" class="panel-collapse collapse">
      <div class="panel-body">
        Le jeu est présentement en "alpha test". Cela signifie qu'en plus de ne pas être complètement abouti, il peut
        y avoir de nombreux bugs et failles.<br/>
        Il est également possible qu'une mise à jour se lance manuellement pour des raisons de test.
      </div>
    </div>
  </div>
</div><!--End of Collapse-->