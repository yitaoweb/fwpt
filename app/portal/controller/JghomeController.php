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

        $id = $this->request->param('id', 0, 'intval');
      
        $jigou = Db::name('user')->where('id',$id)->find();
        $product = Db::name('portal_fwcp')->where('user_id',$id)->order('time desc')->paginate(10);

		$this->assign($jigou);                 //机构简介
        $this->assign("page", $product->render());
        $this->assign("lists", $product->items());

        $listTpl ='jghome';

        return $this->fetch('/' . $listTpl);
    }

    public function product()
    {

        $id = $this->request->param('id', 0, 'intval');
        $pid = $this->request->param('pid', 0, 'intval');
        $jigou = Db::name('user')->where('id',$id)->find();
        $product = Db::name('portal_fwcp')->where('id',$pid)->find();

        $this->assign($jigou);                 //机构简介
        $this->assign($product);               //产品详情

        $listTpl ='product';

        return $this->fetch('/' . $listTpl);
    }

}
