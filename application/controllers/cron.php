<?php

class Cron extends CI_Controller
{
    #Pour les assauts
    private $restauCible;
    private $restauCommanditaire;
    private $cibleEnCours;
    private $listeCibles = array();
    private $aMessagePublic = array();
    private $lvlAgentSecuriteCible = 0;
    private $ratioAmende = 0;

    public function __construct(){
        parent::__construct();
        /*if($this->input->ip_address() != '82.230.204.51' || $this->input->ip_address() != '50.116.9.254') {
            show_404();
        }*/

        $this->load->model('cron_model');
    }

    public function lafameusemiseajour($pass) {
        if($pass === 'so651-5_difvjsoeirfsoerfDPFVjsdr5PO3J4pojczpeofj2P3Ojpoj'){
            $this->cron_model->cleanReport();
            $this->cron_model->gererSalaire();
            $this->chargeCommlvl2();
            $this->cron_model->restauUpdate();
            $this->assaut();
            $this->gestionEvent();

            echo 'MAJ reussie.';

        } else {
            redirect('jeu');
        }
    }

    public function majxfourpardays($pass){
        if($pass === 'sodicsE934J2OEjcsodjf3'){
            $this->reassortIngredients();
            echo 'Ing reussi.';

            #Si dw > 20, alors on est à la MAJ du soir
            $timestamp = time();
            $dw = date( "H", $timestamp);
            if($dw > 20){
                $this->recalculeStockIngredients();
            }
            /*
             * POUR les Stocks le DIMANCHE
            #Si dw = 0, alors dw = Dimanche
            $timestamp = time();
            $dw = date( "w", $timestamp);
            if($dw == 0){
                $this->recalculeStockIngredients();
            }
            */

        } else {
            redirect('jeu');
        }
    }

    private function chargeCommlvl2(){
        $allChargeComm = $this->db->get_where('employes_restaurant',array('id_employe' => 4, 'niveau >=' => 2))->result_array();

        foreach($allChargeComm as $ChargeComm){
            $restos = $this->db->get_where('restaurant', array('id' => $ChargeComm['id_restau']))->result_array();

            foreach($restos as $resto){
                if($resto['affluenceDay'] > 0){
                    $xp = mt_rand(1,3);
                    $this->restaurant_model->addXp($xp, $resto['id']);
                    $message = '[MISE A JOUR] Le Bouche à Oreille de votre Chargée de Communication vous fait gagner <strong>'.$xp.'</strong> XP.';
                    $this->pizzalib->publierJournal($message, 'prive', $resto['id']);
                }
            }
        }
    }

