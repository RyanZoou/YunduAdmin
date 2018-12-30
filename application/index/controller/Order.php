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

    public function _initialize()
    {
        parent::_initialize();
        $this->userInfo = $this->auth->getUserinfo();
        $this->model = model('Order');
    }

    public function mobileNewMedia()
    {
        $this->view->assign('title', __('Mobile New Media'));
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
        if (!$row)
            $this->error(__('No Results were found'));

        $rowData = $row->toArray();

        if ($rowData['userId'] != $this->userInfo['id']) {
            $this->error(__('You have no permission'));
        }
        $this->view->assign("orderData", $row);
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
