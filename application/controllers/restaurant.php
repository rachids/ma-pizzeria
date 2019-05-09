<?php

class Restaurant extends CI_Controller {

    private $oRestaurant;

    public function __construct() {
        parent::__construct();
        $this->load->model('restaurant_model');
        $this->config->load('pizza');
        #Si on est pas connecté: renvoi sur le formulaire de connexion
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('flashmessage', 'Il faut être connecté pour voir cette page.');
            redirect('user/connexion','location');
        } else {
            #Si on a pas de restaurant
            if(!$this->restaurant_model->hasRestaurant($this->session->userdata('id'))) {
                #renvoi sur la page de création du restau
                $this->session->set_flashdata('flashmessage', 'Vous devez créer votre restaurant pour pouvoir jouer.');
                
                if(current_url() != site_url('jeu/restaurant/creer')){
                    redirect('jeu/restaurant/creer','location');
                }

            }
        }

        $this->oRestaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));
    }

    public function creer(){
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nom', 'Nom de votre Restaurant', 'required|alpha_dash_space|min_length[5]|max_length[25]|is_unique[restaurant.nom]');

        if($this->form_validation->run()) {
            $this->restaurant_model->addRestaurant();
            $restaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));
            $newdata = array(
                 'hasRestaurant'  => TRUE,
                 'nomRestaurant'  => $restaurant->nom,
                 'idRestaurant'   => $restaurant->id
            );

            $this->load->model('rapport_model');

            $message = $this->pizzalib->getPseudoByID($this->session->userdata('id')).
                ' vient de créer son restaurant: <strong>'.$restaurant->nom.'</strong>.';

            $this->rapport_model->addMessage($message, 'public',0);

            $this->session->set_userdata($newdata);
            $this->session->set_flashdata('flashmessage', 'Votre restaurant vient d\'ouvrir ses portes !');
            redirect('jeu/restaurant', 'location');
        }
     
        $this->load->view('templates/header');
        $this->load->view('jeu/restaurant/create');
        $this->load->view('templates/footer');
    }

    public function gestion() {
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('flashmessage', 'Il faut être connecté pour voir cette page.');
            redirect('user/connexion','location');
        } elseif(!$this->restaurant_model->hasRestaurant($this->session->userdata('id'))) {
            #renvoi sur la page de gestion du restau
            redirect('jeu/restaurant/create','location');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|integer|greater_than[0]|less_than[100]');

        if($this->form_validation->run()) {
            $action = $this->input->get_post("action");
            switch($action){
                case 'ameliorer':
                    if($this->ajoutPDB() === 'LOW_MONEY') {
                         $data['retour'] = '<div class="alert alert-danger">Pas assez d\'argent en caisse.</div>';
                    } else {
                         $data['retour'] = '<div class="alert alert-info">Amélioration de la santé du bâtiment réussie.</div>';
                    }
                break;
                case 'reparer':
                  if($this->reparer() === 'LOW_MONEY') {
                       $data['retour'] = '<div class="alert alert-danger">Pas assez d\'argent en caisse.</div>';
                  } else {
                       $data['retour'] = '<div class="alert alert-info">Réparation réussie.</div>';
                  }
                break;
                case 'augmenterStock':
                  if($this->augmenterStock() === 'LOW_MONEY') {
                       $data['retour'] = '<div class="alert alert-danger">Pas assez d\'argent en caisse.</div>';
                  } else {
                       $data['retour'] = '<div class="alert alert-info">Augmentation du stock réussie.</div>';
                  }
                break;
                case 'augmenterCapacite':
                    if($this->augmenterCapacite() === 'LOW_MONEY') {
                        $data['retour'] = '<div class="alert alert-danger">Pas assez d\'argent en caisse.</div>';
                    } else {
                        $data['retour'] = '<div class="alert alert-info">Aménagement réussi.</div>';
                    }
                    break;
            }
            $this->oRestaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));
        } else {
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            $data['retour'] = validation_errors();
        }

        $this->load->model('niveau_model');

        $restaurant = $this->oRestaurant;

        $data['nom'] = $restaurant->nom;
        $data['argent'] = $restaurant->argent;
        $data['etat'] = $restaurant->etat;
        $data['etatMax'] = $restaurant->etatMax;
        $data['stock'] = $restaurant->stock;
        $data['capacite'] = $restaurant->capacite;
        $data['open'] = $restaurant->open;
        $data['openMax'] = $restaurant->openMax;
        $data['notoriete'] = $restaurant->notoriete;
        $n = 1 + $restaurant->notoriete;
        $data['experience'] = $restaurant->experience;
        $data['xpRestant'] = $this->niveau_model->getNiveau($data['notoriete'])->experience;
        /*$data['xpRestant'] = ($n-$data['notoriete']+$this->config->item('jeu_lvlUpNotoriete'))
        * ($data['notoriete'] + $n) * $this->config->item('jeu_lvlUpNotoriete') / 2;*/
        $data['pourcentageNotoriete'] = $data['experience'] * 100 / $data['xpRestant'];

        $this->load->model('stock_model');

        $data['stockListing'] = $this->stock_model->listing($restaurant->id);

        $data['stockOccupe'] = $this->stock_model->stockOccupe($restaurant->id);

        $data['javascript'] = '
        <script type="text/javascript">
        $(document).ready(function(){
            $(".inputAmeliorer").on("change", function(){
                var cost;
                var base;
                var amount;

                base = '.$restaurant->etatMax.'
                amount = parseInt($(".inputAmeliorer").val());
                reach = base + amount

                cost = (((reach-'.$this->config->item('restaurant_etat').')-(base-'.$this->config->item('restaurant_etat').')+1)*((reach-'.$this->config->item('restaurant_etat').')+(base-'.$this->config->item('restaurant_etat').'))*'.$this->config->item('restaurant_upgradePDB').')/2;

                $("#costAmeliorateHealth").html(cost);
            });

            $(".inputReparer").on("change", function(){
                var cost;
                var amount;

                amount = parseInt($(".inputReparer").val());

                cost = (amount+1)*(amount*'.$this->config->item('restaurant_reparer').')/2
                $("#costRepairHealth").html(cost);
            });

            $(".inputAugmenter").on("change", function(){
                var cost;
                var base;
                var amount;

                base = '.$restaurant->stock.'
                amount = parseInt($(".inputAugmenter").val());
                reach = base + amount

                cost = (((reach-'.$this->config->item('restaurant_stock').')-(base-'.$this->config->item('restaurant_stock').')+1)*((reach-'.$this->config->item('restaurant_stock').')+(base-'.$this->config->item('restaurant_stock').'))*'.$this->config->item('restaurant_upgradeStock').')/2;

                $("#costAmeliorateStock").html(cost);
            });

            $(".inputCapacite").on("change", function(){
                var cost;
                var base;
                var amount;

                base = '.$restaurant->capacite.'
                amount = parseInt($(".inputCapacite").val());
                reach = base + amount

                cost = (((reach-'.$this->config->item('restaurant_capacite').')-(base-'.$this->config->item('restaurant_capacite').')+1)*((reach-'.$this->config->item('restaurant_capacite').')+(base-'.$this->config->item('restaurant_capacite').'))*'.$this->config->item('restaurant_upgradeCapacite').')/2;

                $("#costAmeliorateCapacity").html(cost);
            });
        });
        </script>
        ';


        $this->load->view('templates/header');
        $this->load->view('jeu/restaurant/gestion', $data);
        $this->load->view('templates/footer');
    }

    private function ajoutPDB() {
        $restaurant = $this->oRestaurant;

        $nombrePDB =  ($this->input->post('nombre') + $restaurant->etatMax);

        $cout = ((($nombrePDB-$this->config->item('restaurant_etat'))-($restaurant->etatMax-$this->config->item('restaurant_etat'))+1)*(($nombrePDB-$this->config->item('restaurant_etat'))+($restaurant->etatMax-$this->config->item('restaurant_etat')))*$this->config->item('restaurant_upgradePDB'))/2;

        if($restaurant->argent > $cout) {
            $this->restaurant_model->addPDB($this->input->post('nombre'),$cout);
            return true;
        } else {
            return 'LOW_MONEY';
        }
        return false;
    }

    private function augmenterCapacite() {
        $restaurant = $this->oRestaurant;

        $nombreCapacite =  ($this->input->post('nombre') + $restaurant->capacite);

        $cout = (
                    (
                        ($nombreCapacite - $this->config->item('restaurant_capacite'))
                        - ($restaurant->capacite - $this->config->item('restaurant_capacite'))
                    +1)
                    * (
                        ($nombreCapacite - $this->config->item('restaurant_capacite')) +
                        ($restaurant->capacite - $this->config->item('restaurant_capacite'))
                    )
                    * $this->config->item('restaurant_upgradeCapacite')
                )/2;

        if($restaurant->argent > $cout) {
            $this->restaurant_model->addCapacite($this->input->post('nombre'),$cout);
            return true;
        } else {
            return 'LOW_MONEY';
        }
        return false;
    }

    private function augmenterStock() {
        $restaurant = $this->oRestaurant;

        $nombreStock =  ($this->input->post('nombre') + $restaurant->stock);

        $cout = (
            (
                ($nombreStock - $this->config->item('restaurant_stock'))
                    - ($restaurant->stock - $this->config->item('restaurant_stock'))
                    +1)
                * (
                ($nombreStock - $this->config->item('restaurant_stock')) +
                    ($restaurant->stock - $this->config->item('restaurant_stock'))
            )
                * $this->config->item('restaurant_upgradeStock')
        )/2;

        if($restaurant->argent > $cout) {
            $this->restaurant_model->addStock($this->input->post('nombre'),$cout);
            return true;
        } else {
            return 'LOW_MONEY';
        }
        return false;
    }

    private function reparer() {
        $restaurant = $this->oRestaurant;

        $nombrePDB =  $this->input->post('nombre');

        //On calcule le nombre MAX qui peut être soumis :
        $reparationMax = $restaurant->etatMax - $restaurant->etat;

        //Si le nombre soumis est supérieur à MAX alors on le réduit au chiffre trouvé l. 152
        ($nombrePDB > $reparationMax) ? $nombrePDB = $reparationMax : null;

        $cout =  ($nombrePDB+1)*($nombrePDB*$this->config->item('restaurant_reparer'))/2;

        if($restaurant->argent > $cout) {
            $this->restaurant_model->reparer($nombrePDB,$cout);
            return true;
        } else {
            return 'LOW_MONEY';
        }
        return false;
    }

    public function ouvrir($param = null){
        if($param === 'go') {
            $this->oRestaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));
            $this->load->model('pizza_model');
            $recettes = $this->pizza_model->getPizzaByRestaurant($this->oRestaurant->id);
            if($this->oRestaurant->argent < 0){
                $data['retour'] = '<div class="alert alert-danger">Votre caisse est en négatif. Vous pouvez vendre vos
                    ouvertures pour rembourser vos dettes.</div>';
            } elseif(empty($recettes)){
                $data['retour'] = '<div class="alert alert-danger">Vous devez avoir au moins une recette active pour pouvoir
                    accueillir des clients.</div>';
            } elseif($this->oRestaurant->etat < $this->config->item('restaurant_seuilEtatSecurite')){
                $data['retour'] = '<div class="alert alert-danger">L\'infrastructure du bâtiment est trop fragile ! Faites
                des réparations pour accueillir les clients en sécurité.</div>';
            } elseif($this->oRestaurant->open > 0) {
                $param = array($this->oRestaurant);
                $this->load->library('client',$param);
                $clients = $this->client->ouverture();
                $this->restaurant_model->ouverture($this->oRestaurant->id); #retire 1 ouverture au restaurant.
                $this->restaurant_model->ajoutAffluence($this->oRestaurant->id, $clients); #Maj affluence.
                $data['retour'] = '<div class="alert alert-info">Vous avez attiré <strong>'.$clients.' clients.</strong>
                Consulter votre rapport pour voir le bilan.</div>';
            } else {
                $data['retour'] = '<div class="alert alert-danger">Impossible d\'ouvrir le restaurant
                 aujourd\'hui.</div>';
            }
        } elseif($param === 'privatiser'){
            $this->oRestaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));
            if($this->oRestaurant->etat < $this->config->item('restaurant_seuilEtatSecurite')){
                $data['retour'] = '<div class="alert alert-danger">L\'infrastructure du bâtiment est trop fragile ! Faites
                des réparations pour accueillir les clients en sécurité.</div>';
            } elseif($this->oRestaurant->open > 0) {
                $this->restaurant_model->ouverture($this->oRestaurant->id); #retire 1 ouverture au restaurant.
                $this->restaurant_model->encaisser($this->config->item('restaurant_coutPrivatisation'), $this->oRestaurant->id); #Maj affluence.
                $message = 'Vous avez privatisé votre restaurant pour la somme de '.$this->config->item('restaurant_coutPrivatisation')
                .' '.pizzaMoney();
                $this->pizzalib->publierJournal($message, 'prive', $this->oRestaurant->id);
                $data['retour'] = '<div class="alert alert-info">Vous avez privatisé votre restaurant.</div>';
            } else {
                $data['retour'] = '<div class="alert alert-danger">Impossible de privatiser le restaurant
                 aujourd\'hui.</div>';
            }
        }
        $this->oRestaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));

        $data['disabled'] = false;
        $data['open'] = $this->oRestaurant->open;
        $data['openMax'] = $this->oRestaurant->openMax;
        if($data['open'] == 0) {
            $data['disabled'] = true;
        }

        $this->load->view('templates/header');
        $this->load->view('jeu/restaurant/ouvrir', $data);
        $this->load->view('templates/footer');

    }
}