<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 04/07/14
 * Time: 13:26
 * To change this template use File | Settings | File Templates.
 */
class Client
{
    private $oRestauActuel = null;
    private $iClientsAttires = 0;
    private $aRecettes = array();
    private $aRecetteActuelle = array();
    private $pizzaChoisie = null; # Numéro de la pizza choisie
    private $clientGout = array(0,0,0); #Note du client
    private $aIngredients = array();
    private $aScoresParPizza = array();
    private $sLevelUp = '';
    private $aMessages = array();
    private $nbClientsIngredients = 0; #Client perdu à cause de manque ingrédients.
    private $nbClientsPrix = 0; #Client perdu à cause du prix trop élevé.
    private $pourboire = 0;
    private $cumulPourboire = 0;
    #Niveau des employés
    private $lvlChargeComm = 0;
    private $lvlServeur = 0;

    public function __construct($restaurant) {
        $this->ci =& get_instance();
        $this->ci->load->model('restaurant_model');
        $this->ci->load->model('ingredient_model');
        $this->ci->load->model('pizza_model');
        $this->ci->load->model('stock_model');
        $this->ci->load->model('param_model');

        $this->oRestauActuel = $restaurant[0];
        $this->lvlChargeComm = $this->checkChargeCommunication();
        $this->lvlServeur = $this->checkServeur();
    }

    /**
     * Fonction qui gère l'ouverture du restaurant
     * @return int Retourne le nombre de clients attirés.
     */
    public function ouverture() {
        #Initialisation des variables
        $this->aScoresParPizza = null;
        $this->sLevelUp = null;

        #On récupère les recettes du restaurant
        $this->aRecettes = $this->ci->pizza_model->getPizzaByRestaurant($this->oRestauActuel->id);

        #On récupère le score de chaque recette de pizza
        foreach ($this->aRecettes as $recette) {
            $this->aIngredients[] = $this->getPizzaScore($recette);
        }

        #On génère le nombre de clients attirés par le restaurant
        $this->iClientsAttires = $this->calculClient();

        if($this->lvlServeur >= 1) {
            $this->oRestauActuel->capacite += ceil($this->oRestauActuel->capacite*0.10);
        }

        #Cherche le nombre de clients MAX
        if($this->iClientsAttires > $this->oRestauActuel->capacite) {
            $clientsMax = $this->oRestauActuel->capacite;

            if($this->lvlChargeComm == 0) {
                $count = 'des clients';
            } else {
                $count = isPluriel($this->iClientsAttires - $this->oRestauActuel->capacite, 'client', 'clients');
            }

            $message = 'La capacité du restaurant est insuffisante. Vous avez perdu '.$count.' !';

            $this->aMessages[] = $message;

        } else {
            $clientsMax = $this->iClientsAttires;
        }

        #Et pour chaque client on fait les confrontations!
        for($i = 0; $i < $clientsMax; $i++) {
            #On génère les goûts du clients
            $this->clientGout = $this->generateScores();

            #Le client choisi la pizza la plus proche
            $this->pizzaChoisie = $this->chosePizza();

            #On stocke la pizza retenue
            $this->aRecetteActuelle = $this->aRecettes[$this->pizzaChoisie];

            #On enlève les ingrédients et s'il n'y en a pas
            #on perd le client
            if($this->consommeStock() === 'ok') {

                #Son score est évalué par le client
                $score = $this->clientReview($this->clientGout, $this->aIngredients[$this->pizzaChoisie]);
                $this->paiePizza($score);

            }else{
                $this->aMessages['recueil'][$this->aRecetteActuelle['id']]['fail']['msg'] = 'Ingrédients manquants.';
                $this->aMessages['recueil'][$this->aRecetteActuelle['id']]['fail']['notes'][] = 0;
                #On empile les résultats dans notre conteneur
                if(!empty($this->aScoresParPizza)){
                    if(array_key_exists($this->aRecetteActuelle['id'], $this->aScoresParPizza)) {
                        $this->aScoresParPizza[$this->aRecetteActuelle['id']]['vente_rate']++;
                        $this->aScoresParPizza[$this->aRecetteActuelle['id']]['count']++;
                    } else {
                        $this->aScoresParPizza[$this->aRecetteActuelle['id']] = array('xp' => 0,'score' => 0, 'CA' => 0, 'count' => 1, 'vente_rate' => 1, 'nom' => $this->aRecetteActuelle['nom']);
                    }
                } else {
                    $this->aScoresParPizza[$this->aRecetteActuelle['id']] = array('xp' => 0,'score' => 0, 'CA' => 0, 'count' => 1, 'vente_rate' => 1, 'nom' => $this->aRecetteActuelle['nom']);
                }
            }
        }

        $this->ci->stock_model->retireVide($this->oRestauActuel->id);
        $this->publierRapport();

        #On retourne le nombre de clients reçus
        return $this->iClientsAttires;

    }

