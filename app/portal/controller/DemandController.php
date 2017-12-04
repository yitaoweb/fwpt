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
class DemandController extends HomeBaseController
{
    public function index()
    {

        $cid                  = $this->request->param('cid', 0, 'intval');
        $fid                  = $this->request->param('fid', 0, 'intval');
        $cid2                  = $this->request->param('cid2', 0, 'intval');

        $portalSsfwModel = new PortalSsfwModel();
        $AreaModel = new PortalXzqyModel();

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
        //服务区域
        $area = $AreaModel->where('parent_id', 8)->where('status', 1)->order('list_order')->select();

        $where = array();
       
        if ($cid > 0) {
            $arr=Db::name('portal_ssfw')->where('parent_id',$cid)->field('id')->select()->toArray();
             $arr = array_column($arr,'id');
            //var_dump($arr);die;
            $where['a.ssfw_id'] = array('in',$arr);
        }

        if ($cid2 > 0) {
            $where['a.ssfw_id'] = $cid2;
        }

        if ($fid) {
            $where['u.qy_area'] = $fid;
        }


        $xuqiu = Db::name('user')
                        ->alias('u')
                        ->join('__PORTAL_FWXQ__ a','a.user_id = u.id')
                        ->where($where)->order('a.id')->paginate(10);


        $this->assign("page", $xuqiu->render());
        $this->assign("lists", $xuqiu->items());


        
        $this->assign('cid', $cid);                   //当前一级分类id
        $this->assign('fid', $fid);                   //当前服务区域id
        $this->assign('cid2', $cid2);                 //当前二级分类id

        $this->assign('cat', $cat);                   //当前一级分类详情
        $this->assign('cat2', $cat2);                 //当前二级分类详情
        $this->assign('catall', $catall);             //所有一级分类详情
		$this->assign('area', $area);                 //服务区域详情
        $listTpl ='demandlist';

        return $this->fetch('/' . $listTpl);
    }

    public function view()
    {

        $id = $this->request->param('id', 0, 'intval');
       
        $demand = Db::name('portal_fwxq')->where('id',$id)->find();

        $user = Db::name('user')->where('id',$demand['user_id'])->find();

        //推荐机构
        $jigou = Db::name('user')->where("user_type",3)->where('user_status', 1)->order('create_time')->limit(10)->select()->toArray();
      
        $this->assign('jigou', $jigou);        //推荐机构


        $this->assign($demand);                 //机构简介
        $this->assign($user);   
        $listTpl ='demandview';

        return $this->fetch('/' . $listTpl);
    }

}