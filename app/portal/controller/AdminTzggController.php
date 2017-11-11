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

use app\portal\model\PortalTzggModel;
use cmf\controller\AdminBaseController;
use think\Db;

class AdminTzggController extends AdminBaseController
{
    public function index()
    {
        $id  = $this->request->param('id', 0, 'intval');
        $portalTagModel = new PortalTzggModel();
        $tzgg = $portalTagModel->find();
        $this->assign('tzgg', $tzgg);
        return $this->fetch();
    }
    public function editPost(){
    	$portalTagModel = new PortalTzggModel();
    	$portalTagModel->fbrname = $_POST['fbrname'];
    	$portalTagModel->time = date('y-m-d h:i:s',time());
    	$portalTagModel->nr = $_POST['nr'];
    	$a = $portalTagModel->where('id',$_POST['id'])->update(
                 ['fbrname'=>$_POST['fbrname'],'time'=>date('y-m-d h:i:s',time()),'nr'=>$_POST['nr']]
    		);
         $this->success('修改成功!');
    }

}