    /**
     * @param $aRecette Array contenant les ingrédients de la recette donnée.
     * @return array Retourne un array contenant les scores de la recette ainsi que son ID et son prix
     */
    private function getPizzaScore($aRecette) {
        $aIngredients = json_decode($aRecette['ingredients']);
        $iScoreSale = 0;
        $iScoreSucre = 0;
        $iScoreCalories = 0;
        $iCompteur = 0;

        foreach($aIngredients as $ingredient) {
            $valeurs = $this->ci->ingredient_model->get($ingredient);
            $iScoreSale += $valeurs->scoreSale;
            $iScoreSucre += $valeurs->scoreSucre;
            $iScoreCalories += $valeurs->scoreCalorie;
            $iCompteur++;
        }

        return array(
            'scoreSale' => round($iScoreSale/$iCompteur),
            'scoreSucre' => round($iScoreSucre/$iCompteur),
            'scoreCalorie' => round($iScoreCalories/$iCompteur),
            'id'           => $aRecette['id'],
            'prix'         => $aRecette['prix']
        );

    }

    private function calculClient() {
        $notoriete = $this->oRestauActuel->notoriete;
        $ancienneNotoriete = $notoriete - 1;

        $nombreBrut = ($notoriete - $ancienneNotoriete + $this->ci->config->item('jeu_apportNotoriete')) * ($notoriete + $ancienneNotoriete) * $this->ci->config->item('jeu_apportNotoriete') / 2;
        $marge = ceil($nombreBrut * $this->ci->config->item('jeu_margeNotoriete'));

        $event = $this->ci->param_model->checkEventEnCours();

        if($event->event == 1){
            $max = 2 * ($nombreBrut + $marge);
        } else {
            $max = $nombreBrut + $marge;
        }

        return mt_rand($nombreBrut - $marge, $max);
    }

    private function generateScores() {
        //le client appréciera une pizza au score proche.
        $scoreSucre = mt_rand(0,10);
        $scoreSale = mt_rand(0,10);
        $scoreCalorique = mt_rand(0,10);

        return array('scoreSale' => $scoreSale, 'scoreSucre' => $scoreSucre, 'scoreCalorie' => $scoreCalorique);
    }

    private function chosePizza(){
        #Array permettant de nous aider à obtenir l'info qu'on veut.
        //On chope le score du client
        $scoreClient = $this->clientGout;
        #On récupère le score des recettes.
        $pizzas = $this->aIngredients;
        #Initialisation de la différence
        $diff = 0;
        #On groupe les résultat dans un array
        $aResult = array();

        #Pour chaque Recette..
        foreach($pizzas as $pizza){
            #Calcul de la différence des score entre attente du client et score de la pizza
            foreach($scoreClient as $key=>$score){
                #On veut un nb positif
                $diff += abs($score - $pizza[$key]);
            }
            $aResult[] = $diff;
            #Reset de la variable $diff
            $diff = 0;
        }

        #On retourne l'ID de la pizza la moins distante du désir du client.
        $choix = array_keys($aResult,min($aResult));
        return $choix[0];
    }

    private function clientReview($aScoreClient, $aScorePizza){
        $score = 10; //Par défaut le client note au top puis ça se dégrade.

        $n = count($aScoreClient);

        for($i = 0; $i<$n; $i++) {
            $calcul = abs(current($aScoreClient) - current($aScorePizza));
            #Si la note est la même, rajoute 2pts
            if($calcul === 0) {
                $score += 2;
            #S'il y a une différence d'1 point, rajoute 1 pt.
            } elseif($calcul == 1) {
                $score += 1;
            } elseif($calcul > 2) {
                $score -= floor($calcul/2);
            }

            next($aScorePizza);
            next($aScoreClient);
        }

        if ($score >= 10){
            $this->aMessages['recueil'][$aScorePizza['id']]['excellente']['msg'] = 'Je crois que je viens de vivre un orgasme culinaire !';
            $this->aMessages['recueil'][$aScorePizza['id']]['excellente']['notes'][] = $score;
            $this->pourboire += 3;
        }elseif($score >= 7){
            $this->aMessages['recueil'][$aScorePizza['id']]['super']['msg'] = 'Une pizza vraiment bonne !';
            $this->aMessages['recueil'][$aScorePizza['id']]['super']['notes'][] = $score;
            $this->pourboire += 2;
        }elseif($score >= 5){
            $this->aMessages['recueil'][$aScorePizza['id']]['mouais']['msg'] = 'Ca va.. j\'ai connu pire.';
            $this->aMessages['recueil'][$aScorePizza['id']]['mouais']['notes'][] = $score;
            $this->pourboire += 1;
        }elseif($score >= 3){
            $this->aMessages['recueil'][$aScorePizza['id']]['hum']['msg'] = 'Pas top hein... Je ne reviendrai pas.';
            $this->aMessages['recueil'][$aScorePizza['id']]['hum']['notes'][] = $score;
        }else {
            $this->aMessages['recueil'][$aScorePizza['id']]['bof']['msg'] = 'ARKKKK ! Comment pouvez vous servir cette chose ?!';
            $this->aMessages['recueil'][$aScorePizza['id']]['bof']['notes'][] = $score;
        }

        #Intervention du serveur
        if($this->lvlServeur >= 2) {
            if($score < 5) {
                $score += mt_rand(1,3); #Le serveur ajoute entre 1 et 3 pts.
                $this->aMessages['serveur'] = 'Votre serveur a permis de remonter la note de certains clients.';
            }
        }

        return $score;
    }

