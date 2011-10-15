<?php
class YupooAPI {
    public $api_key;
    public $secret;
    public $perm;
    public $used;
    private static $AUTH_URL = 'http://www.yupoo.com/services/auth/';
    private static $ENDPOINT = 'http://www.yupoo.com/api/json/';
    private static $UPLOAD_URL = 'http://www.yupoo.com/api/upload';
    public $error_code;
    public $error_msg;
    public $die_on_error;
    public $token;
    public function __construct($api_key, $secret, $perm = 'read', $die_on_error = false) {
        $this->api_key = $api_key;
        $this->secret  = $secret;
        $this->die_on_error = $die_on_error;
        $this->perm = $perm;
        $this->used = 'web';                // web / desktop / mobile
        $this->service = "yupoo";
        //  echo $api_key . ":" . $secret;
        // Call CURL as REQUET METHOD;
        /*
        require_once 'HTTP/Request.php';
        $this->req = new HTTP_Request();
        $this->req->setMethod(HTTP_REQUEST_METHOD_POST);
        */
    }
    private function api_call($method, $args) {
        $args = array_merge(array("method" => $method, "api_key" => $this->api_key), $args);
        /*
        if (!empty($this->token)) {
            $args = array_merge($args, array("auth_token" => $this->token));
        } elseif (!empty($_SESSION['phpyupoo_auth_token'])) {
            $args = array_merge($args, array("auth_token" => $_SESSION['phpyupoo_auth_token']));
        }
        */
        ksort($args);
        $auth_sig = '';
        foreach ($args as $key => $data) {
            $auth_sig .= $key . $data;
        }
        if (!empty($this->secret)) {
            $api_sig = md5($this->secret . $auth_sig);
            $args["api_sig"] = $api_sig;
        }
        $query_string = http_build_query($args);
        $ch = curl_init(YupooAPI::$ENDPOINT);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.7) Gecko/2009021906 Firefox/3.0.7");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIE, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec($ch);
        curl_close($ch);
        $res_data = json_decode($content, true);
        return $res_data;
    }
	
	public function generate_authurl(){
	    $api_sig = md5($this->secret . "api_key" . $this->api_key . "perms" . $this->perm);
        $endpoint = YupooAPI::$AUTH_URL . '?api_key='.$this->api_key.'&perms='.$this->perm.'&api_sig='.$api_sig;
		return $endpoint;
	}
	
    private function auth() {
        $api_sig = md5($this->secret . "api_key" . $this->api_key . "perms" . $this->perm);
        $endpoint = YupooAPI::$AUTH_URL . '?api_key='.$this->api_key.'&perms='.$this->perm.'&api_sig='.$api_sig;
        header("Location: $endpoint");
        exit;
    }
    public function get_frob() {
        if ($this->used == 'web') {
            $frob = isset($_GET['frob']) ? $_GET['frob'] : false;
            if (!$frob) {
                $this->auth();
                return;
            }
            return $frob;
        } else {
            // TODO: 补充两外2个用途
        }
    }
    public function get_token($frob) {
        $method = 'yupoo.auth.getToken';
        $result = $this->api_call($method, array('frob' => $frob));
        if ($result['stat'] == 'ok') {
            $this->token = $result['auth']['token'];
        } else {   
            $this->error_code = $result['err']['code'];
            $this->error_msg = $result['err']['msg'];
            $this->token = false;
        }
        return $this->token;
    }
	
	public function get_userid_by_name($username)
	{
		$method = 'yupoo.people.findByUsername';
		$args["username"] = $username;
		$result = $this->api_call($method, $args);
		return $result;
	}
	
	public function get_user_collection($userid, $page)
	{
		$method = 'yupoo.favorites.getList';
		$args["user_id"] = $userid;
		$args["page"] = $page;
		$args["per_page"] = 20;
		$result = $this->api_call($method, $args);
		return $result;
	}
	
	public function get_yupoo_recommend($page)
	{
		$method = 'yupoo.interestingness.getList';
		$args["page"] = $page;
		$args["per_page"] = 20;
		$result = $this->api_call($method, $args);
		return $result;
	}
	
	public function get_yupoo_recommend_date($page, $date)
	{
		$method = 'yupoo.interestingness.getList';
		$args["date"] = $date;
		$args["page"] = $page;
		$args["per_page"] = 20;
		$result = $this->api_call($method, $args);
		return $result;
	}
	
	public function get_photo_info($photoID)
	{
		$method = 'yupoo.photos.getInfo';
		$args["photo_id"] = $photoID;
		$result = $this->api_call($method, $args);
		return $result;
	}
	
	public function search_photo($keywords, $page)
	{
		$method = 'yupoo.photos.search';
		$args["text"] = $keywords;
		$args["page"] = $page;
		$args["per_page"] = 20;
		$result = $this->api_call($method, $args);
		return $result;
	}
	
	public function search_user($userid, $page, $token)
	{
		$method = 'yupoo.people.getPhotos';
		$args["auth_token"] = $token;
		$args["user_id"] = $userid;
		$args["page"] = $page;
		$args["per_page"] = 20;
		$result = $this->api_call($method, $args);
		return $result;
	}
	
    public function upload($photo, $args) {
        $args = array_merge(array("api_key" => $this->api_key, "format" => 'json'), $args);
        ksort($args);
        $auth_sig = '';
        foreach ($args as $key => $data) {
            $auth_sig .= $key . $data;
        }
        if (!empty($this->secret)) {
            $api_sig = md5($this->secret . $auth_sig);
            $args["api_sig"] = $api_sig;
        }
        if (is_file($photo)) {
            $args['photo'] = "@$photo";
        }
        $ch = curl_init(YupooAPI::$UPLOAD_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.7) Gecko/2009021906 Firefox/3.0.7");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIE, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $content = curl_exec($ch);
        curl_close($ch);
        $res_data = json_decode($content, true);
        return $res_data;
    }
    public function get_size($args, $size = 'medium') {
        $method = "yupoo.photos.getSizes";
        $result = $this->api_call($method, $args);
        if ($result['stat'] == 'ok') {
            $sizes = $result['sizes'];
            foreach ($sizes as $ss) {
                if ( strtolower($ss['label'])  == 'medium') {
                    $default_img = $ss;
                }
                if ( strtolower($ss['label'])  == strtolower($size)) {
                    return $ss;
                }
            }
            return $default_img;
        } else {
            $this->error_code = $result['err']['code'];
            $this->error_msg = $result['err']['msg'];
        }
        return false;
    }
    public function get_photo_url($photo, $size= 'm') {
    }  
    function people_getUploadStatus()
    {
        /* Requires Authentication */
        $this->request("yupoo.people.getUploadStatus");
        return $this->parsed_response ? $this->parsed_response['rsp']['user'] : false;
    }
    function photos_addTags ($photo_id, $tags)
    {
        $this->request("yupoo.photos.addTags", array("photo_id"=>$photo_id, "tags"=>$tags), TRUE);
        return $this->parsed_response ? true : false;
    }
    function photos_delete($photo_id)
    {
        $this->request("yupoo.photos.delete", array("photo_id"=>$photo_id), TRUE);
        return $this->parsed_response ? true : false;
    }
    public function get_error_msg() {
        return $this->error_msg;
    }
    public function get_error_code() {
        return $this->error_code;
    }
}