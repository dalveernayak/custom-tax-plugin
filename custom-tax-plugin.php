<?php
/*
Plugin Name: Custom Tax Plugin
Description: Add a custom % tax to the total in WooCommerce.
Version: 1.0
Author: Dalveer Nayak Wordpress Developer
*/

// Your code will go here.

function custom_tax_settings($settings) {
    $settings[] = array(
        'name'     => 'Custom Tax Percentage',
        'id'     => 'custom_tax_percentage',
        'type'     => 'text',
        'label'    => __('Custom Tax Percentage', 'custom-tax-plugin'),
        'desc_tip' => __('Enter the tax percentage to apply.', 'custom-tax-plugin'),
        'default'  => '10',
        'desc'     => __('%', 'custom-tax-plugin'),
    );

    return $settings;
}
add_filter('woocommerce_get_settings_tax', 'custom_tax_settings');


function custom_add_tax_to_total($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Get the custom tax percentage from the settings
    $tax_percentage = get_option('custom_tax_percentage', 10) / 100;

    // Calculate the tax amount
    $tax_amount = $cart->cart_contents_total * $tax_percentage;

    // Add the tax to the cart
    $cart->add_fee(__('Custom Tax', 'custom-tax-plugin'), $tax_amount, true);
}
add_action('woocommerce_cart_calculate_fees', 'custom_add_tax_to_total');



add_action('woocommerce_update_options_tax', 'save_custom_tax_percentage');


function save_custom_tax_percentage() {
    if (isset($_POST['custom_tax_percentage'])) {
        $custom_tax_percentage = sanitize_text_field($_POST['custom_tax_percentage']);
        update_option('custom_tax_percentage', $custom_tax_percentage);
        
        // Debugging statement
          print_r($_POST['custom_tax_percentage']);
        error_log('Custom Tax Percentage updated: ' . $custom_tax_percentage);
    }
}
