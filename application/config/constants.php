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

// Dwi Kuswarno

defined('_HOSTNAME')        	OR define('_HOSTNAME','localhost');
defined('_USERNAME_DB')        OR define('_USERNAME_DB','root'); # live
defined('_PASSWD_DB')        	OR define('_PASSWD_DB',''); #live
#defined('_USERNAME_DB')        	OR define('_USERNAME_DB','root'); #localhost
#defined('_PASSWD_DB')        	OR define('_PASSWD_DB',''); #localhost
defined('_DBNAME_DB')        	OR define('_DBNAME_DB','simrs_db'); #localhost/live
#defined('_SUB_DOMAIN')       	OR define('_SUB_DOMAIN','.mymeters.id'); #live
defined('_SUB_DOMAIN')        	OR define('_SUB_DOMAIN',''); #localhost
#defined('_PREFIX_TABLE')        OR define('_PREFIX_TABLE','erp_');
defined('_PREFIX_TABLE')        OR define('_PREFIX_TABLE','');
 
defined('_APP_NAME')        	OR define('_APP_NAME','PT. Gerbang Data Indonesia - HR System');
defined('_SUP_APP_NAME')        OR define('_SUP_APP_NAME','PT. Gerbang Data Indonesia');
defined('_TITLE')        		OR define('_TITLE', 'PT. Gerbang Data Indonesia - HR System');
defined('_COMPANY_NAME')        OR define('_COMPANY_NAME','PT. Gerbang Data Indonesia');
defined('_COMPANY_NAME_ABBR')   OR define('_COMPANY_NAME_ABBR','GDI');
defined('_COPYRIGHT')        	OR define('_COPYRIGHT','Copyright &copy; PT. Gerbang Data Indonesia');
 
defined('_URL_ADMIN')        	OR define('_URL_ADMIN','http://localhost/_hrm/');
defined('_URL')        			OR define('_URL','http://localhost/_hrm/');
defined('_ASSET')        		OR define('_ASSET',_URL."public/assets/");  
defined('_ASSET_IMG')        	OR define('_ASSET_IMG',_URL."public/assets/images/");  
defined('_ASSET_PLUGINS')       OR define('_ASSET_PLUGINS',_URL."public/assets/plugins/");  
defined('_ASSET_UPLOADS')       OR define('_ASSET_UPLOADS',_URL."public/assets/uploads/"); 
defined('_ASSET_CSS')        	OR define('_ASSET_CSS',_URL."public/assets/css/");  
defined('_IMG_WEB')        		OR define('_IMG_WEB',_URL.'public/assets/images/');  

// Cut-off
$mk_currenttime  = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$mk_cutofflogo = mktime(23, 59, 59, 2, 13, 2022); // memasuki 14 Februari 2022
defined('_CUT_OFF_LOGO')        			OR define('_CUT_OFF_LOGO', $mk_cutofflogo);  

// Template
/*defined('_ASSET_LOGO')        	OR define('_ASSET_LOGO',_ASSET_IMG."logo/nbid_logo.png");  
defined('_ASSET_LOGO_2022')     OR define('_ASSET_LOGO_2022',_ASSET_IMG."logo/nbid_new_logo.png");  
defined('_ASSET_LOGO_2022_INSIDE')     OR define('_ASSET_LOGO_2022_INSIDE',_ASSET_IMG."logo/nbid_new_logo_inside.png");  */
/*defined('_ASSET_LOGO')        	OR define('_ASSET_LOGO',_ASSET_IMG."logo/gerbangdata.PNG");  
defined('_ASSET_LOGO_2022')     OR define('_ASSET_LOGO_2022',_ASSET_IMG."logo/gerbangdata.PNG");  
defined('_ASSET_LOGO_2022_INSIDE')     OR define('_ASSET_LOGO_2022_INSIDE',_ASSET_IMG."logo/gerbangdata.PNG");*/
defined('_ASSET_ILUSTRASI_LOGIN') OR define('_ASSET_ILUSTRASI_LOGIN', _ASSET_IMG . "login/ilustrasi.png");
defined('_ASSET_LOGO')        	OR define('_ASSET_LOGO',_ASSET_IMG."logo/gerbangdata.jpg");  
defined('_ASSET_LOGO_2022')     OR define('_ASSET_LOGO_2022',_ASSET_IMG."logo/gerbangdata.jpg");  
defined('_ASSET_LOGO_2022_INSIDE')     OR define('_ASSET_LOGO_2022_INSIDE',_ASSET_IMG."logo/gdi_inside.png");  
defined('_ASSET_LOGO_2022_SURAT')     OR define('_ASSET_LOGO_2022_SURAT',_ASSET_IMG."logo/nbid_new_logo_surat.png");
defined('_ASSET_PROFILE_PICTURE') OR define('_ASSET_PROFILE_PICTURE', _ASSET_IMG . "logo/profile.jpg");  

