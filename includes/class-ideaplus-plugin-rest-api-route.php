<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that regiest api route for interaction Ideaplus Server
 *
 * @link       http://www.ideaplus.com/
 * @since      1.0.0
 *
 * @package    Ideaplus_Plugin
 * @subpackage Ideaplus_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define rest-api route
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @author     brazz <767502630@qq.com>
 * @package    Ideaplus_Plugin
 * @subpackage Ideaplus_Plugin/includes
 * @since      1.0.0
 */
class Ideaplus_Plugin_Rest_Api_Route extends WC_REST_Controller
{

    protected $namespace = 'wc/v2';

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $ideaplus_plugin The ID of this plugin.
     */
    private $ideaplus_plugin;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $ideaplus_plugin The name of this plugin.
     * @param string $version         The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct($ideaplus_plugin, $version)
    {
        $this->ideaplus_plugin = $ideaplus_plugin;
        $this->version         = $version;
    }

    /**
     * @description Regiest rest-api route init
     * @author      ylw <767502630@qq.com>
     */
    public function init()
    {
        register_rest_route($this->namespace, '/' . $this->ideaplus_plugin . '/authToken', [
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'set_auth_token'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
                'show_in_index'       => false,
                'args'                => [
                    'token'   => [
                        'required'    => false,
                        'type'        => 'string',
                        'description' => __('Ideaplus access key', 'ideaplus'),
                    ],
                    'shop_id' => [
                        'required'    => false,
                        'type'        => 'integer',
                        'description' => __('Ideaplus Identifier', 'ideaplus'),
                    ],
                ],
            ],
        ]);
        register_rest_route($this->namespace, '/' . $this->ideaplus_plugin . '/syncGoodsIds', [
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'sync_goods_id'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
                'show_in_index'       => false,
                'args'                => [
                    'goods_ids' => [
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __('Ideaplus GoodsID', 'ideaplus'),
                    ],
                ],
            ],
        ]);
    }

    /**
     * @description Generate auth token
     * @author      ylw <767502630@qq.com>
     *
     * @param $request
     *
     * @return string[]
     */
    public function set_auth_token($request)
    {
        $token    = $request->get_param('token');
        $store_id = $request->get_param('shop_id');
        $store_id = intval($store_id);
        if (!is_string($token) || strlen($token) == 0 || $store_id == 0) {
            $error = 'Failed to update access data';
            return [
                'error' => $error,
            ];
        }
        $options            = [];
        $options['token']   = $token;
        $options['shop_id'] = $store_id;
        Ideaplus_Plugin_Func::update_options($options);
        return [
            'error'   => "success",
            'message' => 'success',
        ];
    }

    /**
     * @description Store goods id with ideaplus
     * @author      ylw <767502630@qq.com>
     *
     * @param $request
     *
     * @return string[]
     */
    public function sync_goods_id($request)
    {
        $goods_ids         = $request->get_param('goods_ids');
        $request_goods_ids = explode(',', $goods_ids);
        $exists_goods_ids  = Ideaplus_Plugin_Func::get_option('goods_ids', [], Ideaplus_Plugin_Func::SYNCED_GOODS_IDS_KEY);
        $all_goods_ids     = array_merge($request_goods_ids, $exists_goods_ids);
        Ideaplus_Plugin_Func::update_option('goods_ids', $all_goods_ids, Ideaplus_Plugin_Func::SYNCED_GOODS_IDS_KEY);
        return [
            'error'   => "success",
            'message' => 'success',
        ];
    }

    /**
     * Check whether a given request has permission to read printful endpoints.
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request)
    {
        if (!wc_rest_check_user_permissions('read')) {
            return new WP_Error('woocommerce_rest_cannot_view', __('Sorry, you cannot list resources.', 'woocommerce'), ['status' => rest_authorization_required_code()]);
        }
        return true;
    }

    /**
     * Check if a given request has access to update a product.
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_Error|boolean
     */
    public function update_item_permissions_check($request)
    {
        $params  = $request->get_url_params();
        $product = wc_get_product((int)$params['product_id']);
        if (empty($product) && !wc_rest_check_post_permissions('product', 'edit', $product->get_id())) {
            return new WP_Error('woocommerce_rest_cannot_edit', __('Sorry, you are not allowed to edit this resource.', 'woocommerce'), ['status' => rest_authorization_required_code()]);
        }
        return true;
    }
}