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
use app\portal\model\PortalSshyModel;
use app\portal\model\PortalQyjsModel;
use app\portal\model\PortalXzqyModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminTagController 标签管理控制器
 * @package app\portal\controller
 */
class AdminQylistController extends AdminBaseController
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
        $portalTagModel = new UserModel();
        $portalSshyModel = new PortalSshyModel();
        $portalXzqyModel = new PortalXzqyModel();
        $categoriesTree = $portalSshyModel->adminCategoryTree();
        $xzqyTree = $portalXzqyModel->adminCategoryTree();
        $this->assign("qy_shstatid", 2);

        if($arrData){
            if(isset($arrData['name'])){
                if($arrData['name'] != ''){
                 $portalTagModel->where('user_nickname','like',"%{$arrData['name']}%");
                 $this->assign('name', $arrData['name']);
                 }
            }
            if(isset($arrData['xyflid'] )){
                if($arrData['xyflid'] != ''){
                 $categoriesTree = $portalSshyModel->adminCategoryTree($arrData['xyflid']);
                 $portalTagModel->where('qy_sshy',$arrData['xyflid']);
                 $this->assign("xyflid", $arrData['xyflid']);
                 }
            }
            if(isset($arrData['xzqyid'])){
                if($arrData['xzqyid'] != ''){
                 $xzqyTree = $portalXzqyModel->adminCategoryTree($arrData['xzqyid']);
                 $portalTagModel->where('qy_area',$arrData['xzqyid']);
                 $this->assign("xzqyid", $arrData['xzqyid']);
                 }
            }
            if(isset($arrData['qy_shstatid'])){
                if($arrData['qy_shstatid'] != ''){
                 $portalTagModel->where('qy_shstat',$arrData['qy_shstatid']);
                 $this->assign("qy_shstatid", $arrData['qy_shstatid']);
                 }
            }
           
        } 
        $portalTagModel->where('user_type',['=',2],['=',4],['=',5],'or');
        $tags = $portalTagModel->paginate(10);    
        $xzqy = Db::name('portal_xzqy')->select();  
        $this->assign("tags", $tags);
        $this->assign("xzqy", $xzqy);
        $this->assign("categoriesTree", $categoriesTree);
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
        $portalTagModel = new UserModel();
        $portalCategoryModel = new PortalSshyModel();
        $QyjsModel = new PortalQyjsModel();
        $xzqy = Db::name('portal_xzqy')->select(); 
        $lxd = Db::name('portal_lxd')->select(); 
        $djlx = Db::name('portal_djlx')->select(); 
        $xl = Db::name('portal_xl')->select(); 
        $huafenlx = Db::name('portal_huafenlx')->select(); 
        $categoriesTree = $portalCategoryModel->adminCategoryTree();
        $qyjsTree = $QyjsModel->adminCategoryTree();
        $this->assign("xzqy", $xzqy); 
        $this->assign("lxd", $lxd); 
        $this->assign("djlx", $djlx); 
        $this->assign("xl", $xl); 
        $this->assign("huafenlx", $huafenlx); 
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign('categories_tree', $categoriesTree);
        $this->assign('qyjs_tree', $qyjsTree);
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
        $portalTagModel = new UserModel();
        $pass = rand_number(0,999999);
        $qy_code = encode('user',1,$arrData['qy_area']);
        $arrData['qy_code'] = $qy_code;
        $arrData['user_login'] = $qy_code;
        $arrData['user_pass'] = cmf_password($pass);
        // $arrData['user_type'] = 2;

        $subject="用户注册通知";
        $content="尊敬的企业用户".$arrData['user_nickname'].":<br>";
        $content=$content."您已成功注册，账号：".$qy_code."  密码：".$pass."<br>请牢记！";

        $result = cmf_send_email($arrData['user_email'], $subject, $content);
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
        $portalPostModel = new UserModel();
        $post = $portalPostModel->where('id', $id)->find();
        $portalCategoryModel = new PortalSshyModel();
                $QyjsModel = new PortalQyjsModel();
        $xzqy = Db::name('portal_xzqy')->select(); 
        $lxd = Db::name('portal_lxd')->select(); 
        $djlx = Db::name('portal_djlx')->select(); 
        $sshy = Db::name('portal_sshy')->select(); 
        $xl = Db::name('portal_xl')->select(); 
        $huafenlx = Db::name('portal_huafenlx')->select(); 
        $categoriesTree = $portalCategoryModel->adminCategoryTree();
        $qyjsTree = $QyjsModel->adminCategoryTree($post['qy_js']);
        $this->assign("xzqy", $xzqy); 
        $this->assign("lxd", $lxd); 
        $this->assign("djlx", $djlx); 
        $this->assign("sshy", $sshy); 
        $this->assign("xl", $xl); 
        $this->assign("huafenlx", $huafenlx); 
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign('categories_tree', $categoriesTree);
        $this->assign('post', $post);
        $this->assign('qyjs_tree', $qyjsTree);
        return $this->fetch();
    }
    public function editPost(){
        $data = $this->request->param();
        $portalPostModel = new UserModel();
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

        $portalTagModel = new UserModel();
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
        $portalTagModel = new UserModel();

        $portalTagModel->where(['id' => $intId])->delete();
        //Db::name('portal_tag_post')->where('tag_id', $intId)->delete();
        $this->success(lang("DELETE_SUCCESS"));
    }
}