    private function assaut() {
        $this->load->model('assaut_model');
        $attaques = $this->assaut_model->get();

        foreach($attaques as $assaut){
            #On initialise l'Agent de Sécurité puisque chaque restaurant aura son propre employé
            $this->lvlAgentSecurite = 0;
            $this->ratioAmende = $this->config->item('pizza_ratioAmende');

            #Assignation des attributs
            $this->cibleEnCours = $assaut['restau_cible'];
            $this->restauCible = $this->restaurant_model->getRestaurant($this->cibleEnCours);
            $this->restauCommanditaire= $this->restaurant_model->getRestaurant($assaut['restau_commanditaire']);

            #On vérifie que la cible en cours n'a pas déjà été attaquée
            if(!in_array($this->cibleEnCours, $this->listeCibles)){

                #Si aucune attaque, on vérifie qu'il n'y a pas de présence policière
                $datePolice = new DateTime($this->restauCible->police);

                if(new DateTime() > $datePolice){
                    #On l'ajoute a la liste pour qu'elle ne subisse pas + d'1 attaque.
                    $this->listeCibles[] = $this->cibleEnCours;

                    #Récupération de l'employé
                    $this->lvlAgentSecuriteCible = $this->checkNiveauAgentSecurite($assaut['restau_cible']);

                    #Jet de dé
                    $dice = mt_rand(1,100);

                    switch($assaut['ordre']){
                        case 'cambriolage':

                            $chanceReussite = $this->config->item('pizza_cambriolage_chance');

                            if($this->lvlAgentSecuriteCible >= 2) {
                                $chanceReussite -= $this->config->item('pizza_securite_lvl2');
                            }

                            echo 'Chance de réussite : '.$chanceReussite.'% - Jet: '.$dice.'<br/>';

                            #Tryin
                            if($chanceReussite >= $dice){
                                #Réussite !
                                $facteur = (mt_rand(1,4)/10);
                                $caisse = $this->restauCible->argent;

                                #Si la caisse est vide ou en négatif il se passe rien.
                                if($caisse <= 0){
                                    $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                            Mission : '.$assaut['ordre'].' sur le restaurant '. $this->restauCible->nom.'<br/>
                            Chance de réussite : '.$chanceReussite.'% - Jet: '.$dice.'<br/>
                            Bilan : Les vandales vous appellent pour vous dire que la caisse de la cible est complètement
                             vide ! Ils n\'ont rien pu dérober.';

                                    $this->pizzalib->publierJournal($message, 'prive', $assaut['restau_commanditaire']);
                                } else {
                                    $sommeVolee = round($caisse*$facteur);

                                    $this->restaurant_model->debiter($sommeVolee, $this->restauCible->id);

                                    #Appel à la police pour protéger.
                                    $this->protectionPoliciere();

                                    $msgCible = '<strong>CAMBRIOLAGE !</strong><br/>Des voleurs se sont introduits dans votre
                            restaurant et ont volé <strong>'.$sommeVolee.'</strong> '.pizzaMoney().' !<br/>
                            Vous bénéficiez d\'une présence policière vous permettant d\'être protégé contre toute attaque
                            pendant 24h.';

                                    $this->pizzalib->publierJournal($msgCible, 'prive', $assaut['restau_cible']);

                                    $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                            Mission : '.$assaut['ordre'].' sur le restaurant '. $this->restauCible->nom.'<br/>
                            Chance de réussite : '.$chanceReussite.'% - Jet: '.$dice.'<br/>
                            Bilan : Les vandales vous appellent pour vous dire que la mission est réussie. Ils ont pu
                            dérober <strong>'.$sommeVolee.'</strong> '.pizzaMoney().' !';

                                    $this->pizzalib->publierJournal($message, 'prive', $assaut['restau_commanditaire']);

                                    $message = '<strong>Au vol !</strong><br/>La police rapporte que le restaurant '.
                                        $this->restauCible->nom.' vient d\'être victime d\'un cambriolage. Le montant dérobé
                            s\'élève à <strong>'.$sommeVolee.'</strong> '.pizzaMoney().' !';

                                    $this->aMessagePublic[] = $message;
                                }

                            } else {
                                #Echec !
                                $this->assautRate($assaut['ordre'], $chanceReussite, $dice);
                            }
                            break;
                        case 'graffiti':
                            $chanceReussite = $this->config->item('pizza_graffiti_chance');

                            if($this->lvlAgentSecuriteCible >= 2) {
                                $chanceReussite -= $this->config->item('pizza_securite_lvl2');
                            }

                            echo 'Chance de réussite : '.$chanceReussite.'% - Jet: '.$dice.'<br/>';

                            #Tryin
                            if($chanceReussite >= $dice){
                                #Réussite !
                                $degradation = mt_rand(1,12);

                                $this->restaurant_model->affecterEtatBatiment($degradation, $this->restauCible->id);

                                #Appel à la police pour protéger.
                                $this->protectionPoliciere();

                                $msgCible = '<strong>GRAFFITIS !</strong><br/>De terribles malandrins ont recouvert vos
                                 murs de graffitis avec des mots vraiment pas cool et souvent mal orthographié.<br/>
                                 Il en résulte une dégradation de la santé de votre bâtiment (- '.$degradation.' pts).<br/>
                            Vous bénéficiez d\'une présence policière vous permettant d\'être protégé contre toute attaque
                            pendant 24h.';

                                $this->pizzalib->publierJournal($msgCible, 'prive', $assaut['restau_cible']);

                                $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                            Mission : '.$assaut['ordre'].' sur le restaurant '. $this->restauCible->nom.'<br/>
                            Chance de réussite : '.$chanceReussite.'% - Jet: '.$dice.'<br/>
                            Bilan : Les Graffeurs ont vidés leurs bombes sur son restaurant. Le bâtiment perd <strong>'.
                                    $degradation.'</strong> points de santé !';

                                $this->pizzalib->publierJournal($message, 'prive', $assaut['restau_commanditaire']);

                                $message = '<strong>Des graffitis partout !</strong><br/>C\'est l\'incompréhension pour
                                les dirigeants de '. $this->restauCible->nom.' qui découvrent avec stupéfaction la violence
                                verbale dont a été victime leur restaurant. Le bâtiment est couvert de tags et a perdu
                                <strong>'.$degradation.'</strong> points de santé !';

                                $this->aMessagePublic[] = $message;
                            } else {
                                $this->assautRate($assaut['ordre'], $chanceReussite, $dice);
                            }
                            break;
                        case 'casse':
                            $chanceReussite = $this->config->item('pizza_casse_chance');

                            if($this->lvlAgentSecuriteCible >= 2) {
                                $chanceReussite -= $this->config->item('pizza_securite_lvl2');
                            }

                            echo 'Chance de réussite : '.$chanceReussite.'% - Jet: '.$dice.'<br/>';

                            #Tryin
                            if($chanceReussite >= $dice){
                                #Réussite !
                                $facteur = (mt_rand(1,4)/10);
                                $caisse = $this->restauCible->argent;
                                $sommeVolee = round($caisse*$facteur);
                                $degradation = mt_rand(7,25);

                                $this->restaurant_model->affecterEtatBatiment($degradation, $this->restauCible->id);
                                $this->restaurant_model->debiter($sommeVolee, $this->restauCible->id);

                                #Appel à la police pour protéger.
                                $this->protectionPoliciere();

                                $msgCible = '<strong>CASSE !</strong><br/>Des casseurs se sont introduits dans votre
                            restaurant et ont tout détruit !<br/>
                            Le coût des réparations s\'élève à <strong>'.$sommeVolee.'</strong> '.pizzaMoney().' !<br/>
                            De plus votre bâtiment doit être réparé car il a perdu - '.$degradation.' pts.<br/>
                            Vous bénéficiez d\'une présence policière vous permettant d\'être protégé contre toute attaque
                            pendant 24h.';

                                $this->pizzalib->publierJournal($msgCible, 'prive', $assaut['restau_cible']);

                                $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                            Mission : '.$assaut['ordre'].' sur le restaurant '. $this->restauCible->nom.'<br/>
                            Chance de réussite : '.$chanceReussite.'% - Jet: '.$dice.'<br/>
                            Bilan : Les casseurs vous informent que la mission est réussie. Ils ont détruit pour
                            <strong>'.$sommeVolee.'</strong> '.pizzaMoney().' d\'équipements et le restaurant a perdu
                             <strong>'.$degradation.'</strong> points de santé !';

                                $this->pizzalib->publierJournal($message, 'prive', $assaut['restau_commanditaire']);

                                $message = '<strong>Dégats matériels !</strong><br/>La police rapporte que le restaurant '.
                                    $this->restauCible->nom.' a subi un carnage dans la soirée. Une horde de casseurs aurait
                                    saccager le restaurant. Les experts chiffrent le bilan à <strong>'.$sommeVolee.'</strong>
                                    '.pizzaMoney().', le bâtiment quant à lui perd <strong>'.$degradation.'</strong>
                                    points de santé !';

                                $this->aMessagePublic[] = $message;
                            } else {
                                #Echec !
                                $this->assautRate($assaut['ordre'], $chanceReussite, $dice);
                            }
                            break;
                    }

                } else {
                    $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                            Mission : '.$assaut['ordre'].' sur le restaurant '. $this->restauCible->nom.'<br/>
                            Bilan : Les vandales vous appellent pour vous informer que le restaurant est surveillé par
                             la police ! Il est bien trop risqué de procéder à l\'attaque. La mission est annulée.';

                    $this->pizzalib->publierJournal($message, 'prive', $assaut['restau_commanditaire']);
                }




            } else {
                #Cette cible a déjà été attaquée.

                $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                Mission : '.$assaut['ordre'].' sur le restaurant '. $this->restauCible->nom.'<br/>
                Bilan : Les vandales vous appellent pour vous dire que la cible subit déjà l\'attaque d\'un autre groupe
                de vandales ! Ils gardent votre argent mais vous pouvez avoir la satisfaction que votre cible s\'est attiré
                d\'autres ennemis.';

                $this->pizzalib->publierJournal($message, 'prive', $assaut['restau_commanditaire']);

            }

            #La mission est archivée
            $this->assaut_model->setMissionDone($assaut['id']);
        }

        $this->publierJournal();

        var_dump($attaques);
    }

