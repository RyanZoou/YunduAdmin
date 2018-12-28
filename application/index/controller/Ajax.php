<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Lang;
use app\common\model\Category;

/**
 * Ajax异步请求接口
 * @internal
 */
class Ajax extends Frontend
{

    protected $noNeedLogin = ['lang'];
    protected $noNeedRight = ['*'];
    protected $layout = '';

    /**
     * 加载语言包
     */
    public function lang()
    {
        header('Content-Type: application/javascript');
        $callback = $this->request->get('callback');
        $controllername = input("controllername");
        $this->loadlang($controllername);
        //强制输出JSON Object
        $result = jsonp(Lang::get(), 200, [], ['json_encode_param' => JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE]);
        return $result;
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        return action('api/common/upload');
    }

    public function platformCategoryChoose()
    {
        $ctgrModel = new Category();
        $ctgr = $ctgrModel->getCategoryArray('serving media');
        $ctgr = $ctgrModel->generateTree($ctgr);
        $categoryList = $ctgrModel->getTreeName($ctgr);

        $param = $this->request->request();

        if ($param['level'] == 1) {
            $data = [];
            foreach (array_keys($categoryList) as $v) {
                $data[] = [
                    'value' => $v,
                    'name' => $v
                ];
            }
            $this->success('', null, $data);
        }
        if ($param['level'] == 2) {
            $data = [];
            foreach (array_keys($categoryList[$param['ad_platform']]) as $v) {
                $data[] = [
                    'value' => $v,
                    'name' => $v
                ];
            }
            $this->success('', null, $data);
        }
        if ($param['level'] == 3) {
            $data = [];
            foreach (array_keys($categoryList[$param['ad_platform']][$param['ad_mode']]) as $v) {
                $data[] = [
                    'value' => $v,
                    'name' => $v
                ];
            }
            $this->success('', null, $data);
        }

        return '请选择';
    }


}

