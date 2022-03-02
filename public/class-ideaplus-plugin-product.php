<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Ideaplus_Plugin
 * @subpackage Ideaplus_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Your Name <email@example.com>
 * @subpackage Ideaplus_Plugin/public
 * @package    Ideaplus_Plugin
 */
class Ideaplus_Plugin_Product extends Ideaplus_Plugin_Public
{
    protected $class_name = 'product';

    public function __construct($loader, $ideaplus_plugin, $version)
    {
        parent::__construct($loader, $ideaplus_plugin, $version);
        $this->loader->add_action('woocommerce_before_add_to_cart_button', $this, 'init');
    }

    public function init()
    {
        $currenct_product_id = get_the_ID();
        $synced_goods_ids    = Ideaplus_Plugin_Func::get_option('goods_ids', [], Ideaplus_Plugin_Func::SYNCED_GOODS_IDS_KEY);
        // This product does not belong to ideaplus platform
        if (!in_array($currenct_product_id, $synced_goods_ids)) {
            return false;
        }
        parent::init(); // TODO: Change the autogenerated stub
    }
}