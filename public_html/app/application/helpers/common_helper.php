<?php
defined('BASEPATH') or exit('No direct script access allowed');

function get_phone_number_by_type($type)
{
    $CI = &get_instance();
    $CI->load->database();

    $row = $CI->db
        ->select('phone_number, status')
        ->from('mou_person')
        ->where('type', $type)   // 🔥 IMPORTANT
        ->where('status', 1)
        ->limit(1)
        ->get()
        ->row();

    return (!empty($row)) ? $row->phone_number : null;
}
