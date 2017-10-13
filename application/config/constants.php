<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

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
define('SHOW_DEBUG_BACKTRACE', TRUE);

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
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//Error Codes
define('INVALID_ACCESS_TOKEN', 402);
define('HEADER_MISSING', 403);
define('MISSING_PARAMETER', 404);
define('EMAIL_ALREADY_REGISTERED', 405);
define('USER_STATUS_NOT_ACTIVE', 406);
define('UNKNOWN_DEVICE_TYPE', 407);
define('PHONE_NUMBER_ALREADY_REGISTERED', 408);
define('FB_ID_NOT_REGISTERED', 409);
define('TWITTER_ID_NOT_REGISTERED', 410);
define('USER_BLOCKED', 411);
define('INVALID_OTP', 412);
define('WRONG_PASSWORD', 413);
define('PHONE_NOT_REGISTERED', 414);
define('INVALID_FORGOT_TOKEN', 415);
define('WRONG_PIN', 416);

define('EMAIL_ERROR', 515);
define('SUCCESS', 200);

//Field Lengths
define('NAME_LENGTH', 100);
define('EMAIL_LENGTH', 100);
define('FB_ID_LENGTH', 256);
define('PHONE_LENGTH', 15);
define('COUNTRY_CODE_LENGTH', 5);
define('PASSWORD_LENGTH', 50);
define('DEVICE_TOKEN_LENGTH', 256);
define('FORGOT_TOKEN_LENGTH', 100);
define('OTP_LENGTH', 4);
define('PIN_LENGTH', 4);

//Other constant
define('USER_SUBSCRIBED', 1);
define('USER_NOT_SUBSCRIBED', 0);
define('USER_ROLE', 2);
define('USER_ACTIVE', 1);
define('USER_BLOCK', 2);
define('USER_DELETED', 3);
define('PHONE_VERIFIED', 1);
define('PHONE_UNVERIFIED', 2);
define('DEVICE_TYPE_ANDROID', 1);
define('DEVICE_TYPE_IPHONE', 2);
define('CREDENTIALS_TYPE_PHONE', 1);
define('CREDENTIALS_TYPE_EMAIL', 2);
define('VALIDATE_PIN_LOGIN', 1);
define('VALIDATE_PIN_USER', 2);
define('SUBSCRIPTION_VALADITY_WEEKLY',1);
define('SUBSCRIPTION_VALADITY_MONTHLY',2);
define('SUBSCRIPTION_VALADITY_QUARTERLY',3);
define('SUBSCRIPTION_VALADITY_HALFYEARLY',4);
define('SUBSCRIPTION_VALADITY_YEARLY',5);
define('MEMBERSHIP_TYPE_PRO', 2);
define('MEMBERSHIP_TYPE_PLATINUM', 3);
define('MEMBERSHIP_TYPE_NON_PREMIUM', 1);
define('PRODUCT_KEYFOB', 1);
define('PRODUCT_ORIGINAL_TAG', 2);
define('PRODUCT_CUSTOM_TAG', 3);
define('PRODUCT_OTHER', 4);
define('SUBSCRIPTION_ABOVE_TEXT',2);
define('SUBSCRIPTION_BELOW_TEXT',1);
defined("AWS_URI")        OR define('AWS_URI', '');
defined("ACCESS_KEY")     OR define('ACCESS_KEY', 'AKIAIGTT2CNXI3KAGXSQ');
defined("SECRET_KEY")     OR define('SECRET_KEY', '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');
defined("BUCKET_NAME")    OR define('BUCKET_NAME', 'appinventiv-development');