    private function protectionPoliciere(){
        $date = new DateTime('now');
        if($this->lvlAgentSecuriteCible >= 3) {
            $date->modify( '+2 days' );
        } else {
            $date->modify( '+1 day' );
        }

        $this->restaurant_model->setProtectionPoliciere($date, $this->cibleEnCours);
    }

    private function checkNiveauAgentSecurite($id){

        $this->load->model('employe_model');
        $employe = $this->employe_model->getRestauEmploye(2, $id);
        if(!empty($employe)){
            return $employe->niveau;
        } else {
            return 0;
        }
    }

    private function assautRate($mission, $chance, $jet){
        #Echec !
        #ARRESTATION
        if($this->lvlAgentSecuriteCible >= 3){
            $chanceEchapper = 0;
        } else {
            $chanceEchapper = $this->config->item('pizza_echapper_chance');
        }
        $jetArret = mt_rand(1,100);

        #Tu t'es fais arrêter !
        if($jetArret > $chanceEchapper) {
            $chanceDenonciation = $this->config->item('pizza_denonciation_chance');
            $jetDenoncer = mt_rand(1,100);

            #Woah.. ils t'ont pas balance !! CHANCEUX!
            if($jetDenoncer > $chanceDenonciation){
                $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                                    Mission : '.$mission.' sur le restaurant '. $this->restauCible->nom.'<br/>
                                    Chance de réussite : '.$chance.'% - Jet: '.$jet.'<br/>
                                    Chance évasion : '.$chanceEchapper.'% - Jet: '.$jetArret.'<br/>
                                    Chance dénonciation : '.$chanceDenonciation.'% - Jet: '.$jetDenoncer.'<br/>
                            Bilan : Les vandales se sont fait arrêter par la police mais ils ne vous ont pas dénoncé.';

                $this->pizzalib->publierJournal($message, 'prive', $this->restauCommanditaire->id);

                #MSG PUBLIC
                $this->aMessagePublic[] = $this->messagePublic($mission,'nondenoncer');

            } else {
                #Ils ont balance !

                $valeurNotoriete = mt_rand(1, $this->restauCommanditaire->notoriete);
                $amende = ($this->config->item('pizza_'.$mission.'_cout') * $this->ratioAmende) * $valeurNotoriete;

                $this->restaurant_model->debiter($amende, $this->restauCommanditaire->id);

                $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                                    Mission : '.$mission.' sur le restaurant '. $this->restauCible->nom.'<br/>
                                    Chance de réussite : '.$chance.'% - Jet: '.$jet.'<br/>
                                    Chance évasion : '.$chanceEchapper.'% - Jet: '.$jetArret.'<br/>
                                    Chance dénonciation : '.$chanceDenonciation.'% - Jet: '.$jetDenoncer.'<br/>
                            Bilan : Les Graffeurs se sont fait arrêter par la police et vous ont dénoncé !<br/>
                            La police vous inflige une amende de '.$amende.' '.pizzaMoney().'.';

                $this->pizzalib->publierJournal($message, 'prive', $this->restauCommanditaire->id);

                #Génération du msg public
                $this->aMessagePublic[] = $this->messagePublic($mission, 'denoncer', $amende);
            }
        #Mission ratée mais Evasion réussie
        } else {
            $message = '<strong>RAPPORT D\'ASSAUT :</strong><br/>
                                    Mission : '.$mission.' sur le restaurant '. $this->restauCible->nom.'<br/>
                                    Chance de réussite : '.$chance.'% - Jet: '.$jet.'<br/>
                                    Chance évasion : '.$chanceEchapper.'% - Jet: '.$jetArret.'<br/>
                            Bilan : Les Graffeurs n\'ont pas réussi à remplir leur mission. La police est intervenue trop
                             tôt ! Ils ont néanmoins réussi à s\'enfuir.';

            $this->pizzalib->publierJournal($message, 'prive', $this->restauCommanditaire->id);

            $this->aMessagePublic[] = $this->messagePublic($mission, 'evasion');
        }
    }

