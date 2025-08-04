<?php

class User_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table_name = 'user_p';
    }

    function get_user_details($userId)
    {
        $this->db->select("id, center, subcenter, typee, username,  status, create_date, expire_date, device_type, device_token");
        $this->db->from($this->userId.' AS user');
        $this->db->where('id',$userId);
        $this->db->order_by('id', 'DESC');
        $result = $this->db->get();
        //pr($this->db->last_query());
        return $result->result_array();
    }
}