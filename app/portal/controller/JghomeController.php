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

use cmf\controller\HomeBaseController;
use app\portal\model\PortalSsfwModel;
use app\portal\model\PortalXzqyModel;
use think\Db;
class JghomeController extends HomeBaseController
{
    public function index()
    {

        $id                  = $this->request->param('id', 0, 'intval');
      
        $jigou = Db::name('user')->where('id',$id)->find();

		$this->assign($jigou);                 //服务区域详情
        
        $listTpl ='jghome';

        return $this->fetch('/' . $listTpl);
    }

}
