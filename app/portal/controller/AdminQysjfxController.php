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

use app\portal\model\QysjfxModel;
use app\portal\model\UserModel;
use app\portal\model\PortalSshyModel;
use cmf\controller\AdminBaseController;
use think\Db;

/**
 * Class AdminTagController 标签管理控制器
 * @package app\portal\controller
 */
class AdminQysjfxController extends AdminBaseController
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
        $intId = $this->request->param("name", 0, 'intval');
        $aa = 0;
        $tb_qpflx = 0;
        $tb_qpfll = 0;
        $xse_tbpfl = 0;
        $lse_tbpfl = 0;
        $portalTagModel = new QysjfxModel();
        $portalSshyModel = new PortalSshyModel();
        $data = $this->request->param();
        if(isset($data['xyflid'])){
            if($data['xyflid'] != ''){

            
            $portalTagModel->where('fl',$data['xyflid']);
            $aa = $data['xyflid'];
            $this->assign("xyflid", $data['xyflid']);
            $timestampfl = date('y-m-d',time());

            $tbfl=date('Y-m-01',strtotime((date('y',time())-1).'-'.(date('m',time())).'-01'));
            $tb_qfl = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$timestampfl.'", "%y") and fl='.$aa.'');
            $tb_hfl = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$tbfl.'", "%y") and fl='.$aa.'');
            if($tb_qfl[0]['xse'] == null || $tb_hfl[0]['xse'] == null){
                  $xse_tbpfl = "没有数据";
            }else{
                $xse_tbpfl = ($tb_qfl[0]['xse']-$tb_hfl[0]['xse'])/$tb_hfl[0]['xse']*100;
            }
            if($tb_qfl[0]['lse'] == null || $tb_hfl[0]['lse'] == null){
               $lse_tbpfl = "没有数据";
            }else{
                $lse_tbpfl = ($tb_qfl[0]['lse']-$tb_hfl[0]['lse'])/$tb_hfl[0]['lse']*100;
            }
            $tb_qpflx = $tb_qfl[0]['xse'];
            $tb_qpfll = $tb_qfl[0]['lse'];

            }
        }else{
            $timestampfl = date('y-m-d',time());

            $tbfl=date('Y-m-01',strtotime((date('y',time())-1).'-'.(date('m',time())).'-01'));
            $tb_qfl = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$timestampfl.'", "%y")');
            $tb_hfl = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$tbfl.'", "%y")');
            if($tb_qfl[0]['xse'] == null || $tb_hfl[0]['xse'] == null){
                  $xse_tbpfl = "没有数据";
            }else{
                $xse_tbpfl = ($tb_qfl[0]['xse']-$tb_hfl[0]['xse'])/$tb_hfl[0]['xse']*100;
            }
            if($tb_qfl[0]['lse'] == null || $tb_hfl[0]['lse'] == null){
               $lse_tbpfl = "没有数据";
            }else{
                $lse_tbpfl = ($tb_qfl[0]['lse']-$tb_hfl[0]['lse'])/$tb_hfl[0]['lse']*100;
            }
            $tb_qpflx = $tb_qfl[0]['xse'];
            $tb_qpfll = $tb_qfl[0]['lse'];
        }
            $this->assign("tb_qpflx", $tb_qpflx);
            $this->assign("tb_qpfll", $tb_qpfll);

            $this->assign("xse_tbpfl", sprintf("%.2f", $xse_tbpfl));
            $this->assign("lse_tbpfl", sprintf("%.2f", $lse_tbpfl));
        $portalTagModel->order(["id" => "ASC"]);
        $tags = $portalTagModel->paginate(10);
        
        
        $categoriesTree = $portalSshyModel->adminCategoryTree($aa);
        if(isset($data['post'])){
            $timestamqs = $data['post']['published_timeq'];
            $timestamhs = $data['post']['published_timeh'];
            $timestamq=strtotime($timestamqs); 
            $timestamh=strtotime($timestamhs);   
            $tbq=date('Y-m-01',strtotime((date('y',$timestamq)-1).'-'.(date('m',$timestamq)).'-01')); 
            $tbh=date('Y-m-01',strtotime((date('y',$timestamh)-1).'-'.(date('m',$timestamh)).'-01')); 

            $tb_q = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$timestamqs.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$timestamhs.'", "%y%m")');

            $tb_h = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$tbq.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$tbh.'", "%y%m")');

            $tb_qpf = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$timestamqs.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$timestamhs.'", "%y%m") and zfl=1');

            $tb_hpf = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$tbq.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$tbh.'", "%y%m") and zfl=1');

            $tb_qls = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$timestamqs.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$timestamhs.'", "%y%m") and zfl=2');

            $tb_hls = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$tbq.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$tbh.'", "%y%m") and zfl=2');

            $tb_qzs = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$timestamqs.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$timestamhs.'", "%y%m") and zfl=5');

            $tb_hzs = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$tbq.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$tbh.'", "%y%m") and zfl=5');

            $tb_qcy = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$timestamqs.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$timestamhs.'", "%y%m") and zfl=6');

            $tb_hcy = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$tbq.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$tbh.'", "%y%m") and zfl=6');

            $this->assign("timestamq", $timestamqs);
            $this->assign("timestamh", $timestamhs);
            // if(isset($data['xyflid'])){

            //     $tb_q = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$timestamqs.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$timestamhs.'", "%y%m") and pt_qysjfx.fl="'.$data['xyflid'].'"');

            //     $tb_h = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y%m")>date_format("'.$tbq.'", "%y%m") and date_format(pt_qysjfx.date, "%y%m")<date_format("'.$tbh.'", "%y%m") and pt_qysjfx.fl="'.$data['xyflid'].'"');

            //     $this->assign("timestamq", $timestamqs);
            //     $this->assign("timestamh", $timestamhs);
            // }
        }
        else{
            $timestamp = date('y-m-d',time());

            $tb=date('Y-m-01',strtotime((date('y',time())-1).'-'.(date('m',time())).'-01')); 
            $tb_q = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$timestamp.'", "%y")');
            $tb_h = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$tb.'", "%y")'); 

            $tb_qpf = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$timestamp.'", "%y") and zfl=1');

            $tb_hpf = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$tb.'", "%y")  and zfl=1');

            $tb_qls = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$timestamp.'", "%y")  and zfl=2');

            $tb_hls = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$tb.'", "%y")  and zfl=2');

            $tb_qzs = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$timestamp.'", "%y") and zfl=5');

            $tb_hzs = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$tb.'", "%y") and zfl=5');

            $tb_qcy = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$timestamp.'", "%y") and zfl=6');

            $tb_hcy = Db::query('select sum(xse)as xse,sum(lse)as lse from pt_qysjfx where date_format(pt_qysjfx.date, "%y")=date_format("'.$tb.'", "%y") and zfl=6');

            $this->assign("timestamq", '');
            $this->assign("timestamh", '');
        }
        if($tb_q[0]['xse'] == null || $tb_h[0]['xse'] == null){
           $xse_tb = "没有数据";
        }else{
            $xse_tb = ($tb_q[0]['xse']-$tb_h[0]['xse'])/$tb_h[0]['xse']*100;
        }
        if($tb_q[0]['lse'] == null || $tb_h[0]['lse'] == null){
           $lse_tb = "没有数据";
        }else{
            $lse_tb = ($tb_q[0]['lse']-$tb_h[0]['lse'])/$tb_h[0]['lse']*100;
        }

        if($tb_qpf[0]['xse'] == null || $tb_hpf[0]['xse'] == null){
           $xse_tbpf = "没有数据";
        }else{
            $xse_tbpf = ($tb_qpf[0]['xse']-$tb_hpf[0]['xse'])/$tb_hpf[0]['xse']*100;
        }
        if($tb_qpf[0]['lse'] == null || $tb_hpf[0]['lse'] == null){
           $lse_tbpf = "没有数据";
        }else{
            $lse_tbpf = ($tb_qpf[0]['lse']-$tb_hpf[0]['lse'])/$tb_hpf[0]['lse']*100;
        }

        if($tb_qls[0]['lse'] == null || $tb_hls[0]['lse'] == null){
           $lse_tbls = "没有数据";
        }else{
            $lse_tbls = ($tb_qls[0]['lse']-$tb_hls[0]['lse'])/$tb_hls[0]['lse']*100;
        }

        if($tb_qzs[0]['xse'] == null || $tb_hzs[0]['xse'] == null){
           $xse_tbzs = "没有数据";
        }else{
            $xse_tbzs = ($tb_qzs[0]['xse']-$tb_hzs[0]['xse'])/$tb_hzs[0]['xse']*100;
        }
        if($tb_qzs[0]['lse'] == null || $tb_hzs[0]['lse'] == null){
           $lse_tbzs = "没有数据";
        }else{
            $lse_tbzs = ($tb_qzs[0]['lse']-$tb_hzs[0]['lse'])/$tb_hzs[0]['lse']*100;
        }

        if($tb_qcy[0]['xse'] == null || $tb_hcy[0]['xse'] == null){
           $xse_tbcy = "没有数据";
        }else{
            $xse_tbcy = ($tb_qcy[0]['xse']-$tb_hcy[0]['xse'])/$tb_hcy[0]['xse']*100;
        }
        if($tb_qcy[0]['lse'] == null || $tb_hcy[0]['lse'] == null){
           $lse_tbcy = "没有数据";
        }else{
            $lse_tbcy = ($tb_qcy[0]['lse']-$tb_hcy[0]['lse'])/$tb_hcy[0]['lse']*100;
        }
        
        
        $this->assign("arrStatus", $portalTagModel::$STATUS);
        $this->assign("tags", $tags);
        if(isset($data['dc'])){

            header("Content-type:application/vnd.ms-excel");    
            header("Content-Disposition:filename=限上企业数据分析.xls");    
            
            $strexport="当月销售额（万元）\t当月零售额（万元）\t同期%\t同比%\t环比%\r";    
            foreach ($tags as $row){    
               
                $strexport.=$row['xse']."\t";    
                $strexport.=$row['lse']."\t";    
                $strexport.=$row['tq']."\t"; 
                $strexport.=$row['tb']."\t"; 
                $strexport.=$row['hb']."\r";    
                  
            }    
            $strexport.='合计'."\t";
            $strexport.='销售总额：'.$tb_qpflx."\t";
            $strexport.='零售总额：'.$tb_qpfll."\t";
            $strexport.='销售额同比：'.$xse_tbpfl."\t";
            $strexport.='零售额同比：'.$lse_tbpfl."\r";
            $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);    
            exit($strexport);   
        }
        $this->assign("xmcounxse", $tb_q[0]['xse']);
        $this->assign("xmcounlse", $tb_q[0]['lse']);

        $this->assign("tb_qpfxs", $tb_qpf[0]['xse']);
        $this->assign("tb_qpfls", $tb_qpf[0]['lse']);

        $this->assign("tb_qls", $tb_qls[0]['lse']);

        $this->assign("tb_qzsxs", $tb_qzs[0]['xse']);
        $this->assign("tb_qzsls", $tb_qzs[0]['lse']);

        $this->assign("tb_qcyxs", $tb_qcy[0]['xse']);
        $this->assign("tb_qcyls", $tb_qcy[0]['lse']);

        $this->assign("categoriesTree", $categoriesTree);
        
        $this->assign("xse_tb", sprintf("%.2f", $xse_tb));
        $this->assign("lse_tb", sprintf("%.2f", $lse_tb));

        $this->assign("xse_tbpf", sprintf("%.2f", $xse_tbpf));
        $this->assign("lse_tbpf", sprintf("%.2f", $lse_tbpf));

        $this->assign("lse_tbls", sprintf("%.2f", $lse_tbls));

        $this->assign("xse_tbzs", sprintf("%.2f", $xse_tbzs));
        $this->assign("lse_tbzs", sprintf("%.2f", $lse_tbzs));

        $this->assign("xse_tbcy", sprintf("%.2f", $xse_tbcy));
        $this->assign("lse_tbcy", sprintf("%.2f", $lse_tbcy));

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
        $portalPostModel = new QysjfxModel();
        
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
            $portalPostModel = new QysjfxModel();
            $a = $portalPostModel->where('id',$data['id'])->update(
                     ['stat'=>1]
                );
        }else if($data['s'] == 'bh'){
            $portalPostModel = new QysjfxModel();
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
