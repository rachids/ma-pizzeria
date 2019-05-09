<?php

class Book extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('book_model');
    }

    public function index() {
        $data['book'] = $this->book_model->getMessages();
        $data['title'] = 'Livre d\'Or';
        $data['sendok'] = false;

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('pseudo', 'Le pseudo', 'required');
        $this->form_validation->set_rules('message', 'Le message', 'required');

        $this->load->view('templates/header', $data);

        if($this->form_validation->run() === false) {
            $this->load->view('book/formulaire.php');
        } else {
            $data['sendok'] = true;
            $this->book_model->addMessage();
        }

        #PAGINATION
        $this->load->library('pagination');

        $config['base_url'] = 'http://127.0.0.1/codeigniter/index.php/book/index/';
        $config['total_rows'] = 6;
        $config['per_page'] = 5;

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('book/index', $data);

        $this->load->view('templates/footer', $data);
    }
}