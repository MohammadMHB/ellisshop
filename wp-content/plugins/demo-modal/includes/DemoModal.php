<?php
if (!defined('ABSPATH')) {
    exit;
}

class DemoModal
{
    public function __construct()
    {
        add_action('init', [$this, 'load_textdomain']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_notices', [$this, 'admin_notice']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles']);

        add_action('woocommerce_before_cart_table', [$this, 'woocommerce_demo_notice']);
        add_action('woocommerce_before_checkout_billing_form', [$this, 'woocommerce_demo_notice']);
        add_action('woocommerce_thankyou', [$this, 'woocommerce_demo_notice']);
        add_action('woocommerce_email_before_order_table', [$this, 'add_demo_notice_to_email']);
        add_action('woocommerce_before_checkout_process', [$this, 'prevent_checkout']);
        add_action('woocommerce_checkout_process', [$this, 'prevent_checkout']);

        // Custom hooks for custom_wp_die_action
        add_filter('query_vars', [$this, 'add_custom_query_var']);
        add_action('init', [$this, 'add_custom_rewrite_rule']);
        add_action('template_redirect', [$this, 'handle_custom_wp_die_action']);
    }

    public function load_textdomain()
    {
        load_plugin_textdomain('demo-modal', false, dirname(plugin_basename(__FILE__)) . '/../languages');
    }

    public function enqueue_admin_styles()
    {
        wp_enqueue_style('demo-modal-admin-style', plugins_url('../assets/css/demo-modal.css', __FILE__));
    }

    public function enqueue_scripts()
    {
        if (!is_admin()) {
            wp_enqueue_style('sweetalert2', plugins_url('../assets/css/sweetalert2.min.css', __FILE__));
            wp_enqueue_script('sweetalert2', plugins_url('../assets/js/sweetalert2.all.min.js', __FILE__), [], null, ['in_footer' => true]);
            wp_enqueue_script('jquery');
            wp_enqueue_script('demo-modal-script', plugins_url('../assets/js/demo-modal.js', __FILE__), ['jquery', 'sweetalert2'], null, ['in_footer' => true]);
            wp_enqueue_style('demo-modal-style', plugins_url('../assets/css/demo-modal.css', __FILE__));
            wp_enqueue_style('demo-modal-admin-style', plugins_url('../assets/css/demo-modal.css', __FILE__));

            $current_user = wp_get_current_user();
            $is_admin = in_array('administrator', $current_user->roles);

            wp_localize_script('demo-modal-script', 'modal_object', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'modalTitle' => esc_html__('Attention of dear users:', 'demo-modal'),
                'modalText'  => esc_html__('This website is designed only to show the demo of the template, and no sales, commercial services or offering of products are made in it.', 'demo-modal'),
                'confirmButtonText' => esc_html__('OK', 'demo-modal'),
                'adminText' => esc_html__('To disable this warning, you can go to the plugin manager and disable the "Demo Modal" plugin', 'demo-modal'),
                'isAdmin' => $is_admin,
                'siteUrl' => get_site_url()
            ]);
        }
    }

    public function admin_notice()
    {
        echo '<div class="notice notice-warning is-dismissible">
        <p class="demo-modal-admin-notice">
            <span class="attention">' . esc_html__('Attention:"Demo Modal" plugin is active', 'demo-modal') . '</span> <br> ' . esc_html__('This plugin is designed to display a notification on your site that informs visitors that this site is currently a test (demo and under design) and no sales are being made.', 'demo-modal') . '<br>' . esc_html__('This notification is displayed on the main page of the site as well as on WooCommerce related pages. If you would like this notification to not be displayed anymore, please go to the plugin management section and disable the "Demo Modal" plugin. This will remove the notification from your site.', 'demo-modal') . '
        </p>
    </div>';
    }

    public function woocommerce_demo_notice()
    {
        echo '<div class="woocommerce-error" id="demo-notice">' . esc_html__('This website is designed only to show the demo of the template, and no sales, commercial services', 'demo-modal') . '</div>';
    }

    public function add_demo_notice_to_email($order)
    {
        echo '<p style="background-color: #ffeb3b; color: #d32f2f; padding: 10px; border-radius: 5px; text-align: center; font-size: 16px;">' .
            esc_html__('This website is designed only to show the demo of the template, and no sales, commercial services', 'demo-modal') .
            '</p>';
    }

    public function prevent_checkout()
    {
        if (is_checkout()) {
            wp_die('Order submission prevented.', 'Order submission', ['response' => 200]);
        }
    }

    // Add custom query variable
    public function add_custom_query_var($vars)
    {
        $vars[] = 'custom_wp_die_action';
        return $vars;
    }

    // Add custom rewrite rule
    public function add_custom_rewrite_rule()
    {
        add_rewrite_rule('^custom_wp_die_action/?$', 'index.php?custom_wp_die_action=1', 'top');
    }

    // Handle the custom action
    public function handle_custom_wp_die_action()
    {
        global $wp_query;
        if (isset($wp_query->query_vars['custom_wp_die_action'])) {
            $this->custom_wp_die_action();
            exit;
        }
    }

    // Custom action method
    public function custom_wp_die_action()
    {
        $custom_message = '
        <style>
            @font-face {
                font-family: "Vazir";
                src: url("https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@latest/dist/Vazir-Regular.woff2") format("woff2"),
                     url("https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@latest/dist/Vazir-Regular.woff") format("woff");
                font-weight: normal;
                font-style: normal;
            }
        </style>
        <div style="color:red; font-family: \'Vazir\', sans-serif; font-size:1.5em; line-height:2em; text-align:justify;">
            <span>' . esc_html__('Attention of dear users:', 'demo-modal') . '</span> <br><br>
            ' . esc_html__('The purchase steps you have seen are designed only as a simulation and demo of the site template and have no operational validity or value. This demonstration is only for the purpose of presenting you how the site works, and as a result, no order has been registered and no obligation has been created on the part of the site. Please be assured that all the actions you have seen in this demo are only for demonstration purposes and will not have any real effect on your account or placing an order.', 'demo-modal') . '
        </div>';
        wp_die($custom_message, 'demo purchase', ['response' => 200]);
    }
}