    private function consommeStock() {
        $ing = json_decode($this->aRecetteActuelle['ingredients']);

        $flagContinue = true; // Tant que cette valeur vaut true, on continue.

        foreach($ing as $ingredient) {
            $qte = $this->ci->stock_model->enStock($ingredient, $this->oRestauActuel->id)->qte;

            if($qte == 0) {
                $flagContinue = false;
                $this->nbClientsIngredients++;

                return 'Ingredient_absent';
            }
        }

        if($flagContinue){
            #Si on a pas été bloqué par ingrédient absent
            #alors on retire l'array d'ingrédients du stock.
            $this->ci->stock_model->retrait($ing, $this->oRestauActuel->id);
            return 'ok';
        }
    }

    private function paiePizza($score){
        $coutProduction = 0;
        $prixPizza = $this->aRecetteActuelle['prix'];
        $aIngredients = json_decode($this->aRecetteActuelle['ingredients']);

        foreach($aIngredients as $ingredient) {
            $valeurs = $this->ci->ingredient_model->get($ingredient);
            $coutProduction += $valeurs->prixNormalise;
        }

        $differencePrix = $prixPizza - $coutProduction;
        $seuilClient = ($score*($coutProduction + $differencePrix))/2;

        if($prixPizza > $seuilClient){
            $paie = $seuilClient;
            $score -= (10 - $score);
            $this->nbClientsPrix++;
        } else {
            $paie = $prixPizza;
        }

        $this->ci->restaurant_model->encaisser($paie, $this->oRestauActuel->id);
        $xpGagnee = $this->gererXP($score);

        #On empile les résultats dans notre conteneur
        if(!empty($this->aScoresParPizza)) {
            if(array_key_exists($this->aRecetteActuelle['id'], $this->aScoresParPizza)) {
                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['xp'] += $xpGagnee;
                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['score'] += $score;
                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['CA'] += $paie;
                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['count']++;
            } else {
                $this->aScoresParPizza[$this->aRecetteActuelle['id']] = array(
                    'xp'    =>  $xpGagnee,
                    'score' => $score,
                    'CA' => $paie,
                    'count' => 1,
                    'vente_rate' => 0,
                    'nom' => $this->aRecetteActuelle['nom']
                );
            }
        } else {
            $this->aScoresParPizza[$this->aRecetteActuelle['id']] = array('xp' => $xpGagnee,'score' => $score, 'CA' => $paie, 'count' => 1, 'vente_rate' => 0, 'nom' => $this->aRecetteActuelle['nom']);
        }
    }

    private function gererXP($score) {
        //(A3-A2+10)*(A3+A2)*10/2
        $expActuel = $this->oRestauActuel->experience;
        $notoriete = $this->oRestauActuel->notoriete;
        $n = $notoriete + 1;

        $xpSupp = 0;

        switch(true){
            case ($score <= 0):
                $xpSupp -= 1;
                break;
            case ($score <= 5):
                $xpSupp += 1;
                break;
            case ($score <= 9):
                $xpSupp += 2;
                break;
            case ($score >= 10):
                $xpSupp += 3;
                break;
        }

        #Pourboire ajouté à l'XP
        if($this->lvlServeur >= 3){
            $xpSupp += $this->pourboire;
            $this->cumulPourboire = $this->pourboire;
            $this->pourboire = 0;
        }

        $this->ci->load->model('niveau_model');

        //$formula = ($n-$notoriete+$this->ci->config->item('jeu_lvlUpNotoriete')) * ($notoriete + $n) * $this->ci->config->item('jeu_lvlUpNotoriete') / 2;
        $formula = $this->ci->niveau_model->getNiveau($notoriete)->experience;
        $nouvelleExp = $expActuel + $xpSupp;

        $this->ci->restaurant_model->addXp($xpSupp, $this->oRestauActuel->id);
        $this->oRestauActuel->experience = $nouvelleExp;

        #Level UP!
        if($nouvelleExp > $formula){
            echo 'lvl up !';
            $this->oRestauActuel->notoriete = $n;
            $this->oRestauActuel->experience = 0;
            $this->ci->restaurant_model->levelUp($this->oRestauActuel->id);
            $this->aMessages['levelup'] = 'Votre notoriété augmente.';
            $this->publierLevelUp($n);
        }

        return $xpSupp;
    }

