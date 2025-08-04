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


    function get_web_user($params)
    {
        extract($params);

        $query = $this->db->query("SELECT ( EXISTS(SELECT 1 FROM register WHERE $qry_where_join) OR EXISTS(SELECT 1 FROM register_temp WHERE $qry_where_join ) )as input_exist");
        return $query->result_array();
    }

    function check_user_email($email)
    {
       $query = $this->db->query("(SELECT id,email FROM ".TBL_REGISTRATION." where email = '".$email."')");
        return $query->result_array();
    }

    function check_user_username($username)
    {
        $query = $this->db->query("(SELECT id,username FROM ".TBL_REGISTRATION." where username = '".$username."')");
        return $query->result_array();
    }

    function check_user_agent_username($username)
    {
        $query = $this->db->query("(SELECT id,username FROM ".TBL_AGENT_REGISTRATION." where username = '".$username."')");
        return $query->result_array();
    }

    function get_web_distribution_details($cmobile)
    {
       $query = $this->db->query("SELECT max(distribution_group_id)as distribution_group_id, max(distribution_group_id_sub)as distribution_group_id_sub FROM f_inquiry WHERE distribution_id = 0 and mobile='$cmobile'");
        return $query->result_array();
    }

    function get_max_distribution($cmobile)
    {
       $query = $this->db->query("(SELECT (MAX(distribution_group_id)+1) as distribution_group_id  FROM f_inquiry as f2)");
        return $query->result_array();
    }

    function get_web_distribution_tuid_details($cmobile)
    {
       $query = $this->db->query("SELECT if(max(f1.distribution_uid)>0, max(f1.distribution_uid),'') as tuid FROM f_inquiry as f1 WHERE f1.mobile='$cmobile' and f1.distribution_uid>0");
        return $query->result_array();
    }

    function get_alias()
    {
       $query = $this->db->query("select if(MAX(id)>0 , (MAX(id)+1), 1 ) as id FROM register_temp");
        return $query->result_array();
    }
}