<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//loads composer autoload for loading libraries
require_once APPPATH . "composer/vendor/autoload.php";
require_once APPPATH . "libraries/OpenSSLEncrypt.php";

use Encryption\OpenSSLEncrypt;
use Intervention\Image\ImageManagerStatic as Image;

class AjaxController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->load->helper("json");
        $this->load->library(['S3']);
        $this->load->model("Common_model");
        $this->datetime = date("Y-m-d H:i:s");

    }

    //forgot password handler
    public function forgotPassword()
    {
        $email = $this->input->post("email");
        $this->load->helper("encrypt_openssl");
        
        if ( ! filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            response([
                "code" => INVALID_EMAIL_FORMAT,
                "message" => "Invalid Email Format",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        $email = trim($email);

        $userData = $this->Util_model->selectQuery(
            "id",
            "ad_user",
            [ 
                "where" => [
                    "email" => $email,
                    "role_id" => ADMIN
                ]
        ]);

        if ( ! $userData ) {
            response([
                "code" => INVALID_USER,
                "message" => "Invalid email",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        $forgotToken = hash("sha256", time(). "" .$email);

        try {
            $this->Util_model->updateTableData(["forgot_token" => $forgotToken], "ad_user", ["email"=> $email]);

            /**
             * @todo Send mail with encrypted forgot Token.
             */
            $param["link"] = base_url() . "reset-password?reset=" . encrypt_with_openssl(new OpenSSLEncrypt($forgotToken), true);
            $param["email"] = $email;
            $this->Common_model->sendmailnew($email, 'Adelgaz Reset Password', '', true, $param, 'forgotPasswordAdmin');
            response([
                "code" => SUCCESS,
                "message" => "Forgot password set",
                "success" => true,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        } catch (\Exception $error) {
            response([
                "code" => 500,
                "message" => "Some error occurred, please try again",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        
    }

    //Blocks users
    public function blockUser() 
    {
        $this->load->helper("encrypt_openssl");
        $id = $this->input->post("id");
        $status = $this->input->post("updated_status");
        if (is_array($id)) {
			for ($i = 0;$i < count($id);$i++) {
				$userId[$i]['id'] = decrypt_with_openssl(new OpenSSLEncrypt(), $id[$i], true);
				$userId[$i]['status'] = $status;
			}
		} else {
			$userId = decrypt_with_openssl(new OpenSSLEncrypt(), $id);
		}
		
        if ( !isset($userId) || empty($userId) ) {
            response([
                "code" => INVALID_USER,
                "message" => "Invalid user",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        try {
			if (is_array($userId)) {
				$this->Common_model->update_batch_data('ad_user', $userId, 'id');
			} else {
				$this->Util_model->updateTableData(["status" => $status], "ad_user", ["id" => $userId]);
			}
            response([
                "code" => SUCCESS,
                "message" => "success",
                "success" => true,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        } catch(\Exception $error) {
            response([
                "code" => 500,
                "message" => "Some Error Occurred! please try again",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
    }

    //adds tag detail
    public function addTag()
    {
        $postData = $this->input->post();
        //check postdata
        if ( empty(array_filter($postData)) ) {
            response([
                "success" => false,
                "message" => "tag name is required",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        //trim tag name
        $tagName = trim($postData["tag_name"]);
        //match tag name with pattern only letters and numbers
        if ( ! preg_match("/^[\wáéíóú¿¡üñçåÁÉÍÓÚ¿¡ÜÑÇÅ][\w\sáéíóú¿¡üñçåÁÉÍÓÚ¿¡ÜÑÇÅ]*$/", $tagName ) ) {
            response([
                "success" => false,
                "message" => "invalid tag name, must contain only letters",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        $image = $_FILES['tag_image'];
        //get images sizes
        $imageSize = getimagesize($image['tmp_name']);
        $validMimeTypes = ['image/png','image/jpg', 'image/jpeg'];

        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $imageName = "adelgaz_" . shell_exec("date +%s%N") . "." . $extension;

        if ( ! $imageSize || null === $imageSize) {
            response([
                "code" => NOT_AN_IMAGE,
                "message" => "File is not an image",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        if ( ! in_array( $imageSize['mime'], $validMimeTypes ) ) {
            response([
                "code" => NOT_AN_IMAGE,
                "message" => "File is not an image",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        if ( $image['size'] > MAX_IMAGE_SIZE ) {
            response([
                "code" => IMAGE_TOO_BIG,
                "message" => "Image too big",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        $s3 = new S3();
        $name = shell_exec("date +%s%N");
        $name = filter_var($name, FILTER_SANITIZE_NUMBER_INT);
        $name = "{$name}.{$extension}";
        $thumbnail = APPPATH . "../public/thumbnails/{$name}";
        $img = Image::make($image['tmp_name'])
                ->resize(100, 100)
                ->save($thumbnail);

        $image = $this->Common_model->s3_uplode("adelgaz_".$name, $image["tmp_name"]);
        $imageThumb = $this->Common_model->s3_uplode("thumbnail_adelgaz_".$name, $thumbnail);
        
        try {
            $uploadData["name"] = $tagName;
            $uploadData["tag_image"] = $image;
            $uploadData["tag_cropped_image"] = $imageThumb;
            $uploadData["created"] = $this->datetime;
            
            $this->Util_model->insertTableData($uploadData, "ad_tags");
        } catch (\Exception $error) {
            unlink($thumbnail);
            response([
                "code" => 500,
                "message" => "Some Error Occurred! please try again",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        
        unlink($thumbnail);
        response([
            "code" => SUCCESS,
            "message" => "success",
            "success" => true
        ]);
    }

    //add Category
    public function addCategory()
    {
        $postData = $this->input->post();
        //check postdata
        if ( empty(array_filter($postData)) ) {
            response([
                "success" => false,
                "message" => "category name is required",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        //trim category name
        $categoryName = trim($postData["category_name"]);
        //match category name with pattern only letters and numbers
        if ( ! preg_match("/^[\wáéíóú¿¡üñçåÁÉÍÓÚ¿¡ÜÑÇÅ][\w\sáéíóú¿¡üñçåÁÉÍÓÚ¿¡ÜÑÇÅ]*$/", $categoryName ) ) {
            response([
                "success" => false,
                "message" => "invalid category name, must contain only letters",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        
        $postType = trim($postData["post_type"]);
        if (empty($postType) || ($postType != FITNESS_VIDEO && $postType != FITNESS_PLAN)) {
			response([
                "success" => false,
                "message" => "Post Type is invalid",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
		}
		
        $image = $_FILES['category_image'];
        //get images sizes
        $imageSize = getimagesize($image['tmp_name']);
        $validMimeTypes = ['image/png','image/jpg', 'image/jpeg'];

        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $imageName = "adelgaz_" . shell_exec("date +%s%N") . "." . $extension;

        if ( ! $imageSize || null === $imageSize) {
            response([
                "code" => NOT_AN_IMAGE,
                "message" => "File is not an image",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        if ( ! in_array( $imageSize['mime'], $validMimeTypes ) ) {
            response([
                "code" => NOT_AN_IMAGE,
                "message" => "File is not an image",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        if ( $image['size'] > MAX_IMAGE_SIZE ) {
            response([
                "code" => IMAGE_TOO_BIG,
                "message" => "Image too big",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        $s3 = new S3();
        $name = shell_exec("date +%s%N");
        $name = filter_var($name, FILTER_SANITIZE_NUMBER_INT);
        $name = "{$name}.{$extension}";
        $thumbnail = APPPATH . "../public/thumbnails/{$name}";
        $img = Image::make($image['tmp_name'])
                ->resize(100, 100)
                ->save($thumbnail);

        $image = $this->Common_model->s3_uplode("adelgaz_".$name, $image["tmp_name"]);
        $imageThumb = $this->Common_model->s3_uplode("thumbnail_adelgaz_".$name, $thumbnail);
        
        try {
            $uploadData["category_name"] = $categoryName;
            $uploadData["post_type"] = $postType;
            $uploadData["category_image"] = $image;
            $uploadData["category_cropped_image"] = $imageThumb;
            $uploadData["created"] = $this->datetime;
            $uploadData["updated"] = $this->datetime;
            $this->Util_model->insertTableData($uploadData, "ad_category");
        } catch (\Exception $error) {
            unlink($thumbnail);
            response([
                "code" => 500,
                "message" => "Some Error Occurred! please try again",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        
        unlink($thumbnail);
        response([
            "code" => SUCCESS,
            "message" => "success",
            "success" => true
        ]);
    }

    //generates referral code
    public function generateReferralCode()
    {
        $email = $this->input->post("email");
        
        if ( ! filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            response([
                "code" => INVALID_EMAIL_FORMAT,
                "message" => "Invalid Email Format",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        $email = trim($email);

        //get registered user data
        $registeredUser = $this->Util_model->selectQuery(
            "id",
            "ad_user",
            [
                "where" => [
                    "email" => $email,
                    "status !=" => USER_DELETED
                ]
            ]
        );

        if ( $registeredUser ) {
            response([
                "code" => USER_ALREADY_REGISTERED,
                "message" => "User already registered",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        // Referral  
        $referralUpdateFlag = false;
        $referralData = $this->Util_model->selectQuery(
            "id",
            "ad_referral",
            [
                "where" => [
                    "email" => $email
                ]
            ]
        );

        if ( $referralData ) {
            $referralUpdateFlag = true;
        }

        //fetch all referral codes
        $referralCodes = $this->Util_model->selectQuery(
            "referral_code",
            "ad_referral"
        );
        
        if ( ! $referralCodes ) {
            $referralCodes = [];    
        }
        
        $referralCodes = array_column($referralCodes, "referral_code");
        
        $referral = "";
        //loop till a unique referral code is generated
        while(true) {
            $referral = $this->Util_model->generateRandomString(false, 4);
            $referral = strtoupper($referral);
            if ( ! in_array($referral, $referralCodes) ) {
                break;
            }
        }
        unset($referralData);
        $referralData["referral_code"] = $referral;
        $referralData["created"] = $this->datetime;

        try {
            if ( $referralUpdateFlag ) {
                $this->Util_model->updateTableData($referralData, "ad_referral", ["email" => $email]);
            } else {
                $referralData["email"] = $email;
                $this->Util_model->insertTableData($referralData, "ad_referral", ["email" => $email]);
            }
            $this->session->set_userdata("adelgaz_referral_email", $email);
            $this->session->set_userdata("adelgaz_referral_code", $referralData["referral_code"]);
            // $param["referral_code"] = $referral;
            // $param["email"] = $email;
            // $this->Common_model->sendmailnew($email, 'Adelgaz Referral Code', $referral, true, $param, 'referralCode');
            response([
                "code" => SUCCESS,
                "message" => "Referral code generated successfully",
                "success" => true,
                "referral_code" => $referral,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        } catch (\Exception $error) {
            response([
                "code" => 500,
                "message" => "Some error occurred, please try again",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
    }

    public function sendReferralCode()
    {
        $email = $this->session->userdata("adelgaz_referral_email");
        $referral = $this->session->userdata("adelgaz_referral_code");
        $param["referral_code"] = $referral;
        $param["email"] = $email;
        if ($this->Common_model->sendmailnew($email, 'Adelgaz Referral Code', $referral, true, $param, 'referralCode')){
            $this->session->unset_userdata("adelgaz_referral_email");
            $this->session->unset_userdata("adelgaz_referral_code");
            response([
                "code" => SUCCESS,
                "message" => "Referral Code {$param["referral_code"]} sent to {$param["email"]}",
                "success" => true,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        } else {
            response([
                "code" => 500,
                "message" => "Some error occurred, please try again",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
    }

    //Adds news
    public function addNews()
    {
        $postData = $this->input->post();

        $mandatoryFields = [
            "news_name",
            "news_description"
        ];
        $check = $this->Util_model->checkRequiredFields($postData, $mandatoryFields);
        if ( $check["error"] ||
         (!isset($_FILES["news_image"]) || empty(array_filter($_FILES["news_image"]["tmp_name"])) ) ||
         (!isset($_FILES["news_video"]["tmp_name"]) || empty(array_filter($_FILES["news_video"])))
         ) {
            response([
                "code" => REQUIRED_FIELDS_MISSING,
                "message" => "Required fields missing",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        
        $newsName = trim($postData["news_name"]);
        if ( ! preg_match("/^[\wáéíóú¿¡üñçåÁÉÍÓÚ¿¡ÜÑÇÅ][\w\sáéíóú¿¡üñçåÁÉÍÓÚ¿¡ÜÑÇÅ]*$/", $newsName ) ) {
            response([
                "success" => false,
                "message" => "invalid news name, must contain only letters and numbers",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        
        $newsDescription = trim($postData["news_description"]);
        $newsTags = isset($postData["news_tags"])&&!empty($postData["news_tags"])?$postData["news_tags"]:"";
        $newsVideo = $_FILES["news_video"];
        $image = $_FILES["news_image"];
        $validImageMimeTypes = ['image/png', 'image/bmp', 'image/gif','image/jpg', 'image/jpeg'];
        $validVideoMimeTypes = ["video/mp4"];
        $extension = [];
        foreach ($image["name"] as $key => $value) {
            $imageSize = getimagesize($image['tmp_name'][$key]);

            $extension[] = pathinfo($value, PATHINFO_EXTENSION);
            // $imageName = "adelgaz_" . shell_exec("date +%s%N") . "." . $extension;

            if ( ! $imageSize || null === $imageSize) {
                response([
                    "code" => NOT_AN_IMAGE,
                    "message" => $value . " File is not an image",
                    "success" => false,
                    $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
                ]);
            }

            if ( ! in_array( $imageSize['mime'], $validImageMimeTypes ) ) {
                response([
                    "code" => NOT_AN_IMAGE,
                    "message" => $value . " File is not an image",
                    "success" => false,
                    $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
                ]);
            }
            if ( $image['size'][$key] > MAX_IMAGE_SIZE ) {
                response([
                    "code" => IMAGE_TOO_BIG,
                    "message" => $value . " Image too big",
                    "success" => false,
                    $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
                ]);
            }
        }
        if ( isset($newsTags) && !empty($newsTags) ) {
            if ( count($newsTags) > TAG_LIMIT ) {
                response([
                    "code" => TOO_MANY_TAGS,
                    "message" => "Too many tags selected, please select upto " .  TAG_LIMIT . " tags.",
                    "success" => false,
                    $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
                ]);
            }
            $insertData["tags"] = implode(",", $newsTags);
        } else {
            $insertData["tags"] = "";
        }
        
        $videoMimeType = mime_content_type($newsVideo["tmp_name"]);
        $videoExtension = pathinfo($newsVideo["name"], PATHINFO_EXTENSION);

        if ( ! in_array($videoMimeType, $validVideoMimeTypes) ) {
            response([
                "code" => NOT_A_VIDEO,
                "message" => $value . " File is not a video",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        $imageArr = [];

        foreach ($image["name"] as $key => $value) {
            $s3 = new S3();
            $name = shell_exec("date +%s%N");
            $name = filter_var($name, FILTER_SANITIZE_NUMBER_INT);
            $name = "{$name}.{$extension[$key]}";
    
            $imageArr[] = $this->Common_model->s3_uplode("adelgaz_".$name, $image["tmp_name"][$key]);
        }
        //video options
        $videoName = shell_exec("date +%s%N");
        $videoName = filter_var($videoName, FILTER_SANITIZE_NUMBER_INT);
        $videoName = "{$videoName}.{$videoExtension}";
        $video = $this->Common_model->s3_uplode("adelgaz_".$videoName, $newsVideo["tmp_name"]);
        //generate thumbnail and upload
        $thumbnail_image_name = uniqid().time().".jpeg";
        $thumbnail = APPPATH."../public/thumbnails/";
        $thumb_path = $thumbnail.$thumbnail_image_name;
        $uploadedVideoName = substr($video, strrpos($video, '/') + 1);
        shell_exec("ffmpeg -i '".$s3->getAuthenticatedURL(BUCKET_NAME, $uploadedVideoName, 500)."' -deinterlace -an -ss 2 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumb_path 2>&1");
        $thumb_img = $this->Common_model->s3_uplode($thumbnail_image_name, $thumbnail.$thumbnail_image_name);
        
        unlink($thumb_path);
        $insertData["image_resources"] = implode(",", $imageArr);
        
        $insertData["post_type"] = FITNESS_BLOG;
        $insertData["post_name"] = $newsName;
        $insertData["post_desc"] = $newsDescription;
        $insertData["created"] = $this->datetime;
        $insertData["video_resource"] = $video;
        $insertData["video_thumbnail"] = $thumb_img;

        try {
            $this->Util_model->insertTableData($insertData, "ad_posts");
        } catch (\Exception $error) {
            response([
                "code" => 500,
                "message" => "Some error occurred, please try again",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }

        response([
            "code" => SUCCESS,
            "message" => "success",
            "success" => true,
            $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
        ]);

    }


    //Adds news
    public function addRecipe()
    {
        $postData = $this->input->post();

        $mandatoryFields = [
            "recipe_name",
            "recipe_description",
            "recipe_category",
            "recipe_cooking_time",
            "recipe_preparation_time"
        ];

        $arrayCheck = function ( $index ) {
            if ( empty(trim($index)) ) {
                return false;
            }

            return true;
        };

        $check = $this->Util_model->checkRequiredFields($postData, $mandatoryFields);
        if ( $check["error"] ||
         (!isset($_FILES["recipe_image"]) || empty(array_filter($_FILES["recipe_image"]["name"])) ) ||
         (!isset($_FILES["recipe_video"]) || empty($_FILES["recipe_video"]["name"])) ||
         (!isset($postData["recipe_ingredients"]) || empty(array_filter($postData["recipe_ingredients"], $arrayCheck)))
         ) {
            response([
                "code" => REQUIRED_FIELDS_MISSING,
                "message" => "Fill all required fields",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        // print_r($_FILES["recipe_image"]);die;
        $recipeName = trim($postData["recipe_name"]);
        if ( ! preg_match("/^[\wáéíóú¿¡üñçåÁÉÍÓÚ¿¡ÜÑÇÅ][\w\sáéíóú¿¡üñçåÁÉÍÓÚ¿¡ÜÑÇÅ]*$/", $recipeName ) ) {
            response([
                "success" => false,
                "message" => "invalid recipe name, must contain only letters and numbers",
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        //post data
        $recipeCategory = $postData["recipe_category"];
        $recipeDescription = trim($postData["recipe_description"]);
        $recipeTags = isset($postData["recipe_tags"])&&!empty($postData["recipe_tags"])?$postData["recipe_tags"]:"";
        $recipeVideo = $_FILES["recipe_video"];
        $image = $_FILES["recipe_image"];
        $validImageMimeTypes = ['image/png', 'image/bmp', 'image/gif','image/jpg', 'image/jpeg'];
        $validVideoMimeTypes = ["video/mp4"];
        $extension = [];

        
        foreach ($image["name"] as $key => $value) {
            $imageSize = getimagesize($image['tmp_name'][$key]);

            $extension[] = pathinfo($value, PATHINFO_EXTENSION);
            // $imageName = "adelgaz_" . shell_exec("date +%s%N") . "." . $extension;

            if ( ! $imageSize || null === $imageSize) {
                response([
                    "code" => NOT_AN_IMAGE,
                    "message" => $value . " File is not an image",
                    "success" => false,
                    $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
                ]);
            }

            if ( ! in_array( $imageSize['mime'], $validImageMimeTypes ) ) {
                response([
                    "code" => NOT_AN_IMAGE,
                    "message" => $value . " File is not an image",
                    "success" => false,
                    $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
                ]);
            }
            if ( $image['size'][$key] > MAX_IMAGE_SIZE ) {
                response([
                    "code" => IMAGE_TOO_BIG,
                    "message" => $value . " Image too big",
                    "success" => false,
                    $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
                ]);
            }
        }

        $videoMimeType = mime_content_type($recipeVideo["tmp_name"]);
        $videoExtension = pathinfo($recipeVideo["name"], PATHINFO_EXTENSION);

        if ( ! in_array($videoMimeType, $validVideoMimeTypes) ) {
            response([
                "code" => NOT_A_VIDEO,
                "message" => $value . " File is not a video",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        if ( isset($recipeTags) && !empty($recipeTags) ) {
            if ( count($recipeTags) > TAG_LIMIT ) {
                response([
                    "code" => TOO_MANY_TAGS,
                    "message" => "Too many tags selected, please select upto " .  TAG_LIMIT . " tags.",
                    "success" => false,
                    $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
                ]);
            }
            $insertData["tags"] = implode(",", $recipeTags);
        } else {
            $insertData["tags"] = "";
        }
        $imageArr = [];

        foreach ($image["name"] as $key => $value) {
            $s3 = new S3();
            $name = shell_exec("date +%s%N");
            $name = filter_var($name, FILTER_SANITIZE_NUMBER_INT);
            $name = "{$name}.{$extension[$key]}";
    
            $imageArr[] = $this->Common_model->s3_uplode("adelgaz_".$name, $image["tmp_name"][$key]);
        }
        //video options
        $videoName = shell_exec("date +%s%N");
        $videoName = filter_var($videoName, FILTER_SANITIZE_NUMBER_INT);
        $videoName = "{$videoName}.{$videoExtension}";
        $video = $this->Common_model->s3_uplode("adelgaz_".$videoName, $recipeVideo["tmp_name"]);
        //generate thumbnail and upload
        $thumbnail_image_name = uniqid().time().".jpeg";
        $thumbnail = APPPATH."../public/thumbnails/";
        $thumb_path = $thumbnail.$thumbnail_image_name;
        $uploadedVideoName = substr($video, strrpos($video, '/') + 1);
        shell_exec("ffmpeg -i '".$s3->getAuthenticatedURL(BUCKET_NAME, $uploadedVideoName, 500)."' -deinterlace -an -ss 2 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumb_path 2>&1");
        $thumb_img = $this->Common_model->s3_uplode($thumbnail_image_name, $thumbnail.$thumbnail_image_name);
        
        unlink($thumb_path);
        $insertData["image_resources"] = implode(",", $imageArr);
        //recipe data

        $insertData["post_type"] = FITNESS_PLAN;
        $insertData["post_name"] = $recipeName;
        $insertData["post_desc"] = $recipeDescription;
        $insertData["created"] = $this->datetime;
        $insertData["video_resource"] = $video;
        $insertData["video_thumbnail"] = $thumb_img;
        $insertData["category_id"] = $recipeCategory;

        $recipeInfo["prep_time"] = trim($postData["recipe_cooking_time"]);
        $recipeInfo["cooking_time"] = trim($postData["recipe_preparation_time"]);
        $recipe = array_filter($postData["recipe_ingredients"]);
        $this->db->trans_begin();
        try {
            if ( isset($postData["recipe_of_the_week"]) && $postData["recipe_of_the_week"] == 1 ) {
                $this->Util_model->updateTableData(["recipe_week" => 2], "ad_recipe_info", []);
                $recipeInfo["recipe_week"] = RECIPE_WEEK;
            }
            $id = $this->Util_model->insertTableData($insertData, "ad_posts", true);
            $recipeInfo["recipe_id"] = $id;
            $ingredientInfo = [];
            foreach ( $recipe as $ingredients ) {
                $ingredientInfo[] = [
                    "post_id" => $id,
                    "ingredients_text" => trim($ingredients)
                ];
            }
            $this->Util_model->insertTableData($recipeInfo, "ad_recipe_info");
            $this->Util_model->insertBatch($ingredientInfo, "ad_recipe_ingredients", true);
            $this->db->trans_commit();
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            response([
                "code" => 500,
                "message" => "Some error occurred, please try again",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
        
        response([
            "code" => SUCCESS,
            "message" => "success",
            "success" => true,
            $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
        ]);

    }

    public function checkOldPassword()
    {
        $password = $this->input->post("password");
        $email = $this->session->userdata("adelgaz_session");
        $userData = $this->Util_model->selectQuery("password", "ad_user",
         ["where" => ["email" => $email]]);

        if (  password_verify($password, $userData[0]["password"]) ) {
            response([
                "code" => SUCCESS,
                "message" => "success",
                "success" => true,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        } else {
            response([
                "code" => WRONG_PASSWORD,
                "message" => "wrong password",
                "success" => false,
                $this->security->get_csrf_token_name() => $this->security->get_csrf_hash()
            ]);
        }
    }
}

