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
            $where['a.ssfw_id'] = $cid;
        }

        if ($cid2 > 0) {
            $where['a.ssfw_id'] = $cid2;
        }

        if ($fid) {
            $where['u.qy_area'] = $fid;
        }


        $xuqiu = Db::name('user');
                        $xuqiu->alias('u');
                        $xuqiu->join('__PORTAL_FWXQ__ a','a.user_id = u.id');
               
                        if(isset($_GET['name'])){
                          $title = $_GET['name'];
                        if ($_GET['name']) {
                             $xuqiu->where('content','like',"%$title%");
                        }
                        }
                        $xuqiu = $xuqiu->where($where)->order('a.id')->paginate(10);


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
        $arr = array();
        //推荐机构
        $jigou = Db::name('user')->where("user_type",3)->where('user_status', 1)->order('create_time')->limit(10)->select()->toArray();
        $get_ly = Db::query('select a.id as id,b.avatar as b_img,b.user_nickname as b_name,a.nr as a_nr,a.time as a_time from pt_ly as a left join pt_user as b on a.user_id = b.id where pid=0 and qf=2 and cp_id='.$id);
        $i = 0;
        foreach ($get_ly as $s) {          
            $get_ly[$i]['replyBody'] = Db::query('select a.id as pid,b.avatar as pb_img,b.user_nickname as pb_name,a.nr as pa_nr,a.time as pa_time from pt_ly as a left join pt_user as b on a.user_id = b.id where pid='.$s['id']);
            $i=$i+1;
        }
        $users = cmf_get_current_user();

        $this->assign('jigou', $jigou);        //推荐机构
        $this->assign('get_ly', $get_ly);
        // $this->assign('fb_user', $fb_user);      
        $this->assign('user_types', $users['user_type']);
        $this->assign($demand);   
        $this->assign('xq_id',$demand['id']);              //机构简介
        $this->assign($user);   
        $listTpl ='demandview';

        return $this->fetch('/' . $listTpl);
    }

    public function sq_fu(){
        $xq_id = $this->request->param('id', 0, 'intval');
        $users = cmf_get_current_user();
        $demand = Db::name('portal_fwcp')->where('user_id',$users['id'])->select();
        $fwxq = Db::name('portal_fwxq')->where('id',$xq_id)->find();
        $qy = Db::name('user')->where('id',$fwxq['user_id'])->find();
                $listTpl ='jg_sq_dj';
        $this->assign('demand', $demand); 
        $this->assign('qy', $qy);
        $this->assign('fwxq', $fwxq);
        $this->assign('users', $users); 
        $this->assign('xq_id', $xq_id); 
        $this->assign("time", date('y-m-d h:i:s',time()));
        return $this->fetch('/' . $listTpl);

    }

    public function post_dj()
    {
        $arrData = $this->request->param();
        $arrData['stat'] = 0;
        $portalTagModel = new PortalDjModel();
        $portalTagModel->isUpdate(false)->allowField(true)->save($arrData);
        $this->success("申请已提交");
    }

    public function yb(){
        $users = cmf_get_current_user();
        $ly = Db::name('ly');
        if(isset($users['id'])){
            $ret = $ly->insert(['user_id'=>$users['id'],'cp_id'=>$_POST['xq_id'],'pid'=>0,'qf'=>$_POST['qf'],'nr'=>$_POST['a'],'time'=>date('y-m-d h:i:s',time())]);
            return $ret;
        }else{
            return '2';
        }
        
    }

    public function pyb(){
        $users = cmf_get_current_user();
        $ly = Db::name('ly');
        if(isset($users['id'])){
            $ret = $ly->insert(['user_id'=>$users['id'],'pid'=>$_POST['pid'],'qf'=>$_POST['qf'],'nr'=>$_POST['content'],'time'=>date('y-m-d h:i:s',time())]);
            return $ret;
        }else{
            return '2';
        }
        
    }

}
