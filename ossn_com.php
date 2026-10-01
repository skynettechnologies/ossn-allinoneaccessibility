<?php
define('__OSSN_AIOA__', ossn_route()->com . 'AllInOneAccessibility/');
require_once __OSSN_AIOA__ . 'lib_AioaApiClient.php';

function aioa_initialize() {
    ossn_register_com_panel('AllInOneAccessibility', 'settings');
    if (ossn_isAdminLoggedin()) {
        ossn_register_action('aioa/admin/settings', __OSSN_AIOA__ . 'actions/settings.php');
    }
    ossn_extend_view('ossn/site/head', 'aioa/widget');
}
ossn_register_callback('ossn', 'init', 'aioa_initialize');
