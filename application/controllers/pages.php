<?php

class Pages extends CI_Controller {

    public function index() {

        $this->load->model('news_model');

        $data['topRestau'] = $this->restaurant_model->getTopRestaurants(3);
        $data['news'] = $this->news_model->getLastNews();

        $date = new DateTime($data['news']->date);

        $data['dateNews'] = $date->format('d/m/Y');
        $data['fullDateNews'] = $date->format('d/m/Y à H:i:s');

        $this->load->model('commentaire_model');

        $data['nbComments'] = $this->commentaire_model->countCommentaires($data['news']->id);


        $this->load->view('templates/header');
        $this->load->view('pages/home', $data);
        $this->load->view('templates/footer');
    }

    public function view($page = 'home') {
        if ( !file_exists(APPPATH . '/views/pages/'.$page.'.php')) {
            show_404();
        }

        $data['title'] = ucfirst($page);

        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);
    }

    public function regles() {
        $this->load->view('templates/header');
        $this->load->view('pages/regles');
        $this->load->view('templates/footer');
    }

    public function credit() {
        $this->load->view('templates/header');
        $this->load->view('pages/credits');
        $this->load->view('templates/footer');
    }

    public function classement($type = "notoriete"){

        switch($type){
            case 'notoriete':
                $data['intro'] = '<p>Les 10 restaurants ayant la meilleure notoriété.</p>';
                $data['topRestau'] = $this->restaurant_model->getTopRestaurants(10);
                $data['valeur'] = 'Notoriété';
                break;
            case 'richesse':
                $data['intro'] = '<p>Les 10 restaurants les plus riches</p>';
                $data['topRestau'] = $this->restaurant_model->getTopRestaurants(10, 'richesse');
                $data['valeur'] = 'Richesse';
                break;
            case 'capacite':
                $data['intro'] = '<p>Les 10 plus grands restaurants</p>';
                $data['topRestau'] = $this->restaurant_model->getTopRestaurants(10, 'capacite');
                $data['valeur'] = 'Capacité';
                break;
            case 'affluence':
                $data['intro'] = '<p>Les 10 restaurants les plus populaires</p>';
                $data['topRestau'] = $this->restaurant_model->getTopRestaurants(10, 'affluence');
                $data['valeur'] = 'Affluence totale';
                break;
            default:
                $data['intro'] = '<p>Les 10 restaurants ayant la meilleure notoriété.</p>';
                $data['topRestau'] = $this->restaurant_model->getTopRestaurants(10);
                $data['valeur'] = 'Notoriété';
                break;
        }

        $this->load->view('templates/header');
        $this->load->view('jeu/classement', $data);
        $this->load->view('templates/footer');
    }
}