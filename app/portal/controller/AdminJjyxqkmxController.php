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

use app\portal\model\JjyxqkmxModel;
use app\portal\model\UserModel;
use app\portal\model\PortalSshyModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminTagController 标签管理控制器
 * @package app\portal\controller
 */
class AdminJjyxqkmxController extends AdminBaseController
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
        $id=0;
        $portalTagModel = new JjyxqkmxModel();
        $data = $this->request->param();
        if(isset($data['fl'])){
            if($data['fl'] != 0){
            $id = $data['fl'];
            $portalTagModel->where('fl',$id);
            }
        }
        $portalTagModel->order(["id" => "ASC"]);
        $tags = $portalTagModel->paginate(10);
        if(isset($data['fl'])){
            if($data['fl'] != 0){
            $id = $data['fl'];
            $xmcoun = Db::query('select sum(jysr) as jysr,sum(gmzcz) as gmzcz,sum(gdzctze) as gdzctze,sum(zsyzdwe) as zsyzdwe,sum(jcke) as jcke,sum(shsr) as shsr from pt_jjyxqkmx where fl='.$id.'');
            }else{
            $xmcoun = Db::query('select sum(jysr) as jysr,sum(gmzcz) as gmzcz,sum(gdzctze) as gdzctze,sum(zsyzdwe) as zsyzdwe,sum(jcke) as jcke,sum(shsr) as shsr from pt_jjyxqkmx');
        }  
        }else{
            $xmcoun = Db::query('select sum(jysr) as jysr,sum(gmzcz) as gmzcz,sum(gdzctze) as gdzctze,sum(zsyzdwe) as zsyzdwe,sum(jcke) as jcke,sum(shsr) as shsr from pt_jjyxqkmx');
        }   
         if(isset($data['dc'])){
            header("Content-type:application/vnd.ms-excel");    
            header("Content-Disposition:filename=经济运行情况表.xls");    
            
            $strexport="企业名称\t经营收入(万元)\t工业总产值(万元)\t固定资产(万元)\t招商引资(万元)\t进出口(万元)\t税收(万元)\r";    
            foreach ($tags as $row){    
              
                $strexport.=$row['qyname']."\t";     
                $strexport.=$row['jysr']."\t";    
                $strexport.=$row['gmzcz']."\t";    
                $strexport.=$row['gdzctze']."\t"; 
                $strexport.=$row['zsyzdwe']."\t"; 
                $strexport.=$row['jcke']."\t";
                $strexport.=$row['shsr']."\r";    
                  
            }    
            $strexport.='合计'."\t";
            $strexport.='经营收入'.$xmcoun[0]['jysr']."\t";
            $strexport.='工业总产值'.$xmcoun[0]['gmzcz']."\t";
            $strexport.='固定资产'.$xmcoun[0]['gdzctze']."\t";
            $strexport.='招商引资'.$xmcoun[0]['zsyzdwe']."\t";
            $strexport.='进出口'.$xmcoun[0]['jcke']."\t";
            $strexport.='税收'.$xmcoun[0]['shsr']."\r";
            $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);    
            exit($strexport);   
        }    
        $this->assign("arrStatus", $portalTagModel::$STATUS);
        $this->assign("tags", $tags);
        $this->assign("xmcoun", $xmcoun);
        $this->assign("id", $id);
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
        $portalPostModel = new JjyxqkmxModel();
        
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
            $portalPostModel = new JjyxqkmxModel();
            $a = $portalPostModel->where('id',$data['id'])->update(
                     ['stat'=>1]
                );
        }else if($data['s'] == 'bh'){
            $portalPostModel = new JjyxqkmxModel();
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
