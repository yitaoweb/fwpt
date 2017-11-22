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

class JglistController extends HomeBaseController
{
    public function index()
    {

        $fid                  = $this->request->param('fid', 0, 'intval');
        $cid                  = $this->request->param('cid', 0, 'intval');
        $portalCategoryModel = new PortalSsfwModel();
        $AreaModel = new PortalXzqyModel();
        $category = $portalCategoryModel->where('id', $cid)->where('status', 1)->find();
        $catall = $portalCategoryModel->where('parent_id',0)->where('status', 1)->select();
        $cat2 = $portalCategoryModel->where('parent_id', $cid)->where('status', 1)->select();
        $area = $AreaModel->where('parent_id', 8)->where('status', 1)->order('list_order')->select();
        
        $this->assign('category', $category);
        $this->assign('catall', $catall);
		$this->assign('area', $area);
        $listTpl ='jglist';

        return $this->fetch('/' . $listTpl);
    }

}
