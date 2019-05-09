<?php

class Thread_model extends CI_Model {
    public $error       = array();
    public $error_count = 0;
    public $data        = array();
    public $fields      = array();
    
    public function __construct() 
    {
        parent::__construct();
    }

    public function category_get_all($cat_id = 0)
    {
        $this->data = array();
        $this->db->order_by('id', 'asc');
        $query = $this->db->get_where(TBL_CATEGORIES, array('parent_id' => $cat_id));
        $counter = 0;
        foreach ($query->result() as $row) {
            $this->data[$counter]['id'] = $row->id;
            $this->data[$counter]['parent_id'] = $row->parent_id;
            $this->data[$counter]['name'] = $row->name;
            $this->data[$counter]['slug'] = $row->slug;
            $this->data[$counter]['description'] = $row->description;
            $this->data[$counter]['lastPost'] = $this->getLastPost($row->id);
            $children = $this->category_get_children($row->id, ' - ', $counter);
            $counter = $counter + $children;
            $counter++;
        }
        return $this->data;
    }

    public function getLastPost($idCategorie){
        $sql = 'SELECT p.*, t.*
        FROM '.TBL_POSTS.' p, '.TBL_THREADS.' t, '.TBL_CATEGORIES.' c
        WHERE p.thread_id = t.id AND t.category_id = c.id AND c.id = ?
        ORDER BY p.`date_add` DESC LIMIT 0,1';

        return $this->db->query($sql, array($idCategorie))->row();
    }

    public function category_get_children($id, $separator, $counter)
    {
        $this->db->order_by('name', 'asc');
        $query = $this->db->get_where(TBL_CATEGORIES, array('parent_id' => $id));
        if ($query->num_rows() == 0)
        {
            return FALSE;
        }
        else
        {
            foreach($query->result() as $row)
            {
                $counter++;
                $this->data[$counter]['id'] = $row->id;
                $this->data[$counter]['parent_id'] = $row->parent_id;
                $this->data[$counter]['name'] = $separator.$row->name;
                $this->data[$counter]['slug'] = $row->slug;
                $this->data[$counter]['real_name'] = $row->name;
                $children = $this->category_get_children($row->id, $separator.' - ', $counter);

                if ($children != FALSE)
                {
                    $counter = $counter + $children;
                }
            }
            return $counter;
        }
    }

    public function category_get_all_parent($id, $counter)
    {
        $row = $this->db->get_where(TBL_CATEGORIES, array('id' => $id))->row_array();
        $this->data[$counter] = $row;
        if ($row['parent_id'] != 0) {
            $counter++;
            $this->category_get_all_parent($row['parent_id'], $counter);
        }
        return array_reverse($this->data);
    }
    // end category function
    
    public function get_all($start, $limit)
    {
        $sql = "SELECT a.*, b.name as category_name, b.slug as category_slug, c.date_add 
                FROM ".TBL_THREADS." a, ".TBL_CATEGORIES." b, ".TBL_POSTS." c 
                WHERE a.category_id = b.id AND a.id = c.thread_id 
                AND c.date_add = (SELECT MAX(date_add) FROM ".TBL_POSTS." WHERE thread_id = a.id LIMIT 1) 
                ORDER BY c.date_add DESC LIMIT ".$start.", ".$limit;
        return $this->db->query($sql)->result();
    }
    
    public function get_by_category($start, $limit, $cat_id)
    {
        $sql = "SELECT a.*, b.name as category_name, b.slug as category_slug, a.date_add
                FROM ".TBL_THREADS." a, ".TBL_CATEGORIES." b, ".TBL_POSTS." c 
                WHERE a.category_id = b.id AND a.id = c.thread_id AND a.category_id = ?
                AND c.date_add = (SELECT MAX(date_add) FROM ".TBL_POSTS." WHERE thread_id = a.id LIMIT 1) 
                ORDER BY a.date_last_post DESC LIMIT ?, ?";
        return $this->db->query($sql, array($cat_id, $start, $limit))->result();
    }
    
    public function get_total_by_category($cat_id)
    {
        $cat_string = "(";
        foreach ($cat_id as $key => $id) {
            if ($key == 0) {
                $cat_string .= " a.category_id = ".$id;
            } else {
                $cat_string .= " OR a.category_id = ".$id;
            }
        }
        $cat_string .= ")";
        
        $sql = "SELECT a.* FROM ".TBL_THREADS." a WHERE ".$cat_string;
        return $this->db->query($sql)->num_rows();
    }
    
    public function create($idCategorie, $idAuteur, $titre, $message)
    {

        $this->load->helper('url');

        $slug = url_title($titre, 'dash', true);

        // check slug
        if (strlen($slug) == 0) {
            return 'Slug cannot be empty';
        } else {
            $slug_check = $this->db->get_where(TBL_THREADS, array('slug' => $slug));
            if ($slug_check->num_rows() > 0) {
                return 'Sujet existant';
            }
        }

        // check category
        $category_check = $this->db->get_where(TBL_CATEGORIES, array('id' => $idCategorie));
        if ($category_check->num_rows() === 0) {
            return 'Catégorie invalide';
        }

        $dateMsg = new DateTime();

        $data = array(
            'category_id' =>  $idCategorie,
            'title' =>  $titre,
            'slug'      =>  $slug,
            'date_add'  =>  $dateMsg->format('Y-m-d H:i:s'),
            'date_last_post ' => $dateMsg->format('Y-m-d H:i:s')
        );

        #Ajout du Thread
        $this->db->insert(TBL_THREADS, $data);

        $post = array(
            'thread_id' =>  $this->db->insert_id(),
            'author_id' =>  $idAuteur,
            'post'      =>  $message,
            'date_add'  =>  $dateMsg->format('Y-m-d H:i:s')
        );

        return $this->db->insert(TBL_POSTS, $post);
    }
        
    public function get_posts($thread_id, $start, $limit)
    {
        $sql = "SELECT a.*, b.pseudo, b.id as user_id FROM ".TBL_POSTS." a, ".TBL_USERS." b
                WHERE a.thread_id = ".$thread_id." AND a.author_id = b.id 
                ORDER BY a.date_add ASC LIMIT ".$start.", ".$limit;
        return $this->db->query($sql)->result();
    }
    
    public function reply($idSujet, $idAuteur, $message)
    {
        $dateMsg = new DateTime();
        $data = array(
            'thread_id' =>  $idSujet,
            'author_id' =>  $idAuteur,
            'post'      =>  $message,
            'date_add'  =>  $dateMsg->format('Y-m-d H:i:s')

        );

        #Ajout du POST
        $this->db->insert(TBL_POSTS, $data);

        #Modif date dernier message Thread
        $update = array('date_last_post'    =>  $dateMsg->format('Y-m-d H:i:s'));
        $this->db->where('id', $idSujet);
        return $this->db->update(TBL_THREADS, $update);
    }

    public function edit($idPost, $message){
        $dateMsg = new DateTime();
        $data = array(
            'post'       =>  $message,
            'date_edit'  =>  $dateMsg->format('Y-m-d H:i:s')
        );

        $this->db->where('id', $idPost);
        return $this->db->update(TBL_POSTS, $data);
    }
}
