<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zuco
 * Date: 04/07/14
 * Time: 12:41
 * To change this template use File | Settings | File Templates.
 */
class Commentaire_model extends CI_Model
{
    private $table = 'news_commentaires';

    public function __construct() {
        $this->load->database();
    }

    public function addCommentaire($idNews, $idUser) {
        $data = array(
            'message'     =>  $this->input->post('message'),
            'id_news'   =>  $idNews,
            'id_user'   =>  $idUser,
            'date'      =>  'CURRENT_TIMESTAMP'
        );

        return $this->db->insert($this->table, $data);
    }

    public function getCommentaires($idNews) {
        $sql = 'SELECT c.message, c.date, c.id_user FROM '.$this->table.' c, news n
        WHERE c.id_news = n.id AND c.id_news = ?';
        $query = $this->db->query($sql, array($idNews));
        return $query->result_array();
    }

    public function countCommentaires($idNews) {
        $sql = 'SELECT COUNT(c.id) as nb FROM '.$this->table.' c, news n
        WHERE c.id_news = n.id AND c.id_news = ?';
        $query = $this->db->query($sql, array($idNews));
        return $query->row()->nb;
    }

}
