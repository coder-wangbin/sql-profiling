<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/10/26
 * Time: 22:41
 */

namespace App\Model;

use BaAGee\MySQL\Expression;
use BaAGee\NkNkn\Base\ModelAbstract;

/**
 * Class Requests
 * @package App\Model
 */
class RequestsModel extends ModelAbstract
{
    /**
     * @var string
     */
    public static $tableName = 'requests';

    /**
     * @param     $xId
     * @param     $traceId
     * @param     $url
     * @param int $requestTime
     * @param     $allCostTime
     * @param     $sqlCount
     * @return int
     * @throws \Exception
     */
    public function save($xId, $traceId, $url, int $requestTime, $allCostTime, $sqlCount)
    {
        $lId = intval(microtime(true) * 1000) + mt_rand(10000, 99999);
        $this->tableObj->insert([
            'x_id'          => $xId,
            'l_id'          => $lId,
            'trace_id'      => $traceId,
            'url'           => $url,
            'request_time'  => date('Y-m-d H:i:s', $requestTime),
            'all_cost_time' => $allCostTime,
            'sql_count'     => $sqlCount
        ]);
        return $lId;
    }

    /**
     * @param        $xId
     * @param        $page
     * @param        $limit
     * @param string $url
     * @param string $traceId
     * @param string $startTime
     * @param string $endTime
     * @return array
     * @throws \Exception
     */
    public function search($xId, $page, $limit, $url = '', $traceId = '', $startTime = '', $endTime = '')
    {
        $co = [
            'x_id' => ['=', $xId]
        ];
        if (!empty($url)) {
            $co['url'] = ['like', '%' . $url . '%'];
        }
        if (!empty($traceId)) {
            $co['trace_id'] = ['=', $traceId];
        }
        if (!empty($startTime = trim($startTime)) && !empty($endTime = trim($endTime))) {
            // $expression = new Expression(
            //     sprintf('unix_timestamp(`request_time`) > %s and unix_timestamp(`request_time`) <= %s', strtotime($startTime), strtotime($endTime))
            // );
            // $co[] = $expression;

            $co['request_time'] = ['between', [$startTime, $endTime]];
        }

        $list  = $this->tableObj->fields([
            'l_id', 'trace_id', 'url', 'request_time', 'all_cost_time', 'sql_count', 'create_time'
        ])->where($co)->limit(($page - 1) * $limit, $limit)
            ->orderBy(['create_time' => 'desc'])->select();
        $count = $this->tableObj->fields(['count(*) as c'])->where($co)->select()[0]['c'] ?? 0;
        return compact('list', 'count');
    }

    public function getByLId($lId)
    {
        $res = $this->tableObj->where([
            'l_id' => ['=', $lId]
        ])->limit(1)->select();
        return $res[0] ?? [];
    }

    public function getLIdByXIds(array $xIds)
    {
        $list = $this->tableObj->where([
            'x_id' => ['in', $xIds]
        ])->fields(['l_id'])->select();
        if (!empty($list)) {
            return array_column($list, 'l_id');
        }
        return [];
    }

    public function deleteByXId($xId)
    {
        return $this->tableObj->where([
            'x_id' => ['=', $xId]
        ])->delete();
    }
}