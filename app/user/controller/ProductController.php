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

class ProductController extends UserBaseController
{

    /**
     * 个人中心我的收藏列表
     */
    public function index()
    {
        $user = cmf_get_current_user();
        $userId               = cmf_get_current_user_id();
        $productQuery            = Db::name("portal_fwcp");
        $where['user_id']     = $userId;
        $product           = $productQuery->where($where)->order('id desc')->paginate(10);
        $portal_xzqy=Db::name('portal_xzqy')->select();
        $this->assign($user);
        $this->assign("page", $product->render());
        $this->assign("lists", $product->items());
        $this->assign("area", $portal_xzqy);
        $this->assign("um",5);
        return $this->fetch();
    }

    public function ddList()
    {
        $user = cmf_get_current_user();
        $userId = cmf_get_current_user_id();
        $dd=Db::name('portal_dj');
        $product  = $dd->where('jg_id',$userId)->paginate(10);
        $fwcp=Db::name('portal_fwcp')->select();
        $fwxq=Db::name('portal_fwxq')->select();
        $qy_user=Db::name('user')->select();
        $this->assign("page", $product->render());
        $this->assign("lists", $product->items());
        $this->assign("qy_user",$qy_user);
        $this->assign($user);
        $this->assign("fwcp",$fwcp);
        $this->assign("fwxq",$fwxq);
        $this->assign("um",6);
        return $this->fetch();
    }

    public function statup(){
        $stat = $this->request->param("stat", 0, "intval");
        $id = $this->request->param("id", 0, "intval");
        $portalPostModel = new PortalDjModel();
        $jg_sunCon=0;
        if($stat == 3){
            $jg_sunCon=1;
        }
        $a = $portalPostModel->where('id',$id)->update(
                 ['stat'=>$stat,'jg_sunCon'=>$jg_sunCon]
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


    /**
     * 服务产品发布
     */
    public function add()
    {
        $user = cmf_get_current_user();
        $this->assign($user);
        $fuwu=Db::name('portal_ssfw')->where('1=1')->order('id')->select();
        $this->assign('fuwu',$fuwu);
        $this->assign("um",5);
        $this->assign("time", date('y-m-d h:i:s',time()));
        return $this->fetch();
    }

    /**
     * 服务产品提交
     */
    public function addPost()
    {
        if ($this->request->isPost()) {
            $validate = new Validate([
                'title' => 'require|max:100',
                'price'     => 'number',
                'unit'   => 'require',
     
            ]);
            $validate->message([
                'title.require' => '服务名称不能为空',
                'title.max' => '服务名称最大长度为100个字符',
                'price.require' => '收费标准必须是数字',
                'unit.require' => '单位不能为空',

            ]);

            $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $editData = new UserModel();
            if ($editData->productData($data)) {
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

    
}