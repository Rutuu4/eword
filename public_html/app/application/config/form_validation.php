<?php 
$config = array(
        'user_register' => array(
                array(
                        'field' => 'first_name',
                        'label' => 'first_name',
                        'rules' => 'trim|required'
                ),array(
                        'field' => 'email',
                        'label' => 'email',
                        'rules' => 'trim|required'
                ),
                array(
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'trim|required'
                ),
                array(
                        'field' => 'device_type',
                        'label' => 'device type',
                        'rules' => 'trim|required'
                )
        ),
        'user_login_with_otp' => array(
                array(
                        'field' => 'username',
                        'label' => 'username',
                        'rules' => 'trim|required'
                )
        ),
        'user_details' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required'
                )
        ),'sub_category_product_list' => array(
                array(
                        'field' => 'sub_category_id',
                        'label' => 'sub_category_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'page_no',
                        'label' => 'page_no',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'type',
                        'label' => 'type',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'group_product_list' => array(
                array(
                        'field' => 'group_id',
                        'label' => 'group_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'product_details' => array(
                array(
                        'field' => 'product_id',
                        'label' => 'product_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'product_image' => array(
                array(
                        'field' => 'image_id',
                        'label' => 'image_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'product_favourite' => array(
                array(
                        'field' => 'product_id',
                        'label' => 'product_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'add_product_favourite_group' => array(
                array(
                        'field' => 'group_id',
                        'label' => 'group_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'add_to_cart' => array(
                array(
                        'field' => 'product_id',
                        'label' => 'product_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'quantity',
                        'label' => 'quantity',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'remove_cart' => array(
                array(
                        'field' => 'cart_id',
                        'label' => 'cart_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'cart_list' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'favourite_product_list' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'page_no',
                        'label' => 'page_no',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'shipping_charge' => array(
                array(
                        'field' => 'shipping_id',
                        'label' => 'shipping_id',
                        'rules' => 'trim|required'
                ),array(
                        'field' => 'total_weight',
                        'label' => 'total_weight',
                        'rules' => 'trim|required'
                ),array(
                        'field' => 'total_shipping_weight',
                        'label' => 'total_shipping_weight',
                        'rules' => 'trim|required'
                )
        ),'process_to_checkout' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required'
                )
        ),'order_list' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'page_no',
                        'label' => 'page_no',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'payment_sucess' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'order_id',
                        'label' => 'order_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'firstname',
                        'label' => 'firstname',
                        'rules' => 'trim|required'
                )
        ),'edit_profile' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'forgot_password' => array(
                array(
                        'field' => 'username',
                        'label' => 'username',
                        'rules' => 'trim|required'
                )
        ),
        'agent_user_register' => array(
                array(
                        'field' => 'name',
                        'label' => 'name',
                        'rules' => 'trim|required'
                ),array(
                        'field' => 'company_name',
                        'label' => 'company_name',
                        'rules' => 'trim|required'
                ),
                
                array(
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'trim|required'
                ),
                array(
                        'field' => 'device_type',
                        'label' => 'device type',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),
                array(
                        'field' => 'device_token',
                        'label' => 'device token',
                        'rules' => 'trim|required'
                )
        ),
        'add_aget_user' => array(
                array(
                        'field' => 'agent_id',
                        'label' => 'agent_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'name',
                        'label' => 'name',
                        'rules' => 'trim|required'
                ),
                array(
                        'field' => 'password',
                        'label' => 'password',
                        'rules' => 'trim|required'
                )
        ),
        'change_agent_user_password' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),
                array(
                        'field' => 'password',
                        'label' => 'password',
                        'rules' => 'trim|required'
                )
        ),'agent_user_list' => array(
                array(
                        'field' => 'agent_id',
                        'label' => 'agent_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                )
        ),'aget_user_login' => array(
                array(
                        'field' => 'app_type',
                        'label' => 'app_type',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'username',
                        'label' => 'username',
                        'rules' => 'trim|required'
                ),array(
                        'field' => 'password',
                        'label' => 'password',
                        'rules' => 'trim|required'
                ),array(
                        'field' => 'device_type',
                        'label' => 'device_type',
                        'rules' => 'trim|required'
                ),array(
                        'field' => 'device_token',
                        'label' => 'device_token',
                        'rules' => 'trim|required'
                )
        ),'razorpay_create_order' => array(
                array(
                        'field' => 'user_id',
                        'label' => 'user_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'order_id',
                        'label' => 'order_id',
                        'rules' => 'trim|required|is_natural_no_zero'
                ),array(
                        'field' => 'amount',
                        'label' => 'amount',
                        'rules' => 'trim|required'
                ),array(
                        'field' => 'invoice',
                        'label' => 'invoice',
                        'rules' => 'trim|required'
                )
        ),
);
?>