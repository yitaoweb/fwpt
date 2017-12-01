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

use app\portal\model\PortalFwxqModel;
use app\portal\model\PortalFwcpModel;
use cmf\controller\AdminBaseController;
use app\portal\model\PortalSsfwModel;
use think\Db;

/**
 * Class AdminTagController 标签管理控制器
 * @package app\portal\controller
 */
class AdminFwxqController extends AdminBaseController
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
        $portalTagModel = new PortalFwxqModel();
        
        $arrData = $this->request->param(); 
        if($arrData){
            if(isset($arrData['name'])){
                 $portalTagModel->where('title','like',"%{$arrData['name']}%");
                 $this->assign('name', $arrData['name']);
            }
           
        } 
        $tags = $portalTagModel->paginate(10);    
        $user = Db::name('user')->select(); 
        $portal_yj = Db::name('portal_yj')->find(); 
        $portal_ssfw = Db::name('portal_ssfw')->select();  
        $this->assign("arrStatus", $portalTagModel::$STATUS);
        $this->assign("tags", $tags);
        $this->assign("user", $user);
        $this->assign("portal_yj", $portal_yj);
        $this->assign("portal_ssfw", $portal_ssfw);
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
        $parentId = $this->request->param('parent', 0, 'intval');
        $portalTagModel = new PortalFwxqModel();
        $user = Db::name('user')->where('user_type=2')->select(); 
        $portalCategoryModel = new PortalSsfwModel();
        $categoriesTree      = $portalCategoryModel->adminCategoryTree($parentId); 
        $this->assign("categories_tree", $categoriesTree);
        $this->assign("time", date('y-m-d h:i:s',time())); 
        $this->assign("user", $user);   
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
        $portalTagModel = new PortalFwxqModel();
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
        $portalPostModel = new PortalFwxqModel();
        $post = $portalPostModel->where('id', $id)->find();
        $user = Db::name('user')->where('user_type=2')->select();  
        $ssfw = Db::name('portal_ssfw')->select();  
        $portalCategoryModel = new PortalSsfwModel();
        $categoriesTree = $portalCategoryModel->adminCategoryTree();
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign("user", $user);
        $this->assign("ssfw", $ssfw);
        $this->assign('categories_tree', $categoriesTree);
        $this->assign('post', $post);
        return $this->fetch();
    }
    public function editPost(){
        $data = $this->request->param();
        $portalPostModel = new PortalFwxqModel();
        $a = $portalPostModel->where('id',$data['id'])->update(
                 ['title'=>$data['title'],'ssfw_id'=>$data['ssfw_id'],'user_id'=>$data['user_id'],'img'=>$data['img'],'time'=>$data['time'],'content'=>$data['content']]
            );
        $this->success('保存成功!');
    }
    public function sh()
    {
        $id = $this->request->param('id', 0, 'intval');
        $portalPostModel = new PortalFwxqModel();
        $post = $portalPostModel->where('id', $id)->find();
        $user = Db::name('user')->where('user_type=2')->select();  
        $ssfw = Db::name('portal_ssfw')->select();  
        $portalCategoryModel = new PortalSsfwModel();
        $categoriesTree = $portalCategoryModel->adminCategoryTree();
        $this->assign("time", date('y-m-d h:i:s',time()));
        $this->assign("user", $user);
        $this->assign("ssfw", $ssfw);
        $this->assign('categories_tree', $categoriesTree);
        $this->assign('post', $post);
        return $this->fetch();
    }
    public function shPost(){
        $data = $this->request->param();
        $portalPostModel = new PortalFwxqModel();
       if($data['bh'] == 't'){
            $a = $portalPostModel->where('id',$data['id'])->update(
                 ['sh_state'=>1]
            );
            $this->success('审核成功!');
         }
         if($data['bh'] == 'f'){
            $a = $portalPostModel->where('id',$data['id'])->update(
                 ['sh_state'=>2,'bh'=>$data['bh']]);
                 $this->success('已驳回!');
         }
    }
    public function upStatus()
    {
        $intId     = $this->request->param("id");
        $intStatus = $this->request->param("status");
        $intStatus = $intStatus ? 1 : 0;
        if (empty($intId)) {
            $this->error(lang("NO_ID"));
        }

        $portalTagModel = new PortalFwxqModel();
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
        $portalTagModel = new PortalFwxqModel();

        $portalTagModel->where(['id' => $intId])->delete();
        //Db::name('portal_tag_post')->where('tag_id', $intId)->delete();
        $this->success(lang("DELETE_SUCCESS"));
    }
    public function tui_jian_jg(){
        $portalTagModel = new PortalFwcpModel();
        $intId = $this->request->param("id", 0, 'intval');
        $intFw_id = $this->request->param("fw_id", 0, 'intval');
        $portalTagModel->where('ssfw_id='.$intFw_id);
        $tags = $portalTagModel->paginate();    
        $fwxq = Db::name('portal_fwxq')->where('id='.$intId)->find(); 
        $arr = 0;
        if($fwxq['tui_jian_jg']){
            $arr = explode(',',$fwxq['tui_jian_jg']);
        }
        
        $user = Db::name('user')->select();
        $portal_ssfw = Db::name('portal_ssfw')->select();
        $this->assign("portal_ssfw", $portal_ssfw);
        $this->assign("user", $user);
        $this->assign("tags", $tags);
        $this->assign("arr", $arr);
        $this->assign("intId", $intId);
        return $this->fetch();
    }
    public function tjjgPost(){   
        $data = $this->request->param();
        $portalTagModel = new PortalFwxqModel();
         $a = $portalTagModel->where('id',$data['id'])->update(
                 ['tui_jian_jg'=>json_encode($data['tui_jian_jg'])]
            );
         $this->success(lang("推荐成功"));
    } 
}
