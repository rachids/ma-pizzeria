<?php
class User extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() {
        if($this->user_model->isLoggedIn()) {
            redirect('user/profil', 'location');
        } else {
            redirect('user/connexion', 'location');
        }
    }

    public function profil($id = null) {
        if(!$this->user_model->isLoggedIn()){
         redirect('user/connexion','location');
        } else {
          #Si ID vaut null mais qu'on est loggé, alors ID vaut l'ID du membre connecté
          ($id === null) ? $id = $this->session->userdata('id') : null;

          $this->load->view('templates/header');

          $data['membre'] = $this->user_model->getMember($id);
          # Si getMember nous renvoie un array vide,
          # alors le membre n'existe pas.
          if(empty($data['membre'])) {
            $this->session->set_flashdata('flashmessage', 'Ce membre n\'existe pas.');
            redirect(site_url());
          }

            $data['restaurant'] = $this->restaurant_model->getRestaurantByMember($id);

          $this->load->view('user/profil/monprofil', $data);
        
          $this->load->view('templates/footer');
        }
    }

    public function stats($id = null) {
        if(!$this->user_model->isLoggedIn()){
            redirect('user/connexion','location');
        } else {
            #Si ID vaut null mais qu'on est loggé, alors ID vaut l'ID du membre connecté
            ($id === null) ? $id = $this->session->userdata('id') : null;

            if(!$id){
                redirect('user/connexion', 'location');
            } else {
                $this->load->view('templates/header');

                $data['membre'] = $this->user_model->getMember($id);
                # Si getMember nous renvoie un array vide,
                # alors le membre n'existe pas.
                if(empty($data['membre'])) {
                    $this->session->set_flashdata('flashmessage', 'Ce membre n\'existe pas.');
                    redirect(site_url());
                }

                $data['restaurant'] = $this->restaurant_model->getRestaurantByMember($id);

                $this->load->view('user/profil/stats', $data);

                $this->load->view('templates/footer');
            }
        }
    }

    public function update() {
        if($this->user_model->isLoggedIn()) {
            $id = $this->session->userdata('id');

            $data['membre'] = $this->user_model->getMember($id);
            $donnees = null;

            $this->load->library('form_validation');

            $this->form_validation->set_rules('email', 'Adresse email', 'required|valid_email|callback_checkUpdateEmail');
            $this->form_validation->set_rules('password', 'Mot de passe', 'required|alpha_dash_space');
            $this->form_validation->set_rules('password-check', 'Vérification du mot de passe', 'matches[password]');
            $this->form_validation->set_rules('sexe', 'Sexe', 'required|is_natural|less_than[3]');

            $this->form_validation->set_message('is_natural', 'Champ Sexe invalide');
            $this->form_validation->set_message('less_than', 'Champ Sexe invalide');

            if($this->form_validation->run()) {
                $this->load->library('encrypt');

                $password = $this->input->post('password');
                $pcheck = $this->input->post('password-check');
                $sPassword = $this->encrypt->sha1($password);
                $updateFlag = false;

                if($pcheck === ''){
                    #Pas de modif de mot de passe
                    if($sPassword === $data['membre']->password){
                        $donnees = array(
                            'email' => $this->input->post('email'),
                            'sexe'  => $this->input->post('sexe')
                        );
                        $updateFlag = true;
                    }
                } else {
                    $donnees = array(
                        'email' => $this->input->post('email'),
                        'sexe'  => $this->input->post('sexe'),
                        'password' => $sPassword
                    );
                    $updateFlag = true;
                }

                if($updateFlag){
                    $this->user_model->update($donnees, $this->session->userdata('id'));
                    $data['membre'] = $this->user_model->getMember($id);

                    $data['msg'] = '<div class="alert alert-success">Votre profil a été mis à jour.</div>';
                }else {
                    $data['msg'] = '<div class="alert alert-danger">Votre mot de passe ne correspond pas à ce compte.
                    Si vous souhaitez le modifier, il faut remplir les deux champs de mot de passe et veiller à ce
                    qu\'ils soient identiques.</div>';
                }
            } else {
                $data['msg'] = validation_errors('<div class="alert alert-danger">','</div>');
            }

            $this->load->view('templates/header');
            $this->load->view('user/profil/update', $data);
            $this->load->view('templates/footer');
        } else {
            redirect('user/connexion', 'location');
        }
    }

    public function upload(){
        $config['upload_path'] = $this->config->item('avatarTemp');
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']	= '100';
        $config['max_width']  = '1024';
        $config['max_height']  = '768';
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        $this->load->view('templates/header');

        if ( ! $this->upload->do_upload('image'))
        {
            #Echec de l'envoi...
            $this->session->set_flashdata('errorMsg',
                $this->upload->display_errors('<div class="alert alert-danger">','</div>'));
        }
        else
        {
            $upload_data = $this->upload->data();

            #Si on a bien une image
            if($upload_data['is_image']){
                #Charge la clé d'AvatarsIO API
                $key = $this->config->item('avatarsioKey');
                #Load la librairie
                $this->load->library('avatarsio/Avatar', array($key));

                $tampon = $this->session->userdata('username').$this->session->userdata('id');

                $urlFichier = $this->avatar->upload($upload_data['full_path'],'pizzaiolo-'.$tampon);

                $data = array(
                    'avatar' => $urlFichier->data.'?size=large'
                );

                $this->user_model->update($data, $this->session->userdata('id'));

                $this->session->set_flashdata('errorMsg',
                    '<div class="alert alert-success">Votre avatar a été mis à jour</div>');
            } else {
                $this->session->set_flashdata('errorMsg',
                    '<div class="alert alert-danger">Le fichier transmis n\'est pas une image.</div>');
            }
        }
        redirect('user/update','location');
    }

    public function inscription() {
      if($this->user_model->isLoggedIn()) {
        redirect('user/profil', 'refresh');
      } else {
        $this->load->view('templates/header');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Cette adresse Email', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required|matches[password-check]|alpha_dash_space');
        $this->form_validation->set_rules('password-check', 'Retapez le mot de passe', 'required');
        $this->form_validation->set_rules('pseudo', 'Ce Pseudo', 'required|min_length[5]|max_length[12]|is_unique[user.pseudo]|alpha_dash_space');

        $this->form_validation->set_message('is_unique', '%s est déjà utilisé(e).');

        if(!$this->form_validation->run()) {
          $this->load->view('user/inscription');
        } else {
          $this->user_model->addUser();
          $this->load->view('user/inscription-success');
        }

        $this->load->view('templates/footer');
      }
    }

    public function loginFB() {
        #Load la librairie
        $this->load->library('facebookv3/facebook', array(
                'appId' => '767636019954598',
                'secret' => '89c28e00966e0651e5309d6bd579937e',
                ));

        $this->facebook->getUser();

            try {
                $user = $this->facebook->api('/me');
                #Connexion à FB réussie
                $userBDD = $this->user_model->getFbUser($user['id']);

                if(empty($userBDD)) {
                    #On l'inscrit dans la base si on ne l'a pas trouvé
                    $this->user_model->addFacebookUser($user);
                    redirect(current_url());
                } else {
                    #On récupère ses infos sur la table user comme le login classique
                    if($this->restaurant_model->hasRestaurant($userBDD->id)){
                        $restaurant = $this->restaurant_model->getRestaurantByMember($userBDD->id);
                        $session_data = array(
                            'hasRestaurant'  => TRUE,
                            'nomRestaurant'  => $restaurant->nom,
                            'idRestaurant'   => $restaurant->id
                        );
                    }

                    $session_data['username'] = $userBDD->pseudo;
                    $session_data['logged_in'] = true;
                    $session_data['id'] = $userBDD->id;
                    $session_data['fb'] = true;

                    $this->session->set_userdata($session_data);

                    $this->session->set_flashdata('flashmessage', 'Connexion réussie. Bienvenue.');
                    redirect('user/profil/'.$this->session->userdata('id'),'refresh');
                }
            } catch (FacebookApiException $e) {
                $user = null;
            }
        if(!$user) {
            $this->facebook->destroySession();
           redirect($this->facebook->getLoginUrl(array(
                'redirect_uri' => site_url('user/loginFB'),
                'scope' => array("email","public_profile","user_birthday") // permissions here
            )));
        }
    }

    public function connexion() {
      if($this->user_model->isLoggedIn()){
           redirect('user/profil','refresh');
      } else {
           //on charge la validation de formulaires
           $this->load->library('form_validation');

           //on définit les règles de succès
           $this->form_validation->set_rules('username','Login','required');
           $this->form_validation->set_rules('password','Mot de passe','required');

           //si la validation a échouée on redirige vers le formulaire de login
           if(!$this->form_validation->run()){
                $this->load->view('templates/header');
                $this->load->view('user/login');
                $this->load->view('templates/footer');
           } else {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $validCredentials = $this->user_model->validCredentials($username,$password);

                if($validCredentials){
                  if($this->restaurant_model->hasRestaurant($this->session->userdata('id'))){
                    $restaurant = $this->restaurant_model->getRestaurantByMember($this->session->userdata('id'));
                    $newdata = array(
                         'hasRestaurant'  => TRUE,
                         'nomRestaurant'  => $restaurant->nom,
                         'idRestaurant'   => $restaurant->id
                    );

                    $this->session->set_userdata($newdata);
                  }

                  $this->session->set_flashdata('flashmessage', 'Connexion réussie. Bienvenue.');
                  redirect('user/profil/'.$this->session->userdata('id'),'refresh');
                } else {
                  $data['error_credentials'] = 'Mauvais identifiants';
                  $this->load->view('templates/header', $data);
                  $this->load->view('user/login', $data);
                  $this->load->view('templates/footer', $data);
                }
           }
      }
    }

    public function deconnexion() {
      $this->session->sess_destroy();
      redirect(site_url(), 'location');
    }

    public function checkUpdateEmail(){
        $id = $this->session->userdata('id');
        $email = $this->user_model->getMember($id)->email;

        #Si on trouve un email déjà existant mais pour un ID différent alors c pas bon.
        if($this->user_model->verifEmail($email, $id) == 1) {
            $this->form_validation->set_message('checkUpdateEmail', 'Cet email est déjà utilisé.');
            return FALSE;
        } else {
            #Soit c'est un tout nouvel email, soit c'est l'email déjà utilisé par le membre actuel donc on est bon.
            return true;
        }
    }
}