    private function publierJournal(){
        $news = '<p class="text-danger"><strong>Les Faits Divers</strong></p>';

        if(!empty($this->aMessagePublic)){
            foreach($this->aMessagePublic as $msg){
                $news .= '<p class="text-primary">'.$msg.'</p>';
            }

            $this->pizzalib->publierJournal($news);
        }
    }

    private function messagePublic($mission, $etat, $amende = 0){
        switch($mission){
            case "cambriolage":
                if($etat == 'nondenoncer'){
                    $message = '<strong>Cambrioleurs !</strong><br/>Des voyous ont été repérés aux
                                    abords du restaurant '. $this->restauCible->nom.'. Alors qu\'ils s\'apprêtaient à
                                    voler la caisse, la police est intervenu et les a arrêté.<br/>
                                    L\'enquête est au point mort et on n\'en sait pas plus sur les raisons de ce
                                    cambriolage.';

                } elseif($etat == 'evasion'){
                    $message = '<strong>Cambrioleurs !</strong><br/>Incroyable scénario aux
                                    abords du restaurant '. $this->restauCible->nom.'. Des cambrioleurs tentaient de
                                    dérober les caisses du restaurant mais la police est arrivée à temps.<br/>
                                    Malheureusement les individus ont réussi à prendre la fuite.';

                } elseif($etat == 'denoncer'){
                    $message = '<strong>Cambrioleurs !</strong><br/>Des cambrioleurs ont été pris en flagrant délit
                                dans le restaurant '. $this->restauCible->nom.'. <br/>
                                L\'enquête aurait révélé que le restaurant '.$this->restauCommanditaire->nom.' serait le
                                commanditaire de cette attaque ! Il devrait payer une amende de '.$amende.' '.pizzaMoney().'.';

                }
                break;

            case "graffiti":
                if($etat == 'nondenoncer'){
                    $message = '<strong>Graffiti !</strong><br/>Des voyous ont été repérés aux
                                    abords du restaurant '. $this->restauCible->nom.'. Alors qu\'ils s\'apprêtaient à
                                    recouvrir les murs de graffitis, la police est intervenu et les a arrêté.<br/>
                                    L\'enquête est au point mort et on n\'en sait pas plus sur les raisons de ce
                                    vandalisme.';

                } elseif($etat == 'evasion'){
                    $message = '<strong>Graffiti !</strong><br/>Des voyous ont été repérés aux
                                    abords du restaurant '. $this->restauCible->nom.'. La police est intervenue alors
                                     qu\'ils s\'apprêtaient à recouvrir les murs de graffitis. <br/>
                                     Malheureusement les individus ont réussi à prendre la fuite.';

                } else{
                    $message = '<strong>Graffiti !</strong><br/>Des voyous ont été repérés aux
                                    abords du restaurant '. $this->restauCible->nom.'. Alors qu\'ils s\'apprêtaient à
                                    recouvrir les murs du restaurant '. $this->restauCible->nom.' de graffitis, les voyous
                                    ont été arrêtés et placé en détention par la police.<br/>
                                    L\'enquête aurait révélé que le restaurant '.$this->restauCommanditaire->nom.'
                                    serait le commanditaire de ce vandalisme ! Il devrait payer une amende de
                                    '.$amende.' '.pizzaMoney().'.';

                }
                break;

            case "casse":
                if($etat == 'nondenoncer'){
                    $message = '<strong>Casseurs !</strong><br/>Des casseurs armés de battes de baseball et de
                         pied-de-biche ont été repérés aux abords du restaurant '. $this->restauCible->nom.'.
                         Il semblerait qu\'ils souhaitaient entièrement démolir le restaurant. L\'intervention de la police
                         a pu éviter ce vandalisme et tous les individus ont été maîtrisé et arrêté.<br/>
                         L\'enquête est au point mort et on n\'en sait pas plus sur les raisons de cette montée de violence.';

                } elseif($etat == 'evasion'){
                    $message = '<strong>Casseurs !</strong><br/>Des casseurs ont été repérés aux
                                    abords du restaurant '. $this->restauCible->nom.'. La police est intervenue alors
                                     qu\'ils s\'apprêtaient à démolir les vitrines du restaurant. <br/>
                                     Malheureusement les individus ont réussi à prendre la fuite.';

                } else{
                    $message = '<strong>Casseurs !</strong><br/>Des casseurs ont été repérés aux
                                    abords du restaurant '. $this->restauCible->nom.'. Alors qu\'ils s\'apprêtaient à
                                    détruire les biens du restaurant '. $this->restauCible->nom.', les casseurs
                                    ont été arrêtés et placé en détention par la police.<br/>
                                    L\'enquête aurait révélé que le restaurant '.$this->restauCommanditaire->nom.'
                                    serait le commanditaire de cette attaque ! Il devrait payer une amende de
                                    '.$amende.' '.pizzaMoney().'.';

                }
                break;
        }

        return $message;
    }

