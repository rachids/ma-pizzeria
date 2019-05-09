<?php

class Marche extends CI_Controller {
    private $idRestau;
    private $niveauControleurDeGestion;

    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('flashmessage', 'Il faut être connecté pour voir cette page.');
            redirect('user/connexion','location');
        } elseif(!$this->restaurant_model->hasRestaurant($this->session->userdata('id'))) {
            redirect('jeu/restaurant','location');
        }

        $this->idRestau = $this->session->userdata('idRestaurant');
        $this->niveauControleurDeGestion = $this->checkNiveauControleurGestion();
    }
    
    public function ingredients($action = null) {
        $this->load->model('ingredient_model');

        $header['css'] = '<link href="'.base_url('assets/css/tableau.css').'" rel="stylesheet">';
        $footer['javascript'] = '<script type="text/javascript" src="'.base_url('assets/js/tableau.js').'"></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $(\'input[name^="ing"]\').on("change", function(){
                var number = $(\'input[name^="ing"]\').map(function(){return {val: $(this).val(), prix: $(this).attr("data-prix")};}).get();
                var cout = 0;
                var stock = 0;

                for(var key in number)
                {
                    stock = stock + parseInt(number[key].val)
                    cout = cout + ( parseInt(number[key].prix) * parseInt(number[key].val))
                }

                $(".cost").html(cout);
                $(".storage").html(stock);
            });
        });
         </script>
        ';

        $this->load->library('form_validation');
        $data['ingredients'] = $this->ingredient_model->getAll();

        foreach($data['ingredients'] as $ing){
            if($ing['stockDispo'] > 0){
                $this->form_validation->set_rules('ing['.$ing['id'].']', $ing['nom'], 'required|integer|greater_than[-1]|less_than[100]');
            }
        }

        #Formulaire envoyé
        if($this->form_validation->run()) {
            #La fonction VerifierAchat fait tout le boulot !
            $data['retour'] = $this->verifierAchat();
        } else {
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            $data['retour'] = validation_errors();
        }


        $data['ingredients'] = $this->ingredient_model->getAll();

        $data['IngNecessaire'] = array();
        #LVL1: Contrôleur de Gestion met en gras. Il faut récupérer les recettes du restaurant
        if($this->niveauControleurDeGestion >= 1) {
            $this->load->model('pizza_model');
            $pizzas = $this->pizza_model->getPizzaByRestaurant($this->idRestau);
            $ListeIngredientsNecessaires = array();

            #On mets tous les ingrédients des recettes dans un tableau
            foreach($pizzas as $pizza){
                $json = json_decode($pizza['ingredients'], true);
                $ListeIngredientsNecessaires = array_merge($ListeIngredientsNecessaires, $json);
            }

            #On supprime les ingrédients doublons
            $aIngredientsNecessaires = array_unique($ListeIngredientsNecessaires);

            $data['IngNecessaire'] = $aIngredientsNecessaires;

        }
        $data['IngStock'] = array();
        #LVL 2: Contrôleur affiche ce qu'il y a en stock
        if($this->niveauControleurDeGestion >= 2) {
            $this->load->model('stock_model');
            $arrayStock = $this->stock_model->listing($this->idRestau);

            $lvl2 = array();

            foreach($arrayStock as $stock) {
                $lvl2[$stock['id']] = $stock['quantite'];
            }
            $data['IngStock'] = $lvl2;
        }

        $data['niveauControleur'] = $this->niveauControleurDeGestion;



        $this->load->view('templates/header', $header);
        $this->load->view('jeu/marche/ingredients/list', $data);
        $this->load->view('templates/footer', $footer);

    }

    public function verifierAchat(){
        #On vire les valeurs vide
        $postIngredients = $this->input->post('ing');


        $this->load->model('stock_model');
        $restaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));
        $stockLibre = $this->stock_model->stockLibre($restaurant->id, $restaurant->stock);

        #Flag : Tant qu'il vaut true, on est bon !
        $flag = true;
        $ttc = 0;
        $quantiteTotale = 0;

        $msg = '';
        foreach($postIngredients as $ing => $qte){
            if($qte > 0){
                $ingredient = $this->ingredient_model->get($ing);

                #Si l'ingrédient existe
                if($ingredient){
                    $quantiteTotale += $qte;
                    $ttc += $qte * $ingredient->prixReel;

                    $msg .= '<li><strong>'.$qte.' '.$ingredient->nom.'</strong></li>';

                    #Verif argent
                    if($ttc > $restaurant->argent){
                        $flag = false;
                        return '<div class="alert alert-danger">Achat annulé.
                    (Cela coûte <strong>'.$ttc.'</strong>
                    <img src='.base_url($this->config->item('jeu_MoneySymbol')).' alt="$"/>)</div>';
                    }

                    #Verif stock ingredient
                    if($qte > $ingredient->stockDispo) {
                        $flag = false;
                        return '<div class="alert alert-danger">Il n\'y a pas autant de '.$ingredient->nom.' en stock</div>';
                    }

                    #Verif stock restaurant
                    if($quantiteTotale > $stockLibre){
                        $flag = false;
                        return '<div class="alert alert-danger">Plus de place dans votre stock.
                (Il ne vous reste que <strong>'.$stockLibre.' emplacement(s) libre</strong>)</div>';
                    }
                } else {
                    return '<div class="alert alert-danger">Erreur inconnue. Achat annulé</div>';
                }
            }
        }



        #Tout est ok !
        if($flag){
            foreach($postIngredients as $ing => $qte){
                #on retire la quantité du stock du marché
                $this->ingredient_model->retraitStock($qte, $ing);
                #on ajoute le tout dans le stock du restaurant
                if($qte > 0){
                    $this->stock_model->ajoutStock($qte, $restaurant->id, $ing);
                }
            }

            #ponctionne son fric
            $this->restaurant_model->payer($ttc);
            return '<div class="alert alert-info">Récapitulatif d\'achat :<br/>
                Coût total : <strong>'.$ttc.' <img src='.base_url($this->config->item('jeu_MoneySymbol')).' alt="$"/></strong>
                Quantité totale : <strong>'.$quantiteTotale.'</strong>
                 <ul>'.$msg.'</ul>
                </div>';
        }
    }

    private function checkNiveauControleurGestion(){

        $this->load->model('employe_model');
        $employe = $this->employe_model->getRestauEmploye(1, $this->idRestau);
        if(!empty($employe)){
            return $employe->niveau;
        } else {
            return 0;
        }
    }
}