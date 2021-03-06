<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 21:20
 */

namespace App\Action\Api;

use App\Service\DataService;
use BaAGee\NkNkn\Base\ActionAbstract;

/**
 * Class RequestListApi
 * @package App\Action\Api
 */
class RequestListApi extends ActionAbstract
{
    /**
     * @var array
     */
    protected $paramRules = [
        'x_id'         => ['integer|required', 'url不合法'],
        'page'         => ['integer|optional|default[1]', '页码不合法'],
        'limit'        => ['integer|optional|default[10]', '每页条数不合法'],
        'trace_id'     => ['string|optional|default[]', 'traceId不合法'],
        'url'          => ['string|optional|default[]', 'url不合法'],
        'request_time' => ['string|optional|default[]', '时间范围不合法'],
        'show_host'    => ['integer|optional|default[0]', '是否隐藏host不合法'],
    ];

    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    protected function execute(array $params = [])
    {
        $dataService = new DataService();
        $startTime   = $endTime = [];
        if (!empty($params['request_time'])) {
            list($startTime, $endTime) = explode('~', $params['request_time']);
        }

        return $dataService->searchRequest($params['x_id'], $params['page'],
            $params['limit'], $params['url'], $params['trace_id'], $params['show_host'], $startTime, $endTime);
    }
}
