<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home_control extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->modName = 'front';
        $this->table_name = 'candidates';

        $this->page_title = $this->lang->line('candidate_management');
    }

    public function index()
    {
        $data['main_content'] = $this->modName.'/home/index';
        $this->load->view($this->modName.'/include/template', $data);
    }

    public function check_email()
    {
        $email = $this->input->post('email');
        echo check_email($this->table_name,$email,'','email');
    }

    public function dashboard()
    {
        $data['main_content'] = $this->modName."/home/index";
        $this->load->view($this->modName.'/include/template',$data);
    }

    public function login()
    {
        $candidate_session = $this->session->userdata($this->config->item('siteslug').'_candidate_session');

        $email = $this->input->post('login_email');
        $password = $this->input->post('login_password');

        if(!empty($candidate_session))
        {
            if(!empty($_REQUEST['redirect']))
                redirect($_REQUEST['redirect']);
            else
                redirect('dashboard');
        }
        else
        {
            $data['main_content'] = $this->modName."/home/login";
            $this->load->view($this->modName.'/include/template',$data);
        }
    }

    public function candidate_signup()
    {
        $is_email = check_email($this->table_name,trim(strtolower($this->input->post('email'))),'','email');
       
        if($is_email == '0')
        {
            $data = array (
                "first_name" => trim($this->input->post('first_name')),
                "last_name" => trim($this->input->post('last_name')),
                "email" => trim(strtolower($this->input->post('email'))),
                "phone_no" => $this->input->post('phone_no'),
                "password" => encrypt_script($this->input->post('password')),
                "verification_id" => generateRandomString(8),
                "created_date" => date('Y-m-d H:i:s'),
                "status" => 1,
            );
            
            $inserted_data = $this->general_model->insert($this->table_name, $data);

            if (!empty($inserted_data))
            {
                $this->send_email($inserted_data,'1');

                $noti['user_type'] = 1;
                $noti['noti_type'] = 3;
                $noti['data']['master_id'] = $inserted_data;
                $noti['data']['name'] = $data['first_name'].' '.$data['last_name'].' ('.$data['email'].')';
                notification_insert($noti);

                $response = array(
                    "status" => $this->lang->line('success'),
                    "message" => $this->lang->line('successfully_registered')
                ); 
            }
            else
            {
                $response = array(
                    "status" => $this->lang->line('danger'),
                    "message" => $this->lang->line('common_add_error')
                );
            }
        }
        else
        {
            $response = array(
                "status" => $this->lang->line('danger'),
                "message" => $this->lang->line('email_already_existing')
            );
        }

        $this->session->set_flashdata('message', $response);
        echo json_encode($response);
    }

    public function candidate_signin($login_email='',$login_password='')
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
       
        if(!empty($email) && !empty($password))
        {
            $match  = array('email'=>$email);
            $query_data = array (
                "table" => $this->table_name,
                "condition" => $match,
            );
            $isEmailExist = $this->general_model->get_query_data($query_data);
           
            if(!empty($isEmailExist))
            {
                $password = encrypt_script($password);
                $match  = array('email'=>$email,'password'=>$password );
                $query_data = array (
                    "table" => $this->table_name,
                    "condition" => $match,
                );
                $emialPwdCorr = $this->general_model->get_query_data($query_data);

                if(!empty($emialPwdCorr))
                {
                    if($emialPwdCorr[0]['verification_status'] == 1 && $emialPwdCorr[0]['status'] == 1)
                    {
                        $newdata = array(
                            'name'      => $emialPwdCorr[0]['first_name'].' '.$emialPwdCorr[0]['last_name'],
                            'id'        => $emialPwdCorr[0]['id'],
                            'useremail' => $emialPwdCorr[0]['email'],
                            'active'    => TRUE);
                        
                        $this->session->set_userdata($this->config->item('siteslug').'_candidate_session', $newdata);

                        $response = array(
                            "status" => $this->lang->line('success'),
                            "message" => $this->lang->line('loggedin')
                        );
                    }
                    elseif($emialPwdCorr[0]['status'] == 0)
                    {
                        $response = array(
                            "status" => $this->lang->line('warning'),
                            "message" => $this->lang->line('inactive_account')
                        );
                    }
                    else
                    {
                        $this->send_email($emialPwdCorr[0]['id'],'1');
                        $response = array(
                            "status" => $this->lang->line('warning'),
                            "message" => $this->lang->line('unable_login')
                        );
                        
                    }
                }
                else
                {
                    if(!empty($isEmailExist[0]['linkedin_id']) && $isEmailExist[0]['verification_status'] == 1)
                    {
                        $response = array(
                            "status" => $this->lang->line('warning'),
                            "message" => $this->lang->line('login_with_linkedin')
                        );
                    }
                    else
                    {
                        $response = array(
                            "status" => $this->lang->line('danger'),
                            "message" => $this->lang->line('invalid_credentails')
                        );
                    }
                }

            }
            else
            {
                $response = array(
                    "status" => $this->lang->line('danger'),
                    "message" => $this->lang->line('email_not_registered')
                );
            }
        }

        $this->session->set_flashdata('message', $response);
        echo json_encode($response);
    }

    public function send_email($id = '',$type = '')
    {
        $match  = array('id'=>$id);
        $query_data = array (
            "table" => $this->table_name,
            "condition" => $match,
        );
        $result = $this->general_model->get_query_data($query_data);
       
        if(!empty($result))
        {
            $password  = decrypt_script($result[0]['password']);
            $signInLink = $this->config->item('candidate_base_url');
            
            $to         = $result[0]['email'];
           
            $from       = $this->config->item('admin_email');
            $full_name  = $this->config->item('sitename');
            $from       = $full_name . '<' . $from . '>';
            $name = $result[0]['first_name']." ".$result[0]['last_name'];
            if($type == '1')
            {
                $content = $this->load->view($this->modName.'/email_templates/candidate_verify_mail.html','',true);
                $subject    = $this->config->item('sitename')." : Verification";
                $get_name = array('/{logo_img}/','/{site_name}/','/{site_link}/','/{spacer_img}/','/{devMobileNo}/','/{devEmail}/','/{name}/','/{verify_link}/');
                
                $verify_link = $this->config->item('candidate_base_url') . 'candidate-verification/'.$result[0]['verification_id'];
                
                $set_name = array($this->logoImg,$this->siteName,$this->siteLink,$this->spacer_img,$this->devMobileNo,$this->devEmail,$name,$verify_link);
            }
            elseif($type == '2')
            {
                $content = $this->load->view($this->modName.'/email_templates/candidate_credentail_mail.html','',true);
                $subject    = $this->config->item('sitename')." : Login credentials";
                
                $get_name = array('/{logo_img}/','/{site_name}/','/{site_link}/','/{spacer_img}/','/{devMobileNo}/','/{devEmail}/','/{signin_img}/','/{signInLink}/','/{name}/','/{email}/','/{password}/');
                
                $password = decrypt_script($result[0]['password']);
                $set_name = array($this->logoImg,$this->siteName,$this->siteLink,$this->spacer_img,$this->devMobileNo,$this->devEmail,$this->signin_img,$signInLink,$name,trim($result[0]['email']),$password);
            }   
            
            $email_template = preg_replace($get_name, $set_name, $content);
            
            $sendmail = $this->general_model->send_email($to, $subject, $email_template, $from);
            if($sendmail)
            {
                if($type == 1)
                {
                    $response = array(
                        "status" => $this->lang->line('success'),
                        "message" => $this->lang->line('verify_email_address')
                    );
                }
            }
            else
            {
                $response = array(
                    "status" => $this->lang->line('danger'),
                    "message" => $this->lang->line('unable_send_mail')
                );
            }
        }
        else
        {
            $response = array(
                "status" => $this->lang->line('danger'),
                "message" => $this->lang->line('unable_send_mail')
            );
        }
        return $response;
    }

    public function candidate_verification()
    {
        $verification_id   = $this->router->uri->segments[2];

        if(!empty($verification_id))
        {
            $match  = array('verification_id'=>$verification_id);
            $query_data = array (
                "table" => $this->table_name,
                "condition" => $match,
            );
            $result = $this->general_model->get_query_data($query_data);

            if(!empty($result))
            {
                if($result[0]['verification_status'] == 0)
                {
                    $newdata = array(
                        'name'      => $result[0]['first_name'].' '.$result[0]['last_name'],
                        'id'        => $result[0]['id'],
                        'useremail' => $result[0]['email'],
                        'active'    => TRUE);
                    
                    $this->session->set_userdata($this->config->item('siteslug').'_candidate_session', $newdata);

                    $cdata['verification_id'] = '';
                    $cdata['verification_status'] = '1';
                    $this->general_model->update($this->table_name, $cdata, array('id' => $result[0]['id'])); 

                    // $this->send_email($result[0]['id'],'2');

                    $response = array(
                        "status" => $this->lang->line('success'),
                        "message" => $this->lang->line('successfully_verified')
                    );

                    $this->session->set_flashdata('message', $response);
                    redirect('dashboard');
                }
                else
                {
                    $response = array(
                        "status" => $this->lang->line('danger'),
                        "message" => $this->lang->line('verification_link_expired')
                    );
                }
            }
            else
            {
                $response = array(
                    "status" => $this->lang->line('danger'),
                    "message" => $this->lang->line('incorrect_verification_code')
                );
            }
        }
        else
        {
            $response = array(
                "status" => $this->lang->line('danger'),
                "message" => $this->lang->line('incorrect_verification_code')
            );
        }

        $this->session->set_flashdata('message', $response);
        redirect('dashboard');  
    }

    public function linkedin_connection()
    {
        if(isset($_GET['code'])) // get code after authorization
        {
            $url = 'https://www.linkedin.com/oauth/v2/accessToken'; 
            $param = 'grant_type=authorization_code&code='.$_GET['code'].'&redirect_uri='.$this->config->item('linkedin_candidate_callback_url').'&client_id='.$this->config->item('linkedin_client_id').'&client_secret='.$this->config->item('linkedin_client_secret');
            $return = (json_decode(post_curl($url,$param),true)); // Request for access token
            if(!empty($return['error'])) // if invalid output error
            {
               $response = array(
                    "status" => $this->lang->line('danger'),
                    "message" => $this->lang->line('common_error')
                );
            }
            else // token received successfully
            {
                $url = 'https://api.linkedin.com/v1/people/~:(id,firstName,lastName,pictureUrls::(original),headline,publicProfileUrl,location,industry,positions,email-address)?format=json&oauth2_access_token='.$return['access_token'];
                $User = json_decode(post_curl($url),true); // Request user information on received token
                
                if(!empty($User))
                {
                    $is_email = check_email($this->table_name,trim(strtolower($User['emailAddress'])),'','email');
           
                    if($is_email == '0')
                    {
                        $data = array (
                            "first_name" => trim($User['firstName']),
                            "last_name" => trim($User['lastName']),
                            "email" => trim(strtolower($User['emailAddress'])),
                            "phone_no" => "",
                            "password" => "",
                            "linkedin_id" => trim($User['id']),
                            "verification_id" => "",
                            "verification_status" => 1,
                            "created_date" => date('Y-m-d H:i:s'),
                            "status" => 1,
                        );
                        
                        $inserted_data = $this->general_model->insert($this->table_name, $data);

                        if (!empty($inserted_data))
                        {
                            $response = $this->send_email($inserted_data,'1');

                            $noti['user_type'] = 1;
                            $noti['noti_type'] = 3;
                            $noti['data']['master_id'] = $inserted_data;
                            $noti['data']['name'] = $data['first_name'].' '.$data['last_name'].' ('.$data['email'].')';
                            notification_insert($noti);
                
                            $newdata = array(
                                'name'      => $User['firstName']." ".$User['lastName'],
                                'id'        => $inserted_data,
                                'useremail' => $User['emailAddress'],
                                'active'    => TRUE);
                            
                            $this->session->set_userdata($this->config->item('siteslug').'_candidate_session', $newdata);

                            $response = array(
                                "status" => $this->lang->line('success'),
                                "message" => $this->lang->line('loggedin')
                            );
                        }
                        else
                        {
                            $response = array(
                                "status" => $this->lang->line('danger'),
                                "message" => $this->lang->line('common_error')
                            );
                        }
                    }
                    else
                    {
                        $match  = array('email'=>$User['emailAddress']);
                        $query_data = array (
                            "table" => $this->table_name,
                            "condition" => $match,
                        );
                        $exist_email = $this->general_model->get_query_data($query_data);
                        if(!empty($exist_email) && $exist_email[0]['status'] == 1)
                        {
                            $this->general_model->update($this->table_name, array('linkedin_id' => $User['id'],'verification_status' => 1), array('id' => $exist_email[0]['id'])); 

                            $newdata = array(
                                'name'      => $exist_email[0]['first_name']." ".$exist_email[0]['last_name'],
                                'id'        => $exist_email[0]['id'],
                                'useremail' => $exist_email[0]['email'],
                                'active'    => TRUE);
                            
                            $this->session->set_userdata($this->config->item('siteslug').'_candidate_session', $newdata);

                            $response = array(
                                "status" => $this->lang->line('success'),
                                "message" => $this->lang->line('loggedin')
                            );
                        }
                        elseif($exist_email[0]['status'] == 0)
                        {
                            $response = array(
                                "status" => $this->lang->line('warning'),
                                "message" => $this->lang->line('inactive_account')
                            );
                        }
                        else
                        {
                            $response = array(
                                "status" => $this->lang->line('warning'),
                                "message" => $this->lang->line('common_error')
                            );   
                        }
                    }
                }
                else
                {
                    $response = array(
                        "status" => $this->lang->line('danger'),
                        "message" => $this->lang->line('common_error')
                    );
                }
            }
        }
        else
        {
            $response = array(
                "status" => $this->lang->line('danger'),
                "message" => $this->lang->line('common_error')
            );
        }

        $this->session->set_flashdata('message', $response);
        $redirect_session = $this->session->userdata($this->config->item('siteslug').'_redirect_session');
        if(!empty($redirect_session['redirect']))
        {
            $red_link = urldecode($redirect_session['redirect']);
            if(!empty($redirect_session['query']))
            {
                $red_link .= '?';
                $red_link .= urldecode($redirect_session['query']);
            }
            $this->session->unset_userdata($this->config->item('siteslug').'_redirect_session');
            redirect($red_link);
        }
        else
            redirect('dashboard');
    }

    public function redirect()
    {
        if(!empty($this->input->post('redirect_uri')))
        {
            $newdata = array(
                'redirect'      => urlencode($this->input->post('redirect_uri')),
                'query'         => urlencode($this->input->post('query_string')),
            );
            $this->session->set_userdata($this->config->item('siteslug').'_redirect_session',$newdata);
            return true;
        }
        else
        {
            return false;
        }
    }

    public function logout()
    {   
        $candidate_session = $this->session->userdata($this->config->item('siteslug').'_candidate_session');
        if($candidate_session['active']==TRUE)
        {
            $this->session->unset_userdata($this->config->item('siteslug').'_candidate_session');
            $this->session->sess_destroy();
            redirect('dashboard');
        }
        else
            redirect('dashboard');
    }
}