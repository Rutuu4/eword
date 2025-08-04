<?php

class Inquiry_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table_name = 'inquiry_send';
        $this->tbl_cus_inq_follo_rec = 'customer_inquiry_followup_record';
    }

    function inquiry_details($inquiry_id)
    {
        $this->db->select("iqs.id,fi.id AS inquiry_id,iqs.inquiry_id,fi.mobile,iqs.customer_inquiry_status,iqs.customer_followupdatee,iqs.customer_remark,fi.inquiryid");
        $this->db->join('f_inquiry AS fi','iqs.inquiry_id = fi.id','left');
        $this->db->from($this->table_name.' AS iqs');
        $this->db->where('iqs.id',$inquiry_id);
        $this->db->order_by('id', 'DESC');
        $result = $this->db->get();
        return $result->result_array();
    }

    function inquiry_follwup_track($inquiry_id)
    {
        $this->db->select("id,inquiryfollowup_id,ipfollowupdatee,followupdatee,status as customer_inquiry_status,remark_parameter");
        $this->db->from($this->tbl_cus_inq_follo_rec.' AS iqs');
        $this->db->where('inquiryfollowup_id',$inquiry_id);
        $this->db->order_by('id', 'DESC');
        $result = $this->db->get();
        //pr($this->db->last_query());
        return $result->result_array();
    }
}