<?php
if(!ossn_isAdminLoggedin()){redirect(REF);return;}

$customPosition=input('is_widget_custom_position')?'1':'0';
$customSize=input('is_widget_custom_size')?'1':'0';

$requiredFields=array(
 'widget_position','widget_icon_type','widget_icon_size',
 'widget_color_code_text','is_widget_custom_position','is_widget_custom_size'
);
if($customPosition==='1'){
 $requiredFields=array_merge($requiredFields,array('position_x_value','position_x_direction','position_y_value','position_y_direction'));
}
if($customSize==='1'){
 $requiredFields[]='widget_icon_size_custom';
}
foreach($requiredFields as $field){
 $value=input($field);
 if($value===null || $value===''){
  ossn_trigger_message(ossn_print('aioa:error:required'),'error');
  redirect(REF);
  return;
 }
}

$component=new OssnComponents();
$color=trim((string)input('widget_color_code_text'));
if($color==='')$color=trim((string)input('widget_color_code'));
$color=ltrim($color,'#');
$color=preg_replace('/[^0-9A-Fa-f]/','',$color);
if(strlen($color)!==6){ossn_trigger_message(ossn_print('aioa:error:color'),'error');redirect(REF);return;}
$position=(string)input('widget_position');
$allowed=array('top_left','top_center','top_right','middle_left','middle_right','bottom_left','bottom_center','bottom_right');
if(!in_array($position,$allowed,true))$position='bottom_right';
$iconType=(string)input('widget_icon_type');
if(!preg_match('/^aioa-icon-type-(?:[1-9]|1[0-9]|2[0-9])$/',$iconType))$iconType='aioa-icon-type-1';
$iconSize=(string)input('widget_icon_size');
if(!in_array($iconSize,array('aioa-big-icon','aioa-medium-icon','aioa-default-icon','aioa-small-icon','aioa-extra-small-icon'),true))$iconSize='aioa-default-icon';
$widgetSize=input('widget_size')==='1'?'1':'0';

$current=$component->getSettings('AllInOneAccessibility');
$current=is_object($current)?get_object_vars($current):($current?:array());
$old=array(
 'widget_position_right'=>isset($current['widget_position_right'])?$current['widget_position_right']:'',
 'widget_position_left'=>isset($current['widget_position_left'])?$current['widget_position_left']:'',
 'widget_position_top'=>isset($current['widget_position_top'])?$current['widget_position_top']:'',
 'widget_position_bottom'=>isset($current['widget_position_bottom'])?$current['widget_position_bottom']:'',
 'widget_icon_size_custom'=>isset($current['widget_icon_size_custom'])?$current['widget_icon_size_custom']:'20'
);

if($customSize==='1'){
 $iconSizeCustomRaw=input('widget_icon_size_custom');
 if(!is_numeric($iconSizeCustomRaw)||(int)$iconSizeCustomRaw<20||(int)$iconSizeCustomRaw>150){
  ossn_trigger_message(ossn_print('aioa:error:icon_size'),'error');redirect(REF);return;
 }
 $customIcon=(int)$iconSizeCustomRaw;
}else{
 $customIcon=(int)$old['widget_icon_size_custom'];
}

$left=$old['widget_position_left'];$right=$old['widget_position_right'];$top=$old['widget_position_top'];$bottom=$old['widget_position_bottom'];
if($customPosition==='1'){
 $xRaw=input('position_x_value');
 if(!is_numeric($xRaw)||(int)$xRaw<0||(int)$xRaw>250){
  ossn_trigger_message(ossn_print('aioa:error:position'),'error');redirect(REF);return;
 }
 $x=(int)$xRaw;

 $yRaw=input('position_y_value');
 if(!is_numeric($yRaw)||(int)$yRaw<0||(int)$yRaw>250){
  ossn_trigger_message(ossn_print('aioa:error:position'),'error');redirect(REF);return;
 }
 $y=(int)$yRaw;

 $xdir=(string)input('position_x_direction')==='to-the-left'?'to-the-left':'to-the-right';
 $ydir=(string)input('position_y_direction')==='to-the-top'?'to-the-top':'to-the-bottom';

 $left='';$right='';$top='';$bottom='';
 if($xdir==='to-the-left')$left=(string)$x;else$right=(string)$x;
 if($ydir==='to-the-top')$top=(string)$y;else$bottom=(string)$y;
}
$settings=array(
 'enabled'=>input('enabled')?'1':'0','widget_color_code'=>'#'.strtolower($color),
 'widget_position'=>$position,'widget_icon_type'=>$iconType,'widget_icon_size'=>$iconSize,
 'widget_size'=>$widgetSize,'is_widget_custom_position'=>$customPosition,'is_widget_custom_size'=>$customSize,
 'widget_icon_size_custom'=>(string)$customIcon,'widget_position_right'=>(string)$right,
 'widget_position_left'=>(string)$left,'widget_position_top'=>(string)$top,'widget_position_bottom'=>(string)$bottom
);
$saved=$component->setSettings('AllInOneAccessibility',$settings);
$remoteOk=true;
$remoteMessage='';
if(class_exists('AioaApiClient')){
 $host=parse_url(ossn_site_url(),PHP_URL_HOST);
 if(!$host&&!empty($_SERVER['HTTP_HOST']))$host=$_SERVER['HTTP_HOST'];
 $ur=AioaApiClient::updateSettings($host?:'localhost',$settings);
 $remoteOk=$ur['success'];
 if(!$remoteOk)$remoteMessage=$ur['message'];
}
if($saved){
 $msg=ossn_print('aioa:saved');
 if(!$remoteOk)$msg.=' '.ossn_print('aioa:remote_warning').' '.$remoteMessage;
 ossn_trigger_message($msg,'success');
}else{
 ossn_trigger_message(ossn_print('aioa:error:save'),'error');
}
redirect(REF);