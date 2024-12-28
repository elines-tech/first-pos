<?php
defined('BASEPATH') or exit('No direct script access allowed');

define('SESS_KEY', 'Kaemsoftware-superadmin');

define('AppName', 'First Software');

defined('mailName') or define('mailName', 'Tamakan POS');
defined('mailId') or define('mailId', 'support@tmkn-pos.com');
defined('mailpassword') or define('mailpassword', 'E;HyIz6i}0$Y');

//defined('MAIN_DBNAME') or define('MAIN_DBNAME', 'neosaoco_pos_kaemsoft');

defined('FATOORAH_KEY') or define('FATOORAH_KEY', 'j7m_Kai8lTg4Q_gxIRSKwE7Juo8HzVcZOeB27JF4LrTNGVTaM2Hp-oGIorPdv75mvEKTEgHg_FHnF0h7ARayGbjQ0xBxwbe_lug8Jy1P2bw_6fuGZuIDg7jnicf5UPdjI-WOOHK3HrU8AE1kJnv6T4eBCmCAZu2Ky9pyMV-NaeiA-z5Uu4T5BC13rFT6I7ITMXsPGomXfZjYUpeZK9_ikoOq2gLD-qw5QVO62Wl3Sw0NN5fq1rqLjeD1n3GoeRIK8Wy9eSdPo1iCkRKernNNqa9cL0ctFLYEOD-DbdOTKMKsNOW1Qdp2MTTouKEe0BKrFBLT4psW1lJkr-EusqT9G3gW0W2qnRfYw61mi0JSg6XH5qrA-IdvwA2L17z0FKNlSCloZakUysN8k7AlXzpGF1j2ndi1B7XBrw8ouiWJqDLOE2w1kwoT_BnngSvhtzXYN6zTu3DyUKQEyNnYfX56gJLivDOHOM7J40YA1ZrAvdPM08ZNbQvE4amphACYxLJJFU2rdj8ANsH7hkzQmvGWHMtx1ZTmg52O8Xppm0oIo2snl4GqkKGNRyHIIK5Rro0LF5nRhBOVKwy35TJmvI_93213I9dh-eoC2Js4haDig9i8FtJRnMPAULMHidMuj8eOwn5TzRr6BSGAZajKqf-pYxFgAtxKJCHrsrGotvm611t1ngd0');
defined('FATOORAH_URL') or define('FATOORAH_URL', 'https://apitest.myfatoorah.com');
defined('PAY_GATEWAY_CURRENCY') or define('PAY_GATEWAY_CURRENCY', 'KWD'); //SAR

defined('MAIN_DB_NAME') or define('MAIN_DB_NAME','neosaoco_pos_kaemsoft');
defined('MAIN_DBNAME') or define('MAIN_DBNAME', 'neosaoco_pos_kaemsoft');
defined('MAIN_DB_HOST')or define('MAIN_DB_HOST','localhost');
defined('MAIN_DB_UNAME')or define('MAIN_DB_UNAME','root');
defined('MAIN_DB_PASSWORD')or define('MAIN_DB_PASSWORD','root');

/*
defined('MAIN_DB_NAME') or define('MAIN_DB_NAME', 'neosaoco_pos_kaemsoft');
defined('MAIN_DB_HOST') or define('MAIN_DB_HOST', 'localhost');
defined('MAIN_DB_UNAME') or define('MAIN_DB_UNAME', 'root');
defined('MAIN_DB_PASSWORD') or define('MAIN_DB_PASSWORD', '');
*/

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
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);
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
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
