<?php

class News_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_news($slug = false) {
        if($slug === false) {
            $sql = 'SELECT n.*, COUNT(c.id) as nbcomments
            FROM news n
            LEFT JOIN news_commentaires c ON n.id = c.id_news
            GROUP BY n.id';

            $query = $this->db->query($sql);
            return $query->result_array();
        }

        $query = $this->db->get_where('news', array('slug' => $slug));
        return $query->row_array();
    }

    public function set_news() {
        $this->load->helper('url');

        $slug = url_title($this->input->post('title'), 'dash', true);

        $date = new DateTime();

        $data = array(
            'title' =>  $this->input->post('title'),
            'slug'  =>  $slug,
            'text'  =>  $this->input->post('text'),
            'date'  =>  $date->format('Y-m-d H:i:s')
        );

        return $this->db->insert('news', $data);
    }

    public function getLastNews() {
        $sql = 'SELECT * FROM news ORDER BY date DESC LIMIT 0,1';

        return $this->db->query($sql)->row();
    }
}