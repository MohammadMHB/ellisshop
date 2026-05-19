<?php
/*
Plugin Name: Demo Modal
Plugin URI: https://abzarwp.com
Description: Display a "Demo Site" message as a modal and warning on specific pages.
Version: 1.0.0
Author: abzarwp.com
Author URI: https://abzarwp.com
Text Domain: demo-modal
Copyright 2024 abzarwp.com
*/


if (!defined('ABSPATH')) {
    exit;
}


require_once plugin_dir_path(__FILE__) . 'includes/DemoModal.php';


function run_demo_modal()
{
    $demo_modal = new DemoModal();
}
add_action('plugins_loaded', 'run_demo_modal');


function flush_rewrite_rules_on_activation()
{
    (new DemoModal())->add_custom_rewrite_rule();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activation');