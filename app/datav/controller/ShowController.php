<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\datav\controller;

use cmf\controller\HomeBaseController;
use app\user\model\UserModel;
use think\Db;

 
class ShowController extends HomeBaseController
{
    public function _initialize()
    {
        parent::_initialize();
 
        $session_admin_id = cmf_get_current_admin_id();
        if (!empty($session_admin_id)) {
            $user = Db::name('user')->where(['id' => $session_admin_id])->find();
            $role_user = Db::name('role_user')->where(['user_id' => $session_admin_id])->find();
            if ($role_user['id'] >= 1) {
               $role = Db::name('role')->where(['id' => $role_user['role_id']])->find();
            }else{
                $role = Db::name('role')->where(['id' => 1])->find();
            }
            $this->assign("role", $role);
            $this->assign("admin", $user);
            $title='长治市中小商贸流通企业公共服务数据分析平台';
            $this->assign('title',$title);
        } else {
            $this->error("您还没有登录！", url("datav/public/login"));
            exit();
        }
    }
    //数据分析-首页
    public function index()
    {
         
        

        $qy_num = Db::query('select count(id) as num from pt_user where user_type = 2');
        $jg_num = Db::query('select count(id) as num from pt_user where user_type = 3');
        $lxd_num = Db::query('select count(id) as num from pt_portal_lxd');
        $fw_num = Db::query('select count(id) as num from pt_portal_fwxq');
        $cp_num = Db::query('select count(id) as num from pt_portal_fwcp');
        $dj_num = Db::query('select count(id) as num from pt_portal_dj');
        $zj_num = Db::query('select count(id) as num from pt_portal_zjk');

     
        $this->assign('qy_num',$qy_num[0]['num']);
        $this->assign('jg_num',$jg_num[0]['num']);
        $this->assign('lxd_num',$lxd_num[0]['num']);
        $this->assign('fw_num',$fw_num[0]['num']);
        $this->assign('cp_num',$cp_num[0]['num']);
        $this->assign('dj_num',$dj_num[0]['num']);
        $this->assign('zj_num',$zj_num[0]['num']);
  

        return $this->fetch(':show');
    }


    //数据分析-网站流量页面
    public function site()
    {
        
        $sub_title='平台整体流量分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':site');
    }


    //数据分析-限额以上企业数据页面
    public function xsqy()
    {
        $sub_title='限额以上企业数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':xsqy');
    }

    //数据分析 - 经济运行情况数据分析
    public function jj()
    {
        $sub_title='经济运行情况数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':jj');
    }


    //数据分析 - 服务数据页面
    public function fw()
    {
        $sub_title='服务数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':fw');
    }


    //数据分析 - 开发区项目数据页面
    public function xm()
    {
        $sub_title='开发区项目数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':xm');
    }


    //数据分析 - 企业机构 百度地图gis页面
    public function ent()
    {
        $sub_title='企业机构GIS数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':ent');
    }
}
