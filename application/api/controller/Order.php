<?php
namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Order AS ModerOrder;
use app\common\model\SmallProgram;
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
        $small_program = [];
        $data['user_id'] = $this->userInfo['id'];

        if (!isset($params['ad_type'])){
            $this->error(__('未添加广告类型'));
        }
        if (!$params['ad_name']) {
            $this->error(__('请输入投放名称'));
        }
        if (!$params['ad_business']) {
            $this->error(__('请输入投放行业'));
        }
        if (!$params['ad_proved_img_path']) {
            $this->error(__('请上传资质证明'));
        }
        $data['adType'] = addslashes($params['ad_type']);
        $data['adName'] = addslashes($params['ad_name']);
        $data['adBusinessType'] = addslashes($params['ad_business']);
        $data['adProvedFilePath'] = addslashes(str_replace($rootUrl,'',$params['ad_proved_img_path']));

        if ($params['ad_type'] != '小程序制作') {
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

            if (!$params['ad_platform']) {
                $this->error(__('请选择投放平台'));
            }
            if (!isset($params['ad_mode']) || !$params['ad_mode']) {
                $this->error(__("请选择 '{$params['ad_mode']}' 平台下的广告类型"));
            }
            if (isset($params['ad_position']) && !$params['ad_position']) {
                $params['ad_position'] = 'all';
            }
            if (!isset($params['ad_gender']) || !$params['ad_gender']) {
                $this->error(__('请选择性别'));
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

            if (!$params['ad_city'] && !isset($params['ad_city_all'])) {
                $this->error(__('请选择投放地区'));
            }
            if (!$params['ad_date_range']) {
                $this->error(__('请选择日期范围'));
            }
            if (!$params['ad_time_special']) {
                $this->error(__('请选择投放时段'));
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

            $special_time = '';
            $arr = json_decode($params['ad_time_special'], true);
            foreach ($arr as $key => $val) {
                if (empty($val)) {
                    $special_time .= $key .', ';
                } else {
                    $special_time .= "$key:" . join('、', $val) . ',';
                }
            }

            $data['adPlatform'] = addslashes($params['ad_platform']);
            $data['adPosition'] = @addslashes($params['ad_position']);
            $data['adMode'] = @addslashes($params['ad_mode']);
            $data['adForGender'] = addslashes($params['ad_gender']);
            $data['adForArea'] = isset($params['ad_city_all']) ? '全国' : addslashes($params['ad_city']);
            $data['adDateRange'] = addslashes($params['ad_date_range']);
            $data['adTimeSpecial'] = addslashes(rtrim($special_time, ','));
            $data['adDailyPrice'] = intval($params['ad_daily_price']) > 200 ? intval($params['ad_daily_price']) : 200;
            $data['adPayType'] = addslashes($params['ad_pay_type']);
        } else {
            if (!$params['company_name']) {
                $this->error(__('请输入 企业名称'));
            }
            $small_program['companyName'] = addslashes($params['company_name']);

            if (!$params['email']) {
                $this->error(__('请输入 邮箱'));
            }
            $small_program['email'] = addslashes($params['email']);

            if (!$params['contact_user']) {
                $this->error(__('请输入 联系人名称'));
            }
            $small_program['contactUser'] = addslashes($params['contact_user']);

            if (!$params['phone_number']) {
                $this->error(__('请输入 手机号码'));
            }
            $small_program['phoneNumber'] = addslashes($params['phone_number']);

            if (!$params['contact_address']) {
                $this->error(__('请输入 联系地址'));
            }
            $small_program['contactAddress'] = addslashes($params['contact_address']);

            if (!$params['function_feature']) {
                $this->error(__('请输入 功能需求'));
            }
            $small_program['functionFeature'] = addslashes($params['function_feature']);
        }

        Db::startTrans();
        try {
            $order = ModerOrder::create($data);
            $orderId = $order->getLastInsID();
            $small_program['orderId'] = $orderId;
            SmallProgram::create($small_program);
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
        if ($rowData['user_id'] != $this->userInfo['id']){
            $this->error(__('您没有权限取消该申请'));
        }

        if ($rowData['status'] != 'new'){
            $this->error(__('该订单已经被审核无法取消，请联系管理员取消'));
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

    public function editStatus($ids, $status)
    {
        if (!$ids) {
            $this->error(__('取消申请必须传入申请编号'));
        }
        $row = ModerOrder::get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        //设置仅管理员才能操作状态


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