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
use app\portal\model\PortalCategoryModel;
use think\Db;

class IndexController extends HomeBaseController
{
    public function index()
    {
    	//供应信息
    	$gong = Db::name('portal_gxfb')->where("gx",2)->where('state', 1)->order('time')->limit(2)->select()->toArray();
    	//求购信息
    	$qiu = Db::name('portal_gxfb')->where("gx",1)->where('state', 1)->order('time')->limit(2)->select()->toArray();
    	//需求信息
    	$where['state'] = array('egt',0);
    	$supply = Db::name('portal_fwxq')->where($where)->order('time')->limit(9)->select()->toArray();
    	//专家库
    	$zjk = Db::name('portal_zjk')->limit(4)->select();
    	$this->assign('gong', $gong);        //供应信息
    	$this->assign('qiu', $qiu);          //求购信息
    	$this->assign('supply', $supply);	 //需求信息
    	$this->assign('zjk',$zjk);           //专家库

        return $this->fetch(':index');
    }
}
