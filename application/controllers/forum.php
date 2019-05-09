<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 10/07/14
 * Time: 00:44
 * To change this template use File | Settings | File Templates.
 */
class Forum extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('thread_model');
    }

    public function index($id = 0) {
        $data['categories'] = $this->thread_model->category_get_all($id);

        $this->load->view('templates/header');
        $this->load->view('forum/index', $data);
        $this->load->view('templates/footer');
    }

    public function categorie($slug = null){
        if(!$slug){
            redirect('forum');
        } else {
            $category = $this->db->get_where(TBL_CATEGORIES, array('slug' => $slug))->row();
            $data['showForm'] = (!$this->session->userdata('logged_in')) ? false : true;
            $data['msg'] = '';

            #Si le topic existe
            if(!$category){
                $this->session->set_flashdata('flashforum', '<div class="alert alert-danger">Catégorie introuvable</div>');
                redirect('forum');
            }

            if($data['showForm']){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('titre', 'Titre', 'required|alpha_dash_space|min_length[6]|max_length[255]|xss_clean');
                $this->form_validation->set_rules('message', 'Message', 'required|alpha_dash_space|min_length[6]|max_length[1000]|xss_clean');
                if($this->form_validation->run()) {
                    $this->thread_model->create(
                        $category->id,
                        $this->session->userdata('id'),
                        $this->input->post('titre'),
                        $this->input->post('message')
                    );
                    $data['msg'] = '<div class="alert alert-success">Votre sujet a été ajouté.</div>';
                }
            }


            $data['topics'] = $this->thread_model->get_by_category(0,10,$category->id);
            $data['categorie'] = $category;


            $this->load->view('templates/header');
            $this->load->view('forum/categorie', $data);
            $this->load->view('templates/footer');
        }
    }

    public function topic($slug = null){
        if(!$slug){
            redirect('forum');
        } else {
            $topic = $this->db->get_where(TBL_THREADS, array('slug' => $slug))->row();

            #Si le topic existe
            if(!$topic){
                $this->session->set_flashdata('flashforum', '<div class="alert alert-danger">Sujet introuvable</div>');
                redirect('forum');
            }

            $data['msg'] = '';
            $data['topic'] = $topic;
            $data['categorie'] = $category = $this->db->get_where(TBL_CATEGORIES, array('id' => $topic->category_id))->row();

            $data['showForm'] = (!$this->session->userdata('logged_in')) ? false : true;

            if($data['showForm']){
                $this->load->library('form_validation');
                $this->form_validation->set_rules('message', 'Message', 'required|alpha_dash_space|min_length[6]|max_length[1000]|xss_clean');

                if($this->form_validation->run()) {
                    $this->thread_model->reply($topic->id, $this->session->userdata('id'), $this->input->post('message'));
                    $data['comment']['msg'] = '<div class="alert alert-success">Votre message a été ajouté.</div>';
                }
            }

            $data['posts'] = $this->thread_model->get_posts($topic->id,0, 10);


            $this->load->view('templates/header');
            $this->load->view('forum/topic', $data);
            $this->load->view('templates/footer');
        }
    }

    public function editer($idPost = null) {
        $idUser = $this->session->userdata('id');
        if(!$idUser || !$idPost){
            redirect('forum');
        } else {
            $data['post'] = $this->db->get_where(TBL_POSTS, array('id' => $idPost))->row();

            if(!$data['post'] || $data['post']->author_id != $idUser){
                redirect('forum');
            } else {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('message', 'Message', 'required|alpha_dash_space|min_length[6]|max_length[1000]|xss_clean');
                $topic = $this->db->get_where(TBL_THREADS, array('id' => $data['post']->thread_id))->row();
                $data['redirection'] = 'forum/topic/'.$topic->slug;

                if($this->form_validation->run()) {
                    $this->thread_model->edit($data['post']->id, $this->input->post('message'));


                    $this->session->set_flashdata('flashforum', '<div class="alert alert-success">Votre message a été modifié.</div>');
                    redirect($data['redirection']);
                }

                $this->load->view('templates/header');
                $this->load->view('forum/edit', $data);
                $this->load->view('templates/footer');
            }
        }
    }
}