    private function reassortIngredients(){
        $this->load->model('ingredient_model');
        $aIngredients = $this->ingredient_model->getAll();

        foreach($aIngredients as $ingredient){
            $stockMax = $ingredient['stockMax'];
            $stock = $ingredient['stockDispo'];

            $prctStock = $stock/$stockMax;

            $rand = mt_rand(1,5);

            $reassort = floor($stockMax * (1 - $prctStock)/$rand);

            echo '<p>+ '.$reassort.' '.$ingredient['nom'].' (multiplicateur x'.$rand.')</p>';

            $newStock = $stock + $reassort;

            #Décommenter pour que le prix se base APRES réassort
            //$prctStock = $newStock/$stockMax;

            switch(true){
                case ($prctStock < $this->config->item('ingredient_valeurPlus2')):
                    $prix = $ingredient['prixNormalise'] + 2;
                    break;
                case ($prctStock < $this->config->item('ingredient_valeurPlus1')):
                    $prix = $ingredient['prixNormalise'] + 1;
                    break;
                case ($prctStock > $this->config->item('ingredient_valeurMoins2')):
                    $prix = $ingredient['prixNormalise'] - 2;
                    break;
                case ($prctStock > $this->config->item('ingredient_valeurMoins1')):
                    $prix = $ingredient['prixNormalise'] - 1;
                    break;
                default:
                    $prix = $ingredient['prixNormalise'];
            }

            #Si jamais la réduc est trop balèze
            if($prix <= 0){
                $prix = 1;
            }


            $arr = array(
                'prixReel' =>  $prix,
                'stockDispo'    =>  $newStock
            );

            $this->ingredient_model->update($arr, $ingredient['id']);
        }
    }

