<?php
require_once '../oauth.php';
require_once 'doubanoauth.php';
/** 
 * 豆瓣操作类 
 * 
 * 
 */ 
class DoubanClient 
{ 
    /** 
     * 构造函数 
     *  
     * @access public 
     * @param mixed $akey 应用APP KEY 
     * @param mixed $skey 应用APP SECRET 
     * @param mixed $accecss_token OAuth认证返回的token 
     * @param mixed $accecss_token_secret OAuth认证返回的token secret 
     * @return void 
     */ 
    function __construct( $akey , $skey , $accecss_token , $accecss_token_secret ) 
    { 
        $this->oauth = new DoubanOAuth( $akey , $skey , $accecss_token , $accecss_token_secret ); 
    } 
	
	function verify_credentials()
	{
	  return $this->oauth->get('http://api.douban.com/people@me?alt=json'); 
	}

    /** 
     * 最新公共微博 
     *  
     * @access public 
     * @return array 
     */ 
    function public_timeline() 
    { 
        return $this->oauth->get('http://api.t.sina.com.cn/statuses/public_timeline.json'); 
    } 

    /** 
     * 最新关注人微博 
     *  
     * @access public 
     * @return array 
     */ 
    /*function friends_timeline() 
    { 
        return $this->home_timeline(); 
    }*/
	
	function friends_timeline($page = 1 , $count = 20) 
    { 
        return $this->request_with_pager( 'http://api.t.sina.com.cn/statuses/friends_timeline.json' , $page , $count );  
    }
} 

?>