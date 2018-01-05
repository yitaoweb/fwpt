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

use app\portal\model\PortalJjyxqkmbxqModel;
use app\portal\model\PortalSsfwModel;
use app\portal\model\PortalXzqyModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminTagController 标签管理控制器
 * @package app\portal\controller
 */
class AdminJjyxmbxqController extends AdminBaseController
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
        $portalTagModel = new PortalJjyxqkmbxqModel();
         $portalXzqyModel = new PortalXzqyModel();
        $xzqyTree = $portalXzqyModel->adminCategoryTree();
        $this->assign("xzqyid", 0);
        $arrData = $this->request->param(); 
        if(isset($arrData['xzqyid'])){
                if($arrData['xzqyid'] != ''){
                 $portalTagModel->where('xzqy_id',$arrData['xzqyid']);
                 $this->assign("xzqyid", $arrData['xzqyid']);
                 }
            }
        $tags = $portalTagModel->paginate(10);    
        $this->assign("arrStatus", $portalTagModel::$STATUS);
        $this->assign("tags", $tags);
        $this->assign("xzqyTree", $xzqyTree);
        $this->assign('page', $tags->render());
        return $this->fetch();
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
        $portalTagModel = new PortalJjyxqkmbxqModel();
         $portalXzqyModel = new PortalXzqyModel();
        $xzqyTree = $portalXzqyModel->adminCategoryTree();
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign("arrStatus", $portalTagModel::$STATUS);
        $this->assign("xzqyTree", $xzqyTree);
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
        $portalTagModel = new PortalJjyxqkmbxqModel();
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
        $portalPostModel = new PortalJjyxqkmbxqModel();
        $portalSsfwModel = new PortalSsfwModel();

        $post = $portalPostModel->where('id', $id)->find();
        $categoriesTree  = $portalSsfwModel->adminCategoryTree($post['ssfwid']);
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign("categories_tree", $categoriesTree);
        $this->assign('post', $post);
        return $this->fetch();
    }
    public function editPost(){
        $data = $this->request->param();
        $portalPostModel = new PortalJjyxqkmbxqModel();
        $portalPostModel->allowField(true)->save($data,['id' => $data['id']]);
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

        $portalTagModel = new PortalJjyxqkmbxqModel();
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
        $portalTagModel = new PortalJjyxqkmbxqModel();

        $portalTagModel->where(['id' => $intId])->delete();
        //Db::name('portal_tag_post')->where('tag_id', $intId)->delete();
        $this->success(lang("DELETE_SUCCESS"));
    }
}
