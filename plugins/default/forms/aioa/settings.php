<?php
$settings = isset($params['settings']) && is_array($params['settings']) ? $params['settings'] : array();
$iconBase = isset($params['icon_base']) ? $params['icon_base'] : ossn_site_url('components/AllInOneAccessibility/assets/icons/');
$remote = isset($params['remote']) ? $params['remote'] : array('success'=>true);
$positions = array(
 'top_left'=>ossn_print('aioa:position:top_left'),'top_center'=>ossn_print('aioa:position:top_center'),
 'top_right'=>ossn_print('aioa:position:top_right'),'middle_left'=>ossn_print('aioa:position:middle_left'),
 'middle_right'=>ossn_print('aioa:position:middle_right'),'bottom_left'=>ossn_print('aioa:position:bottom_left'),
 'bottom_center'=>ossn_print('aioa:position:bottom_center'),'bottom_right'=>ossn_print('aioa:position:bottom_right')
);
$iconSizes=array(
 'aioa-big-icon'=>array('label'=>'Big','px'=>62),'aioa-medium-icon'=>array('label'=>'Medium','px'=>54),
 'aioa-default-icon'=>array('label'=>'Default','px'=>46),'aioa-small-icon'=>array('label'=>'Small','px'=>38),
 'aioa-extra-small-icon'=>array('label'=>'Extra Small','px'=>30)
);
$e=function($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');};
?>
<link rel="stylesheet" href="<?php echo $e(ossn_site_url('components/AllInOneAccessibility/assets/css/settings.css')); ?>">
<script src="<?php echo $e(ossn_site_url('components/AllInOneAccessibility/assets/js/settings.js')); ?>"></script>
<div class="aioa-settings">
<?php if(!$remote['success']): ?>
<div class="aioa-alert aioa-alert-warning"><strong>Live settings:</strong> <?php echo $e(isset($remote['message'])?$remote['message']:'The AIOA API could not be reached.'); ?><br>Current values are shown below. Saving will keep the OSSN enable/disable setting locally.</div>
<?php endif; ?>
<div class="aioa-card">
<h1 class="aioa-settings-title"><img src="<?php echo $e($iconBase.'all-in-one-accessibility-logo.svg'); ?>" alt="All in One Accessibility"></h1>
<div class="aioa-note"><strong>NOTE: Currently, All in One Accessibility is dedicated to enhancing accessibility specifically for websites and online stores.</strong></div>
<div class="aioa-note"><p>Please <b><a href="https://ada.skynettechnologies.us/trial-subscription" target="_blank" rel="noopener">Upgrade</a></b> to full version of All in One Accessibility Pro with 10 days free trial.</p></div>

<form method="post" action="<?php echo $e(ossn_site_url('action/aioa/admin/settings')); ?>">
<div class="aioa-section">
<label class="aioa-field-label">Widget status</label>
<label class="aioa-toggle"><input type="checkbox" name="enabled" value="1" <?php echo !empty($settings['enabled'])?'checked':''; ?>> Enable All in One Accessibility widget</label>
</div>

<div class="aioa-section">
<label class="aioa-field-label" for="aioa-color-text">Hex color code</label>
<div class="aioa-color-row">
<input type="color" name="widget_color_code" id="aioa-color" class="aioa-color" value="<?php echo $e($settings['widget_color_code']); ?>" aria-label="Pick widget color">
<input type="text" name="widget_color_code_text" id="aioa-color-text" class="aioa-input" maxlength="7" value="<?php echo $e($settings['widget_color_code']); ?>" aria-describedby="aioa-color-text-error">
</div>
<span id="aioa-color-text-error" class="aioa-field-error aioa-hidden" role="alert"></span>
<p class="aioa-help">You can customize the ADA Widget color. For example: #FF5733</p>
</div>

<div class="aioa-section">
<label class="aioa-field-label">Select position type:</label>
<div class="aioa-radio-group">
<label class="aioa-radio-inline"><input type="radio" name="is_widget_custom_position" value="0" id="aioa-pos-type-fix" <?php echo $settings['is_widget_custom_position']!=='1'?'checked':''; ?>> Fix position</label>
<label class="aioa-radio-inline"><input type="radio" name="is_widget_custom_position" value="1" id="aioa-pos-type-custom" <?php echo $settings['is_widget_custom_position']==='1'?'checked':''; ?>> Custom position</label>
</div>
<div id="aioa-fixed-position-container" class="<?php echo $settings['is_widget_custom_position']==='1'?'aioa-hidden':''; ?>">
<label class="aioa-field-label">Where would you like to place the accessibility icon on your site?</label>
<div class="aioa-grid">
<?php foreach($positions as $value=>$label): ?>
<div class="aioa-cell"><label><input type="radio" name="widget_position" value="<?php echo $e($value); ?>" <?php echo $settings['widget_position']===$value?'checked':''; ?>> <?php echo $e($label); ?></label></div>
<?php endforeach; ?>
</div>
</div>
<div id="aioa-custom-position-controls" class="aioa-reveal <?php echo $settings['is_widget_custom_position']!=='1'?'aioa-hidden':''; ?>">
<div class="aioa-pos-row"><div class="aioa-value-input"><input type="number" name="position_x_value" id="aioa-pos-x" min="0" max="250" value="<?php echo $e($settings['position_x_value']); ?>" aria-describedby="aioa-pos-x-error"><span class="aioa-unit">PX</span></div>
<select name="position_x_direction"><option value="to-the-right" <?php echo $settings['position_x_direction']==='to-the-right'?'selected':''; ?>>To the right</option><option value="to-the-left" <?php echo $settings['position_x_direction']==='to-the-left'?'selected':''; ?>>To the left</option></select></div>
<span id="aioa-pos-x-error" class="aioa-field-error aioa-hidden" role="alert"></span>
<div class="aioa-pos-row"><div class="aioa-value-input"><input type="number" name="position_y_value" id="aioa-pos-y" min="0" max="250" value="<?php echo $e($settings['position_y_value']); ?>" aria-describedby="aioa-pos-y-error"><span class="aioa-unit">PX</span></div>
<select name="position_y_direction"><option value="to-the-bottom" <?php echo $settings['position_y_direction']==='to-the-bottom'?'selected':''; ?>>To the bottom</option><option value="to-the-top" <?php echo $settings['position_y_direction']==='to-the-top'?'selected':''; ?>>To the top</option></select></div>
<span id="aioa-pos-y-error" class="aioa-field-error aioa-hidden" role="alert"></span>
<p class="aioa-help">0 - 250px are permitted values</p>
</div>
</div>

<div class="aioa-section">
<label class="aioa-field-label">Select Widget Size:</label>
<div class="aioa-radio-group">
<label class="aioa-radio-inline"><input type="radio" name="widget_size" value="0" <?php echo $settings['widget_size']==='0'?'checked':''; ?>> Regular Size</label>
<label class="aioa-radio-inline"><input type="radio" name="widget_size" value="1" <?php echo $settings['widget_size']==='1'?'checked':''; ?>> Oversize</label>
</div><p class="aioa-help">It only works on desktop view.</p>
</div>

<div class="aioa-section">
<label class="aioa-field-label">Select icon type:</label>
<div class="aioa-icon-grid">
<?php for($i=1;$i<=29;$i++): $value='aioa-icon-type-'.$i; ?>
<div class="aioa-option"><input type="radio" name="widget_icon_type" id="<?php echo $e($value); ?>" value="<?php echo $e($value); ?>" <?php echo $settings['widget_icon_type']===$value?'checked':''; ?>><label for="<?php echo $e($value); ?>" class="aioa-icon-swatch"><img src="<?php echo $e($iconBase.$value.'.svg'); ?>" alt="Icon type <?php echo $i; ?>" loading="lazy"></label></div>
<?php endfor; ?>
</div></div>

<div class="aioa-section aioa-icon-sizes">
<label class="aioa-field-label">Select icon size type:</label>
<div class="aioa-radio-group">
<label class="aioa-radio-inline"><input type="radio" name="is_widget_custom_size" value="0" id="aioa-size-type-fix" <?php echo $settings['is_widget_custom_size']!=='1'?'checked':''; ?>> Fix icon size</label>
<label class="aioa-radio-inline"><input type="radio" name="is_widget_custom_size" value="1" id="aioa-size-type-custom" <?php echo $settings['is_widget_custom_size']==='1'?'checked':''; ?>> Custom icon size</label>
</div>
<div id="aioa-fixed-size-container" data-icon-base="<?php echo $e($iconBase); ?>" class="<?php echo $settings['is_widget_custom_size']==='1'?'aioa-hidden':''; ?>">
<label class="aioa-field-label">Select icon size for Desktop:</label>
<div class="aioa-icon-grid">
<?php foreach($iconSizes as $value=>$info): ?>
<div class="aioa-option"><input type="radio" name="widget_icon_size" id="size-<?php echo $e($value); ?>" value="<?php echo $e($value); ?>" <?php echo $settings['widget_icon_size']===$value?'checked':''; ?>><label for="size-<?php echo $e($value); ?>" class="aioa-icon-swatch"><img class="aioa-icon-size-preview" src="<?php echo $e($iconBase.$settings['widget_icon_type'].'.svg'); ?>" style="width:<?php echo (int)$info['px']; ?>px;height:<?php echo (int)$info['px']; ?>px" data-size-label="<?php echo $e($info['label']); ?>" alt="<?php echo $e($info['label']); ?> icon size" loading="lazy"></label></div>
<?php endforeach; ?>
</div></div>
<div id="aioa-custom-size-controls" class="aioa-reveal <?php echo $settings['is_widget_custom_size']!=='1'?'aioa-hidden':''; ?>">
<label class="aioa-field-label">Select exact icon size:</label>
<div class="aioa-pos-row"><div class="aioa-value-input"><input type="number" name="widget_icon_size_custom" id="aioa-icon-size-custom" min="20" max="150" value="<?php echo $e($settings['widget_icon_size_custom']); ?>" aria-describedby="aioa-icon-size-custom-error"><span class="aioa-unit">PX</span></div></div>
<span id="aioa-icon-size-custom-error" class="aioa-field-error aioa-hidden" role="alert"></span>
<p class="aioa-help">20 - 150px are permitted values</p>
</div></div>

<div class="aioa-footer"><input type="submit" class="aioa-save-btn" value="<?php echo $e(ossn_print('save')); ?>"><p class="aioa-help">It may take a few seconds for changes to appear on your website. If you don't see the changes, try clearing your browser cache or checking in a private browsing window.</p></div>
</form></div></div>