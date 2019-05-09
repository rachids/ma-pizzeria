<?php

class News extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->model('commentaire_model');
    }

    public function index() {
        $data['news'] = array_reverse($this->news_model->get_news());
        $data['title'] = 'Les niouz';

        $this->load->view('templates/header', $data);
        $this->load->view('news/index', $data);
        $this->load->view('templates/footer', $data);
    }

    public function lire($slug) {
        $data['news'] = $this->news_model->get_news($slug);

        if( empty($data['news'])) {
            show_404();
        }

        #COMMENTAIRES
        if(!$this->session->userdata('logged_in')){
            $data['comment']['msg'] = '<p>Vous devez être connecté pour poster un commentaire.</p>';
            $data['showForm'] = false;
        } else {
            $data['comment']['msg'] = '<p>Commentez cette news en <a href="#commentForm">cliquant ici</a>.</p>';
            $data['showForm'] = true;

            $this->load->library('form_validation');

            $this->form_validation->set_rules('message', 'Commentaire', 'required|alpha_dash_space|min_length[6]|max_length[255]|xss_clean');

            if($this->form_validation->run()) {
                $this->commentaire_model->addCommentaire($data['news']['id'], $this->session->userdata('id'));
                $data['comment']['msg'] = '<div class="alert alert-success">Votre commentaire a été ajouté.</div>';
                $data['showForm'] = false;
            }
        }

        #Chargement de tous les commentaires
        $data['comment']['listeComments'] = $this->commentaire_model->getCommentaires($data['news']['id']);

        $this->load->view('templates/header', $data);
        $this->load->view('news/view', $data);
        $this->load->view('templates/footer', $data);
    }
}