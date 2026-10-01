<?php
$component = new OssnComponents();
$values = $component->getSettings('AllInOneAccessibility');

$defaults = array(
 'enabled'=>'1','widget_color_code'=>'#420083','widget_position'=>'bottom_right',
 'widget_icon_type'=>'aioa-icon-type-1','widget_icon_size'=>'aioa-default-icon',
 'widget_size'=>'0','is_widget_custom_position'=>'0','is_widget_custom_size'=>'0',
 'widget_icon_size_custom'=>'20','widget_position_top'=>'','widget_position_bottom'=>'',
 'widget_position_left'=>'','widget_position_right'=>'','position_x_direction'=>'to-the-right',
 'position_x_value'=>'','position_y_direction'=>'to-the-bottom','position_y_value'=>''
);
$s=array_merge($defaults,array());
if($values){
 foreach($values as $k=>$v){$s[$k]=(string)$v;}
}
if(!preg_match('/^#[0-9A-Fa-f]{6}$/',$s['widget_color_code'])) $s['widget_color_code']='#420083';
if(!in_array($s['widget_position'],array('top_left','top_center','top_right','middle_left','middle_right','bottom_left','bottom_center','bottom_right'),true)) $s['widget_position']='bottom_right';
if(!preg_match('/^aioa-icon-type-(?:[1-9]|1[0-9]|2[0-9])$/',$s['widget_icon_type'])) $s['widget_icon_type']='aioa-icon-type-1';
if(!in_array($s['widget_icon_size'],array('aioa-big-icon','aioa-medium-icon','aioa-default-icon','aioa-small-icon','aioa-extra-small-icon'),true)) $s['widget_icon_size']='aioa-default-icon';
foreach(array('widget_size','is_widget_custom_position','is_widget_custom_size') as $k) $s[$k]=$s[$k]==='1'?'1':'0';
$s['widget_icon_size_custom']=(string)max(20,min(150,(int)$s['widget_icon_size_custom']?:20));
foreach(array('widget_position_top','widget_position_bottom','widget_position_left','widget_position_right') as $k){
 $s[$k]=$s[$k]===''?'':(string)max(0,min(250,(int)$s[$k]));
}
$s['position_x_direction']=$s['widget_position_left']!==''?'to-the-left':'to-the-right';
$s['position_x_value']=$s['widget_position_left']!==''?$s['widget_position_left']:$s['widget_position_right'];
$s['position_y_direction']=$s['widget_position_top']!==''?'to-the-top':'to-the-bottom';
$s['position_y_value']=$s['widget_position_top']!==''?$s['widget_position_top']:$s['widget_position_bottom'];

$remote=array('success'=>false,'message'=>'Live settings are not available.');
if(class_exists('AioaApiClient')){
 $host=parse_url(ossn_site_url(),PHP_URL_HOST);
 if(!$host && !empty($_SERVER['HTTP_HOST'])) $host=$_SERVER['HTTP_HOST'];
 AioaApiClient::registerDomain($host?:'localhost','OSSN Administrator','no-reply@'.($host?:'localhost'));
 $remote=AioaApiClient::fetchSettings($host?:'localhost');
 if($remote['success'] && !empty($remote['settings'])){
   $remoteSettings=$remote['settings'];
   foreach(array_keys($defaults) as $k) if(isset($remoteSettings[$k])) $s[$k]=(string)$remoteSettings[$k];
   if(isset($remoteSettings['is_enabled'])) $s['enabled']=(string)$remoteSettings['is_enabled'];
   if(isset($remoteSettings['widget_enabled'])) $s['enabled']=(string)$remoteSettings['widget_enabled'];
   // Re-normalize through the local values for safe output.
   if(!preg_match('/^#[0-9A-Fa-f]{6}$/',$s['widget_color_code'])) $s['widget_color_code']='#420083';
   if(!in_array($s['widget_position'],array('top_left','top_center','top_right','middle_left','middle_right','bottom_left','bottom_center','bottom_right'),true)) $s['widget_position']='bottom_right';
   if(!preg_match('/^aioa-icon-type-(?:[1-9]|1[0-9]|2[0-9])$/',$s['widget_icon_type'])) $s['widget_icon_type']='aioa-icon-type-1';
   if(!in_array($s['widget_icon_size'],array('aioa-big-icon','aioa-medium-icon','aioa-default-icon','aioa-small-icon','aioa-extra-small-icon'),true)) $s['widget_icon_size']='aioa-default-icon';
   foreach(array('widget_size','is_widget_custom_position','is_widget_custom_size') as $k) $s[$k]=$s[$k]==='1'?'1':'0';
   $s['widget_icon_size_custom']=(string)max(20,min(150,(int)$s['widget_icon_size_custom']?:20));
   foreach(array('widget_position_top','widget_position_bottom','widget_position_left','widget_position_right') as $k) $s[$k]=$s[$k]===''?'':(string)max(0,min(250,(int)$s[$k]));
   $s['position_x_direction']=$s['widget_position_left']!==''?'to-the-left':'to-the-right';
   $s['position_x_value']=$s['widget_position_left']!==''?$s['widget_position_left']:$s['widget_position_right'];
   $s['position_y_direction']=$s['widget_position_top']!==''?'to-the-top':'to-the-bottom';
   $s['position_y_value']=$s['widget_position_top']!==''?$s['widget_position_top']:$s['widget_position_bottom'];
 }
}
echo ossn_view_form('aioa/settings',array(
 'action'=>ossn_site_url().'action/aioa/admin/settings',
 'class'=>'ossn-admin-form',
 'params'=>array('settings'=>$s,'remote'=>$remote,'icon_base'=>ossn_site_url('components/AllInOneAccessibility/assets/icons/'))
));
