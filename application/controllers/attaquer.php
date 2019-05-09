<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 11/07/14
 * Time: 19:54
 * To change this template use File | Settings | File Templates.
 */
class Attaquer extends CI_Controller
{
    private $idRestau;

    public function __construct(){
        parent::__construct();

        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('flashmessage', 'Il faut être connecté pour voir cette page.');
            redirect('user/connexion','location');
        } elseif(!$this->restaurant_model->hasRestaurant($this->session->userdata('id'))) {
            redirect('jeu/restaurant','location');
        }

        $this->idRestau = $this->session->userdata('idRestaurant');
    }

    public function index(){
        $data['restaurants'] = $this->restaurant_model->getRestaurant();

        $this->load->library('form_validation');
        $data['message'] = null;

        $this->form_validation->set_rules('restaurant', 'Restaurant', 'required|integer');
        $this->form_validation->set_message('integer', 'Vous n\'avez pas sélectionné de restaurant.');
        $this->form_validation->set_rules('mission', 'Mission', 'required|callback_checkMissions');
        $this->form_validation->set_message('mission', 'Vous n\'avez pas sélectionné de mission.');

        if($this->form_validation->run()){
            $this->assaut();
        }

        $this->load->view('templates/header');
        $this->load->view('jeu/attaquer/index', $data);
        $this->load->view('templates/footer');
    }

    private function assaut(){

        #Vérification de la thune
        $caisse = $this->restaurant_model->getRestaurant($this->idRestau)->argent;

        switch($this->input->post('mission')){
            case 'cambriolage':
                $cout = $this->config->item('pizza_cambriolage_cout');
                break;
            case 'graffiti':
                $cout = $this->config->item('pizza_graffiti_cout');
                break;
            case 'casse':
                $cout = $this->config->item('pizza_casse_cout');
                break;
        }

        if($caisse >= $cout){
            $this->load->model('assaut_model');

            if($this->input->post('restaurant') == $this->idRestau){
                $this->session->set_flashdata('flashassaut', '<div class="alert alert-danger">Vous ne pouvez pas choisir votre restaurant comme cible</div>');
                redirect('jeu/attaquer/index','location');
            } else {
                $this->restaurant_model->payer($cout);

                #Retrait de la protection policière si existante
                $this->restaurant_model->setProtectionPoliciere(new DateTime(), $this->idRestau);

                $message = 'Vous avez planifié une attaque sur <strong>'.
                    $this->restaurant_model->getRestaurant($this->input->post('restaurant'))->nom.
                    '</strong>. Vous recevrez un rapport à la mise à jour.';

                $this->pizzalib->publierJournal($message, 'prive', $this->idRestau);

                $this->assaut_model->programmerAttaque($this->input->post('mission'),$this->input->post('restaurant'),$this->idRestau);
                $this->session->set_flashdata('flashassaut', '<div class="alert alert-success">Votre attaque a été programmée.</div>');
                redirect('jeu/attaquer/index','location');
            }
        } else {
            $this->session->set_flashdata('flashmessage', 'Vous n\'avez pas assez d\'argent en caisse pour faire cette action');
            redirect('jeu/attaquer/index','location');
        }

    }

    public function checkMissions($mission){
        $missionsValides = array('cambriolage', 'graffiti', 'casse');
        return in_array($mission, $missionsValides);
    }



}
