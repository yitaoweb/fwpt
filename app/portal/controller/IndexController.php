<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use think\Db;
use cmf\controller\HomeBaseController;

class IndexController extends HomeBaseController
{
    public function index()
    {
    	// $ssfw = Db::name('portal_ssfw')->where("parent_id",0)->where('status', 1)->order('list_order')->select()->toArray();
    	// $this->assign('ssfw',$ssfw);
        return $this->fetch(':index');
    }
}
