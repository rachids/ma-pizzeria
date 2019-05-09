<?php

class User_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    function addUser() {
        $this->load->library('encrypt');

        $sPassword = $this->encrypt->sha1($this->input->post('password'));

        $data = array(
            'pseudo'    =>  $this->input->post('pseudo'),
            'password'  =>  $sPassword,
            'email'     =>  $this->input->post('email'),
        );

        return $this->db->insert('user', $data);
    }

    function getMember($id = null) {
        if($id){
            $sql = 'SELECT * FROM user WHERE id = ?';

            return $this->db->query($sql, array($id))->row();
        }

        $query = $this->db->get('user');
        return $query->result_array();
    }

    function validCredentials($username,$password){
        #On charge la librairie Encrypte pour crypter le mot de passe
         $this->load->library('encrypt');

         $password = $this->encrypt->sha1($password);

         //requête préparée, beaucoup plus sécurisée
         $q = "SELECT * FROM user WHERE email = ? AND password = ?";

         $data = array($username,$password);
         $q = $this->db->query($q,$data);

         if($q->num_rows() > 0){
              $r = $q->result();
              $session_data = array('username' => $r[0]->pseudo,'logged_in' => true, 'id' => $r[0]->id);
              $this->session->set_userdata($session_data);
              return true;
         } else { return false; }
    }

    function isLoggedIn(){
        if($this->session->userdata('logged_in')) { 
            return true; 
        } else { 
            return false; 
        }
    }

    function getFbUser($id) {
        $sql = 'SELECT * FROM user WHERE facebook = ?';

        return $this->db->query($sql, array($id))->row();
    }

    function addFacebookUser($aUser) {
        $sexe = 0;
        if($aUser['gender'] === 'male') {
            $sexe = 1;
        } elseif($aUser['gender'] === 'female') {
            $sexe = 2;
        }

        $data = array(
            'pseudo'    =>  $aUser['first_name'] . ' ' . substr($aUser['last_name'], 0, 1).'.', #Rachid Z.
            'password'  =>  'facebook Connect',
            'email'     =>  $aUser['email'],
            'facebook'  =>  $aUser['id'],
            'sexe'      =>  $sexe,
            'avatar'    =>  'http://graph.facebook.com/'.$aUser['id'].'/picture?type=large'
        );

        return $this->db->insert('user', $data);
    }

    function count(){
        $sql = 'SELECT COUNT(*) as nb FROM user';

        return $this->db->query($sql)->row()->nb;
    }

    public function update($data, $idMembre){
        return $this->db->update('user', $data, 'id = '. $idMembre);
    }

    public function verifEmail($email, $id){
        $sql = 'SELECT COUNT(*) as nb FROM user WHERE email = ? AND id != ?';

        return $this->db->query($sql, array($email,$id))->row()->nb;
    }

    public function check_role()
    {
        $user_id = $this->session->userdata('id');
        // get roles
        if ($user_id) {
            $row = $this->db->get_where(TBL_USERS, array('id' => $user_id))->row();
            $roles = $this->db->get_where(TBL_ROLES, array('id' => $row->role_id))->row_array();
            foreach ($roles as $key => $value) {
                $this->session->set_userdata($key, $value);
            }
        }
    }
}