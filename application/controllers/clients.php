<?php

class Clients extends CI_Controller {
    private $aRestaurants;
    private $aRestauActuel;
    private $iClientsAttires = 0;
    private $aRecettes;
    private $aRecetteActuelle;
    private $pizzaChoisie; // Numéro de la pizza choisie
    private $aIngredients;
    private $aScoresParPizza;
    private $sLevelUp = '';

    public function __construct(){
        parent::__construct();
        $this->load->model('restaurant_model');
        $this->load->model('ingredient_model');
        $this->load->model('pizza_model');
    }

    public function cestPartiMonKiKi_rash93190($password = null) {
        if($password === $this->config->item('jeu_passwordClients')) {
            #On récupère tous les restaurants
            $this->aRestaurants = $this->restaurant_model->getAllRestaurants();

            #Pour chaque restaurant, on applique
            foreach($this->aRestaurants as $restaurant){
                $this->aScoresParPizza = null;
                $this->sLevelUp = null;

                $this->aRestauActuel = $restaurant;
                $this->aRecettes = $this->pizza_model->getPizzaByRestaurant($this->aRestauActuel['id']);

                #On récupère le score de chaque recette de pizza
                foreach ($this->aRecettes as $recette) {
                    $this->aIngredients[] = $this->getPizzaScore($recette);
                }

                #On génère le nombre de clients attirés par le restaurant
                $this->iClientsAttires = $this->calculClient();

                //echo '<p>Joueur '.$this->aRestauActuel['id_joueur'].' a attire '.$this->iClientsAttires.' clients';

                for($i = 0; $i < $this->iClientsAttires; $i++) {
                    #On génère les goûts du clients
                    $clientGout = $this->generateScores();

                    #Le client choisi une pizza au hasard
                    $this->pizzaChoisie = array_rand($this->aRecettes);

                    #On stocke la pizza retenue
                    $this->aRecetteActuelle = $this->aRecettes[$this->pizzaChoisie];

                    #Son score est évalué par le client
                    $score = $this->clientReview($clientGout, $this->aIngredients[$this->pizzaChoisie]);

                    #On enlève les ingrédients et s'il n'y en a pas
                    #on perd le client
                    if($this->consommeStock() === 'ok') {
                        $this->paiePizza($score);
                    }else{
                        #On empile les résultats dans notre conteneur
                        if(!empty($this->aScoresParPizza)){
                            if(array_key_exists($this->aRecetteActuelle['id'], $this->aScoresParPizza)) {
                                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['vente_rate']++;
                                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['count']++;
                            } else {
                                $this->aScoresParPizza[$this->aRecetteActuelle['id']] = array('score' => 0, 'CA' => 0, 'count' => 1, 'vente_rate' => 1, 'nom' => $this->aRecetteActuelle['nom']);
                            }
                        } else {
                            $this->aScoresParPizza[$this->aRecetteActuelle['id']] = array('score' => 0, 'CA' => 0, 'count' => 1, 'vente_rate' => 1, 'nom' => $this->aRecetteActuelle['nom']);
                        }
                    }
                }
                $this->stock_model->cleanStock($this->aRestauActuel['id']);
                $this->stock_model->retireVide($this->aRestauActuel['id']);
                $this->publierRapport();
            }

        } else {
            redirect(site_url());
        }
    }

    private function calculClient() {
        $notoriete = $this->aRestauActuel['notoriete'];
        $ancienneNotoriete = $notoriete - 1;

        $nombreBrut = ($notoriete - $ancienneNotoriete + $this->config->item('jeu_apportNotoriete')) * ($notoriete + $ancienneNotoriete) * $this->config->item('jeu_apportNotoriete') / 2;
        $marge = ceil($nombreBrut * $this->config->item('jeu_margeNotoriete'));

        return mt_rand($nombreBrut - $marge, $nombreBrut + $marge);
    }

    private function generateScores() {
        //le client appréciera une pizza au score proche.
        $scoreSucre = mt_rand(0,10);
        $scoreSale = mt_rand(0,10);
        $scoreCalorique = mt_rand(0,10);

        return array('sale' => $scoreSale, 'sucre' => $scoreSucre, 'calorie' => $scoreCalorique);
    }

