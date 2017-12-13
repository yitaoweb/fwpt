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
class ExpertsController extends HomeBaseController
{
    public function index()
    {

        $cid                  = $this->request->param('cid', 0, 'intval');
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
            $where['ssfwid'] = array('in',$arr);
        }

        if ($cid2 > 0) {
            $where['ssfwid'] = $cid2;
        }

        $experts = Db::name('portal_zjk');
            if(isset($_GET['name'])){
              $title = $_GET['name'];
            if ($_GET['name']) {
                 $experts->where('name','like',"%$title%");
            }
            }
        $experts = $experts->where($where)->order('time')->paginate(10);

        $this->assign("page", $experts->render());
        $this->assign("lists", $experts->items());


        
        $this->assign('cid', $cid);                   //当前一级分类id

        $this->assign('cid2', $cid2);                 //当前二级分类id

        $this->assign('cat', $cat);                   //当前一级分类详情
        $this->assign('cat2', $cat2);                 //当前二级分类详情
        $this->assign('catall', $catall);             //所有一级分类详情
		$this->assign('area', $area);                 //服务区域详情
        $listTpl ='expertslist';

        return $this->fetch('/' . $listTpl);
    }

    public function view()
    {

        $id = $this->request->param('id', 0, 'intval');
       
        $experts = Db::name('portal_zjk')->where('id',$id)->find();

    
        $this->assign($experts);                 //机构简介
        
        $listTpl ='expertsview';

        return $this->fetch('/' . $listTpl);
    }

}