if($mk_currenttime > $mk_cutofflogo){
	defined('_ASSET_LOGO_FRONT')        	OR define('_ASSET_LOGO_FRONT',_ASSET_LOGO_2022);  
	defined('_ASSET_LOGO_INSIDE')        	OR define('_ASSET_LOGO_INSIDE',_ASSET_LOGO_2022_INSIDE);  
} else {
	defined('_ASSET_LOGO_FRONT')        	OR define('_ASSET_LOGO_FRONT',_ASSET_LOGO);  
	defined('_ASSET_LOGO_INSIDE')        	OR define('_ASSET_LOGO_INSIDE',_ASSET_LOGO);  
}
defined('_TEMPLATE_PATH')       			OR define('_TEMPLATE_PATH',"tpl/"); 
defined('_TEMPLATE_LOGIN')					OR define('_TEMPLATE_LOGIN',_TEMPLATE_PATH);   
defined('_TEMPLATE')        				OR define('_TEMPLATE',_TEMPLATE_PATH."main_page");   
defined('_TEMPLATE_EMAIL')					OR define('_TEMPLATE_EMAIL',_TEMPLATE_PATH."template_email/");   
defined('_ASSET_METRONIC_TEMPLATE')        	OR define('_ASSET_METRONIC_TEMPLATE',_URL."public/assets/metronic/assets/");  
defined('_ASSET_GLOBAL_METRONIC_TEMPLATE')  OR define('_ASSET_GLOBAL_METRONIC_TEMPLATE',_URL."public/assets/metronic/assets/global/");  
defined('_ASSET_LAYOUTS_METRONIC_TEMPLATE') OR define('_ASSET_LAYOUTS_METRONIC_TEMPLATE',_URL."public/assets/metronic/assets/layouts/");  
defined('_ASSET_PAGES_METRONIC_TEMPLATE')   OR define('_ASSET_PAGES_METRONIC_TEMPLATE',_URL."public/assets/metronic/assets/pages/");

// Login/user mailing
defined('_MAIL_SYSTEM_NAME')        		OR define('_MAIL_SYSTEM_NAME', _COMPANY_NAME_ABBR.' Support System');
defined('_MAIL_SYSTEM_EMAIL')        		OR define('_MAIL_SYSTEM_EMAIL', 'kuswarno@gerbangdata.co.id');
defined('_ACCOUNT_TITLE')        			OR define('_ACCOUNT_TITLE', 'vendor');  // lowercase
defined('_ACCOUNT_KEYLENGTH')        		OR define('_ACCOUNT_KEYLENGTH', '25');  // max 25
defined('_NEW_ACCOUNT_EXPIRE')        		OR define('_NEW_ACCOUNT_EXPIRE', '24');  // in hour
defined('_RESET_ACCOUNT_PASSWORD_EXPIRE')   OR define('_RESET_ACCOUNT_PASSWORD_EXPIRE', '1');  // in hour
defined('_COOKIES_NAME')   					OR define('_COOKIES_NAME', 'caterp');  // cookies_name
defined('_COOKIES_EXPIRE')   				OR define('_COOKIES_EXPIRE', '30');  // in days

defined('_BLN')        OR define('_BLN',['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']);