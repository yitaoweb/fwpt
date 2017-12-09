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

class PublicController extends HomeBaseController
{

    public function login()
    {
        $session_admin_id = session('ADMIN_ID');

        if (!empty($session_admin_id)) {
            $user = Db::name('user')->where(['id' => $session_admin_id])->find();

            $this->error("您已登录！", url("datav/show/index"));
           
                exit();
        }
        $title='中小商贸流通企业公共服务数据分析平台';
        $this->assign('title',$title);
        return $this->fetch(':login');
    }

    public function dologin()
    {
        $ret = array();
        
        $name = $this->request->param("username");
        if (empty($name)) {
            $ret['code'] = 1;
            $ret['msg'] = lang('USERNAME_OR_EMAIL_EMPTY');
            return $ret;
        }
        $pass = $this->request->param("password");
        if (empty($pass)) {
            $ret['code'] = 1;
            $ret['msg'] = lang('PASSWORD_REQUIRED');
            return $ret;
        }

        $where['user_login'] = $name;

        $result = Db::name('user')->where($where)->find();

        if (!empty($result) && $result['user_type'] == 1) {
            if (cmf_compare_password($pass, $result['user_pass'])) {
                $groups = Db::name('RoleUser')
                    ->alias("a")
                    ->join('__ROLE__ b', 'a.role_id =b.id')
                    ->where(["user_id" => $result["id"], "status" => 1])
                    ->value("role_id");
                if ($result["id"] != 1 && (empty($groups) || empty($result['user_status']))) {
                    $this->error(lang('USE_DISABLED'));
                }
                //登入成功页面跳转
                session('ADMIN_ID', $result["id"]);
                session('name', $result["user_login"]);
                $result['last_login_ip']   = get_client_ip(0, true);
                $result['last_login_time'] = time();
                $token                     = cmf_generate_user_token($result["id"], 'web');
                if (!empty($token)) {
                    session('token', $token);
                }
                Db::name('user')->update($result);
                cookie("admin_username", $name, 3600 * 24 * 30);
                session("__LOGIN_BY_CMF_ADMIN_PW__", null);
                $this->success(lang('LOGIN_SUCCESS'), url("datav/Show/index"));
            } else {
                $this->error(lang('PASSWORD_NOT_RIGHT'));
            }
        } else {
            $this->error(lang('USERNAME_NOT_EXIST'));
        }

    }

    /**
     * 数据分析管理员退出
     */
    public function logout()
    {
        session('ADMIN_ID', null);
        return redirect(url("datav/public/login"));
    }
}
