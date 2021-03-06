<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author:kane < chengjin005@163.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use app\portal\model\UserModel;
use app\portal\model\PortalLxdwzModel;
use cmf\controller\HomeBaseController;
use think\Db;

/**
 * Class AdminTagController 标签管理控制器
 * @package app\portal\controller
 */
class TsController extends HomeBaseController
{
    /**
     * 文章标签管理
     * @adminMenu(
     *     'name'   => '文章标签',
     *     'parent' => 'portal/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章标签',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $arrData = $this->request->param();
        $portalPostModel = Db::name('portal_ts');
        $user = cmf_get_current_user();
      
        if($user['id'] == null){
            $this->success('请登入');
        }else{
            $portalPostModel->insert(
                 ['user_id'=>$user['id'],'time'=>date('y-m-d h:i:s',time()),'nr'=>$arrData['nr']]
            );
            $this->success('投诉成功');
        }
        
    }

    /**
     * 添加文章标签
     * @adminMenu(
     *     'name'   => '添加文章标签',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章标签',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $portalTagModel = new PortalLxdwzModel();
        $portal_lxd = Db::name('portal_lxd')->select();
        $portal_lxdfl = Db::name('portal_lxdfl')->select();
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign("arrStatus", $portalTagModel::$STATUS);
        $this->assign("portal_lxd", $portal_lxd);
        $this->assign("portal_lxdfl", $portal_lxdfl);
        return $this->fetch();
    }

    /**
     * 添加文章标签提交
     * @adminMenu(
     *     'name'   => '添加文章标签提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章标签提交',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {

        $arrData = $this->request->param();
        $portalTagModel = new PortalLxdwzModel();
        $portalTagModel->isUpdate(false)->allowField(true)->save($arrData);

        $this->success(lang("SAVE_SUCCESS"));

    }

    /**
     * 更新文章标签状态
     * @adminMenu(
     *     'name'   => '更新标签状态',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '更新标签状态',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        $portalPostModel = new PortalLxdwzModel();
        $post = $portalPostModel->where('id', $id)->find();
        $portal_lxd = Db::name('portal_lxd')->select();
        $portal_lxdfl = Db::name('portal_lxdfl')->select();
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign("portal_lxd", $portal_lxd);
        $this->assign("portal_lxdfl", $portal_lxdfl);
        $this->assign('post', $post);
        return $this->fetch();
    }
    public function editPost(){
        $data = $this->request->param();
        $portalPostModel = new PortalLxdwzModel();
        $a = $portalPostModel->where('id',$data['id'])->update(
                 ['fl_id'=>$data['fl_id'],'lxd_id'=>$data['lxd_id'],'name'=>$data['name'],'lxfs'=>$data['lxfs'],'time'=>$data['time'],'nr'=>$data['nr']]
            );
        $this->success('保存成功!');
    }
    public function upStatus()
    {
        $intId     = $this->request->param("id");
        $intStatus = $this->request->param("status");
        $intStatus = $intStatus ? 1 : 0;
        if (empty($intId)) {
            $this->error(lang("NO_ID"));
        }

        $portalTagModel = new PortalLxdwzModel();
        $portalTagModel->isUpdate(true)->save(["status" => $intStatus], ["id" => $intId]);

        $this->success(lang("SAVE_SUCCESS"));

    }

    /**
     * 删除文章标签
     * @adminMenu(
     *     'name'   => '删除文章标签',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除文章标签',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $intId = $this->request->param("id", 0, 'intval');

        if (empty($intId)) {
            $this->error(lang("NO_ID"));
        }
        $portalTagModel = new PortalLxdwzModel();

        $portalTagModel->where(['id' => $intId])->delete();
        //Db::name('portal_tag_post')->where('tag_id', $intId)->delete();
        $this->success(lang("DELETE_SUCCESS"));
    }
}
