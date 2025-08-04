<?php

class Shipping_charge_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    
    function shipping_charge($params)
    {
        extract($params);
       
        $totalShippingWeight    = $this->total_shipping_weight($total_shipping_weight);
        $totalWeight            = $this->total_weight($total_weight);

        $courier_slab_id        = $totalShippingWeight['id'];
        $courier_slab_cod_id    = $totalWeight['id'];

        $query = $this->db->query("SELECT c_slab$courier_slab_id,cod_slab$courier_slab_cod_id FROM m_state where id='$shipping_id'");

        if( $query->num_rows() > 0)
        {
            $data =  $query->result_array();

            $response['shipping_charge'] = $data[0]['c_slab'.$courier_slab_id];
            $response['shipping_charge_cod'] = $data[0]['cod_slab'.$courier_slab_cod_id];
                
            return $response;
        }
    }

    function total_shipping_weight($totalShippingWeight)
    {
        $query = $this->db->query("SELECT id FROM m_couriercharge where ".$totalShippingWeight." between fromm and too");
        return $query->row_array();
    }

    function total_weight($totalWeight)
    {
        $query = $this->db->query("SELECT id FROM m_couriercharge where ".$totalWeight." between fromm and too");
        return $query->row_array();
    }
}