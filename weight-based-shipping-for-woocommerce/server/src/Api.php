<?php /** @noinspection PhpMultipleClassesDeclarationsInOneFile */
declare(strict_types=1);

namespace Wbs;

use WbsVendors\Dgm\WpAjaxApi\Endpoint;
use WbsVendors\Dgm\WpAjaxApi\RegisteredEndpoint;
use WbsVendors\Dgm\WpAjaxApi\Request;
use WbsVendors\Dgm\WpAjaxApi\Response;
use WbsVendors\Dgm\WpAjaxApi\WpAjaxApi;


class Api
{
    /**
     * @var RegisteredEndpoint
     */
    public static $config;


    public static function init(): void
    {
        $wpAjaxApi = new WpAjaxApi();
        self::$config = $wpAjaxApi->register(new ConfigEndpoint());
        $wpAjaxApi->install();
    }
}


/**
 * @internal
 */
class ConfigEndpoint extends Endpoint
{
    public $permissions = ['manage_woocommerce'];
    public $urlParams = ['instance_id'];

    public function post(Request $request): Response
    {
        $data = $request->json();

        if (!array_key_exists('config', $data)) {
            return Response::empty(Response::BadRequest);
        }
        $config = $data['config'];

        $instanceId = @$request->query['instance_id'];

        $method = new ShippingMethod($instanceId);
        $method->config($config);

        return Response::empty();
    }
}