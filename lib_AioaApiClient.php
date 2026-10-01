<?php
class AioaApiClient {
    const API_BASE = 'https://ada.skynettechnologies.us/api';
    public static function post($path, array $payload) {
        if (!function_exists('curl_init')) return array('success'=>false,'message'=>'PHP cURL is not available.','data'=>array());
        $ch=curl_init(rtrim(self::API_BASE,'/').'/'.ltrim($path,'/'));
        curl_setopt_array($ch,array(
            CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_CONNECTTIMEOUT=>5,
            CURLOPT_TIMEOUT=>15,CURLOPT_FOLLOWLOCATION=>true,CURLOPT_MAXREDIRS=>3,
            CURLOPT_SSL_VERIFYPEER=>false,CURLOPT_SSL_VERIFYHOST=>0,
            CURLOPT_HTTPHEADER=>array('Accept: application/json','Content-Type: application/x-www-form-urlencoded','User-Agent: AIOA-OSSN/1.2'),
            CURLOPT_POSTFIELDS=>http_build_query($payload,'','&',PHP_QUERY_RFC3986)
        ));
        $raw=curl_exec($ch); $err=curl_error($ch); $status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
        if($raw===false) return array('success'=>false,'message'=>'Accessibility service could not be reached: '.$err,'data'=>array());
        $data=json_decode((string)$raw,true); if(!is_array($data))$data=array();
        return array('success'=>$status>=200&&$status<300,'status'=>$status,'data'=>$data,'message'=>(string)($data['msg']??$data['message']??($status>=200&&$status<300?'OK':'Request failed.')));
    }
    public static function registerDomain($domain,$name,$email){
        return self::post('/add-user-domain',array(
            'name'=>$name?:'Dear Customer','email'=>$email?:'no-reply@'.$domain,
            'company_name'=>'','website'=>base64_encode($domain),'package_type'=>'free-widget',
            'start_date'=>gmdate('Y-m-d H:i:s'),'end_date'=>'','price'=>'','discount_price'=>'0',
            'platform'=>'OSSN','api_key'=>'','is_trial_period'=>'','is_free_widget'=>'1',
            'bill_address'=>'','country'=>'','state'=>'','city'=>'','post_code'=>'',
            'transaction_id'=>'','subscr_id'=>'','payment_source'=>'','no_required_eu'=>'1'
        ));
    }
    public static function fetchSettings($domain){
        $r=self::post('/widget-settings',array('website_url'=>$domain));
        if($r['success']){
            $d=$r['data']['Data']??$r['data']['data']??array();
            $r['settings']=is_array($d)?$d:array();
        } else $r['settings']=array();
        return $r;
    }
    public static function updateSettings($domain,array $s){
        $fields=array('widget_position','is_widget_custom_position','is_widget_custom_size','widget_color_code',
            'widget_icon_type','widget_icon_size','widget_size','widget_icon_size_custom',
            'widget_position_right','widget_position_left','widget_position_top','widget_position_bottom');
        $p=array('u'=>$domain);
        foreach($fields as $f)$p[$f]=isset($s[$f])?(string)$s[$f]:'';
        return self::post('/widget-setting-update-platform',$p);
    }
}
