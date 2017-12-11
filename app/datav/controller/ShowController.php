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
        } else {
            $this->error("您还没有登录！", url("datav/public/login"));
            exit();
        }
    }
    //数据分析-首页
    public function index()
    {
        //header('Access-Control-Allow-Origin:*'); 
        //header('Access-Control-Allow-Origin:http://127.0.0.1'); 
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

        header('Access-Control-Allow-Origin: *');

    	$site_name = cmf_get_site_info('site_info');
    	
        $name=$site_name['site_name'];
  
        $title='长治市中小商贸流通企业公共服务数据分析平台';
        $en = 'changzhi public service platform for SME';
        $this->assign('title',$title);
        $this->assign('en',$en);

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


        
        $token = '224b02e8ae9c56f367c0aa71610c3d18' . '&';
        $strs = 'GET&%2Fctr_active_anal%2Fget_offline_data&app_id%500554165%26end_date%3D2017-12-06%26idx%3D10201%2C10202%2C10203%26start_date%3D2017-11-01';
        $sign = sign($token, $strs);

        $this->assign('sign',$sign);
        //var_dump($sign);
        //return $name;

        return $this->fetch(':show');
    }
    //数据分析-网站流量页面
    public function site()
    {
        //header('Access-Control-Allow-Origin:*'); 
        //header('Access-Control-Allow-Origin:http://127.0.0.1'); 
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

        header('Access-Control-Allow-Origin: *');

        $site_name = cmf_get_site_info('site_info');
        
        $name=$site_name['site_name'];
  
        $title='长治市中小商贸流通企业公共服务数据分析平台';
        $en = 'changzhi public service platform for SME';
        $this->assign('title',$title);
        $this->assign('en',$en);

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


        
        $token = '224b02e8ae9c56f367c0aa71610c3d18' . '&';
        $strs = 'GET&%2Fctr_active_anal%2Fget_offline_data&app_id%500554165%26end_date%3D2017-12-06%26idx%3D10201%2C10202%2C10203%26start_date%3D2017-11-01';
        $sign = sign($token, $strs);

        $this->assign('sign',$sign);
        //var_dump($sign);
        //return $name;

        return $this->fetch(':site');
    }

    //数据分析-限额以上企业数据页面
    public function xsqy()
    {
        //header('Access-Control-Allow-Origin:*'); 
        //header('Access-Control-Allow-Origin:http://127.0.0.1'); 
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

        header('Access-Control-Allow-Origin: *');

        $site_name = cmf_get_site_info('site_info');
        
        $name=$site_name['site_name'];
  
        $title='长治市中小商贸流通企业公共服务数据分析平台';
        $en = 'changzhi public service platform for SME';
        $this->assign('title',$title);
        $this->assign('en',$en);

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


        
        $token = '224b02e8ae9c56f367c0aa71610c3d18' . '&';
        $strs = 'GET&%2Fctr_active_anal%2Fget_offline_data&app_id%500554165%26end_date%3D2017-12-06%26idx%3D10201%2C10202%2C10203%26start_date%3D2017-11-01';
        $sign = sign($token, $strs);

        $this->assign('sign',$sign);
        //var_dump($sign);
        //return $name;

        return $this->fetch(':xsqy');
    }

    //数据分析 - 经济运行情况数据分析
    public function jj()
    {
        //header('Access-Control-Allow-Origin:*'); 
        //header('Access-Control-Allow-Origin:http://127.0.0.1'); 
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

        header('Access-Control-Allow-Origin: *');

        $site_name = cmf_get_site_info('site_info');
        
        $name=$site_name['site_name'];
  
        $title='长治市中小商贸流通企业公共服务数据分析平台';
        $en = 'changzhi public service platform for SME';
        $this->assign('title',$title);
        $this->assign('en',$en);

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


        
        $token = '224b02e8ae9c56f367c0aa71610c3d18' . '&';
        $strs = 'GET&%2Fctr_active_anal%2Fget_offline_data&app_id%500554165%26end_date%3D2017-12-06%26idx%3D10201%2C10202%2C10203%26start_date%3D2017-11-01';
        $sign = sign($token, $strs);

        $this->assign('sign',$sign);
        //var_dump($sign);
        //return $name;

        return $this->fetch(':xm');
    }


    //数据分析 - 服务数据页面
    public function fw()
    {
        //header('Access-Control-Allow-Origin:*'); 
        //header('Access-Control-Allow-Origin:http://127.0.0.1'); 
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

        header('Access-Control-Allow-Origin: *');

        $site_name = cmf_get_site_info('site_info');
        
        $name=$site_name['site_name'];
  
        $title='长治市中小商贸流通企业公共服务数据分析平台';
        $en = 'changzhi public service platform for SME';
        $this->assign('title',$title);
        $this->assign('en',$en);

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


        
        $token = '224b02e8ae9c56f367c0aa71610c3d18' . '&';
        $strs = 'GET&%2Fctr_active_anal%2Fget_offline_data&app_id%500554165%26end_date%3D2017-12-06%26idx%3D10201%2C10202%2C10203%26start_date%3D2017-11-01';
        $sign = sign($token, $strs);

        $this->assign('sign',$sign);
        //var_dump($sign);
        //return $name;

        return $this->fetch(':fw');
    }


    //数据分析 - 开发区项目数据页面
    public function xm()
    {
        //header('Access-Control-Allow-Origin:*'); 
        //header('Access-Control-Allow-Origin:http://127.0.0.1'); 
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

        header('Access-Control-Allow-Origin: *');

        $site_name = cmf_get_site_info('site_info');
        
        $name=$site_name['site_name'];
  
        $title='长治市中小商贸流通企业公共服务数据分析平台';
        $en = 'changzhi public service platform for SME';
        $this->assign('title',$title);
        $this->assign('en',$en);

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


        
        $token = '224b02e8ae9c56f367c0aa71610c3d18' . '&';
        $strs = 'GET&%2Fctr_active_anal%2Fget_offline_data&app_id%500554165%26end_date%3D2017-12-06%26idx%3D10201%2C10202%2C10203%26start_date%3D2017-11-01';
        $sign = sign($token, $strs);

        $this->assign('sign',$sign);
        //var_dump($sign);
        //return $name;

        return $this->fetch(':xm');
    }


    //数据分析 - 企业机构 百度地图gis页面
    public function ent()
    {
        //header('Access-Control-Allow-Origin:*'); 
        //header('Access-Control-Allow-Origin:http://127.0.0.1'); 
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Method:POST,GET');//允许访问的方式

        header('Access-Control-Allow-Origin: *');

        $site_name = cmf_get_site_info('site_info');
        
        $name=$site_name['site_name'];
  
        $title='长治市中小商贸流通企业公共服务数据分析平台';
        $en = 'changzhi public service platform for SME';
        $this->assign('title',$title);
        $this->assign('en',$en);

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


        
        $token = '224b02e8ae9c56f367c0aa71610c3d18' . '&';
        $strs = 'GET&%2Fctr_active_anal%2Fget_offline_data&app_id%500554165%26end_date%3D2017-12-06%26idx%3D10201%2C10202%2C10203%26start_date%3D2017-11-01';
        $sign = sign($token, $strs);

        $this->assign('sign',$sign);
        //var_dump($sign);
        //return $name;

        return $this->fetch(':ent');
    }
}
