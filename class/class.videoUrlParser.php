<?php
/**
 * Video 
 * 
 * @package 
 * @version 1.1beta
 * @copyright 2005-2011 HDJ.ME 
 * @author Dijia Huang <huangdijia@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 *
 * @version 1.2
 * @author icecoffe
 *
 * Usage
 * require_once "VideoUrlParser.class.php";
 * youku
 * $urls[] = "http://v.youku.com/v_playlist/f16819482o1p0.html";
 * $urls[] = "http://v.youku.com/v_show/id_XMzM1NDkxNDMy.html";
 * tudou
 * $urls[] = "http://www.tudou.com/programs/view/Ux14Ia3EFyk/";
 * $urls[] = "http://www.tudou.com/playlist/p/l14689911.html";
 * ku6
 * $urls[] = "http://v.ku6.com/show/1BcHjLqLxKrVmFOhZxcxdQ...html";
 * $urls[] = "http://v.ku6.com/special/show_6565656/fJbjLU2xBCM92JCjtD-NEA...html"
 * 56
 * $urls[] = "http://www.56.com/u24/v_NjYxNDE4Njk.html"
 * $urls[] = "http://www.56.com/w66/play_album-aid-9818891_vid-NjYxNDI4OTI.html";
 *
 * $urls[] = "http://www.letv.com/ptv/vplay/1168109.html";
 * $urls[] = "http://video.sina.com.cn/v/b/46909166-1290055681.html";
 *
 * //优酷
 * http://v.youku.com/v_show/id_XMjU0NjY4OTEy.html
 * <embed src="http://player.youku.com/player.php/sid/XMjU0NjY4OTEy/v.swf" quality="high" width="480" height="400" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>
 * 
 * //酷六
 * http://v.ku6.com/special/show_3917484/x0BMXAbgZdQS6FqN.html
 * <embed src="http://player.ku6.com/refer/x0BMXAbgZdQS6FqN/v.swf" quality="high" width="480" height="400" align="middle" allowScriptAccess="always" allowfullscreen="true" type="application/x-shockwave-flash"></embed>
 * 
 * //土豆
 * http://www.tudou.com/playlist/p/a65929.html?iid=74905844
 * <embed src="http://www.tudou.com/l/A_0urj-Geec/&iid=74905844/v.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="480" height="400"></embed>
 * 
 * //56
 * http://www.56.com/u24/v_NjYxNDE4Njk.html
 * <embed src='http://player.56.com/v_NjYxNDE4Njk.swf'  type='application/x-shockwave-flash' allowFullScreen='true' width='480' height='405' allowNetworking='all' allowScriptAccess='always'></embed>
 * 
 * //新浪播客
 * http://video.sina.com.cn/v/b/46909166-1290055681.html
 * <embed src="http://you.video.sina.com.cn/api/sinawebApi/outplayrefer.php/vid=46909166_1290055681_b0K1GHEwDWbK+l1lHz2stqkP7KQNt6nki2O0u1ehIwZYQ0/XM5GdZNQH6SjQBtkEqDhAQJ42dfcn0Rs/s.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="ssss" allowFullScreen="true" allowScriptAccess="always" width="480" height="370"></embed>
 * 
 * //乐视
 * http://www.letv.com/ptv/vplay/1168109.html
 * <embed src="http://i3.imgs.letv.com/player/swfPlayer.swf?id=1168109&host=app.letv.com&vstatus=1&AP=1&logoMask=0&isShowP2p=0&autoplay=true" quality="high" scale="NO_SCALE" wmode="opaque" bgcolor="#000000" width="480" height="388" name="FLV_player" align="middle" allowscriptaccess="always" allowfullscreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
 */

