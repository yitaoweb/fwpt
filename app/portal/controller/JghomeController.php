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
use app\portal\model\PortalDjModel;
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
        $dd=Db::name('portal_dj');
        $portal_dj  = $dd->where('cq_id',$pid)->paginate(10);
        $fwxq=Db::name('portal_fwxq')->select();
        $this->assign("jigou",$jigou);                 //机构简介
        $this->assign("product",$product);               //产品详情
        $this->assign("fwxq",$fwxq); 
        $this->assign("page", $portal_dj->render());
        $this->assign("lists", $portal_dj->items()); 

        $listTpl ='product';

        return $this->fetch('/' . $listTpl);
    }
    public function dj()
    {
        $redirect = $this->request->post("redirect");
        $users = cmf_get_current_user();
        if (cmf_is_user_login()) {
            $id = $this->request->param('id', 0, 'intval');
            $jid = $this->request->param('jid', 0, 'intval');
         
            $xq = Db::name('portal_fwxq')->where('user_id',$users['id'])->select();

            $jigou = Db::name('user')->where('id',$jid)->find();

            $product = Db::name('portal_fwcp')->where('id',$id)->find();
            $this->assign("time", date('y-m-d h:i:s',time()));
            $this->assign("jigou",$jigou);                 //机构简介
            $this->assign("product",$product);               //产品详情
            $this->assign("xq",$xq);
            $this->assign("qy",$users);
            $listTpl ='jgdj';

            return $this->fetch('/' . $listTpl);
        } else {
            $this->error("此用户未登录!");
        }
        
    }
    public function post_dj()
    {
        $arrData = $this->request->param();
        $arrData['stat'] = 0;
        $portalTagModel = new PortalDjModel();
        $portalTagModel->isUpdate(false)->allowField(true)->save($arrData);

        $this->success("申请已提交");
    }

}
