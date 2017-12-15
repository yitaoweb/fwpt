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
class ContactController extends HomeBaseController
{
    public function index()
    {

        $id                  = $this->request->param('id', 0, 'intval');
        
        $AreaModel = new PortalXzqyModel();

        if ($id > 0) {
            $Contact = Db::name('portal_lxd');
            if(isset($_GET['name'])){
              $title = $_GET['name'];
            if ($_GET['name']) {
                 $Contact->where('lxd_name','like',"%$title%");
            }
            }
            $Contact = $Contact->where('lxd_xzqy',$id)->order('list_order')->paginate(10);
        }else{
            $Contact = Db::name('portal_lxd');
            if(isset($_GET['name'])){
              $title = $_GET['name'];
            if ($_GET['name']) {
                 $Contact->where('lxd_name','like',"%$title%");
            }
            }
            $Contact = $Contact->order('list_order')->paginate(10);
        }

        $xzqys = Db::name('portal_xzqy')->select();

        //服务区域
        $area = $AreaModel->where('parent_id', 8)->where('status', 1)->order('list_order')->select();

        $this->assign("page", $Contact->render());
        $this->assign("lists", $Contact->items());


        $this->assign('xzqys', $xzqys);
        $this->assign('id', $id);                   //当前一级分类id

		$this->assign('area', $area);                 //服务区域详情
        $listTpl ='contactlist';

        return $this->fetch('/' . $listTpl);
    }

    public function view()
    {

        $id = $this->request->param('id', 0, 'intval');
       
        $contact = Db::name('portal_lxd')->where('id',$id)->find();

    $xzqys = Db::name('portal_xzqy')->select();
    $this->assign('xzqys', $xzqys);
        $this->assign($contact);                 //机构简介
        
        $listTpl ='contactview';

        return $this->fetch('/' . $listTpl);
    }

}
