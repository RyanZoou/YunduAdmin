<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\model\Order as OrderModel;

/**
 * 分类管理
 *
 * @icon fa fa-list
 * @remark 用于统一管理网站的所有分类,分类可进行无限级分类
 */
class Order extends Backend
{

    protected $relationSearch = true;

    protected $model = null;

    protected $noNeedRight = ['selectpage'];

    public function _initialize()
    {
        parent::_initialize();
        $this->request->filter(['strip_tags']);
        $this->model = model('Order');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
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
                ->with('user')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = $this->model
                ->with('user')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * Selectpage搜索
     *
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        print_r($this->request->param());exit;
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));

        return parent::edit($ids);
    }

    /**
     * 查看结果
     */
    public function resultData($ids = NULL)
    {
        print_r($this->request->param());exit;
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));

        return parent::edit($ids);
    }

}
