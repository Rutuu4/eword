<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Subscription_control extends REST_Controller {

    function __construct() 
    {
        parent::__construct();
        $this->load->model('General_model');
    }

    public function subscription_list_post()
    {
     $data = $this->post();
     $user_id=$data['user_id'];
/*         $params = array(
                    'table'=>TBL_REGISTRATION, 
                    'where'=>array('id' => $data['user_id']),
                    'compare_type' => '=',
                );
        $chkUser = $this->General_model->get_query_data($params);
        if (!empty($chkUser))
        {     */  

            $fields         = ['subscription_package.*,m_duration.name AS duration_name,m_duration.days'];
            $wherestring    = "subscription_package.status=1";           
            $join_tables = array(  
                TBL_DURATION.' as m_duration' => 'm_duration.id=subscription_package.duration_id',
            );
            $params = array(
                'table'         => TBL_SUBSCRIPTION_PACKAGE.' as subscription_package',
                'fields'        => $fields,
                'wherestring'   => !empty($wherestring)?$wherestring:'',
                'join_type'     => 'left',
                'join_tables'   => $join_tables,
            );
            $porductList = $this->General_model->get_query_data($params);  
            if(!empty($porductList))
            {                      
                $response['message']    = $this->lang->line('success');
                $response['code']       = REST_Controller::HTTP_OK;
                $response['data']       = $porductList;
            }
            else
            {
                $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = $this->lang->line('no_record_found');
            }
            
        /*}
        else
        {
            $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = $this->lang->line('no_record_found');
        }*/

        
        $this->response($response, 200);
    }
    public function create_subscription_payment_id_post()
    {
        $data = $this->post();
        $iData['user_id']             =$data['user_id'];
        $iData['date']                =date("Y/m/d ");
        $iData['datee']               =date("Y-m-d H:i:s");
        $iData['subscription_package_id']     =$data['subscription_package_id'];
        $originalAmount              = $data['amount'];

        // Promocode validation
        $promocode = isset($data['code']) ? trim($data['code']) : '';
        $discountAmount = 0;
        if (!empty($promocode)) {
            $promo = $this->db->where(['code' => $promocode, 'status' => 1, 'id' => $data['promocode_id']])
            ->get('promocode')
            ->row_array();

            if ($promo) {
                $today = date('Y-m-d');

                if (
                    $promo['sold_quantity'] < $promo['total_quantity'] &&
                    $today >= $promo['start_date'] &&
                    $today <= $promo['end_date']
                ) {
                    $iData['discount_amount'] = $promo['amount_off'];
                    $iData['promocode'] = $promocode;
                    $iData['promocode_id'] = $promo['id'];
                    $discountAmount = $promo['amount_off'];
                } else {
                    $response['message'] = "Promocode expired or usage limit reached.";
                    return $this->response($response, 400);
                }
            } else {
                $response['message'] = "Invalid promocode.";
                return $this->response($response, 400);
            }
        }

        $finalAmount = $originalAmount - $discountAmount;

        $iData['amount']      = $finalAmount;
        $iData['grandtotal']  = $finalAmount;


        $wallet_id=$this->General_model->insert(TBL_PACKAGE_SUBSCRIPTION_ORDER, $iData);

        $prifix='EW-';
        $create_id=$prifix.$wallet_id;
        $currency='INR';  
        $fields_string  = [
            'receipt'         => $create_id,
            'amount'          => $finalAmount*100, 
            'currency'        => $currency,
            'payment_capture' => '1'
        ];
        if(!empty($data['mode']))
        {
            if($data['mode']==1)
            {   
               $key_id='rzp_test_OWrWcDN4Od9trI';
               $key_secret='BUttn0pC4khK94fi6xAEa4EC';
           }
           else
           {
            $key_id=KEY_ID;
            $key_secret=KEY_SECRET;
        }
    }
    else
    {
        $key_id=KEY_ID;
        $key_secret=KEY_SECRET;
    }

                //cURL Request
    $ch = curl_init();
                //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, RAZORPAY_API_URI);
    curl_setopt($ch, CURLOPT_USERPWD, $key_id.':'.$key_secret);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $curlresponse   = curl_exec($ch);
    $err            = curl_error($ch);
    curl_close($ch);

    if ($err) {
        $response['message']        = $this->lang->line('something_wrong_with_payment');
        $response['code']           = REST_Controller::HTTP_BAD_REQUEST;
        $this->db->where('id', $wallet_id)->delete(TBL_PACKAGE_SUBSCRIPTION_ORDER);
                    //echo "cURL Error #:" . $err;die;
    } else {

        $razorpayData = json_decode($curlresponse,true);
                    //pr($razorpayData);die;
        if(isset($razorpayData["id"]) && !empty($razorpayData["id"])){
            $response['message']        = $this->lang->line('success');
            $response['code']           = REST_Controller::HTTP_OK;
            $response['data']           = [
                'razorpay_order_id'=>$razorpayData["id"],
                'currency'=>$razorpayData["currency"],
                'amount'=>$razorpayData["amount"],   
                'exam_payment_id'=>$wallet_id,
            ];

            // if (!empty($promocode) && !empty($data['promocode_id'])) {
            //     $this->db->set('sold_quantity', 'sold_quantity + 1', FALSE);
            //     $this->db->where('code', $promocode);
            //     $this->db->where('id',$data['promocode_id']);
            //     $this->db->update('promocode');
            // }
        }
        else{
            $response['message']        = $razorpayData['error']['description'];
            $response['code']           = REST_Controller::HTTP_BAD_REQUEST;
            $this->db->where('id', $wallet_id)->delete(TBL_PACKAGE_SUBSCRIPTION_ORDER);
        }
    }       

    $this->response($response, 200);
}
public function success_subscription_payment_post() 
{   
    $data = $this->post();
    if ($this->form_validation->run('user_details') == FALSE)
    {
        $response['message'] = $this->lang->line('input_fields_required');
        $response['code']    = REST_Controller::HTTP_BAD_REQUEST;
        $response['errors']  = $this->form_validation->error_array();
    }
    else
    {
        $cdata['user_id']   = !empty($data['user_id'])?trim($data['user_id']):'';
        $cdata['amount']          = !empty($data['amount'])?trim($data['amount']):'';
        $cdata['payment_gateway_history']           = !empty($data['payment_gateway_history'])?trim($data['payment_gateway_history']):'';
        $cdata['txnid']             = !empty($data['txnid'])?trim($data['txnid']):'';

        $cdata['subscription_expire_date'] = date('Y-m-d', strtotime('+365 days'));

        if(!empty($data['payment_status']) and $data['payment_status']==1)
        {
            $cdata['payment_gateway_success_status']=1;
        }

        $cdata['promocode'] = isset($data['code']) ? trim($data['code']) : NULL;
        $cdata['discount_amount'] = isset($data['discount']) ? trim($data['discount']) : NULL;
        $cdata['promocode_id'] = isset($data['promocode_id']) ? trim($data['promocode_id']) : NULL;

        $cdata['status']=1;
        $where = array('id' => $data['order_id']);

        $this->General_model->update(TBL_PACKAGE_SUBSCRIPTION_ORDER, $cdata, $where);

        if (!empty($data['code']) && !empty($data['promocode_id'])) {
            $this->db->set('sold_quantity', 'sold_quantity + 1', FALSE);
            $this->db->where('code', $data['code']);
            $this->db->where('id', $data['promocode_id']);
            $this->db->update('promocode');
        }

        if(!empty($data['chat_course_id']))
        {

         if(!empty($data['fullname']))
         {
            $udata['fullname']=!empty($data['fullname'])?trim($data['fullname']):'';
        }


        $udata['interesting_city_ids']             = !empty($data['interesting_city_ids'])?$data['interesting_city_ids']:'';
        $udata['interesting_main_course_id']       = !empty($data['interesting_main_course_id'])?$data['interesting_main_course_id']:'';

        $udata['interesting_exrta_course_id']       = !empty($data['interesting_exrta_course_id'])?$data['interesting_exrta_course_id']:'';

        $udata['course_details_ids']               = !empty($data['course_details_ids'])?$data['course_details_ids']:'';

        $udata['is_chnage_group']=0;
        $udata['interesting_city_ids']               = !empty($data['interesting_city_ids'])?$data['interesting_city_ids']:'';
        $udata['city']               = !empty($data['city'])?$data['city']:'';

        $udata['chat_course_id']=!empty($data['chat_course_id'])?trim($data['chat_course_id']):'';
        $udata['chat_room_group_joining_msg_seen_count']=0;

        if(!empty($data['chat_course_id']) and !empty($data['payment_status']) and $data['payment_status']==1)
        {
            $chat_course_id=$data['chat_course_id'];

            $this->db->select('chat_room_group.id, COUNT(DISTINCT chat_room_txn.id) AS chat_room_group_joining_msg_count')
            ->from('chat_room_group')
            ->join('registration', 'registration.chat_room_group_id = chat_room_group.id AND registration.is_app_admin = 0', 'left')
            ->join('chat_room_txn', 'chat_room_txn.chat_room_group_id = chat_room_group.id', 'left')
            ->where('chat_room_group.chat_course_id', $chat_course_id)
            ->group_by('chat_room_group.id')
            ->having('COUNT(registration.id) <', 500);



            $query = $this->db->get();
            $group_count = $query->num_rows();
            if($group_count>0)
            {
             $row_chk = $query->row_array();
             $udata['chat_room_group_id'] = $row_chk['id'];
             $udata['chat_room_group_joining_msg_count'] = $row_chk['chat_room_group_joining_msg_count'];
             $udata['chat_room_group_joining_datetime']=date("Y-m-d H:i:s");

         }
         else
         {
            $this->db->select('chat_room_group.id, chat_course.name AS chat_course_name')
            ->from('chat_room_group')
            ->join('chat_course', 'chat_course.id = chat_room_group.chat_course_id', 'left')
            ->where('chat_room_group.chat_course_id', $chat_course_id);

            $query = $this->db->get();
            $group_count = $query->num_rows();
            $row_chk_group = $query->row_array(); 
            $chat_course_name = $row_chk_group['chat_course_name'] ?? null;

            $iData['chat_course_id']             = $chat_course_id;
            $iData['name']             = $chat_course_name.' Group-'.$group_count+1;
            $iData['status']=1;
            $iData['member_limit']=500;
            $inserted_data = $this->General_model->insert(TBL_CHAT_GROUP, $iData);
            $udata['chat_room_group_id']=$inserted_data;
            $udata['chat_room_group_joining_datetime']=date("Y-m-d H:i:s");
        }

    }

    $where = array('id' => $data['user_id']);
    $this->General_model->update(TBL_REGISTRATION, $udata, $where);
}

$response['message']    = $this->lang->line('success');
$response['code']       = REST_Controller::HTTP_OK;
}
$this->response($response, 200);
}
public function my_subscription_list_post()
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

    $fields         = ['package_subscription_order.*,m_duration.name AS duration_name,m_duration.days,subscription_package.name AS package_subscription_name'];
    $wherestring    = "package_subscription_order.status=1 and package_subscription_order.user_id='$user_id' order by package_subscription_order.date DESC";           
    $join_tables = array(  
        TBL_SUBSCRIPTION_PACKAGE.' as subscription_package' => 'subscription_package.id=package_subscription_order.subscription_package_id',
        TBL_DURATION.' as m_duration' => 'm_duration.id=subscription_package.duration_id',
    );
    $params = array(
        'table'         => TBL_PACKAGE_SUBSCRIPTION_ORDER.' as package_subscription_order',
        'fields'        => $fields,
        'wherestring'   => !empty($wherestring)?$wherestring:'',
        'join_type'     => 'left',
        'join_tables'   => $join_tables,
    );
    $porductList = $this->General_model->get_query_data($params);  
    if(!empty($porductList))
    {                      
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

public function verify_promocode_post()
{
    $data = $this->post();
    $promocode = isset($data['code']) ? trim($data['code']) : '';

    if (empty($promocode)) {
        $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
        $response['message'] = 'Promocode is required.';
        return $this->response($response, 200);
    }

    $today = date('Y-m-d');

    // Properly escape or bind this value to prevent SQL errors
    $this->db->where('code', $promocode);
    $this->db->where('status', 1);
    $query = $this->db->get('promocode');

    $promoData = $query->result_array();

    if (!empty($promoData)) {
        $promo = $promoData[0];

        if ($promo['start_date'] <= $today && $promo['end_date'] >= $today) {
            if ($promo['sold_quantity'] < $promo['total_quantity']) {
                $response['code'] = REST_Controller::HTTP_OK;
                $response['message'] = 'Promocode is valid.';
                $response['data'] = [
                    'id' => $promo['id'],
                    'code' => $promo['code'],
                    'amount_off' => $promo['amount_off'],
                    'start_date' => $promo['start_date'],
                    'end_date' => $promo['end_date'],
                    'total_quantity' => $promo['total_quantity'],
                    'sold_quantity' => $promo['sold_quantity']
                ];
            } else {
                $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
                $response['message'] = 'Promocode usage limit reached.';
            }
        } else {
            $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
            $response['message'] = 'Promocode is expired or not yet active.';
        }
    } else {
        $response['code'] = REST_Controller::HTTP_BAD_REQUEST;
        $response['message'] = 'Invalid promocode.';
    }

    return $this->response($response, 200);
}




}