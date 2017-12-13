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

use app\portal\model\JjyxqkModel;
use app\portal\model\UserModel;
use app\portal\model\PortalSshyModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminTagController 标签管理控制器
 * @package app\portal\controller
 */
class AdminJjyxqkController extends AdminBaseController
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
      
        $portalTagModel = new JjyxqkModel();

        $portalTagModel->order(["id" => "ASC"]);
        $tags = $portalTagModel->paginate(10);
        $xmcoun = Db::query('select sum(jysr) as jysr,sum(gmzcz) as gmzcz from pt_jjyxqk');
        $this->assign("arrStatus", $portalTagModel::$STATUS);
        $this->assign("tags", $tags);
        $this->assign("xmcoun", $xmcoun);
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
        $portalTagModel = new PortalQysjfxModel();
                $xzqy = Db::name('portal_xzqy')->select();
                $this->assign("xzqy", $xzqy);
        $this->assign("arrStatus", $portalTagModel::$STATUS);
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
     *     'param'  => 's'
     * )
     */

    public function addPost()
    {
        $xh = $this->sjs();
        $portalTagModel = new PortalQysjfxModel();
        $arrData = $this->request->param();  
         if(isset($arrData['fwqy'])){
             $arrData['fwqy'] = json_encode($arrData['fwqy']);
        }  
        $qybm = Db::name('portal_xzqy')->where('id', $arrData['lxd_xzqy'])->find();  
        $counts = $portalTagModel->where('lxd_xzqy='.$arrData['lxd_xzqy'])->count();
        $bm = $qybm['qybm'].'B';
        $bms = $counts+1;
        $bmss =$bm.$bms;
        //var_dump($qybm['qybm']);die;  
        $portalTagModel->isUpdate(false)->allowField(true)->save($arrData);
        //var_dump($portalTagModel->id);die;
        $portalTagModel->where('id',$portalTagModel->id)->update(
                 ['lxd_bm'=>$bmss]
            );
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
        $portalPostModel = new PortalQysjfxModel();
        $post = $portalPostModel->where('id', $id)->find();
        $this->assign('post', $post);
        if($post['fwqy'] !=''){
              $this->assign("fwqy", json_decode($post['fwqy'])); 
        }else{
              $this->assign("fwqy", 2); 
        }
         $xzqy = Db::name('portal_xzqy')->select();
                $this->assign("xzqy", $xzqy);
        return $this->fetch();
    }
    public function editPost(){
        $data = $this->request->param();
         if(isset($data['fwqy'])){
            $data['fwqy'] = json_encode($data['fwqy']);
            $portalPostModel = new PortalQysjfxModel();
            $a = $portalPostModel->where('id',$data['id'])->update(
                     ['lxd_name'=>$data['lxd_name'],'lxd_wz'=>$data['lxd_wz'],'lxd_dz'=>$data['lxd_dz'],'lxd_qq'=>$data['lxd_qq'],'lxd_dh'=>$data['lxd_dh'],'lxd_jj'=>$data['lxd_jj'],'lxd_logo'=>$data['lxd_logo'],'lxd_yyzz'=>$data['lxd_yyzz'],'list_order'=>$data['list_order'],'fwqy'=>$data['fwqy']]
                );
        }else{
           $portalPostModel = new PortalQysjfxModel();
           $a = $portalPostModel->where('id',$data['id'])->update(
                 ['lxd_name'=>$data['lxd_name'],'lxd_wz'=>$data['lxd_wz'],'lxd_dz'=>$data['lxd_dz'],'lxd_qq'=>$data['lxd_qq'],'lxd_dh'=>$data['lxd_dh'],'lxd_jj'=>$data['lxd_jj'],'lxd_logo'=>$data['lxd_logo'],'lxd_yyzz'=>$data['lxd_yyzz'],'list_order'=>$data['list_order']]
            ); 
        }
        
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

        $portalTagModel = new PortalQysjfxModel();
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
        $portalTagModel = new PortalQysjfxModel();
        $portalTagModel->where(['id' => $intId])->delete();
        $PortalLxdwzModel = new PortalLxdwzModel();
        $PortalLxdwzModel->where(['lxd_id' => $intId])->delete();
        //Db::name('portal_tag_post')->where('tag_id', $intId)->delete();
        $this->success(lang("DELETE_SUCCESS"));
    }
    public function sh(){
        $intId = $this->request->param("id", 0, 'intval');
        $portalPostModel = new JjyxqkModel();
        
        $PortalSshyModel = new PortalSshyModel();

        $post = $portalPostModel->where('id', $intId)->find();
        $categoriesTree = $PortalSshyModel->adminCategoryTree($post['fl']);
        $user = Db::name('user')->select();
        $this->assign('post', $post);
        $this->assign('categories_tree', $categoriesTree);
        $this->assign('user', $user);

        return $this->fetch();

    }
    public function shPost(){
        $data = $this->request->param();
        if($data['s'] == 'sh'){
            $portalPostModel = new JjyxqkModel();
            $a = $portalPostModel->where('id',$data['id'])->update(
                     ['stat'=>1]
                );
        }else if($data['s'] == 'bh'){
            $portalPostModel = new JjyxqkModel();
            $a = $portalPostModel->where('id',$data['id'])->update(
                     ['stat'=>2,'bh'=>$data['bh']]
                );

        }
        if($a==1){
         $this->success('操作成功!');
        }else{
            $this->success('失败！');
        }
        
    }
}
