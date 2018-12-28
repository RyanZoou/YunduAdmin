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

    protected $userInfo = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->userInfo = $this->auth->getUserinfo();;
    }

    public function add()
    {
        $params = $this->request->request();
        $rootUrl = $this->request->root();

        $data = [];
        $data['userId'] = $this->userInfo['id'];

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
        if (!isset($params['ad_mode']) || !$params['ad_mode']) {
            $this->error(__("请选择 '{$params['ad_mode']}' 平台下的广告类型"));
        }
        if (isset($params['ad_position']) && !$params['ad_position']) {
            $this->error(__("请选择 '{$params['ad_platform']}' 平台下 '{$params['ad_mode']}' 的广告位置"));
        }
        if (!$params['ad_proved_img_path']) {
            $this->error(__('请上传资质证明'));
        }
        if (!isset($params['ad_gender']) || !$params['ad_gender']) {
            $this->error(__('请选择性别'));
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
        $data['adMode'] = @addslashes($params['ad_mode']);
        $data['adProvedFilePath'] = addslashes(str_replace($rootUrl,'',$params['ad_proved_img_path']));
        $data['adForGender'] = addslashes($params['ad_gender']);
        $data['adForArea'] = addslashes($params['ad_city']);
        $data['adDateRange'] = addslashes($params['ad_date_range']);
        $data['adTimeSpecial'] = @addslashes($params['ad_time_special']);
        $data['adDailyPrice'] = intval($params['ad_daily_price']) > 200 ? intval($params['ad_daily_price']) : 200;
        $data['adPayType'] = addslashes($params['ad_pay_type']);

        $ad_age = [];
        $ad_hobby = [];
        foreach ($params as $key => $val) {
            $key_arr = explode('_', $key);
            if (isset($key_arr[0]) && isset($key_arr[1]) && $key_arr[0]=='ad' && $key_arr[1]=='age') {
                $ad_age[] = $val;
            }
            if (isset($key_arr[0]) && isset($key_arr[1]) && $key_arr[0]=='ad' && $key_arr[1]=='hobby') {
                $ad_hobby[] = $val;
            }
        }

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

        if (!$data['adForAge'] || empty($data['adForAge'])) {
            $this->error(__('请选择面向的年龄段'));
        }
        if (!$data['adHobbyType'] || empty($data['adHobbyType'])) {
            $this->error(__('请选择兴趣分类'));
        }

        Db::startTrans();
        try {
            $order = ModerOrder::create($data);
            $orderId = $order->getLastInsID();
            $data = array('order_id' => $orderId, 'url' => '../order/lists');
            Db::commit();
            $this->success(__('添加成功'), $data);
        } catch (Exception $e)
        {
            Db::rollback();
            $this->error('添加失败');
        }
    }

    public function cancel($ids)
    {
        if (!$ids) {
            $this->error(__('取消申请必须传入申请编号'));
        }
        $row = ModerOrder::get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $rowData = $row->toArray();
        if ($rowData['userId'] != $this->userInfo['id']){
            $this->error(__('您没有权限取消该申请'));
        }

        if ($rowData['status'] != 'new'){
            $this->error(__('该订单已经被审核无法取消，亲联系管理员取消'));
        }

        Db::startTrans();
        try {
            ModerOrder::update(array('status'=> 'canceled'), "id=$ids");
            Db::commit();
            $data = array('url' => '../order/lists');
            $this->success(__('取消成功'), $data);
        } catch (Exception $e)
        {
            Db::rollback();
            $this->error('取消失败');
        }
    }



}