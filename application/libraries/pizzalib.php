<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 04/07/14
 * Time: 16:03
 * To change this template use File | Settings | File Templates.
 */
class Pizzalib
{
    public function __construct() {
        $this->ci =& get_instance();
    }

    public function getPseudoByID($id) {
        $this->ci->load->model('user_model');
        $membre = $this->ci->user_model->getMember($id);

        return $membre->pseudo;
    }

    public function isAdmin($id) {
        $this->ci->load->model('user_model');
        $membre = $this->ci->user_model->getMember($id);

        return ($membre->role === 'admin') ? true : false;
    }

    public function publierJournal($message, $visibilite = 'public', $idRestau = 0) {
        $this->ci->load->model('rapport_model');
        return $this->ci->rapport_model->addMessage($message, $visibilite, $idRestau);
    }
}
