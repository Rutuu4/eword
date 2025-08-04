<?php



class General_model extends CI_Model

{



    function __construct()

    {

        parent::__construct();

    }



    function insert($table, $data)

    {

        $this->db->insert($table, $data);

        return $this->db->insert_id();

    }



    function insert_batch_common($data,$table_name)

    {

        $result =  $this->db->insert_batch($table_name,$data);

        $lastId = mysql_insert_id();

        return $lastId;

    }



    function update($table, $data, $where='', $wherestring='')
    {
        if(!empty($where))
            $this->db->where($where);
        if(!empty($wherestring))
            $this->db->where($wherestring, NULL, FALSE);
        $this->db->update($table, $data);
    }



    function update_batch_record($data,$table_name,$update_id)

    {

       $query = $this->db->update_batch($table_name,$data,$update_id);      

    }



    function delete($table, $where)

    {



        $this->db->where($where);

        $this->db->delete($table);

    }



    function delete_trans_table($id, $field_name ,$table_name) 

    { 

        $this->db->where($field_name, $id); 

        $query = $this->db->delete($table_name); 

        return $query;

    }

    

    function get_query_data($query_data)

    {

        extract($query_data);

        if (!empty($fields))

        {

            foreach ($fields as $coll => $value)

            {

                $this->db->select($value, FALSE);

            }

        }



        $this->db->from($table, NULL, FALSE);

        

        if (!empty($join_tables))

        {

            foreach ($join_tables as $coll => $value) {

                $colldata = explode('jointype', $coll);

                 

                $coll = trim($colldata[0]);

                if (!empty($colldata[1])) {

                    $join_type1 = trim($colldata[1]);

                    if ($join_type1 == 'direct')

                        $join_type1 = '';

                }

                

                if (isset($join_type1))

                    $this->db->join($coll, $value, $join_type1);

                else

                    $this->db->join($coll, $value, $join_type);



                unset($join_type1);

            }

        }



        if (!empty($condition) && $condition != null)

        {

            $this->db->where($condition, FALSE);

        }

            

        if (!empty($wherestring) && $wherestring != '')

        {

            $this->db->where($wherestring, NULL, FALSE);

        }

            

        if (!empty($where_in))

        {

            foreach ($where_in as $key => $value)

            {

                $this->db->where_in($key, $value);

            }

        }



        if (!empty($or_where))

        {

            foreach ($or_where as $key => $value)

            {

                $this->db->or_where($key, $value);

            }

        }



        if (!empty($group_by) && $group_by != null)

        {

            $this->db->group_by($group_by);

        }

            

        if (!empty($having) && $having != null)

        {

            $this->db->having($having, NULL, FALSE);

        }

            

        if (!empty($having_str) && $having_str != null)

        {

            $this->db->having($having_str, NULL, FALSE);

        }

            

        if(!empty($orderby))

        {

            if ($orderby != null && $sort != null && !empty($sort))

            {

                $this->db->order_by($orderby, $sort);

            }                

            else if ($orderby != null)

            {

                if ($orderby == 'special_case')

                    $this->db->order_by('');

                elseif ($orderby == 'special_case_2')

                    $this->db->order_by('');

                else

                    $this->db->order_by($orderby);

            }

        }

        

        if (!empty($where) && !empty($match_values) && $match_values != null && !empty($compare_type) && $compare_type != null)

        {

            $wherestr = '';



            foreach ($where as $key => $val)

            {

                $wherestr .= $key . " = '" . $val . "' AND ";

            }



            $wherestr .= '(';



            foreach ($match_values as $key => $val)

            {

                $wherestr .= $key . " " . $compare_type . " '%" . $val . "%' OR ";

            }



            $wherestr = rtrim($wherestr, 'OR ');



            $wherestr .= ')';



            $this->db->where($wherestr, NULL, FALSE);

        }

        else

        {

            if (!empty($where))

                $this->db->where($where, NULL, FALSE);



            if (!empty($match_values) && $match_values != null && !empty($compare_type) && $compare_type != null)

                $this->db->or_like($match_values, $compare_type);

        }



        if (!empty($offset) && $offset != null && !empty($num) && $num != null)

            $this->db->limit($num, $offset);

        elseif (!empty($num) && $num != null)

            $this->db->limit($num);



        $query_FC = $this->db->get();

        if (!empty($totalrow)){

            return $query_FC->num_rows();

        }

        else{

            if(empty($is_result_array))

            {

                return $query_FC->result_array();

            }

            else

            {

                return $query_FC->result();

            }

        }

    }