    private function getPizzaScore($aRecette) {
        $aIngredients = json_decode($aRecette['ingredients']);
        $iScoreSale = 0;
        $iScoreSucre = 0;
        $iScoreCalories = 0;
        $iCompteur = 0;

        foreach($aIngredients as $ingredient) {
            $valeurs = $this->ingredient_model->get($ingredient);
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

    private function clientReview($aScoreClient, $aScorePizza){
        $score = 10; //Par défaut le client note au top puis ça se dégrade.

        $n = count($aScoreClient);

        for($i = 0; $i<$n; $i++) {
            $calcul = abs(current($aScoreClient) - current($aScorePizza));

            if($calcul === 0) {
                $score += 2;
            } elseif($calcul === 1) {
                $score += 1;
            } elseif($calcul > 2) {
                $score -= floor($calcul/2);
            }

            next($aScorePizza);
            next($aScoreClient);
        }

        return $score;
    }

    private function consommeStock() {
        $this->load->model('stock_model');
        $ing = json_decode($this->aRecetteActuelle['ingredients']);

        $flagContinue = true; // Tant que cette valeur vaut true, on continue.

        foreach($ing as $ingredient) {
            $qte = $this->stock_model->enStock($ingredient, $this->aRestauActuel['id'])->qte;

            if($qte == 0) {
                $flagContinue = false;

                return 'Ingredient_absent';
            }
        }

        if($flagContinue){
            #Si on a pas été bloqué par ingrédient absent
            #alors on retire l'array d'ingrédients du stock.
            $this->stock_model->retrait($ing, $this->aRestauActuel['id']);
            return 'ok';
        }
    }

    private function paiePizza($score){
        $coutProduction = 0;
        $prixPizza = $this->aRecetteActuelle['prix'];
        $aIngredients = json_decode($this->aRecetteActuelle['ingredients']);

        foreach($aIngredients as $ingredient) {
            $valeurs = $this->ingredient_model->get($ingredient);
            $coutProduction += $valeurs->prixNormalise;
        }

        $differencePrix = $prixPizza - $coutProduction;
        $seuilClient = ($score*($coutProduction + $differencePrix))/2;

        if($prixPizza > $seuilClient){
            $paie = $seuilClient;
            $score -= (10 - $score);
        } else {
            $paie = $prixPizza;
        }

        $this->restaurant_model->encaisser($paie, $this->aRestauActuel['id']);
        $this->gererXP($score);

        #On empile les résultats dans notre conteneur
        if(!empty($this->aScoresParPizza)) {
            if(array_key_exists($this->aRecetteActuelle['id'], $this->aScoresParPizza)) {
                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['score'] += $score;
                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['CA'] += $paie;
                $this->aScoresParPizza[$this->aRecetteActuelle['id']]['count']++;
            } else {
                $this->aScoresParPizza[$this->aRecetteActuelle['id']] = array('score' => $score, 'CA' => $paie, 'count' => 1, 'vente_rate' => 0, 'nom' => $this->aRecetteActuelle['nom']);
            }
        } else {
            $this->aScoresParPizza[$this->aRecetteActuelle['id']] = array('score' => $score, 'CA' => $paie, 'count' => 1, 'vente_rate' => 0, 'nom' => $this->aRecetteActuelle['nom']);
        }
    }

    private function gererXP($score) {

        //(A3-A2+10)*(A3+A2)*10/2
        $expActuel = $this->aRestauActuel['experience'];
        $notoriete = $this->aRestauActuel['notoriete'];
        $n = $notoriete + 1;

        $formula = ($n-$notoriete+$this->config->item('jeu_lvlUpNotoriete')) * ($notoriete + $n) * $this->config->item('jeu_lvlUpNotoriete') / 2;
        $nouvelleExp = $expActuel + $score;
        #Level UP!
        if($nouvelleExp >= $formula){
            $this->restaurant_model->levelUp($this->aRestauActuel['id']);
            $this->sLevelUp = '<strong>Votre notoriété augmente !</strong>';
        }
        $this->restaurant_model->addXp($score, $this->aRestauActuel['id']);

        return true;
    }

    private function publierRapport() {
        $this->load->model('rapport_model');

        foreach ($this->aScoresParPizza as $idPizza => $valeurs) {
            $notemoyenne = round($valeurs['score'] / $valeurs['count'], 1);
            $ventes = $valeurs['count']-$valeurs['vente_rate'];

            $this->pizza_model->saveMoyenne($notemoyenne, $idPizza);

            $message = '<strong>RUSH:</strong> '.$valeurs['count'].' clients ont souhaité commander votre pizza <em>'.$valeurs['nom'].'</em>.<br/>
            <strong>Bilan :</strong> '.$ventes.'/'.$valeurs['count'].' ventes réalisées.<br/>
            Gain : <strong>'.$valeurs['CA'].'$</strong> - Note moyenne : '.$notemoyenne.'<br/>
            Vous remportez <strong>'.$valeurs['score'].' XP</strong>. '.$this->sLevelUp;

            $this->rapport_model->addMessage($message, 'prive', $this->aRestauActuel['id']);
        }
    }

    
}