    private function recalculeStockIngredients(){
        #Somme des notoriétés
        $this->db->select_sum('notoriete');
        $query = $this->db->get_where('restaurant', array('affluenceDay >' => 0))->row();

        #Bonus pour éviter pénurie violente
        $bonus = 2;

        #Si aucun joueur n'a joué (;_;) on met la noto à 0.5 pour éviter les "divisions by 0"
        $sommeNotoriete = ($query->notoriete > 0) ? $query->notoriete + $bonus : $bonus + 0.5;

        $this->load->model('ingredient_model');
        $aIngredients = $this->ingredient_model->getAll();

        foreach($aIngredients as $ingredient){
            $maxStock = $sommeNotoriete * $ingredient['rarete'] * 10;

            if($maxStock != $ingredient['stockMax']) {
                echo '<p>Stock '.$ingredient['nom'].', ancien : '.$ingredient['stockMax'].' - new : '.$maxStock.'</p>';
                $arr = array(
                    'stockMax' =>  $maxStock
                );

                $this->ingredient_model->update($arr, $ingredient['id']);
            }
        }
    }

    private function gestionEvent(){
        $this->load->model('param_model');
        $event = $this->param_model->checkEventEnCours();
        #Vérifier qu'il n'y a pas d'événement en cours:
        if($event->event == 0){
            #On commence par le match de foot
            $dice = mt_rand(1,100);

            echo 'Foot = Chance :'.$event->prct_event.'% jet :'.$dice;

            if($dice < $event->prct_event){
                $this->param_model->setEvent(1);
                #Message du match

                $teams = $this->config->item('soccerTeams');
                $diminutifHome = array_rand($teams);
                $fullTeamHome = $teams[$diminutifHome];
                unset($teams[$diminutifHome]);
                $diminutifAway = array_rand($teams);


                $message = '<p class="text-primary">[MISE A JOUR] <strong>Rencontre au sommet !</strong><br/>
                Demain se déroulera la rencontre qu\'attend tout fan de football qui se respecte !<br/>
                En effet les joueurs <em>'.$teams[$diminutifAway].'</em> se déplacent pour rencontrer ceux <em>'.
                $fullTeamHome.'</em>.<br/>
                Alors, '.$diminutifHome.' ou '.$diminutifAway.', pour qui penche votre coeur ?</p>

                <p>Les pizzerias risquent de subir un afflux massif de clients aujourd\'hui !</p>';
                $this->pizzalib->publierJournal($message);

            } else {
                #Sinon incrémentation du pourcentage
                $this->param_model->eventUpChance($this->config->item('soccerEvent'));

                #Plus tard autre événements ici.
            }


        } else {
            $this->param_model->initializeEvent();
        }
    }

    public function test(){
    }
}
