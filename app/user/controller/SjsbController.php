<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>s
// +----------------------------------------------------------------------
namespace app\user\controller;

use cmf\controller\UserBaseController;
use app\portal\model\PortalSshyModel;
use app\user\model\UserModel;
use think\Validate;
use think\Db;

class SjsbController extends UserBaseController
{

    /**
     * 需求列表
     */
    public function index()
    {
        $stat = $this->request->param("stat", 0, "intval");
        $user = cmf_get_current_user();
        $userId               = cmf_get_current_user_id();
        $gongqiuQuery            = Db::name("qysjfx");
        $gongqiuQuery->where('user_id',$userId);
        $all           = $gongqiuQuery->order('id desc')->paginate(10);

        $this->assign($user);
        $this->assign('stat',$stat);
        $this->assign("page", $all->render());
        $this->assign("lists", $all->items());
        $this->assign("um",4);
        return $this->fetch();
    }


    /**
     * 求购列表
     */
    public function qiu()
    {
        $user = cmf_get_current_user();
        $userId               = cmf_get_current_user_id();
        $gongqiuQuery            = Db::name("portal_gxfb");
        $where['user_id']     = $userId;
        $where['gx']     = 1;
        $qiu           = $gongqiuQuery->where($where)->order('id desc')->paginate(10);

        $this->assign($user);
        $this->assign("page", $qiu->render());
        $this->assign("lists", $qiu->items());

        return $this->fetch();
    }


    /**
     * 企业供求信息
     */
    public function add()
    {
        $user = cmf_get_current_user();
        $this->assign($user);
        $portalCategoryModel = new PortalSshyModel();
        $parentId            = $this->request->param('parent', 0, 'intval');
        $categoriesTree      = $portalCategoryModel->adminCategoryTree($parentId);
        $this->assign('categories_tree',$categoriesTree);
        $this->assign("um",4);
        return $this->fetch();
    }

    /**
     * 供求资料提交
     */
    public function addPost()
    {


        $data = $this->request->post();
        $user = cmf_get_current_user();
        $data['fl'] = $user['qy_sshy'];
        $data['user_id']=$user['id'];
        $data['stat']=0;
        $qysjfx=Db::name('qysjfx');
        $ret = $qysjfx->insert($data);
        if($ret == 1){
            $this->success("上报成功！");
        }else{
             $this->error("上报失败！");
        }
                

    }


    /**
     * 用户取消收藏
     */
    public function delete()
    {
        $id                = $this->request->param("id", 0, "intval");
        $portalPostModel=Db::name('qysjfx');
        $data              = $portalPostModel->delete($id);
        if ($data) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    public function edit()
    {
        $id = $this->request->param("id", 0, "intval");
         $user = cmf_get_current_user();
        $this->assign($user);
        $qysjfx=Db::name('qysjfx')->where('id',$id)->order('id')->find();
         $sshy=Db::name('portal_sshy')->where('1=1')->order('id')->select();

         $this->assign('fuwu',$sshy);
        $this->assign('qysjfx',$qysjfx);
        $this->assign("um",4);
        return $this->fetch();

        
    }
    public function editpost(){
        $data = $this->request->post();
        $data['bh']='';
        $data['stat']=0;
        $portalPostModel=Db::name('qysjfx');
        $ret = $portalPostModel->update($data,['id' => $data['id']]);    
        if($ret == 1){
            $this->success("修改成功！");
        }else{
             $this->error("修改失败！");
        }
    }

    public function thb(){
          //环比
          $sbrq = $_POST['sbrq'];
          $user = cmf_get_current_user();
          $timestamp=strtotime($sbrq);   
          $hb=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01')); 
          // var_dump($hb);
          $data['get_hb'] = Db::query('select * from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")=date_format("'.$hb.'", "%y%m") and pt_qysjfx.user_id = '.$user['id'].'');
          
          // // var_dump($get_hb);
          // //同比
 
          // $timestamp=strtotime($sbrq);   
          $tb=date('Y-m-01',strtotime((date('y',$timestamp)-1).'-'.(date('m',$timestamp)).'-01')); 
          // var_dump($tb);
          $data['get_tb'] = Db::query('select * from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")=date_format("'.$tb.'", "%y%m") and pt_qysjfx.user_id = '.$user['id'].'');
          // return json_encode($data);
          // var_dump($get_hb);die;
          // 同期
        return json_encode($data);
    }

}