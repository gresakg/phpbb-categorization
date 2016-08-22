<?php

namespace gresnet\categorization\model;

class Categorization {
    
    protected $db;
    
    public function __construct(\phpbb\db\driver\driver_interface $db) {
        $this->db = $db;
    }
    
    public function store_categorization($categories) {
        $this->db->sql_query("START TRANSACTION");
        $this->db->sql_query("DELETE FROM phpbb_classification WHERE topic_id = ".$categories[0]['topic_id']);
        $sql = "INSERT INTO phpbb_classification (topic_id,category_id,primarna) VALUES " . $this->build_insert($categories);
        //var_dump($sql);
        $this->db->sql_query($sql);
        $this->db->sql_query("COMMIT");
    }
    
    public function get_categorization($topic_id) {
        $sql="SELECT * FROM phpbb_classification WHERE topic_id = " . $topic_id;
        $result = $this->db->sql_query($sql);
        return $this->db->sql_fetchrowset();
    }
    
    public function get_topic_categories($topic_id) {
        $sql = "SELECT 
            cl.category_id, cl.primarna, ca1.parent_id, ca1.category, 
            ca2.category_id as parent_category_id, ca2.category as parent_category, ca2.parent_id as grandparent_id,
            ca3.category_id as grandparent_category_id, ca3.category as grandparent
        FROM forum.phpbb_classification as cl 
        LEFT JOIN forum.phpbb_categories as ca1
        ON cl.category_id = ca1.category_id  
        LEFT JOIN forum.phpbb_categories as ca2
        ON ca2.category_id = ca1.parent_id
        LEFT JOIN forum.phpbb_categories as ca3
        ON ca3.category_id = ca2.parent_id
        WHERE topic_id=".$topic_id . "
        ORDER BY cl.primarna DESC";
        $result = $this->db->sql_query($sql);
        return $this->db->sql_fetchrowset();
    }
    
    public function get_forum_categories($forum_id = false) {
        $sql = "SELECT 
            ca1.category_id, ca1.category, 
            ca2.category_id as parent_id, ca2.category as parent_category,
            ca3.category_id as grandparent_id, ca3.category as grandparent
        FROM forum.phpbb_categories as ca1 
        LEFT JOIN forum.phpbb_categories as ca2
        ON ca2.category_id = ca1.parent_id
        LEFT JOIN forum.phpbb_categories as ca3
        ON ca3.category_id = ca2.parent_id ";
        if($forum_id) {
            $sql .= " WHERE ca1.forum_id =".$forum_id;
        }
        $sql .= " ORDER BY grandparent_id, parent_id";
        $result = $this->db->sql_query($sql);
        return $this->db->sql_fetchrowset();
    }
    
    protected function build_insert($data) {
        $res = "";
        foreach($data as $row) {
            $res .="(";
            foreach($row as $value) {
                $res .= $value . ",";
            }
            $res = rtrim($res,",") . "),";
        }
        
        return rtrim($res,",");
    }
}