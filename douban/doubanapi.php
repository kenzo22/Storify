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
	
	/*$param = array(); 
        if( is_numeric( $uid_or_name ) ) $param['target_id'] = $uid_or_name; 
        else $param['target_screen_name'] = $uid_or_name; 

        return $this->oauth->get( 'http://api.t.sina.com.cn/friendships/show.json' , $param ); */
	
	function verify_credentials()
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/people/%40me' , $param); 
	}
	
	function get_user()
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/people/ahbei' , $param); 
	}
	
	function get_comment($commentID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/review/'.$commentID , $param); 
	}
	
	function get_book($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/book/subject/'.$subjectID , $param); 
	}
	
	function get_movie($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/movie/subject/'.$subjectID , $param);  
	}
	
	function get_music($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/music/subject/'.$subjectID , $param); 
	}
	
	function get_event($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  return $this->oauth->get('http://api.douban.com/event/'.$subjectID , $param); 
	}
	
	function search_book($keywords)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['q'] = $keywords;
	  $param['start-index'] = 1;
	  $param['max-results'] = 10;
	  return $this->oauth->get('http://api.douban.com/book/subjects' , $param); 
	}
	
	function search_movie($keywords)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['q'] = $keywords;
	  $param['start-index'] = 1;
	  $param['max-results'] = 10;
	  return $this->oauth->get('http://api.douban.com/movie/subjects' , $param); 
	}
	
	function search_music($keywords)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['q'] = $keywords;
	  $param['start-index'] = 1;
	  $param['max-results'] = 10;
	  return $this->oauth->get('http://api.douban.com/music/subjects' , $param); 
	}
	
	function search_event($keywords)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['q'] = $keywords;
	  $param['location'] = 'all';
	  $param['start-index'] = 1;
	  $param['max-results'] = 10;
	  return $this->oauth->get('http://api.douban.com/events' , $param); 
	}
	
	function search_book_reviews($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['start-index'] = 1;
	  $param['max-results'] = 10;
	  return $this->oauth->get('http://api.douban.com/book/subject/'.$subjectID.'/reviews' , $param); 
	}
	
	function search_movie_reviews($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['start-index'] = 1;
	  $param['max-results'] = 10;
	  return $this->oauth->get('http://api.douban.com/movie/subject/'.$subjectID.'/reviews' , $param); 
	}
	
	function search_music_reviews($subjectID)
	{
	  $param = array();
	  $param['alt'] = 'json';
	  $param['start-index'] = 1;
	  $param['max-results'] = 10;
	  return $this->oauth->get('http://api.douban.com/music/subject/'.$subjectID.'/reviews' , $param); 
	}
} 

?>