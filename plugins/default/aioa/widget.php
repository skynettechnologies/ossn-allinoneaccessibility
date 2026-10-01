<?php
$component = new OssnComponents();
$settings = $component->getSettings('AllInOneAccessibility');
$enabled = true;
$color = '#420083';
$position = 'bottom_right';
if ($settings) {
    $settings = is_object($settings) ? get_object_vars($settings) : $settings;
    if (isset($settings['enabled'])) $enabled = ((string)$settings['enabled'] === '1');
    if (!empty($settings['widget_color_code'])) $color = $settings['widget_color_code'];
    if (!empty($settings['widget_position'])) $position = $settings['widget_position'];
}
$allowed_positions=array('top_left','top_center','top_right','middle_left','middle_right','bottom_left','bottom_center','bottom_right');
if(!in_array($position,$allowed_positions,true))$position='bottom_right';
if(!preg_match('/^#[0-9A-Fa-f]{6}$/',$color))$color='#420083';
if($enabled){
 $script_url='https://www.skynettechnologies.com/accessibility/js/all-in-one-accessibility-js-widget-minify.js';
 $query=http_build_query(array('colorcode'=>$color,'position'=>$position),'','&',PHP_QUERY_RFC3986);
 ?>
<script id="aioa-adawidget" src="<?php echo htmlspecialchars($script_url.'?'.$query,ENT_QUOTES,'UTF-8'); ?>" async></script>
<?php } ?>