class VideoUrlParser
{
    const USER_AGENT = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko)
        Chrome/8.0.552.224 Safari/534.10";
    const CHECK_URL_VALID = "/(youku\.com|tudou\.com|ku6\.com|56\.com|letv\.com|video\.sina\.com\.cn|tv\.sohu\.com)/";

    /**
     * parse 
     * 
     * @param string $url 
     * @param mixed $createObject 
     * @static
     * @access public
     * @return void
     */
    public function parse($url='', $createObject=true){
        $lowerurl = strtolower($url);
        preg_match(self::CHECK_URL_VALID, $lowerurl, $matches);
        if(!$matches) {
            $data['errorcode']=2;
            return $data;
        }
        switch($matches[1]){
        case 'youku.com':
            $data = self::_parseYouku($url);
            break;
        case 'tudou.com':
            $data = self::_parseTudou($url);
            break;
        case 'ku6.com':
            $data = self::_parseKu6($url);
            break;
        case '56.com':
            $data = self::_parse56($url);
            break;
        case 'letv.com':
            $data = self::_parseLetv($url);
            break;
        case 'video.sina.com.cn':
            $data = self::_parseSina($url);
            break;
        case 'sohu.com':
            $data = self::_parseSohu($url);
            break;
        case 'v.qq.com':
            $data = self::_parseQq($url);
            break;
        default:
            $data['errorcode']=2;
        }
        //if($data && $createObject) 
            //$data['object'] = "<embed src=\"{$data['swf']}\" quality=\"high\" width=\"480\" height=\"400\" align=\"middle\" allowNetworking=\"all\" allowScriptAccess=\"always\" type=\"application/x-shockwave-flash\"></embed>";
        return $data;
    }

    private function _embedSrc($swf){
        return '<embed src="'.$swf.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" allowNetworking="all"  width="420" height="340"></embed>';
    }

    /**
     * 优酷网 
     * http://v.youku.com/v_show/id_XMjI4MDM4NDc2.html
     * http://player.youku.com/player.php/sid/XMjU0NjI2Njg4/v.swf
     */ 
    private function _parseYouku($url){
        preg_match("#v_playlist\/#", $url, $mat);
        if($mat){
            $html = self::_cget($url);
            if(!$html){
                $data['errorcode']=1;
                return $data;
            }
            preg_match('#id="link1" value="(.*?)"#',$html,$matches);
            if(!$matches){
                $data['errorcode']=1;
                return $data;
            }
            $url = $matches[1];
        }
        preg_match("#id\_(\w+)#", $url, $videoID);
        if(!$videoID){
            $data['errorcode']=1;
            return $data;
        }

        $html = self::_cget($url);
        preg_match('#show_info_short">([^<]+)<#',$html,$desc);
        if(!empty($desc[1]))
            $data['desc']=$desc[1];
        if(!$data['desc']){
            preg_match('#class="info" id="long" style="display:none;">\s+<div class="item">([^<]*)<\/div>#',$html,$mach);
            if($mach)
                $data['desc']=$mach[1];
        }

        preg_match('#<embed.*<\/embed>#',$html,$embedcode);
        if(!empty($embedcode)){
            $patten[0]='#width="\d+"#';
            $patten[1]='#height="\d+"#';
            $replace[0]='width="420"';
            $replace[1]='height="340"';
            $data['embedcode']=preg_replace($patten,$replace,$embedcode[0]);
        }

        $link = "http://v.youku.com/player/getPlayList/VideoIDS/{$videoID[1]}/timezone/+08/version/5/source/out?password=&ran=2513&n=3";

        $retval = self::_cget($link);
        if ($retval) {
            $json = json_decode($retval, true);
            $data['errorcode']=0;
            $data['host']='youku';
            $data['img'] = $json['data'][0]['logo'];
            $data['title'] = $json['data'][0]['title'];
            //$data['url'] = $url;
            //$data['swf'] = "http://player.youku.com/player.php/sid/{$videoID[1]}/v.swf";
            return $data;
        } else {
            $data['errorcode']=1;
            return $data;
        }
    }

    /**
     * 土豆网
     * http://www.tudou.com/programs/view/Wtt3FjiDxEE/
     * http://www.tudou.com/v/Wtt3FjiDxEE/v.swf
     * 
     * http://www.tudou.com/playlist/p/a65718.html?iid=74909603
     * http://www.tudou.com/l/G5BzgI4lAb8/&iid=74909603/v.swf
     */
    private function _parseTudou($url){

        $data['host']="tudou";
        preg_match("#view/([-\w]+)/?#", $url, $matches);

        $html = self::_cget($url,true);
        if (empty($matches)) {
            if (strpos($url, "/playlist/") == false) {
                $data['errorcode']=1;
                return $data;
            }

            if(strpos($url, 'iid=') !== false){
                $quarr = explode("iid=", $lowerurl);
                if (empty($quarr[1]))  {
                    $data['errorcode']=1;
                    return $data;
                };
            }elseif(preg_match("#p\/l(\d+)\.#", $lowerurl, $quarr)){
                if (empty($quarr[1])){
                    $data['errorcode']=1;
                    return $data;
                }
            }

            preg_match("/lid_code\s*=(?:\s*lcode\s*)=\s*['\"](.*)['\"]/", $html, $matches);
            $icode = $matches[1];

            preg_match("/iid\s*=\s*.*?\|\|\s*(\d+)/", $html, $matches);
            $iid = $matches[1];

            preg_match("/(iid:$iid.*?)\}/sx", $html, $matches);
            $str = str_replace("\n", "", $matches[1]);
            $str = str_replace(" ", "", $str);
            preg_match('#title:"(.*?)".*?pic:"(.*?)"#s',$str,$info);
            if(!$info){
                $data['errorcode']=1;
                return $data;
            }
            $data['errorcode']=0;
            $data['title'] = iconv('gbk','utf-8',$info[1]);
            $data['img'] = $info[2];
            $data['url'] = $url;
            $data['swf'] = "http://www.tudou.com/l/{$icode}/&iid={$iid}/v.swf";
            $data['embedcode']=self::_embedSrc($data['swf']);
            preg_match('#shortDesc:"(.*?)"#',$str,$desc);
            if($desc)
                $data['desc']=iconv('gbk','utf-8',$desc[1]);
            return $data;
        }

        $data['swf'] = "http://www.tudou.com/v/{$matches[1]}/v.swf";
        preg_match('#title = "(.+?)".*?desc = "(.*?)".*?bigItemUrl = "([\w:\/\.]+)"#s',$html,$ele);
        if($ele){
            $data['errorcode']=0;
            $data['img'] = $ele[3];
            $data['title'] = iconv('GBK','UTF-8',$ele[1]);      
            if($ele[2]!='')
                $data['desc'] = iconv('GBK','UTF-8',$ele[2]);
            $data['embedcode']=self::_embedSrc($data['swf']);
        }else{
            $data['errorcode']=1;
        }
        /*  
        $host = "www.tudou.com";
        $path = "/v/{$matches[1]}/v.swf";

        $ret = self::_fsget($path, $host);
        
        if (preg_match("#\nLocation: (.*)\n#", $ret, $mat)) {
            parse_str(parse_url(urldecode($mat[1]), PHP_URL_QUERY));

            $data['img'] = $snap_pic;
            $data['title'] = $title;
            $data['url'] = $url;
            $data['swf'] = "http://www.tudou.com/v/{$matches[1]}/v.swf";
            $data['embedcode']='<embed src="'.$data['swf'].'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="420" height="340"></embed>';

            return $data;
        }
        */

        return $data;
    }

    /**
     * 酷6网 
     * http://v.ku6.com/film/show_520/3X93vo4tIS7uotHg.html
     * http://player.ku6.com/refer/3X93vo4tIS7uotHg/v.swf
     */
    private function _parseKu6($url){
        preg_match("#/([-\w\.]+)\.html#", $url, $matches);

        if (empty($matches)) return false;

        $link="http://v.ku6.com/fetchVideo4Player/{$matches[1]}.html";
        $retval = self::_cget($link);

        if ($retval) {
            $json = json_decode($retval, true);
            
            $data['errorcode']=0;
            $data['img'] = $json['data']['picpath'];
            $data['title'] = $json['data']['t'];
            $data['url'] = $url;
            $data['swf'] = "http://player.ku6.com/refer/{$matches[1]}/v.swf";
            $data['embedcode']=self::_embedSrc($data['swf']);
            $data['host']='ku6';
            
            $content=self::_cget($url);
            if(preg_match("#infoBox.*?>[\d\D]+?<span>(.*?)</span>#",$content,$matches))
                $data['desc']=iconv('GBK','UTF-8',$matches[1]);
        } else {
            $data['errorcode']=1;
        }
        return $data;
    }

    /**
     * 56网
     * http://www.56.com/u73/v_NTkzMDcwNDY.html
     * http://player.56.com/v_NTkzMDcwNDY.swf
     */
    private function _parse56($url){
        preg_match("#(?:/v_|vid-)(\w+)\.html#", $url, $matches);

        if (empty($matches)) return false;

        $link="http://vxml.56.com/json/{$matches[1]}/?src=out";
        $retval = self::_cget($link);

        if ($retval) {
            $json = json_decode($retval, true);

            $data['errorcode']=0;
            $data['img'] = $json['info']['img'];
            $data['title'] = $json['info']['Subject'];
            $data['url'] = $url;
            $data['swf'] = "http://player.56.com/v_{$matches[1]}.swf";
            $data['embedcode'] = self::_embedSrc($data['swf']);
        } else {
            $data['errorcode'] = 1;
        } 
        $content=self::_cget($url);
        if(preg_match("#videoinfo_raw\">([\d\D]*?)</span>#",$content,$matches)){
            if($matches[1]){
                $data['desc'] = "简介：".preg_replace("#<br \s*/?>#"," ",$matches[1]);
            }
        }
        $data['host']="56";
        return $data;
    }

    /**
     * 乐视网 
     * http://www.letv.com/ptv/vplay/1168109.html
     * http://www.letv.com/player/x1168109.swf
     */
    private function _parseLetv($url){
        $html = self::_fget($url);
        preg_match("#http://v.t.sina.com.cn/([^'\"]*)#", $html, $matches);

        parse_str(parse_url(urldecode($matches[0]), PHP_URL_QUERY));

        preg_match("#vplay/(\d+)#", $url, $matches);
        $data['img'] = $pic;
        $data['title'] = $title;
        $data['url'] = $url;
        $data['swf'] = "http://www.letv.com/player/x{$matches[1]}.swf";

        return $data;
    }

    // 搜狐TV http://my.tv.sohu.com/u/vw/5101536
    private function _parseSohu($url){
        preg_match("#vw/(\d+)#", $url, $matches);
        break;
    }
        
    /*
     * 新浪播客
     * http://video.sina.com.cn/v/b/48717043-1290055681.html
     * http://you.video.sina.com.cn/api/sinawebApi/outplayrefer.php/vid=48717043_1290055681_PUzkSndrDzXK+l1lHz2stqkP7KQNt6nki2O0u1ehIwZYQ0/XM5GdatoG5ynSA9kEqDhAQJA4dPkm0x4/s.swf
     */
    private function _parseSina($url){
        break;
        $content=self::_cget($url);
        $data['host']='video.sina.com.cn';
        if(preg_match("#SCOPE\s*=\s*(\{[\d\D]+?)</script>#",$content,$matches)){
            if(preg_match("#title:\'(.*?)\'#",$matches[1],$title)){
                $data['title']=$title[1];
            }
            if(preg_match("#pic:\'(.*?)\'#",$matches[1],$pic)){
                $data['pic']=$pic[1];
            }
            $data['url']=$url;
            if(preg_match("#swfOutsideUrl:\'(.*?)\'#",$matches[1],$swfurl)){
                $data['swf']=$swfurl[1];
            }
            $data['embedcode']='<div><object id="ssss" width="420" height="340" ><param name="allowScriptAccess" value="always" /><embed pluginspage="http://www.macromedia.com/go/getflashplayer" src="'.$data['swf'].'" type="application/x-shockwave-flash" name="ssss" allowFullScreen="true" allowScriptAccess="always" width="480" height="370"></embed></object></div>';
            if(preg_match("#class=\"videoContent\">\s*?<p>(.*?)</p>#",$content,$matches)){
                $data['desc']=$matches[1];
            }
            $data['error_code']=0;
        }else{
            $data['error_code']=1;
        }
        return $data;
    }

    /*
     * 通过 file_get_contents 获取内容
     */
    private function _fget($url=''){
        if(!$url) return false;
        return file_get_contents($url);
    }

    /*
     * 通过 fsockopen 获取内容
     */
    private function _fsget($path='/', $host='', $user_agent=''){
        if(!$path || !$host) return false;
        $user_agent = $user_agent ? $user_agent : self::USER_AGENT;

        $out = <<<HEADER
GET $path HTTP/1.1
Host: $host
User-Agent: $user_agent
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-cn,zh;q=0.5
Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n\r\n
HEADER;
        $fp = @fsockopen($host, 80, $errno, $errstr, 10);
        if ($fp) {
            fputs($fp, $out);
            while ( !feof($fp) ) {
                $ret .= fgets($fp, 1024);
            }
            fclose($fp);
            return $ret;
        }
        return false;
    }

    /*
     * 通过 curl 获取内容
     */
    private function _cget($url='', $gzip=false, $user_agent=''){
        if(!$url) return ;

        $user_agent = $user_agent ? $user_agent : self::USER_AGENT;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        if(strlen($user_agent)) curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        if($gzip)
            curl_setopt($ch,CURLOPT_ENCODING,'gzip');

        $ret=curl_exec($ch);

        if(curl_errno($ch)){
            curl_close($ch);
            return false;
        }else{
            curl_close($ch);
            if(!is_string($ret) || !strlen($ret)){
                return false;
            }
            return $ret;
        }
    }
}

?>

