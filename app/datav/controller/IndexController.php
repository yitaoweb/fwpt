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
use app\admin\model\AdminMenuModel;
use think\Db;

class IndexController extends HomeBaseController
{
    public function index()
    {
    	$site_name = cmf_get_site_info('site_info');
    	
        $name=$site_name['site_name'];
  
        $title='中小商贸流通企业公共服务数据分析平台';
        $en = 'changzhi public service platform for SME';
        $this->assign('title',$title);
        $this->assign('en',$en);

        $adminMenuModel = new AdminMenuModel();
        $menus          = $adminMenuModel->menuTree();

        $this->assign("menus", $menus);

        $admin = Db::name("user")->where('id', cmf_get_current_admin_id())->find();
        $this->assign('admin', $admin);
        //return $name;

        return $this->fetch(':index');
    }

    public function login()
    {
        $title='中小商贸流通企业公共服务数据分析平台';
        $this->assign('title',$title);
        return $this->fetch(':login');
    }

    public function dologin()
    {
        if ($this->request->isPost()) {                                  
           
            $data = $this->request->post();
        
            $userModel         = new UserModel();
            $user['user_pass'] = $data['password'];
            $user['user_login'] = $data['username'];
            $log                = $userModel->doName($user);

            $session_login_http_referer = session('login_http_referer');
            $redirect                   = empty($session_login_http_referer) ? $this->request->root() : $session_login_http_referer;
            $ret = array();
            switch ($log) {
                case 0:
                    cmf_user_action('login');
                    $ret['code'] = 0;
                    $ret['msg'] = '登录成功';
                    return $ret;
                    break;
                case 1:
                    $ret['code'] = 1;
                    $ret['msg'] = '登录密码错误';
                    return $ret;
                    break;
                case 2:
                    $ret['code'] = 2;
                    $ret['msg'] = '账户不存在';
                    return $ret;
                    break;
                case 3:
                    $ret['code'] = 3;
                    $ret['msg'] = '账号被禁止访问系统';
                    return $ret;
                    break;
                default :
                    $ret['msg'] = '未受理的请求';
                    return $ret;
            }
        } else {
            $ret['msg'] = '请求错误';
            return $ret;
        }

      
    }
}
