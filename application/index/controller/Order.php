<?php

namespace app\index\controller;

use app\common\controller\Frontend;
/**
 * 订单中心
 */
class Order extends Frontend
{
    protected $model = null;

    /**
     * 是否是关联查询
     */
    protected $relationSearch = false;

    protected $layout = 'default';
    protected $noNeedLogin = ['login', 'register', 'third'];
    protected $noNeedRight = ['*'];

    /**
     * 快速搜索时执行查找的字段
     */
    protected $searchFields = 'id,adName,adBusinessType';

    private $userInfo = null;

    public $specialTime = [];

    public $weekMap =[];

    public function _initialize()
    {
        parent::_initialize();
        $this->userInfo = $this->auth->getUserinfo();
        $this->model = model('Order');

        $this->specialTime = [
            'Monday' => [
                'adTime_Monday_0' => 0,
                'adTime_Monday_1' => 1,
                'adTime_Monday_2' => 2,
                'adTime_Monday_3' => 3,
                'adTime_Monday_4' => 4,
                'adTime_Monday_5' => 5,
                'adTime_Monday_6' => 6,
                'adTime_Monday_7' => 7,
                'adTime_Monday_8' => 8,
                'adTime_Monday_9' => 9,
                'adTime_Monday_10' => 10,
                'adTime_Monday_11' => 11,
                'adTime_Monday_12' => 12,
                'adTime_Monday_13' => 13,
                'adTime_Monday_14' => 14,
                'adTime_Monday_15' => 15,
                'adTime_Monday_16' => 16,
                'adTime_Monday_17' => 17,
                'adTime_Monday_18' => 18,
                'adTime_Monday_19' => 19,
                'adTime_Monday_20' => 20,
                'adTime_Monday_21' => 21,
                'adTime_Monday_22' => 22,
                'adTime_Monday_23' => 23
            ],
            'Tuesday' => [
                'adTime_Tuesday_0' => 0,
                'adTime_Tuesday_1' => 1,
                'adTime_Tuesday_2' => 2,
                'adTime_Tuesday_3' => 3,
                'adTime_Tuesday_4' => 4,
                'adTime_Tuesday_5' => 5,
                'adTime_Tuesday_6' => 6,
                'adTime_Tuesday_7' => 7,
                'adTime_Tuesday_8' => 8,
                'adTime_Tuesday_9' => 9,
                'adTime_Tuesday_10' => 10,
                'adTime_Tuesday_11' => 11,
                'adTime_Tuesday_12' => 12,
                'adTime_Tuesday_13' => 13,
                'adTime_Tuesday_14' => 14,
                'adTime_Tuesday_15' => 15,
                'adTime_Tuesday_16' => 16,
                'adTime_Tuesday_17' => 17,
                'adTime_Tuesday_18' => 18,
                'adTime_Tuesday_19' => 19,
                'adTime_Tuesday_20' => 20,
                'adTime_Tuesday_21' => 21,
                'adTime_Tuesday_22' => 22,
                'adTime_Tuesday_23' => 23
            ],
            'Wednesday' => [
                'adTime_Wednesday_0' => 0,
                'adTime_Wednesday_1' => 1,
                'adTime_Wednesday_2' => 2,
                'adTime_Wednesday_3' => 3,
                'adTime_Wednesday_4' => 4,
                'adTime_Wednesday_5' => 5,
                'adTime_Wednesday_6' => 6,
                'adTime_Wednesday_7' => 7,
                'adTime_Wednesday_8' => 8,
                'adTime_Wednesday_9' => 9,
                'adTime_Wednesday_10' => 10,
                'adTime_Wednesday_11' => 11,
                'adTime_Wednesday_12' => 12,
                'adTime_Wednesday_13' => 13,
                'adTime_Wednesday_14' => 14,
                'adTime_Wednesday_15' => 15,
                'adTime_Wednesday_16' => 16,
                'adTime_Wednesday_17' => 17,
                'adTime_Wednesday_18' => 18,
                'adTime_Wednesday_19' => 19,
                'adTime_Wednesday_20' => 20,
                'adTime_Wednesday_21' => 21,
                'adTime_Wednesday_22' => 22,
                'adTime_Wednesday_23' => 23
            ],
            'Thursday' => [
                'adTime_Thursday_0' => 0,
                'adTime_Thursday_1' => 1,
                'adTime_Thursday_2' => 2,
                'adTime_Thursday_3' => 3,
                'adTime_Thursday_4' => 4,
                'adTime_Thursday_5' => 5,
                'adTime_Thursday_6' => 6,
                'adTime_Thursday_7' => 7,
                'adTime_Thursday_8' => 8,
                'adTime_Thursday_9' => 9,
                'adTime_Thursday_10' => 10,
                'adTime_Thursday_11' => 11,
                'adTime_Thursday_12' => 12,
                'adTime_Thursday_13' => 13,
                'adTime_Thursday_14' => 14,
                'adTime_Thursday_15' => 15,
                'adTime_Thursday_16' => 16,
                'adTime_Thursday_17' => 17,
                'adTime_Thursday_18' => 18,
                'adTime_Thursday_19' => 19,
                'adTime_Thursday_20' => 20,
                'adTime_Thursday_21' => 21,
                'adTime_Thursday_22' => 22,
                'adTime_Thursday_23' => 23
            ],
            'Friday' => [
                'adTime_Friday_0' => 0,
                'adTime_Friday_1' => 1,
                'adTime_Friday_2' => 2,
                'adTime_Friday_3' => 3,
                'adTime_Friday_4' => 4,
                'adTime_Friday_5' => 5,
                'adTime_Friday_6' => 6,
                'adTime_Friday_7' => 7,
                'adTime_Friday_8' => 8,
                'adTime_Friday_9' => 9,
                'adTime_Friday_10' => 10,
                'adTime_Friday_11' => 11,
                'adTime_Friday_12' => 12,
                'adTime_Friday_13' => 13,
                'adTime_Friday_14' => 14,
                'adTime_Friday_15' => 15,
                'adTime_Friday_16' => 16,
                'adTime_Friday_17' => 17,
                'adTime_Friday_18' => 18,
                'adTime_Friday_19' => 19,
                'adTime_Friday_20' => 20,
                'adTime_Friday_21' => 21,
                'adTime_Friday_22' => 22,
                'adTime_Friday_23' => 23
            ],
            'Saturday' => [
                'adTime_Saturday_0' => 0,
                'adTime_Saturday_1' => 1,
                'adTime_Saturday_2' => 2,
                'adTime_Saturday_3' => 3,
                'adTime_Saturday_4' => 4,
                'adTime_Saturday_5' => 5,
                'adTime_Saturday_6' => 6,
                'adTime_Saturday_7' => 7,
                'adTime_Saturday_8' => 8,
                'adTime_Saturday_9' => 9,
                'adTime_Saturday_10' => 10,
                'adTime_Saturday_11' => 11,
                'adTime_Saturday_12' => 12,
                'adTime_Saturday_13' => 13,
                'adTime_Saturday_14' => 14,
                'adTime_Saturday_15' => 15,
                'adTime_Saturday_16' => 16,
                'adTime_Saturday_17' => 17,
                'adTime_Saturday_18' => 18,
                'adTime_Saturday_19' => 19,
                'adTime_Saturday_20' => 20,
                'adTime_Saturday_21' => 21,
                'adTime_Saturday_22' => 22,
                'adTime_Saturday_23' => 23
            ],
            'Sunday' => [
                'adTime_Sunday_0' => 0,
                'adTime_Sunday_1' => 1,
                'adTime_Sunday_2' => 2,
                'adTime_Sunday_3' => 3,
                'adTime_Sunday_4' => 4,
                'adTime_Sunday_5' => 5,
                'adTime_Sunday_6' => 6,
                'adTime_Sunday_7' => 7,
                'adTime_Sunday_8' => 8,
                'adTime_Sunday_9' => 9,
                'adTime_Sunday_10' => 10,
                'adTime_Sunday_11' => 11,
                'adTime_Sunday_12' => 12,
                'adTime_Sunday_13' => 13,
                'adTime_Sunday_14' => 14,
                'adTime_Sunday_15' => 15,
                'adTime_Sunday_16' => 16,
                'adTime_Sunday_17' => 17,
                'adTime_Sunday_18' => 18,
                'adTime_Sunday_19' => 19,
                'adTime_Sunday_20' => 20,
                'adTime_Sunday_21' => 21,
                'adTime_Sunday_22' => 22,
                'adTime_Sunday_23' => 23
            ],
        ];

        $this->weekMap = [
            'Monday' => '星期一',
            'Tuesday' => '星期二',
            'Wednesday' => '星期三',
            'Thursday' => '星期四',
            'Friday' => '星期五',
            'Saturday' => '星期六',
            'Sunday' => '星期日',
        ];
    }