    private function publierRapport() {
        $this->ci->load->model('rapport_model');

        foreach ($this->aScoresParPizza as $idPizza => $valeurs) {
            $notemoyenne = round($valeurs['score'] / $valeurs['count'], 1);
            $ventes = $valeurs['count']-$valeurs['vente_rate'];

            $this->ci->pizza_model->saveMoyenne($notemoyenne, $idPizza);

            $this->aMessages['pizza'][$idPizza] = '<strong>'.$valeurs['nom'].':</strong> '.$valeurs['count'].' clients ont souhaité commander cette pizza.<br/>
            <strong>Bilan :</strong> '.$ventes.'/'.$valeurs['count'].' ventes réalisées.<br/>
            Gain : <strong>'.$valeurs['CA'].' '.pizzaMoney().'</strong> - Note moyenne : '.$notemoyenne.'<br/>
            Vous remportez <strong>'.$valeurs['xp'].' XP</strong>.<br/>';

        }

        $message = '<strong>OUVERTURE</strong><br/>';

        $perte = '';
        if($this->lvlChargeComm >= 1) {
            while (list($key, $val) = each($this->aMessages['pizza'])) {
                $message .= $val.'<br/>';

                $message .= 'Recueil des commentaires de clients:<ul>';
                if(isset($this->aMessages['pizza'][$key])){

                    foreach($this->aMessages['recueil'][$key] as $recueil){

                        $message .= '<li>'.$recueil['msg'].' <em>(Notes : ';

                        $listNotes = '';
                        foreach($recueil['notes'] as $note){
                            $listNotes .= $note.', ';
                        }
                        $message .= rtrim($listNotes.', ', ', ');
                        $message .= ')</em></li>';

                    }
                } else {
                    $message .= '<li>Aucun commentaire recueilli</li>';
                }
                $message .='</ul><hr/>';

            }
            if($this->nbClientsIngredients > 0) {
                $perte .= isPluriel($this->nbClientsIngredients, 'client perdu', 'clients perdus').' en raison du manque
                    d\'ingrédients en stock.<br/>';
            }

            if($this->nbClientsPrix > 0) {
                $perte .= isPluriel($this->nbClientsPrix, 'client a trouvé', 'clients ont trouvés').'  vos prix trop cher
                ce qui a conduit à une négociation à la baisse de l\'addition.<br/>';
            }
        } else {
            foreach($this->aMessages['pizza'] as $pizza) {

                $message .= $pizza;
            }
        }

        $message .= $perte;

        if(isset($this->aMessages['serveur'])) {
            $message .= '<p>'.$this->aMessages['serveur'].'</p>';
        }

        if($this->lvlServeur >= 3){
            $message .= '<p>Total pourboire du Serveur : '.$this->cumulPourboire.' '.pizzaMoney().' (converti en XP pour votre
            restaurant)</p>';
        }

        if(isset($this->aMessages['levelup'])) {
            $message .= '<p><strong>'.$this->aMessages['levelup'].'</strong></p>';
        }

        $this->ci->rapport_model->addMessage($message, 'prive', $this->oRestauActuel->id);
    }

    private function publierLevelUp($notoriete) {
        $this->ci->load->model('rapport_model');

        $message = 'La notoriété du restaurant <strong>'.$this->oRestauActuel->nom.'</strong> augmente !
        Elle est maintenant à '.$notoriete.'.';

        $this->ci->rapport_model->addMessage($message, 'public',0);
    }

    private function checkChargeCommunication() {
        $this->ci->load->model('employe_model');
        $employe = $this->ci->employe_model->getRestauEmploye(4, $this->oRestauActuel->id);
        if(!empty($employe)){
            return $employe->niveau;
        } else {
            return 0;
        }
    }

    private function checkServeur() {
        $this->ci->load->model('employe_model');
        $employe = $this->ci->employe_model->getRestauEmploye(3, $this->oRestauActuel->id);
        if(!empty($employe)){
            return $employe->niveau;
        } else {
            return 0;
        }
    }
}