    function follow_up_count($center)

    {

        $query = $this->db->query("SELECT COUNT(*)  as follow_up_count FROM

                (

                  SELECT inquiryfollowup_id, COUNT(*)

                  FROM customer_inquiry_followup_record

                  WHERE center = $center AND STATUS = 1 AND DATE_FORMAT(ipfollowupdatee,'%Y-%m-%d') = '".date('Y-m-d')."'

                  GROUP BY inquiryfollowup_id

                ) d1");

        return $query->result_array();

    }

    

    function getuserpagingid($user_id='',$table_name='')

    {

        $this->db->select('*');

        $this->db->from($table_name);

        $this->db->order_by('id','desc');

        $result = $this->db->get()->result_array();

        $op = 0;

        if(count($result) > 0)

        {

            foreach($result as $key=>$row)

            {

                if($row['id'] == $user_id)

                {

                    $op = $key;

                    $op1 = strlen($op);

                    $op = substr($op,0,$op1-1)*10;

                }

            }

        }

        return $op;       

    }



    function send_email($to = '', $subject = '', $message = '', $from = '', $cc = '', $bcc = '', $data = '') 

    {

        $this->load->library('email');

        $config = Array(

            'protocol' => $this->config->item('protocol'),

            'smtp_host' => $this->config->item('smtp_host'),

            'smtp_port' => 465,

            'smtp_user' => $this->config->item('smtp_user'),

            'smtp_timeout' => '30',

            'smtp_pass' => $this->config->item('smtp_pass'),

            'mailtype' => 'html',

        );

      

        $this->email->initialize($config);

        $this->email->set_newline("\r\n");

        $this->email->set_priority(1);

        $this->email->subject($subject);

        $this->email->message($message);

        $this->email->from($from, $this->config->item('sitename'));

        $this->email->to($to);

        $this->email->cc($cc);

        $this->email->bcc($bcc);

        if (!empty($data['attachment_email'])) {

            foreach ($data['attachment_email'] as $row_attachment)

                $this->email->attach("uploads/attachment_file/" . $row_attachment['attachment']);

        }

        $send_response = $this->email->send();

        //echo $this->email->print_debugger();exit;

        $this->email->clear(TRUE);

        return $send_response;

    }



    function _create_thumbnail($fileName,$width,$height) 

    {

        $this->load->library('image_lib');

        $config['image_library'] = 'gd2';

        $config['source_image'] = $this->config->item('upload_path').$fileName;

        $config['maintain_ratio'] = TRUE;

        $config['width'] = $width;

        $config['height'] = $height;

        $config['new_image'] = $this->config->item('thumb_path').$fileName;               

        $this->image_lib->initialize($config);

        if(!$this->image_lib->resize())

        { 

            echo $this->image_lib->display_errors();

        }        

    }



    function increase_field_value($fields,$table_name,$where) 

    {

        $this->db->set($fields, $fields.'+1', FALSE);

        $this->db->where($where, NULL, FALSE);

        $this->db->update($table_name);

    }



    function decrease_field_value($fields,$table_name,$where) 

    {

        $this->db->set($fields, $fields.'-1', FALSE);

        $this->db->where($where, NULL, FALSE);

        $this->db->update($table_name);

    }
     function main_image_compress($source_image) 
    {

        $this->load->library('image_lib');

        $config['image_library'] = 'gd2';

        $config['source_image'] = $source_image;

        $config['maintain_ratio'] = TRUE;

        $config['width'] = 700;

        $config['height'] = 700;            

        $this->image_lib->initialize($config);

        if(!$this->image_lib->resize())
        { 

            echo $this->image_lib->display_errors();

        }        

    }

}