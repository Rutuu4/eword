<?php

class Product_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table_name = 'user_p';
    }

    function product_list($params)
    {
        extract($params);

        $query = $this->db->query("
                SELECT 
                    mp_product_ff.*, register.company as reg_company, register.alias as reg_alias, register.areaa_googleamp as reg_area, register.package_id,  m_city.name as city_name, m_state.name as state_name,services_master_tag.name as supplier_tag,services_master_stamp.name as supplier_stamp,  GROUP_CONCAT( concat(mpf.filtertype,':', if( mpfd.product_filter_extras='',mpf2.filtername, mpfd.product_filter_extras) ) separator ',') as pfilter_value 
                FROM (
                SELECT mp_product_f.*, SUBSTRING_INDEX( GROUP_CONCAT(distinct concat(mp_product_f.id,'~,',mp_product_f.product_name,'~,',mp_product_f.product_alias,'~,',mp_product_f.images_path,'',mp_product_f.product_images_125,'~,',mp_product_f.sell_price) separator '^,'),'^,', if(count(distinct mp_product_f.id)<4, (1-count(distinct mp_product_f.id)),-3) )as additional_product 
                FROM (
                SELECT mp_product.id, mp_product.register_id, mp_product.product_name, mp_product.product_alias,  mp_product.images_path, mp_product.product_images_125,  mp_product.product_images_250,
                mp_product.mrp_price, mp_product.sell_price, mp_product.product_unit,mp_product.bcategory as category,mp_product.bscategory as sub_category,  (  ((mp_product.product_score*$var_product_score_define)/100) + ((register_t.score2*$var_package_score_define)/100) + 
                 if($var_increment_score_day_define-DATEDIFF(CURDATE(), mp_product.last_update_datee)>0, ( ( ((mp_product.product_score*$var_product_score_define)/100) + ((register_t.score2*$var_package_score_define)/100) )/10 * ($var_increment_score_day_define-DATEDIFF(CURDATE(), mp_product.last_update_datee)) ), 0) )as final_score
                $sql_addquery_select_city 
                FROM mp_product 
                inner join register as register_t on register_t.id=mp_product.register_id 
                $sqla_ddquery_left_city

                where FIND_IN_SET('$id_subcategory',mp_product.bscategory)>0 $sql_addquery_where_filter  and mp_product.status=1 and mp_product.product_images_250!= ''  and mp_product.profile_select_id=1 
                ) AS mp_product_f 
                group by  mp_product_f.register_id  order by $sql_addquery_group_inner_city mp_product_f.final_score desc limit $var_per_page_product_limit_start,$var_per_page_product_limit_final
                ) AS mp_product_ff

                left join mp_product_filter_details as mpfd on mpfd.product_id=mp_product_ff.id 
                left join mp_product_filter as mpf on mpfd.product_filter_id=mpf.idd and mpf.total_filter=1  
                left join mp_product_filter as mpf2 on mpfd.product_filter_idd=mpf2.id 
                    
                left join register on register.id=mp_product_ff.register_id 
                left join m_city on m_city.id=register.city
                left join m_state on m_state.id=m_city.state_id
                left join services_master_tag on services_master_tag.id=register.supplier_tag
                left join services_master_stamp on services_master_stamp.id=register.premium_ads

                group by  mp_product_ff.id order by $sql_addquery_group_outer_city mp_product_ff.final_score desc");
        return $query->result_array();
    }

    function product_list_premium($params){
        extract($params);

        $query = $this->db->query("
                SELECT 
                    mp_product_ff.*, register.company as reg_company, register.alias as reg_alias, register.areaa_googleamp as reg_area, register.package_id,  m_city.name as city_name, m_state.name as state_name,services_master_tag.name as supplier_tag, services_master_stamp.name as supplier_stamp,  GROUP_CONCAT( concat(mpf.filtertype,':', if( mpfd.product_filter_extras='',mpf2.filtername, mpfd.product_filter_extras) ) separator ',') as pfilter_value 
                FROM (
                    SELECT 
                        mp_product_f.*, SUBSTRING_INDEX( GROUP_CONCAT(distinct concat(mp_product_f.id,'~,',mp_product_f.product_name,'~,',mp_product_f.product_alias,'~,',mp_product_f.images_path,'',mp_product_f.product_images_125,'~,',mp_product_f.sell_price) separator '^,'),'^,', if(count(distinct mp_product_f.id)<4, (1-count(distinct mp_product_f.id)),-3) )as additional_product 
                    FROM (
                            SELECT 
                                mp_product.id, mp_product.register_id, mp_product.product_name, mp_product.product_alias,  mp_product.images_path, mp_product.product_images_125,mp_product.product_images_250,mp_product.mrp_price, mp_product.sell_price, mp_product.product_unit,mp_product.bcategory as category,mp_product.bscategory as sub_category, mp_product.product_details,(((mp_product.product_score*$var_product_score_define)/100) + ((register_t.score2*$var_package_score_define)/100) + if($var_increment_score_day_define-DATEDIFF(CURDATE(), mp_product.last_update_datee)>0, ( ( ((mp_product.product_score*$var_product_score_define)/100) + ((register_t.score2*$var_package_score_define)/100) )/10 * ($var_increment_score_day_define-DATEDIFF(CURDATE(), mp_product.last_update_datee)) ), 0) )as final_score  
                            FROM 
                                mp_product 
                            INNER JOIN 
                                register as register_t on register_t.id=mp_product.register_id 
                            WHERE 
                                FIND_IN_SET('$id_subcategory',mp_product.bscategory)>0 $sql_premium_addquery_where_city $sql_premium_addquery_where_filter  and register_t.package_id>0 and mp_product.status=1 and mp_product.product_images_250!= ''  and mp_product.profile_select_id=1  
                            GROUP BY
                                mp_product.id  
                            ORDER BY 
                                rand() 
                        ) AS mp_product_f 
                    GROUP BY  
                        mp_product_f.register_id  
                ) AS mp_product_ff
                LEFT JOIN 
                    mp_product_filter_details as mpfd on mpfd.product_id=mp_product_ff.id 
                LEFT JOIN 
                    mp_product_filter as mpf on mpfd.product_filter_id=mpf.idd and mpf.total_filter=1  
                LEFT JOIN 
                    mp_product_filter as mpf2 on mpfd.product_filter_idd=mpf2.id 
                LEFT JOIN 
                    register on register.id=mp_product_ff.register_id 
                LEFT JOIN 
                    m_city on m_city.id=register.city
                LEFT JOIN 
                    m_state on m_state.id=m_city.state_id
                LEFT JOIN 
                    services_master_tag on services_master_tag.id=register.supplier_tag
                LEFT JOIN 
                    services_master_stamp on services_master_stamp.id=register.premium_ads
                GROUP BY  
                    mp_product_ff.id");
        
        return $query->result_array();
    }

    /*function get_product_details($params)
    {
        extract($params);

        $query = $this->db->query("SELECT mp_product.id,  mp_product.register_id ,mp_product.product_name, mp_product.product_alias, mp_product.product_details, mp_product.images_path,mp_product.product_images_125, mp_product.product_images_500, mp_product.mrp_price, mp_product.sell_price, mp_product.product_unit, mp_product.min_order, mp_product.product_category, mp_product.bscategory,
            register.company as reg_company, register.alias as reg_alias,  register.qimage_t as reg_profile_thumb,
            register.cprofile,  register.establishment_year, register.company_ownership_type, register.number_of_employee, register.annual_turnover , m_subcategory.name as subcategory_name,
            m_city.name as city_name, m_state.name as state_name, group_concat(distinct m_filter.name separator ', ') as bstype, 
            services_master_tag.name as supplier_tag,services_master_stamp.name as supplier_stamp,
            group_concat(joinpfilter.temp_pfilter_value separator '<br/>')as pfilter_value
            from mp_product
            left join (
                SELECT mpfd.id,mpfd.product_id,mpfd.product_filter_id, if( mpfd.product_filter_extras='', CONCAT( mpf.filtertype, ': ' ,GROUP_CONCAT(mpf2.filtername separator ', ')), CONCAT( mpf.filtertype, ': ' ,mpfd.product_filter_extras)  ) as temp_pfilter_value
                FROM mp_product_filter_details as mpfd
                left join mp_product_filter as mpf on mpfd.product_filter_id=mpf.idd and mpf.total_filter=1
                left join mp_product_filter as mpf2 on mpfd.product_filter_idd=mpf2.id
                GROUP by mpfd.product_id, mpfd.product_filter_id  
            ) joinpfilter on joinpfilter.product_id=mp_product.id 
            left join register on register.id=mp_product.register_id 
            left join m_city on m_city.id=register.city
            left join m_state on m_state.id=m_city.state_id 
            left join m_filter on FIND_IN_SET(m_filter.id , register.bstype)>0
            left join services_master_tag on services_master_tag.id=register.supplier_tag
            left join services_master_stamp on services_master_stamp.id=register.premium_ads
            left join m_subcategory on m_subcategory.id=mp_product.bscategory
            where  mp_product.profile_select_id = 1 and mp_product.status = 1 and mp_product.id = $id_product 
            group by mp_product.id, register.id,m_filter.id");
        return $query->result_array();
    }*/

    function get_product_details($params)
    {
        extract($params);

        //mp_product.profile_select_id=1 and
        //and mp_product.status=1
        $query = $this->db->query("SELECT mp_product_f.* , register.company as reg_company, register.alias as reg_alias,  register.qimage_t as reg_profile_thumb,register.cprofile,  register.establishment_year, register.company_ownership_type, register.number_of_employee, register.annual_turnover , m_subcategory.name as subcategory_name,m_city.name as city_name, m_state.name as state_name, group_concat(distinct m_filter.name separator ', ') as bstype, services_master_tag.name as supplier_tag,services_master_stamp.name as supplier_stamp ,mp_product_f.product_category, mp_product_f.bscategory,mp_product_f.bcategory,mp_product_f.product_code,mp_product_f.payment_terms,mp_product_f.supply_ability,mp_product_f.packaging_details,mp_product_f.delivery_time,mp_product_f.dispatch_location
            FROM ( select mp_product.id,  mp_product.register_id ,mp_product.product_name, mp_product.product_alias, mp_product.product_details, mp_product.images_path,mp_product.product_images_125, mp_product.product_images_500, mp_product.mrp_price, mp_product.sell_price, mp_product.product_unit, mp_product.min_order, mp_product.product_category, mp_product.bscategory, mp_product.bcategory,mp_product.product_code,mp_product.payment_terms,mp_product.supply_ability,mp_product.packaging_details,mp_product.delivery_time,mp_product.dispatch_location,mp_product.product_score, GROUP_CONCAT( concat(mpf.filtertype,':', if( mpfd.product_filter_extras='',mpf2.filtername, mpfd.product_filter_extras) ) separator ',') as pfilter_value,
                GROUP_CONCAT(CONCAT_WS('|',mpfd.product_filter_id,product_filter_idd,mpfd.product_filter_extras)) as product_filter_id
            from mp_product 
            left join mp_product_filter_details as mpfd on mpfd.product_id=mp_product.id 
            left join mp_product_filter as mpf on mpfd.product_filter_id=mpf.idd and mpf.total_filter=1  
            left join mp_product_filter as mpf2 on mpfd.product_filter_idd=mpf2.id 
            where  mp_product.id = $id_product 
            group by mp_product.id) AS mp_product_f 
            left join register on register.id=mp_product_f.register_id 
            left join m_city on m_city.id=register.city
            left join m_state on m_state.id=m_city.state_id 
            left join m_filter on FIND_IN_SET(m_filter.id , register.bstype)>0
            left join services_master_tag on services_master_tag.id=register.supplier_tag
            left join services_master_stamp on services_master_stamp.id=register.premium_ads
            left join m_subcategory on m_subcategory.id=mp_product_f.bscategory group by  mp_product_f.id");
        return $query->result_array();
    }

    //additional image load or fetch
    function get_product_images($product_id)
    {
        $query = $this->db->query("SELECT id,mp_product_images.product_images_125, mp_product_images.product_images_500 from mp_product_images where mp_product_images.product_id = $product_id");
        return $query->result_array();
    }

    //related product from mp_product first same register company if not to other supplier same product
    function get_product_additional_product($params)
    {
        extract($params);

        $query = $this->db->query("SELECT mp_product.id, mp_product.product_name, mp_product.product_alias, mp_product.mrp_price, mp_product.sell_price, mp_product.product_unit, mp_product.images_path, mp_product.product_images_250, mp_product.product_images_125,register.id as reg_id, register.company as reg_company, register.alias as reg_alias, m_city.name as city_name, m_state.name as state_name, services_master_tag.name as supplier_tag, services_master_stamp.name as supplier_stamp
            from mp_product 
            left join register on register.id=mp_product.register_id 
            left join m_city on m_city.id=register.city
            left join m_state on m_state.id=m_city.state_id 
            left join services_master_tag on services_master_tag.id=register.supplier_tag
            left join services_master_stamp on services_master_stamp.id=register.premium_ads
            where mp_product.register_id=$regsiter_id and  mp_product.product_category=$product_category  and mp_product.status=1 and mp_product.product_images_250!= '' and mp_product.id!=$product_id group by mp_product.id limit 6");
        return $query->result_array();
    }

    //related product from other supplier same bscategory
    function get_product_related_product($params)
    {
        extract($params);
        $query = $this->db->query("SELECT mp_product.id, mp_product.product_name, mp_product.product_alias, mp_product.mrp_price, mp_product.sell_price, mp_product.product_unit, mp_product.images_path, mp_product.product_images_250, mp_product.product_images_125,register.id as reg_id, register.company as reg_company, register.alias as reg_alias, m_city.name as city_name, m_state.name as state_name, services_master_tag.name as supplier_tag, services_master_stamp.name as supplier_stamp,mp_product.bcategory as category,mp_product.bscategory as sub_category
            from mp_product 
            left join register on register.id=mp_product.register_id 
            left join m_city on m_city.id=register.city
            left join m_state on m_state.id=m_city.state_id 
            left join services_master_tag on services_master_tag.id=register.supplier_tag
            left join services_master_stamp on services_master_stamp.id=register.premium_ads
            where mp_product.register_id=$regsiter_id and  mp_product.product_category=$product_category  and mp_product.status=1 and mp_product.product_images_250!= '' and mp_product.id!=$product_id group by mp_product.id limit 6");
        return $query->result_array();
    }

    function category_specification($params)
    {
        extract($params);
        $query = $this->db->query("
                                SELECT group_concat(id) as filter_id ,filtertype,group_concat(filtername) as filter_name,group_concat(extras)as filter_extras,idd,filter_option 
                                FROM mp_product_filter 
                                WHERE status=1 and subcategory REGEXP '[[:<:]]($sub_category_id)[[:>:]]' 
                                group by idd 
                                order by id,idd"
                            );
        return $query->result_array();
    }

    function get_score_condition()
    {
        $query = $this->db->query("
                                SELECT GROUP_CONCAT(name_condition SEPARATOR '+ ') as qry_score_condition, score from mp_product_score where status = 1"
                            );
        return $query->result_array();
    }

    function get_product_score($score_get_qry,$productId)
    {
        $query = $this->db->query("
                                SELECT ($score_get_qry) as score from mp_product where id = $productId"
                            );
        return $query->result_array();
    }


    function similar_product_list_other_seller($param)
    {
        extract($param);

        $query = $this->db->query("
                SELECT mp_product.id, mp_product.product_name, mp_product.product_alias, mp_product.mrp_price, mp_product.sell_price, mp_product.product_unit, mp_product.images_path, mp_product.product_images_250, mp_product.product_images_125,register.id as reg_id, register.company as reg_company, register.alias as reg_alias, m_city.name as city_name, m_state.name as state_name,services_master_tag.name as supplier_tag, services_master_stamp.name as supplier_stamp
                    from mp_product 
                    left join register              on register.id              =   mp_product.register_id 
                    left join m_city                on m_city.id                =   register.city
                    left join m_state               on m_state.id               =   m_city.state_id 
                    left join services_master_tag   on services_master_tag.id   = register.supplier_tag
                    left join services_master_stamp on services_master_stamp.id = register.premium_ads
                    where 
                        mp_product.bscategory REGEXP '[[:<:]]($subSategoryIds)[[:>:]]' and 
                        mp_product.status               = 1 and 
                        mp_product.profile_select_id    = 1 and 
                        mp_product.product_images_250   != '' and 
                        mp_product.id                   != $productID
                    group by mp_product.id 
                    order by rand() limit 5
                ");
        return $query->result_array();
    }

    function get_product_favourite_product($params)
    {
        extract($params);
        $query = $this->db->query("
                SELECT mp_product.id, mp_product.product_name, mp_product.product_alias, mp_product.images_path, mp_product.product_images_500
                from mp_product_favorite as mpf
                left join mp_product on mpf.product_id = mp_product.id 
                where 
                    mp_product.status = 1 and 
                    mpf.register_id = ".$company_id."
                order by mpf.id DESC"
            );
        return $query->result_array();
    }
}