    public function mobileNewMedia()
    {
        $this->view->assign('title', __('Mobile New Media'));
        $this->view->assign('special_time', $this->specialTime);
        $this->view->assign('week_map', $this->weekMap);
        return $this->view->fetch();
    }

    public function videoAdvertisement()
    {
        $this->view->assign('title', __('Video advertisement'));
        return $this->view->fetch();
    }

    public function smallProgramProduction()
    {
        $this->view->assign('title', __('Small program production'));
        return $this->view->fetch();
    }

    public function fullBigData()
    {
        $this->view->assign('title', __('Anchor advertisement'));
        return $this->view->fetch();
    }

    /**
     * 订单详情
     */
    public function detail($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $rowData = $row->toArray();
        if ($rowData['user_id'] != $this->userInfo['id']) {
            $this->error(__('You have no permission'));
        }

        $keys = array_keys($rowData);
        $key_map = [];
        foreach ($keys as $k) {
            switch ($k){
                case 'adName':
                    $key_map[$k] = '投放名称';
                    break;
                case 'adType':
                    $key_map[$k] = '广告投放';
                    break;
                case 'status':
                    $key_map[$k] = '投放状态';
                    break;
                case 'adBusinessType':
                    $key_map[$k] = '投放行业';
                    break;
                case 'adPlatform':
                    $key_map[$k] = '投放平台';
                    break;
                case 'adMode':
                    $key_map[$k] = '广告形式';
                    break;
                case 'adPosition':
                    $key_map[$k] = '广告位';
                    break;
                case 'adProvedFilePath':
                    $key_map[$k] = '资质证明';
                    break;
                case 'adForGender':
                    $key_map[$k] = '性别';
                    break;
                case 'adForAge':
                    $key_map[$k] = '年龄段';
                    break;
                case 'adHobbyType':
                    $key_map[$k] = '兴趣分类';
                    break;
                case 'adForArea':
                    $key_map[$k] = '投放地区';
                    break;
                case 'adDateRange':
                    $key_map[$k] = '投放日期';
                    break;
                case 'adTimeSpecial':
                    $key_map[$k] = '投放时间';
                    break;
                case 'adDailyPrice':
                    $key_map[$k] = '每日预算';
                    break;
                case 'adPayType':
                    $key_map[$k] = '出价形式';
                    break;
                case 'addTime':
                    $key_map[$k] = '申请时间';
                    break;
            }
        }

        $status_arr = [
            "new" => '新申请',
            "pendding" => '已审核',
            'working' => '投放中',
            'expired' => '已结束',
            'canceled' => '已取消'
        ];

        $this->view->assign("status_arr", $status_arr);
        $this->view->assign("orderData", $rowData);
        $this->view->assign("key_map", $key_map);
        return $this->view->fetch('detail');
    }

    public function lists()
    {
        //设置过滤方法
        $this->view->assign('title', __('Delivery data management'));
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->where('user_id', $this->userInfo['id'])
                ->order($sort, $order)
                ->count();
            $list = $this->model
                ->where($where)
                ->where('user_id', $this->userInfo['id'])
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            foreach ($list as $k => $v)
            {
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


}
