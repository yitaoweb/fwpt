<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use cmf\controller\UserBaseController;
use app\user\model\UserModel;
use app\portal\model\PortalDjModel;
use think\Validate;
use think\Db;

class EntdataController extends UserBaseController
{

    /**
     * 需求列表
     */
    public function index()
    {
        $user = cmf_get_current_user();
        $userId               = cmf_get_current_user_id();
        $gongqiuQuery            = Db::name("portal_fwxq");
        $where['user_id']     = $userId;
        $all           = $gongqiuQuery->where($where)->order('id desc')->paginate(10);
        $where['state'] = array('egt',2);
        $ydj           = $gongqiuQuery->where($where)->order('id desc')->paginate(10);
        $where['state'] = 0;
        $wsl           = $gongqiuQuery->where($where)->order('id desc')->paginate(10);
        $this->assign($user);
        $this->assign("page", $all->render());
        $this->assign("lists", $all->items());
        $this->assign("wpage", $wsl->render());
        $this->assign("wlists", $wsl->items());
        $this->assign("ypage", $ydj->render());
        $this->assign("ylists", $ydj->items());
        $this->assign("um",4);
        return $this->fetch();
    }

    public function wdfwList(){
        $user = cmf_get_current_user();
        $userId  = cmf_get_current_user_id();
        $stat = $this->request->param("stat", '全部', "intval");

        $dd=Db::name('portal_dj');
        if($stat == '全部'){
            $product  = $dd->where('qy_id',$userId)->paginate(10);
            
        }else{
            $product  = $dd->where('qy_id',$userId)->where('stat',$stat)->paginate(10);
        }
        
        $fwcp=Db::name('portal_fwcp')->select();
        $fwxq=Db::name('portal_fwxq')->select();
        $jg_user=Db::name('user')->select();

        $this->assign($user);
        $this->assign("page", $product->render());
        $this->assign("lists", $product->items());
        $this->assign("um",5);
        $this->assign("fwcp", $fwcp);
        $this->assign("fwxq", $fwxq);
        $this->assign("stat", $stat);
        $this->assign("jg_user", $jg_user);
        return $this->fetch();
    }

    public function statup(){
        $stat = $this->request->param("stat", 0, "intval");
        $id = $this->request->param("id", 0, "intval");
        $portalPostModel = new PortalDjModel();
        $qy_sunCon=0;
        if($stat == 3){
            $qy_sunCon=1;
        }
        $a = $portalPostModel->where('id',$id)->update(
                 ['qy_sunCon'=>$qy_sunCon,'stat'=>4]
            );
        $this->success('操作成功!');
    }

    public function delects(){
           $id = $this->request->param("id", 0, "intval");
           $portalPostModel = new PortalDjModel();
           $data = $portalPostModel->delete($id);
        if ($data) {
            $this->success("成功！");
        } else {
            $this->error("失败！");
        }
    }

    public function pj(){
        $id = $this->request->param("id", 0, "intval");
        $user = cmf_get_current_user();
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign($user);
        $this->assign("id", $id);
        $this->assign("um",5);
        return $this->fetch();
    }

    public function pjpost(){
        $data = $this->request->param();
        $portalPostModel = new PortalDjModel();
        if($data['stat'] == 1){
            $a = $portalPostModel->where('id',$data['id'])->update(
                 ['time_pj'=>$data['time_pj'],'h_pj'=>$data['nr']]
            );
        }
        if($data['stat'] == 2){
            $a = $portalPostModel->where('id',$data['id'])->update(
                 ['time_pj'=>$data['time_pj'],'c_pj'=>$data['nr']]
            );
        }
        $this->success('操作成功!');
    }

    public function ts(){
        $id = $this->request->param("id", 0, "intval");
        $user = cmf_get_current_user();
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign($user);
        $this->assign("id", $id);
        $this->assign("um",5);
        return $this->fetch();
    }

    public function tspost(){
        $data = $this->request->param();
        $portalPostModel = new PortalDjModel();

            $a = $portalPostModel->where('id',$data['id'])->update(
                 ['time_pj'=>$data['time_pj'],'ts'=>$data['nr']]
            );

        $this->success('操作成功!');
    }

    /**
     * 企业供求信息
     */
    public function add()
    {
        $user = cmf_get_current_user();
        $this->assign($user);
        $fuwu=Db::name('portal_ssfw')->where('1=1')->order('id')->select();
        $this->assign('fuwu',$fuwu);
        $this->assign("um",4);
        return $this->fetch();
    }

    /**
     * 供求资料提交
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $validate = new Validate([
                'title' => 'require|max:100',
                'content'   => 'require',
     
            ]);
            $validate->message([
                'title.require' => '标题不能为空',
                'title.max' => '标题最大长度为100个字符',
                'content.require' => '内容不能为空',

            ]);

            $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $editData = new UserModel();
            if ($editData->demandData($data)) {
                $this->success("发布成功！", "user/profile/center");
            } else {
                $this->error("发布失败");
            }
        } else {
            $this->error("请求错误");
        }
    }


    /**
     * 用户取消收藏
     */
    public function delete()
    {
        $id                = $this->request->param("id", 0, "intval");
        $userFavoriteModel = new UserFavoriteModel();
        $data              = $userFavoriteModel->deleteFavorite($id);
        if ($data) {
            $this->success("取消收藏成功！");
        } else {
            $this->error("取消收藏失败！");
        }
    }

    public function tj_jg(){
        $arr_jg=array();
        $user = cmf_get_current_user();
        $id = $this->request->param("id", 0, "intval");
        $portal_fwxq = Db::name("portal_fwxq");
        $portal_fwcp = Db::name("portal_fwcp");
        $fwxq = $portal_fwxq->where('id',$id)->find();
        $a=0;
        foreach(json_decode($fwxq['tui_jian_jg']) as $arr){         
               $fwcp[$a] = $portal_fwcp->where('id',$arr)->find();
                $a=$a+1;
        }
        $this->assign($user);
        $this->assign("fwcp",$fwcp);
        $this->assign("um",3);
        return $this->fetch();
    }

}