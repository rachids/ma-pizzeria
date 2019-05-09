<?php

class Ingredient extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('ingredient_model');
        $this->config->load('pizza');
    }

    public function getIngredients(){
        return $this->ingredient_model->getAll();
    }
}