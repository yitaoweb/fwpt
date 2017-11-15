<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------
namespace app\user\controller;

use cmf\controller\HomeBaseController;
use think\Validate;
use app\user\model\UserModel;
use app\portal\model\PortalSshyModel;
use think\Db;

class RegisterController extends HomeBaseController
{

    /**
     * 前台用户注册
     */
    public function index()
    {
        $redirect = $this->request->post("redirect");
        if (empty($redirect)) {
            $redirect = $this->request->server('HTTP_REFERER');
        } else {
            $redirect = base64_decode($redirect);
        }
        $area=Db::name('portal_xzqy')->where(array('parent_id' => 8))->order('list_order')->select();
        $parentId            = $this->request->param('parent', 0, 'intval');
        $portalCategoryModel = new PortalSshyModel();
        $categoriesTree      = $portalCategoryModel->adminCategoryTree($parentId);
        $this->assign('area', $area);
        $this->assign('categories_tree', $categoriesTree);

        session('login_http_referer', $redirect);

        if (cmf_is_user_login()) {
            return redirect($this->request->root() . '/');
        } else {
            return $this->fetch(":register");
        }
    }

    /**
     * 前台用户注册提交
     */
    public function doRegister()
    {
        if ($this->request->isPost()) {
            $rules = [
                'user_nickname'  => 'require',
                'qy_xydm'        => 'require',
                'qy_area'        => 'require|gt:0',
                'qy_address'     => 'require',
                'qy_sshy'        => 'require|gt:0',
                'qy_sq'          => 'require',
                'qy_faren'       => 'require',

                'qy_lxname'      => 'require',

                'mobile'         => 'require',
                'email'          => 'require',

                'qy_zczj'        => 'require',
                'qy_zcsj'        => 'require',
                'qy_class'       => 'require',
                'birthday'       => 'require',
                'qy_zg_num'      => 'require',
                'qy_sme_class'   => 'require|gt:0',

                'qy_jyfw'        => 'require',
                'qy_jianjie'     => 'require',

                'captcha'        => 'require',
                'code'           => 'require',

            ];

            $isOpenRegistration=cmf_is_open_registration();

            if ($isOpenRegistration) {
                unset($rules['code']);
            }

            $validate = new Validate($rules);
            $validate->message([
                'user_nickname.require'  => '企业名称不能为空',
                'qy_xydm.require'        => '统一社会信用代码不能为空',
                'qy_area.require'        => '行政区域不能为空',
                'qy_area.gt'        => '行政区域不能为空',
                'qy_address.require'     => '办公地址不能为空',
                'qy_sshy.require'        => '所属行业分类不能为空',
                'qy_sshy.gt'        => '所属行业分类不能为空',
                'qy_sq.require'          => '所属联系点不能为空',
                'qy_faren.require'       => '企业法人不能为空',

                'qy_lxname.require'      => '联系人不能为空',
                'mobile.require'         => '手机号不能为空',
                'email.require'          => '电子邮箱不能为空',

                'qy_zczj.require'        => '企业注册资金不能为空',
                'qy_zcsj.require'        => '企业注册登记时间不能为空',
                'qy_class.require'       => '企业注册登记类型不能为空',

                'qy_zg_num.require'      => '企业职工人数不能为空',
                'qy_sme_class.require'   => '中小企业划分类型不能为空',

                'qy_jyfw.require'        => '经营范围不能为空',
                'qy_jianjie.require'     => '企业简介不能为空',

                'code.require'     => '验证码不能为空',
                'captcha.require'  => '验证码不能为空',
            ]);

             $data = $this->request->post();
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            if (!cmf_captcha_check($data['captcha'])) {
                $this->error('验证码错误');
            }

            if(!$isOpenRegistration){
                $errMsg = cmf_check_verification_code($data['username'], $data['code']);
                if (!empty($errMsg)) {
                    $this->error($errMsg);
                }
            }

            $register          = new UserModel();
            $log            = $register->registerMobile($data);
            $user['user_pass'] = $data['password'];
            if (Validate::is($data['username'], 'email')) {
                $user['user_email'] = $data['username'];
                $log                = $register->registerEmail($user);
            } else if (preg_match('/(^(13\d|15[^4\D]|17[013678]|18\d)\d{8})$/', $data['username'])) {
                $user['mobile'] = $data['username'];
                $log            = $register->registerMobile($user);
            } else {
                $log = 2;
            }
            $sessionLoginHttpReferer = session('login_http_referer');
            $redirect                = empty($sessionLoginHttpReferer) ? cmf_get_root() . '/' : $sessionLoginHttpReferer;
            switch ($log) {
                case 0:
                    $this->success('注册成功', $redirect);
                    break;
                case 1:
                    $this->error("您的账户已注册过");
                    break;
                case 2:
                    $this->error("您输入的账号格式错误");
                    break;
                default :
                    $this->error('未受理的请求');
            }

        } else {
            $this->error("请求错误");
        }

    }
}