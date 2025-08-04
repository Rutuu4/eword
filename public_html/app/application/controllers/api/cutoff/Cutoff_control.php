<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Cutoff_control extends REST_Controller {

    function __construct() 
    {
        parent::__construct();
        include(substr($this->config->item('base_path'), 0,FOLDER_LENGHT).'/include/database.php');
        foreach (globalVars() as $key => $value) {
            if(is_array(${$value})){
                for ($i=1; $i <= count(${$value}) ; $i++) { 
                    $final[$value][$i] = ${$value}[$i];
                }
            }else{
                $final[$value] = ${$value};
            }
        } 
        $this->globalVars         = $final;
    }
     

    public function cutoff_list_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {           
                $page       = !empty($data['page_no'])?$data['page_no']-1:'1';
                $per_page   = $page * PRODUCT_PAGINATION_SIZE;

                $wherestring    = "cut_off_details.status=1";
                if(!empty($data['year_id']))
                {
                    $year_id=$data['year_id'];
                    $wherestring    .= " and cut_off_details.year_id='$year_id'";
                }
                
                 $wherestring    .= "  order by cut_off_details.display_order ASC";
               

                $fields=['cut_off_details.id,cut_off_details.name,cut_off_details.short_details'];
                $params = array(
                'table'         => TBL_CUT_OFF_DETAILS.' as cut_off_details',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                 "num"           => PRODUCT_PAGINATION_SIZE,
                 "offset"        => $per_page
            );
            $porductList = $this->General_model->get_query_data($params);  
            $cntParams = array(
                    'table'         => TBL_CUT_OFF_DETAILS.' as cut_off_details',
                    'fields'        => $fields,
                    'wherestring'   => !empty($wherestring)?$wherestring:'',
                    'compare_type'  => '=',
                    "totalrow"      => '1',
                );
            $totalProduct = $this->General_model->get_query_data($cntParams); 
             if (!empty($totalProduct)) 
             {
                $total_page = ceil($totalProduct / PRODUCT_PAGINATION_SIZE);
             }  
             if(!empty($porductList))
             {
                         $response['message']    = $this->lang->line('success');
                          $response['code']       = REST_Controller::HTTP_OK;
                          $response['total_page']     = isset($total_page)?$total_page:'1';
                          $response['data']       = $porductList;
            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }

             
                   
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
     public function cutoff_details_post()
    {
         $data = $this->post();
         $user_id=$data['user_id'];
         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {           
           
            $cutoff_id=$data['cutoff_id'];
            $wherestring    = "cut_off_details.id=$cutoff_id";
            $params = array(
                 'table'         => TBL_CUT_OFF_DETAILS.' as cut_off_details',
                 'wherestring'   => !empty($wherestring)?$wherestring:'',

            );
            $porductList = $this->General_model->get_query_data($params);  
            if(!empty($porductList))
            {
                        for ($i=0; $i < count($porductList); $i++) 
                        {
                                $porductList[$i]['pdf_file']     = check_file_exists($porductList[$i]['pdf_file']);

                        }    

                            $response['message']    = $this->lang->line('success');
                            $response['code']       = REST_Controller::HTTP_OK;
                            $response['data']       = $porductList;

            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }
            
                   
        }
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }

        
        $this->response($response, 200);
    }
        

}