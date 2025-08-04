<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('PAGINATION_SIZE', 15);
define('PRODUCT_PAGINATION_SIZE', 15);
define('COMMAN_DATE_FORMATE', 'd/m/yy');
define('COMMAN_DATETIME_FORMATE', 'd-m-Y h:i A');
define('COMMAN_DATETIME_FORMATE_2', 'd M');	
define('ANDROID_CUSTOMER_SERVER_KEY', 'sadsada3s2d3213');

define('SITE_NAME', 'eworldeducation');
define('ANDROID_APP_URI', 'https://goo.gl/kqfPjT ');
define('DOMAIN_NAME', 'eworldeducation');
define('DOMAIN_NAME_URL', 'https://eworldeducation.in');
define('FOLDER_LENGHT',-4);
define('BASE_PATH', substr(preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])).'/', 0,FOLDER_LENGHT));


$mod = 'prod'; // prod for prduction and test for Testing mode
define('RAZORPAY_API_URI', 'https://api.razorpay.com/v1/orders');
if($mod == 'test'){
	define('KEY_ID', 'rzp_test_OWrWcDN4Od9trI');
	define('KEY_SECRET', 'BUttn0pC4khK94fi6xAEa4EC');
}else{
	define('KEY_ID', 'rzp_live_X6XAha9cnR4m6Q');
	define('KEY_SECRET', 'GsjnVQ90lzCAdP9z2jbuA84A');
}


define('TBL_AGENT_REGISTRATION', 'agent_registration');
define('TBL_CATEGORY', 'category');
define('TBL_SUBCATEGORY', 'subcategory');
define('TBL_PRODUCT', 'product');
define('TBL_PRODUCT_IMG','product_img');
define('TBL_M_PRODUCT_UNIT','m_product_unit');
define('TBL_M_PRODUCT_HSNCODE','m_product_hsncode');
define('TBL_PRODUCT_FAVOURITE','product_favourite');
define('TBL_CART','cart');
define('TBL_STATE','m_state');
define('TBL_ORDER','l_order');
define('TBL_PRODUCT_HSNCODE','m_product_hsncode');
define('TBL_ORDER_PRODUCT','orderr_product');

define('TBL_DATA_STATUS','data_status');
define('TBL_CITY','m_city');
define('TBL_DATA_RESOURCE','data_resource');
define('TBL_CUSTOMER_TASK_MANAGER','customer_task_manager');
define('TBL_CUSTOMER_TASK_STATUS','customer_task_status');
define('TBL_CUSTOMER_TASK_MANAGER_FOLLOWP_RECORD','customer_task_manager_followup_record');




//Gcc
define('TBL_USER', 'user');
define('TBL_REGISTRATION', 'registration');
define('TBL_PRAYER_REQUEST', 'prayer_request');
define('TBL_AABHAR_PATRA', 'aabhar_patra');
define('TBL_AAPNI_KHABAR_ANTAR', 'aapni_khabar_antar');
define('TB_ADVERTISEMENT', 'advertisement');
define('TB_ADVERTISEMENT_TYPE', 'm_advertisement_type');
define('TB_BHAJAN_SANGRAH', 'bhajan_sangrah');
define('TB_LANGUAGE', 'm_language');
define('TB_BIOGRAPHY', 'biography');
define('TBL_DEATH_NOTIFICATION', 'death_notification');
define('TBL_FUND_RAISING_DONATION_REQUEST', 'fund_raising_donation_request');
define('TBL_FUND_TYPE', 'm_fund_type');
define('TBL_MESSAGE_TYPE', 'm_message_type');
define('TBL_GOD_MESSAGE', 'god_messages');

define('TBL_HOLY_BIBLE', 'holy_bible');
define('TBL_CHAPTER', 'm_chapter');

define('TBL_STUDENT_POST', 'student_post');
define('TBL_STUDENT_POST_REPORT_SPAM', 'student_post_report_spam');
define('TBL_BHAJAN_FAVOURITE', 'bhajan_favourite');


define('TBL_MATRIMONIAL_MEMBERSHIP_PLAN', 'matrimonial_membership_plan');
define('TBL_CURRENT_RESIDENCY_STATUS', 'm_current_residency_status');
define('TBL_RESIDENCY_TYPE', 'm_residency_type');
define('TBL_RELOCATION_OWENERSHIP', 'm_residence_owenership');
define('TBL_RELOCATION_PLAN', 'm_relocation_plan');
define('TBL_NATIONAL_ORGIN', 'm_national_origin');
define('TBL_MARITAL_STATUS', 'm_marital_status');
define('TBL_PROFILE_CREATED_BY', 'm_profile_created_by');
define('TBL_MATRIMONIAL_USER_DETAILS', 'matrimonial_user_details');
define('TBL_MEMBERSHIP_PLAN_ACTIVATION', 'membership_plan_activation');


