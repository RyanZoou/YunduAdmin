<?php
namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Order AS ModerOrder;
use think\Db;

/**
 * 广告申请订单接口
 */
class Order extends Api
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function add()
    {
        $user_info = $this->auth->getUserinfo();
        $params = $this->request->request();

        $data = [];
        $data['userId'] = $user_info['id'];

        if (!isset($params['ad_type'])){
            $this->error(__('未添加广告类型'));
        }
        if (!$params['ad_name']) {
            $this->error(__('请输入投放名称'));
        }
        if (!$params['ad_business']) {
            $this->error(__('请输入投放行业'));
        }
        if (!$params['ad_platform']) {
            $this->error(__('请选择投放平台'));
        }
        if (!isset($params['ad_position'])) {
            $this->error(__('请选择广告位'));
        }
        if (!$params['ad_proved_img_path']) {
            $this->error(__('请上传资质证明'));
        }
        if (!$params['ad_city']) {
            $this->error(__('请选择投放地区'));
        }
        if (!$params['ad_date_range']) {
            $this->error(__('请选择日期范围'));
        }
        if (!$params['ad_daily_price']) {
            $this->error(__('请输入每日预算'));
        }
        if (!$params['ad_daily_price']) {
            $this->error(__('请输入每日预算'));
        }
        if (intval($params['ad_daily_price']) < 200) {
            $this->error(__('每日预算最少200'));
        }
        if (!isset($params['ad_pay_type'])) {
            $this->error(__('请选择出价形式'));
        }
        $data['adType'] = addslashes($params['ad_type']);
        $data['adName'] = addslashes($params['ad_name']);
        $data['adBusinessType'] = addslashes($params['ad_business']);
        $data['adPlatform'] = addslashes($params['ad_platform']);
        $data['adPosition'] = addslashes($params['ad_position']);
        $data['adProvedFilePath'] = addslashes($params['ad_proved_img_path']);
        $data['adForGender'] = addslashes($params['ad_gender']);
        $data['adForArea'] = addslashes($params['ad_city']);
        $data['adDateRange'] = addslashes($params['ad_date_range']);
        $data['adTimeSpecial'] = @addslashes($params['ad_time_special']);
        $data['adDailyPrice'] = intval($params['ad_daily_price']) > 200 ? intval($params['ad_daily_price']) : 200;
        $data['adPayType'] = addslashes($params['ad_pay_type']);

        $ad_mode = [];
        $ad_age = [];
        $ad_hobby = [];
        foreach ($params as $key => $val) {
            $key_arr = explode('_', $key);
            if (isset($key_arr[0]) && isset($key_arr[1]) && $key_arr[0]=='ad' && $key_arr[1]=='mode') {
                $ad_mode[] = $val;
            }
            if (isset($key_arr[0]) && isset($key_arr[1]) && $key_arr[0]=='ad' && $key_arr[1]=='age') {
                $ad_age[] = $val;
            }
            if (isset($key_arr[0]) && isset($key_arr[1]) && $key_arr[0]=='ad' && $key_arr[1]=='hobby') {
                $ad_hobby[] = $val;
            }
        }
        $data['adMode'] = join(',', $ad_mode);
        if (in_array('不限', $ad_age)) {
            $data['adForAge'] = '不限';
        } else {
            $data['adForAge'] = join(',', $ad_age);
        }
        if (in_array('不限', $ad_hobby)) {
            $data['adHobbyType'] = '不限';
        } else {
            $data['adHobbyType'] = join(',', $ad_hobby);
        }

        if (!$data['adMode'] || empty($data['adMode'])) {
            $this->error(__('请选择广告形式'));
        }
        if (!$data['adHobbyType'] || empty($data['adHobbyType'])) {
            $this->error(__('请选择兴趣分类'));
        }

        Db::startTrans();
        try {
            $order = ModerOrder::create($data);
            $orderId = $order->getLastInsID();
            $data = array('order_id' => $orderId, 'url' => 'result_data/order_id/' . $orderId);
            Db::commit();
            $this->success(__('添加成功'), $data);
        } catch (Exception $e)
        {
            Db::rollback();
            $this->error('添加失败');
        }
    }

    public function getOrders()
    {
        $user_info = $this->auth->getUserinfo();
        print_r($user_info);exit;
    }



}