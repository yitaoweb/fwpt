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

use app\portal\model\PortalLxdModel;
use app\portal\model\PortalLxdwzModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminTagController 标签管理控制器
 * @package app\portal\controller
 */
class AdminLxdController extends AdminBaseController
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
      
        $portalTagModel = new PortalLxdModel();
        $arrData = $this->request->param(); 
        if($arrData){
            if($arrData['lxd_xzqy'] != ''){
                 $portalTagModel->where('lxd_xzqy='.$arrData['lxd_xzqy']);
                 $portalTagModel->order(["list_order" => "ASC"]);
            }
            if($arrData['lxd_name'] != ''){
                 $portalTagModel->where('lxd_name','like',"%{$arrData['lxd_name']}%");
                 $portalTagModel->order(["list_order" => "ASC"]);
                 $this->assign('lxd_name', $arrData['lxd_name']);
            }
           
        } 
        $portalTagModel->order(["list_order" => "ASC"]);
        $tags = $portalTagModel->paginate();
        $xzqy = Db::name('portal_xzqy')->select();  
        $this->assign("arrStatus", $portalTagModel::$STATUS);
        $this->assign("tags", $tags);
        $this->assign("xzqy", $xzqy);
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
        $portalTagModel = new PortalLxdModel();
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
     *     'param'  => ''
     * )
     */

    public function addPost()
    {
        $xh = $this->sjs();
        $portalTagModel = new PortalLxdModel();
        $arrData = $this->request->param();  
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
        $portalPostModel = new PortalLxdModel();
        $post = $portalPostModel->where('id', $id)->find();
        $this->assign('post', $post);
        return $this->fetch();
    }
    public function editPost(){
        $data = $this->request->param();
        $portalPostModel = new PortalLxdModel();
        $a = $portalPostModel->where('id',$data['id'])->update(
                 ['lxd_name'=>$data['lxd_name'],'lxd_wz'=>$data['lxd_wz'],'lxd_dz'=>$data['lxd_dz'],'lxd_qq'=>$data['lxd_qq'],'lxd_dh'=>$data['lxd_dh'],'lxd_jj'=>$data['lxd_jj'],'lxd_logo'=>$data['lxd_logo'],'lxd_yyzz'=>$data['lxd_yyzz'],'list_order'=>$data['list_order']]
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

        $portalTagModel = new PortalLxdModel();
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
        $portalTagModel = new PortalLxdModel();
        $portalTagModel->where(['id' => $intId])->delete();
        $PortalLxdwzModel = new PortalLxdwzModel();
        $PortalLxdwzModel->where(['lxd_id' => $intId])->delete();
        //Db::name('portal_tag_post')->where('tag_id', $intId)->delete();
        $this->success(lang("DELETE_SUCCESS"));
    }
}
