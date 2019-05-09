<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 09/07/14
 * Time: 12:28
 * To change this template use File | Settings | File Templates.
 */
class Emploi extends CI_Controller
{
    private $idRestau;

    public function __construct() {
        parent::__construct();

        if(!$this->session->userdata('logged_in')){
            $this->session->set_flashdata('flashmessage', 'Il faut être connecté pour voir cette page.');
            redirect('user/connexion','location');
        } elseif(!$this->restaurant_model->hasRestaurant($this->session->userdata('id'))) {
            redirect('jeu/restaurant','location');
        }

        $this->load->model('employe_model');
        $this->idRestau = $this->session->userdata('idRestaurant');
    }

    public function index() {
        redirect('jeu/emploi/recruter');
    }

    public function licencier($id = null){
        $employeRestau = $this->employe_model->getRestauEmploye($id, $this->idRestau);
        if($employeRestau) {
            $employe = $this->employe_model->getEmployeByID($id);

            $indemnite = $employe->salaire * $employeRestau->niveau;
            $this->employe_model->licencier($id, $this->idRestau);
            $this->restaurant_model->payer($indemnite, $this->idRestau);
            $msg = 'Vous avez renvoyé votre '.$employe->nom.' et lui avez payé une indemnité de '.$indemnite.'
            '.pizzaMoney().'.';
            $this->pizzalib->publierJournal($msg, 'prive', $this->idRestau);
            $this->session->set_flashdata('flashmessage', $msg);
        }

        redirect('jeu/emploi/recruter');
    }

    public function recruter() {
        $this->load->library('form_validation');
        $data['message'] = null;

        $this->form_validation->set_rules('emploi', 'employé', 'required');

        if($this->form_validation->run()) {
            #On retourne l'ID de l'employé
            $iEmployeID = $this->input->post('emploi');
            $employeChoisi = $this->employe_model->getEmployeByID($iEmployeID);

            if(!empty($employeChoisi)) {
                #Vérifier le salaire et le montant en caisse
                $salaire = $employeChoisi->salaire;
                $caisse = $this->restaurant_model->getRestaurant($this->idRestau)->argent;

                #Vérifier niveau de l'employé
                $employe = $this->employe_model->getRestauEmploye($iEmployeID, $this->idRestau);

                if(!empty($employe)) {
                    #On vérifie que la dernière action date d'il y a plus d'un jour
                    $diffAction = differenceDate($employe->lastAction);
                    if($diffAction === true) {
                        if($employe->niveau < 3) {
                            if($caisse >= ($salaire * ($employe->niveau + 1))) {
                                $this->restaurant_model->payer($salaire * ($employe->niveau + 1));
                                $this->employe_model->recruter($iEmployeID, $this->idRestau);

                                $journal = 'Vous avez promu votre <strong>'.$employeChoisi->nom.'</strong> et il passe
                    au niveau <strong>'.($employe->niveau+1).'</strong>.';

                                $this->pizzalib->publierJournal($journal, 'prive', $this->idRestau);

                                $data['message'] = $journal;
                            } else {
                                $data['message'] = 'Vous n\'avez pas assez d\'argent en caisse.';
                            }

                        } else {
                            $data['message'] = $employeChoisi->nom.' a atteint le niveau maximum.';
                        }
                    } else {
                        $data['message'] = 'Vous devez attendre encore <strong>'.$diffAction['h'].'h'.$diffAction['m'].'min
                    </strong> avant de pouvoir promouvoir votre '.$employeChoisi->nom.'.';
                    }
                } else {

                    if($caisse >= $salaire) {
                        $this->restaurant_model->payer($salaire);
                        $this->employe_model->recruter($iEmployeID, $this->idRestau);

                        $journal = 'Vous avez recruté 1 <strong>'.$employeChoisi->nom.'</strong>.';

                        $this->pizzalib->publierJournal($journal, 'prive', $this->idRestau);

                        $data['message'] = $employeChoisi->nom.' recruté.';

                    } else {
                        $data['message'] = 'Vous n\'avez pas assez d\'argent en caisse.';
                    }
                }
            } else {
                $data['message'] = 'Erreur, veuillez rafraîchir la page et réessayer.';
            }

        }

        $data['employes'] = $this->employe_model->get($this->idRestau);

        $this->load->view('templates/header');
        $this->load->view('jeu/emploi/recruter', $data);
        $this->load->view('templates/footer');
    }

    public function checkEmploye($employe) {
        return in_array($employe, $this->aEmployesDisponible);
    }
}
