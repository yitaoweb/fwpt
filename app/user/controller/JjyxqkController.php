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

class JjyxqkController extends UserBaseController
{

    /**
     * 需求列表s
     */
    public function index()
    {
        $stat = $this->request->param("stat", 0, "intval");
        $user = cmf_get_current_user();
        $userId               = cmf_get_current_user_id();
        $gongqiuQuery            = Db::name("Jjyxqk");
        $gongqiuQuery->where('user_id',$userId);
        $gongqiuQuery->where('stat',$stat);
        $all           = $gongqiuQuery->order('id desc')->paginate(10);

        $this->assign($user);
        $this->assign("page", $all->render());
        $this->assign('stat',$stat);
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
        $sshy=Db::name('portal_sshy')->where('1=1')->order('id')->select();
        $this->assign('fuwu',$sshy);
        $this->assign("um",4);
        return $this->fetch();
    }

    /**
     * 供求资料提交s
     */
    public function addPost()
    {


        $data = $this->request->post();

        $user = cmf_get_current_user();
        $jjyxpymx = Db::query('select count(id)as rzqys,sum(gmzcz)as gmzcz,sum(jysr)as jysr,sum(shsr)as shsr,sum(zsyzdwe)as zsyzdwe,sum(sjlyswzjtze)as sjlyswzjtze,sum(gdzctze)as gdzctze,sum(jcke)as jcke,sum(yjysyfzjf)as yjysyfzjf  from pt_jjyxqkmx where user_id='.$user['id'].' and date_format(pt_jjyxqkmx.date, "%y%m")=date_format("'.$data['date'].'", "%y%m")');

        $data['rzqys']=$jjyxpymx[0]['rzqys'];
        $data['gmzcz']=$jjyxpymx[0]['gmzcz'];
        $data['jysr']=$jjyxpymx[0]['jysr'];
        $data['shsr']=$jjyxpymx[0]['shsr'];
        $data['zsyzdwe']=$jjyxpymx[0]['zsyzdwe'];
        $data['sjlyswzjtze']=$jjyxpymx[0]['sjlyswzjtze'];
        $data['gdzctze']=$jjyxpymx[0]['gdzctze'];
        $data['jcke']=$jjyxpymx[0]['jcke'];
        $data['yjysyfzjf']=$jjyxpymx[0]['yjysyfzjf'];
        $data['stat']=0;
        $data['user_id']=$user['id'];

        $qysjfx=Db::name('jjyxqk');
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
        $portalPostModel=Db::name('jjyxqk');
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
        $qysjfx=Db::name('jjyxqk')->where('id',$id)->order('id')->find();
         $sshy=Db::name('portal_sshy')->where('1=1')->order('id')->select();

         $this->assign('fuwu',$sshy);
        $this->assign('qysjfx',$qysjfx);
        $this->assign("um",4);
        return $this->fetch();

        
    }
    public function editpost(){
        $data = $this->request->post();
        $portalPostModel=Db::name('jjyxqk');
        $data['bh']='';
        $data['stat']=0;
        $ret = $portalPostModel->update($data,['id' => $data['id']]);    
        if($ret == 1){
            $this->success("修改成功！");
        }else{
             $this->error("修改失败！");
        }
    }

}