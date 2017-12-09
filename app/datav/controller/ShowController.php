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

        
        $token = '224b02e8ae9c56f367c0aa71610c3d18' . '&';
        $strs = 'GET&%2Fctr_active_anal%2Fget_offline_data&app_id%500554165%26end_date%3D2017-12-06%26idx%3D10201%2C10202%2C10203%26start_date%3D2017-11-01';
        $sign = sign($token, $strs);

        $this->assign('sign',$sign);
        //var_dump($sign);
        //return $name;

        return $this->fetch(':show');
    }
}
