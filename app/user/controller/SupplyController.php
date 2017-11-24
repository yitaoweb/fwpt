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
use think\Validate;
use think\Db;

class SupplyController extends UserBaseController
{

    /**
     * 供应列表
     */
    public function gong()
    {
        $user = cmf_get_current_user();
        $userId               = cmf_get_current_user_id();
        $gongqiuQuery            = Db::name("portal_gxfb");
        $where['user_id']     = $userId;
        $where['gx']     = 2;
        $gong           = $gongqiuQuery->where($where)->order('id desc')->paginate(10);
     
        $this->assign($user);
        $this->assign("page", $gong->render());
        $this->assign("lists", $gong->items());
        $this->assign("um",2);
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
        $this->assign("um",2);
        return $this->fetch();
    }


    /**
     * 企业供求信息
     */
    public function supply_add()
    {
        $user = cmf_get_current_user();
        $this->assign($user);
        $fuwu=Db::name('portal_ssfw')->where('1=1')->order('id')->select();
        $this->assign('fuwu',$fuwu);
        $this->assign("um",2);
        return $this->fetch();
    }

    /**
     * 供求资料提交
     */
    public function supplyPost()
    {
        if ($this->request->isPost()) {
            $validate = new Validate([
                'title' => 'require|max:100',
                'gx'     => 'number|between:0,2',
                'nr'   => 'require',
     
            ]);
            $validate->message([
                'title.require' => '标题不能为空',
                'title.max' => '标题最大长度为100个字符',
                'gx.number' => '请选择分类',
                'gx.between' => '无效的分类选项',
                'nr.require' => '内容不能为空',

            ]);

            $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $editData = new UserModel();
            if ($editData->supplyData($data)) {
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

    /**
     * 用户收藏
     */
    public function add()
    {
        $data   = $this->request->param();
        $result = $this->validate($data, 'Favorite');

        if ($result !== true) {
            $this->error($result);
        }

        $id    = $this->request->param('id', 0, 'intval');
        $table = $this->request->param('table');


        $findFavoriteCount = Db::name("user_favorite")->where([
            'object_id'  => $id,
            'table_name' => $table,
            'user_id'    => cmf_get_current_user_id()
        ])->count();

        if ($findFavoriteCount > 0) {
            $this->error("您已收藏过啦");
        }


        $title       = base64_decode($this->request->param('title'));
        $url         = $this->request->param('url');
        $url         = base64_decode($url);
        $description = $this->request->param('description', '', 'base64_decode');
        $description = empty($description) ? $title : $description;
        Db::name("user_favorite")->insert([
            'user_id'     => cmf_get_current_user_id(),
            'title'       => $title,
            'description' => $description,
            'url'         => $url,
            'object_id'   => $id,
            'table_name'  => $table,
            'create_time' => time()
        ]);

        $this->success('收藏成功');

    }
}