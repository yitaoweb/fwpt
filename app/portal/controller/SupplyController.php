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
class SupplyController extends HomeBaseController
{
    public function index()
    {

        $cid                  = $this->request->param('cid', 0, 'intval');
        $gx                  = $this->request->param('gx', 0, 'intval');
        $cid2                  = $this->request->param('cid2', 0, 'intval');
        $where = array();
        $portalSsfwModel = new PortalSsfwModel();

        //当前一级分类
        $cat = $portalSsfwModel->where('id', $cid)->where('status', 1)->find();
        //二级分类
        if ($cid == 0) {
            $cat2="";
        }else{
            $cat2 = $portalSsfwModel->where('parent_id', $cid)->where('status', 1)->select();
        }
        
        //所有一级分类
        $catall = $portalSsfwModel->where('parent_id',0)->where('status', 1)->select();
        

   
        if ($cid > 0) {
            $arr=Db::name('portal_ssfw')->where('parent_id',$cid)->field('id')->select()->toArray();
             $arr = array_column($arr,'id');
            //var_dump($arr);die;
            $where['gqfl'] = array('in',$arr);
        }

        if ($cid2 > 0) {
            $where['gqfl'] = $cid2;
        }

        if ($gx) {
            $where['gx'] = $gx;
        }
        $supply = Db::name('portal_gxfb')->where($where)->order('time')->paginate(10);


        $this->assign("page", $supply->render());
        $this->assign("lists", $supply->items());


        
        $this->assign('cid', $cid);                   //当前一级分类id
        $this->assign('gx', $gx);                   //当前服务区域id
        $this->assign('cid2', $cid2);                 //当前二级分类id

        $this->assign('cat', $cat);                   //当前一级分类详情
        $this->assign('cat2', $cat2);                 //当前二级分类详情
        $this->assign('catall', $catall);             //所有一级分类详情

        $listTpl ='supply';

        return $this->fetch('/' . $listTpl);
    }

    public function view()
    {

        $id = $this->request->param('id', 0, 'intval');
       
        $supply = Db::name('portal_gxfb')->where('id',$id)->find();
        $user = Db::name('user')->where('id',$supply['user_id'])->find();

        //供应信息
        $gong = Db::name('portal_gxfb')->where("gx",2)->where('state', 1)->order('time')->limit(10)->select()->toArray();
        //求购信息
        $qiu = Db::name('portal_gxfb')->where("gx",1)->where('state', 1)->order('time')->limit(10)->select()->toArray();
      
        $this->assign('gong', $gong);        //供应信息
        $this->assign('qiu', $qiu);          //求购信息


        $this->assign($supply);                 //机构简介
        $this->assign($user);   
        $listTpl ='view';

        return $this->fetch('/' . $listTpl);
    }

}