define('TBL_HEIGHT', 'm_height');
define('TBL_WEIGHT', 'm_weight');
define('TBL_HAIR_TYPE', 'm_hair_type');
define('TBL_COMPLEXION', 'm_complexion');
define('TBL_BLOOD_GROPUP', 'm_blood_group');
define('TBL_BODY_TYPE', 'm_body_type');
define('TBL_HEALTH_CONDITION', 'm_health_condition');
define('TBL_MEDICATION_TAKING', 'm_medication_taking');
define('TBL_FOOD_HABIT', 'm_food_habit');
define('TBL_ADDICTION', 'm_addiction');

define('TBL_fATHER_STATUS', 'm_father_status');
define('TBL_MOTHER_STATUSN', 'm_mother_status');
define('TBL_PARENTS_MARITAL_STATUS', 'm_parents_marital_status');
define('TBL_FINANCIAL_STATUS', 'm_financial_stutus');
define('TBL_FAMILY_TYPE', 'm_family_type');
define('TBL_FAMILY_VALUES', 'm_family_values');

define('TBL_RELIGIOUS_FAITH', 'm_religious_faith');
define('TBL_CHRISTIAN_FAITH', 'm_christian_faith');
define('TBL_RELIGIOUS_SECT', 'm_religious_sect');
define('TBL_DAILY_PRAYER', 'm_daily_prayer');
define('TBL_BIBLE_READING', 'm_bible_reading');
define('TBL_BIBLE_KNOWLEDGE', 'm_bible_knowledge');
define('TBL_FAITH_COMMITMENT_LEVEL', 'm_faith_commitment_level');

define('TBL_EDUCATION', 'm_education');
define('TBL_HOBBIES', 'm_hobbies');

define('TBL_AAPNI_KHABAR_ANTAR_REPORT_SPAM', 'aapni_khabar_antar_report_spam');
define('TBL_PRAYER_REQUEST_CATEGORY', 'm_prayer_request_category');

define('TBL_BOOK', 'm_book');
define('TBL_HOLY_BIBLE_FAVOURITE', 'holi_bible_favourite');

define('TBL_ADVERTISMENT_PLAN', 'advertisement_plan');
define('TBL_ADVERTISMENT_PACKAGE_TXN', 'advertisement_package_txn');

define('TBL_HELP_SUPPORT','m_help_support');

define('TBL_PURPOSE_OF_FUND_RAISING','m_purpose_of_fund_raising');
define('TBL_FUND_RAISING_REPORT_SPAM','fund_raising_report_spam');

define('TBL_CHAT_ROOM','chat_room');


define('TBL_BOOK_TYPE','m_book_type');
define('TBL_PRAYER_REQUEST_MSG_txn','prayer_request_msg_txn');

define('TBL_MATRIMONIAL_FAVOURITE','matrimonial_favourite');

define('TBL_PACKAGE_SUBSCRIPTION_ORDER','package_subscription_order');
define('TBL_DURATION'	, 'm_duration');
define('TBL_SUBSCRIPTION_PACKAGE'	, 'subscription_package');
define('TBL_MAIN_COURSES'	, 'm_main_courses');
define('TBL_MAIN_VIDEO_LIST'	, 'main_manu_video_list');
define('TBL_PROMOCODE','promocode');


define('TBL_COURSES_DETAILS'	, 'courses_details');
define('TBL_COLLEGE_UNIVERSITY_TYPE'	, 'm_college_university_type');

define('TBL_COLLEGE_UNIVERSITY_DETAILS'	, 'college_university_details');

define('TBL_QUESTION_LIST'	, 'question_list');

define('TBL_ADMISSION_PROCESS'	, 'admission_process');

define('TBL_WEBSITE_LIST'	, 'website_list');

define('TBL_YEAR'	, 'm_year');

define('TBL_CUT_OFF_DETAILS'	, 'cut_off_details');

define('TBL_SCHOLARSHIP_LOAN_DETAILS'	, 'scholarship_loan_details');

define('TBL_DOCUMENTS_DETAILS'	, 'documents_details');

define('TBL_BANNER'	, 'banner');

define('TBL_EXRTA_COURSE'	, 'm_exrta_course');

define('TBL_ADMISION_PROCESS_WEBSITE_LINK_TXN'	, 'admission_process_website_link_txn');

define('TBL_CHAT_COURSE'	, 'chat_course');

define('TBL_CHAT_GROUP'	, 'chat_room_group');

define('TBL_CHAT_ROOM_TXN'	, 'chat_room_txn');

define('IMAGE_PATH'	, '');

define('CHAT_GROUP_MEMBER_PAGINATION_SIZE', 50);
define('CHAT_MESSAGE_PAGINATION_SIZE', 20);
define('TBL_NOTIFICATION'	, 'mobile_notification');
define('TBL_VIEW_NOTIFICATION_TXN'	, 'view_notification_txn');

