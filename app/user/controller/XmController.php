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
use app\user\model\UserModel;
use think\Validate;
use think\Db;

class XmController extends UserBaseController
{

    /**
     * 需求列表
     */
    public function index()
    {
        $id = $this->request->param("id", 0, "intval");
        $stat = $this->request->param("stat", 0, "intval");
        $user = cmf_get_current_user();
        $userId               = cmf_get_current_user_id();
        $gongqiuQuery            = Db::name("xm");
        if($id != 0){
          $gongqiuQuery->where('xm_qf',$id);
        }

          $gongqiuQuery->where('stat',$stat);

          $gongqiuQuery->where('user_id',$userId);

        $all           = $gongqiuQuery->order('id desc')->paginate(10);

        $sshy=Db::name('portal_xzqy')->where('1=1')->order('id')->select();
        $this->assign($user);
        $this->assign("page", $all->render());
        $this->assign("lists", $all->items());
        $this->assign("id", $id);
        $this->assign("stat", $stat);
        $this->assign("um",4);

        $this->assign("fuwu",$sshy);
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
        $id = $this->request->param("id", 0, "intval");
        $user = cmf_get_current_user();
        $this->assign($user);
        $xm=Db::name('xm')->where('xm_qf',$id)->where('user_id',$user['id'])->order('date desc')->limit(1)->select();

        if(count($xm)){
           $this->assign("sdate",$xm[0]['date']);
        }else{
            $this->assign("sdate","没有上报记录");
        }
        $sshy=Db::name('portal_xzqy')->where('1=1')->order('id')->select();
        $this->assign('fuwu',$sshy);
        $this->assign("um",4);     
        $this->assign("id",$id);
        return $this->fetch();
    }

    /**
     * 供求资料提交
     */
    public function addPost()
    {
        $data = $this->request->post();
        $user = cmf_get_current_user();
        $data['user_id']=$user['id'];
        $data['stat']=0;
        if($data['date']){
         $qysjfx=Db::name('xm');
         $ret = $qysjfx->insert($data);
        }else{
            $this->error("请填写上报日期");
        }
       
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
        $portalPostModel=Db::name('xm');
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
        $xm=Db::name('xm')->where('id',$id)->order('id')->find();
         $sshy=Db::name('portal_xzqy')->where('1=1')->order('id')->select();

         $this->assign('fuwu',$sshy);
        $this->assign('xm',$xm);
        $this->assign("um",4);
        return $this->fetch();

        
    }
    public function editpost(){
        $data = $this->request->post();
        $portalPostModel=Db::name('xm');
        $ret = $portalPostModel->update($data,['id' => $data['id']]);    
        if($ret == 1){
            $this->success("修改成功！");
        }else{
             $this->error("修改失败！");
        }
    }
    public function insertid()
    {
        $id = $this->request->param("id", 0, "intval");
         $user = cmf_get_current_user();
        $this->assign($user);
        $xm=Db::name('xm')->where('id',$id)->order('id')->find();
         $sshy=Db::name('portal_xzqy')->where('1=1')->order('id')->select();

         $this->assign('fuwu',$sshy);
        $this->assign('xm',$xm);
        $this->assign("um",4);
        return $this->fetch();       
    }

}