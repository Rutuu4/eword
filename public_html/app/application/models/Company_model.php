<?php

class Company_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table_name = 'user_p';
    }

    function get_company_info_list($params)
    {
        extract($params);

        $query = $this->db->query("SELECT register.id, register.company, register.alias, register.cperson, register.cdesignation, register.cmobile, register.cperson2, register.cmobile2,  register.cdesignation_2, register.phoneno, register.faxno, register.email, register.email_secondary, register.website, register.address, register.pincode, register.areaa_googleamp, register.key_description_profile, register.cprofile, register.establishment_year, register.bstype2, register.company_ownership_type, register.number_of_employee, register.annual_turnover, register.qimage, register.qimage_t, register.bdeal1, register.bdeal2, register.bdeal3, register.bdeal4, register.bdeal5, register.tax_gstno, register.tax_dgftno, register.view_on_google_map_lng,    register.view_on_google_map_lat, register.userrating, register.profileimage_thumb, register.bscategory, register.bcategory, register.package_id,  group_concat(distinct m_subcategory.name) as subcategory_name ,group_concat(distinct m_category.name) as category_name_comma_value,  services_master_stamp.name as services_stamp, m_city.name as city_name, m_state.name as state_name, m_country.name as country_name, m_country.countrycode  as country_code, m_marketlist.market as market_name, group_concat(distinct m_filter.name separator ', ') as bstype,mp_social.facebook_url,mp_social.twitter_url, mp_social.googleplus_url,mp_social.instagram_url,mp_social.youtube_url,mp_social.linkedin_url,mp_social.pintrest_url,mp_social.wordpress_url,mp_social.blogspot_url
            from register 
            left join m_city on m_city.id=register.city
            left join m_state on m_state.id=m_city.state_id
            left join m_country on m_country.id=register.country_id
            left join m_marketlist on m_marketlist.id=register.market
            left join mp_social_media_page_link as mp_social on mp_social.register_id=register.id and mp_social.profile_select_id=1
            left join services_master_stamp on services_master_stamp.id=register.premium_ads
            left join m_subcategory on FIND_IN_SET(m_subcategory.id , register.bscategory)>0
            left join m_category on FIND_IN_SET(m_category.id , register.bcategory)>0
            left join m_filter on FIND_IN_SET(m_filter.id , register.bstype)>0
            where register.id='$company_id' and register.status=1 
            group by register.id");
        return $query->result_array();
    }


    function get_company_category_list($params)
    {
        extract($params);

        $where = '';
        if($is_status == 1){
            $where = ' and mp_product_category.status  = 1';
        }

        $query = $this->db->query("
                                SELECT 
                                    mp_product_category.id,mp_product_category.category_name,mp_product_category.category_alias,mp_product_category.profile_images,mp_product_category.images_path,SUBSTRING(mp_product_category.category_details,1,80) as details , count(mp_product.product_category) as product_count
                                from mp_product_category
                                left join 
                                    mp_product on mp_product.product_category=mp_product_category.id 
                                where 
                                    mp_product_category.register_id         = $company_id and 
                                    mp_product_category.profile_select_id   = $profile_select_id  
                                    ".$where."
                                group by mp_product_category.id 
                                order by 
                                    mp_product_category.order_by_id asc, mp_product_category.last_update_datee desc
                                limit 50");
        return $query->result_array();
    }

    function get_company_product_list($params)
    {
        extract($params);

        $query = $this->db->query("SELECT mp_product.id, mp_product.product_name, mp_product.product_alias, mp_product.mrp_price,mp_product.sell_price,mp_product.product_unit,mp_product.bcategory as category,mp_product.bscategory as sub_category, mp_product.images_path, mp_product.product_images_500, SUBSTRING(mp_product.product_details,1,100)as product_details
            from mp_product 
            where mp_product.register_id=$company_id and mp_product.profile_select_id=1 and mp_product.status=1 and mp_product.product_images_500 is not null  order by mp_product.last_update_datee desc limit 50");
        return $query->result_array();
    }

    function get_sellsaga_product_list($params)
    {
        extract($params);

        $query = $this->db->query("SELECT mp_product.id, mp_product.product_name, mp_product.product_alias, mp_product.mrp_price,mp_product.sell_price,mp_product.product_unit,mp_product.bcategory as category,mp_product.bscategory as sub_category, mp_product.images_path, mp_product.product_images_500, SUBSTRING(mp_product.product_details,1,100)as product_details
            from mp_product 
            where mp_product.app_salesaga_default_product_show = 1 and mp_product.status = 1 and mp_product.product_images_500 is not null  order by mp_product.last_update_datee desc");
        return $query->result_array();
    }

    function get_company_category_product_list($params)
    {
        extract($params);

        $where = '';
        if($is_status == 1){
            $where = ' and mp_product.status = 1';
        }

        $query = $this->db->query("SELECT mp_product.id ,mp_product.product_name, mp_product.product_alias, mp_product.product_details, mp_product.images_path, mp_product.product_images_500, mp_product.mrp_price, mp_product.sell_price, mp_product.product_unit,mp_product.bcategory as category,mp_product.bscategory as sub_category, mp_product.min_order,group_concat(joinpfilter.temp_pfilter_value separator '<br/>')as pfilter_value
            from mp_product
            left join (
                SELECT mpfd.id,mpfd.product_id,mpfd.product_filter_id, if( mpfd.product_filter_extras='', CONCAT( mpf.filtertype, ': ' ,GROUP_CONCAT(mpf2.filtername separator ', ')), CONCAT( mpf.filtertype, ': ' ,mpfd.product_filter_extras)  ) as temp_pfilter_value
                FROM mp_product_filter_details as mpfd
                left join mp_product_filter as mpf on mpfd.product_filter_id=mpf.idd and mpf.total_filter=1
                left join mp_product_filter as mpf2 on mpfd.product_filter_idd=mpf2.id
                GROUP by mpfd.product_id, mpfd.product_filter_id  
            ) joinpfilter on joinpfilter.product_id=mp_product.id 
            where 
                mp_product.register_id          = $company_id and 
                mp_product.profile_select_id    = 1 and 
                mp_product.product_category     = $category_id                 
                ".$where."
            group by mp_product.id 
            order by mp_product.order_by_id asc");
        return $query->result_array();
    }
}