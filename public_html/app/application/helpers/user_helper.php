<?php
	function check_admin_login(){
		$CI = & get_instance();  //get instance, access the CI superobject
  		$adminLogin = $CI->admin_session;
        (!empty($adminLogin['id']))?'':redirect('admin/login?redirect='.urlencode(uri_string()));  	
	}  

    function date_format_con($date,$format)
    {
        return date($format,strtotime($date));
    }

    function string_convert_ucw($string)
    {
        if(!empty($string))
        {
            return ucwords(strtolower(trim($string)));
        }
    }

    function check_file_exists($filename)
    {

        $CI = & get_instance();
        //echo $CI->config->item('org_base_path').'/'.$path.$filename;die;
        if(!empty($filename) && file_exists($CI->config->item('org_base_path').'/'.BASE_PATH.$filename))
        {
            //echo DOMAIN_NAME_URL.BASE_PATH.$filename;die;
            return DOMAIN_NAME_URL.BASE_PATH.$filename;
        }
        else
        {
              return '';
        }
    }

    function remove_file_exists($path,$filename)
    {
        $CI = & get_instance();
        if(!empty($filename) && file_exists($CI->config->item('org_base_path').'/'.$path.$filename))
        {
            unlink($CI->config->item('org_base_path').'/'.$path.$filename);
        }
    }

    function check_file_exists_with_path($filename,$url)
    {
        $CI = & get_instance();
        if(!empty($filename) && file_exists($CI->config->item('org_base_path').'/'.$filename))
        {
            return $url.$filename;
        }
        else
        {
            return '';
        }
    }

    function remove_char($string)
    {
        if(!empty($string))
        {
            return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        }
    }

    function string_convert_ucf($string)
    {
        if(!empty($string))
        {
            return ucfirst(strtolower(trim($string)));
        }
    }

	function pr($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

    function prd($arr)
    {
        $CI = & get_instance();
        echo "<pre>";
        print_r($CI->db->last_query());
        print_r($arr);
        echo "</pre>";
        die;
    }

    function createLable($string)
    {
        $ret = '';
        $cnt = 0;
        foreach (explode(' ', $string) as $word){
            if($cnt <= 1 && !empty($word))
            {
                $ret .= strtoupper($word[0]);
            }
            $cnt++;
        }
        return $ret;
    }

    function clean($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

       return preg_replace('/-+/', '-', $string);
    }


    function encrypt_script($string) {
        $CI = & get_instance();
        $key = $CI->config->item('encryption_key');

        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));

        return $encrypted;
    }

    function decrypt_script($string) {
        $CI = & get_instance();
        $key = $CI->config->item('encryption_key');

        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

        return $decrypted;
    }

    function per_page_array()
    {
        $per_page = array(
            "10"  => "10",
            "25"  => "25",
            "50"  => "50",
            "100" => "100",
            "250" => "250",
            "500" => "500",
        );
        return $per_page;
    }

    function check_email($table_name = '',$email = '', $id = '' , $field_name = '')
    {

        $CI = & get_instance();
        $email = strtolower($email);
        
        if ($id == 0) {
            
            $regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
            if (preg_match($regex, $email)) {

                $user_type = '1';
                $query_data = array (
                    "table" => $table_name,
                    "condition" => array($field_name => $email),
                );
                $exist_email = $CI->general_model->get_query_data($query_data);
                
                if (!empty($exist_email)) {
                    $response = '1';
                } else {
                    $response = '0';
                }
            } else {
                $response = '2';
            }
        } else {
            
            $match = array('id' => $id);
            $query_data = array (
                "table" => $table_name,
                "condition" => $match,
            );
            $exist_id = $CI->general_model->get_query_data($query_data);
            $email_old = $exist_id[0]['email'];
            $regex = '/^([a-zA-Z\d_\.\-\+%])+\@(([a-zA-Z\d\-])+\.)+([a-zA-Z\d]{2,4})+$/';
            if (preg_match($regex, $email)) {
                if ($email == $email_old) {
                    $response = "0";
                } else {
                    $query_data = array
                        (
                        "table" => $table_name,
                        "condition" => array('email' => $email),
                    );
                    
                    $email_exist = $CI->general_model->get_query_data($query_data);
                    if (!empty($email_exist)) {
                        $response = '1';
                    } else {
                        $response = '0';
                    }
                }
            } else {
                $response = '2';
            }
        }
        return $response;
    }

    function check_field_value($table_name = '',$field_name = '',$field_value = '', $id = '') {
        $CI = & get_instance();
        /*echo $table_name.'<br>';
        echo $field_name.'<br>';
        echo $field_value.'<br>';die;*/
        if (!empty($field_value))
        {
            if (!empty($id) && !empty($field_value))
            {
                $where_str = ' id != ' . $id . ' AND '.$field_name.' = "' . $field_value . '"';
            }
            else if (!empty($field_value))
            {
                $where_str = $field_name.' = "' . $field_value . '"';
            }
          
            $sq_data_all = array(
                "table" => $table_name,
                "wherestring" =>$where_str
            );

            $check_value_exists = $CI->general_model->get_query_data($sq_data_all);

            if (!empty($check_value_exists)) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function generateRandomString($length,$type='')
    {
        if($type == 1)
        {
          $characters = '0123456789';
        }
        elseif ($type == 2)
        {
          $characters = 'abcdefghijklmnopqrstuvwxyz';
        }
        elseif ($type == 3)
        {
          $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else
        {
          $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }


        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function post_curl($url,$param="")
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        if($param!="")
        curl_setopt($ch,CURLOPT_POSTFIELDS,$param);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }


    function sendSMS1($sms_msg,$sms_mobile)
    {   
        return $handle=fopen(urlencode("http://mobi1.blogdns.com/WebSMSS/SMSSenders.aspx?UserID=fabfunda&UserPass=Fab@2019$&Message=$sms_msg&MobileNo=$sms_mobile&GSMID=FABFUN"),"r");
    }
     function sendSMS($sms_msg,$sms_mobile)
 { 


$route = "route=4";
$postData = array(
'UserID' => 'SheeStarSrt',
'UserPass' => 'Shs@258',
'Message' => $sms_msg,
'MobileNo' => $sms_mobile,
'GSMID' =>'WHOLTX',
'route' => $route
);
/*API URL*/
$url="http://mobi1.blogdns.com/WebSMSS/SMSSenders.aspx";
/* init the resource */
$ch = curl_init();
curl_setopt_array($ch, array(
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_POST => true,
CURLOPT_POSTFIELDS => $postData
/*,CURLOPT_FOLLOWLOCATION => true*/
));
/*Ignore SSL certificate verification*/
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
/*get response*/
$output = curl_exec($ch);
if(curl_errno($ch))
{
echo 'error:' . curl_error($ch);
}
curl_close($ch);
        

 }


    function generate_password(){
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),0,8);
    }

    function check_product_favorite($param) {
        extract($param);
        $CI = & get_instance();
       
        $sq_data_all = array(
            "table"         => 'mp_product_favorite',
            'where'         => array('register_id' => $register_id,'product_id' => $product_id),
        );

        $check_value_exists = $CI->general_model->get_query_data($sq_data_all);

        if (!empty($check_value_exists)) {
            return 1;
        } else {
            return 0;
        }
    }

    //numeric to alpha char convreter for product folder
    function numeric_to_char($str)
    {
        $alpha = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x ', 'y', 'z');
        $newName = '';
        do {
            $str--;
            $limit = floor($str / 26);
            $reminder = $str % 26;
            $newName = $alpha[$reminder].$newName;
            $str=$limit;
        } while ($str >0);

        return $newName;
    }

    function createFolder($profileSelectId,$register_id)
    {
        if($profileSelectId == 1)
        {
            $folderName = str_replace(' ','',numeric_to_char($register_id));
        }
        else
        {
            $folderName = "t-".str_replace(' ','',numeric_to_char($register_id));
        }

        //echo $folderName.' | ';
        $imageStorePath         = $_SERVER['DOCUMENT_ROOT'].'/img/'.$folderName;
        //$imageStorePath      = '//DNG1/textile-infomedia/img/'.$img_folder_name;
        
        if(!file_exists($imageStorePath))
        { 
            echo mkdir($imageStorePath , 0777, true);
        }
        
        return $folderName;
    }

    function fileInclude()
    {
        $CI = & get_instance();
        $path = substr($CI->config->item('base_path'), 0,-4);
        include($path.'/include/database.php');
    }

    function add_product_order($checkCart,$oderId){
        $CI         = & get_instance();
        $checkCart  = json_decode($checkCart,true);
       // print_r($checkCart);die;

        foreach ($checkCart as $key => $value) {
            //print_r($checkCart);die;
            

            $insData['order_id']                = $oderId;
            $insData['product_id']              = $value['product_id'];
            $insData['quantity']                = $value['quantity'];
            $insData['created_date']            = date('Y-m-d H:i:s');

            $ordProdId = $CI->General_model->insert('product_order_trans', $insData);
        } 
    }


    function globalVars()
    {
        return $globalVars         = [
                                        'define_product_qty_activation',
                                        'define_product_stockstatus_activation',
                                        'define_skucode_activation',
                                        'define_moq_activation',
                                        'define_product_score_activation',
                                        'define_reseller_module_activation',
                                        'define_reseller_discount_activation',
                                        'define_reseller_discount_by_user_seperate_activation',
                                        'dis_shipping_charge_apply',
                                        'define_shipping_type_free_paid_activation',
                                        'define_navigation_shipping_mng_module',
                                        'dis_cod_charge_apply',
                                        'dis_cod_charge_fixed_rate_apply',
                                        'dis_cod_charge_fixed_rate_amount',
                                        'define_navigation_shipping_cod_per_state_option_activation',
                                        'dis_cod_charge_fixed_rate_apply',
                                        'dis_cod_charge_partialy_advanced_payment_apply',
                                        'dis_cod_charge_partialy_advanced_payment_in_percentage',
                                        'dis_gst_charge_apply',
                                        'dis_gst_charge_by_default_fixed',
                                        'dis_gst_charge_by_sgst',
                                        'dis_gst_charge_by_cgst',
                                        'define_gst_product_hsncode_module',
                                        'define_gst_type_by_stateid_default',
                                        'dis_special_online_payment_discount_in_percentage_in_subtotal',
                                        'dis_special_online_payment_discount_in_product_flatprice_in_subtotal',
                                        //'payment_method_array',
                                        //'payment_gateway_company_array',
                                        'dis_bydefault_payment_method',
                                        'dis_gst_charge_apply',
                                        'dis_gst_charge_by_default_fixed',
                                        'dis_gst_charge_by_cgst',
                                        'dis_gst_charge_by_sgst',
                                        'define_gst_product_hsncode_module',
                                        'dis_gst_charge_by_default_fixed',
                                        'define_gst_type_by_stateid_default',
                                        'define_invoice_no_prefix',
                                        'define_product_out_of_stock_buycart_activation',
                                        'define_advanced_order_product_additional_parameter_shopcart_filter_activation',
                                        'define_pickup_and_delivery_future_day_show_list',
                                    ];
    }
?>
