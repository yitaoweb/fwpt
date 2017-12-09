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
namespace app\user\model;

use think\Db;
use think\Model;

class UserModel extends Model
{
    public function doMobile($user)
    {
        $userQuery = Db::name("user");

        $result = $userQuery->where('mobile', $user['mobile'])->find();


        if (!empty($result)) {
            $comparePasswordResult = cmf_compare_password($user['user_pass'], $result['user_pass']);
            $hookParam =[
                'user'=>$user,
                'compare_password_result'=>$comparePasswordResult
            ];
            hook_one("user_login_start",$hookParam);
            if ($comparePasswordResult) {
                //拉黑判断。
                if($result['user_status']==0){
                    return 3;
                }
                session('user', $result);
                $data = [
                    'last_login_time' => time(),
                    'last_login_ip'   => get_client_ip(0, true),
                ];
                $userQuery->where('id', $result["id"])->update($data);
                return 0;
            }
            return 1;
        }
        $hookParam =[
            'user'=>$user,
            'compare_password_result'=>false
        ];
        hook_one("user_login_start",$hookParam);
        return 2;
    }

    public function doName($user)
    {
        $userQuery = Db::name("user");

        $result = $userQuery->where('user_login', $user['user_login'])->find();
        if (!empty($result)) {
            $comparePasswordResult = cmf_compare_password($user['user_pass'], $result['user_pass']);
            $hookParam =[
                'user'=>$user,
                'compare_password_result'=>$comparePasswordResult
            ];
            hook_one("user_login_start",$hookParam);
            if ($comparePasswordResult) {
                //拉黑判断。
                if($result['user_status']==0){
                    return 3;
                }
                session('user', $result);
                $data = [
                    'last_login_time' => time(),
                    'last_login_ip'   => get_client_ip(0, true),
                ];
                $userQuery->where('id', $result["id"])->update($data);
                return 0;
            }
            return 1;
        }
        $hookParam =[
            'user'=>$user,
            'compare_password_result'=>false
        ];
        hook_one("user_login_start",$hookParam);
        return 2;
    }

    public function doEmail($user)
    {

        $userQuery = Db::name("user");

        $result = $userQuery->where('email', $user['email'])->find();


        if (!empty($result)) {
            $comparePasswordResult = cmf_compare_password($user['user_pass'], $result['user_pass']);
            $hookParam =[
                'user'=>$user,
                'compare_password_result'=>$comparePasswordResult
            ];
            hook_one("user_login_start",$hookParam);
            if ($comparePasswordResult) {

                //拉黑判断。
                if($result['user_status']==0){
                    return 3;
                }
                session('user', $result);
                $data = [
                    'last_login_time' => time(),
                    'last_login_ip'   => get_client_ip(0, true),
                ];
                $userQuery->where('id', $result["id"])->update($data);
                return 0;
            }
            return 1;
        }
        $hookParam =[
            'user'=>$user,
            'compare_password_result'=>false
        ];
        hook_one("user_login_start",$hookParam);
        return 2;
    }

    public function registerEmail($user)
    {
        $userQuery = Db::name("user");
        $userQuery->where('user_email', $user['user_email']);
        $userQuery->where('mobile', $user['mobile']);
        $condition['_logic'] = 'OR';
        $result    = $userQuery->find();

        $userStatus = 1;

        if (cmf_is_open_registration()) {
            $userStatus = 2;
        }

        $pass    = rand_number(0,999999);
        $qy_code = encode('user',2,$user['qy_area']);

        if (empty($result)) {
            $data  =[
                'user_type'                =>    3,
                'user_nickname'            =>    $user['user_nickname'],
                'qy_xydm'                  =>    $user['qy_xydm'],
              
                'qy_code'                  =>    $qy_code,
                'qy_area'                  =>    $user['qy_area'],
                'qy_address'               =>    $user['qy_address'],
          
                'qy_zip'                   =>    '046000',
                'user_email'               =>    $user['user_email'],
                'qy_zczj'                  =>    $user['qy_zczj'],
                'qy_zcsj'                  =>    $user['qy_zcsj'],
                
                'qy_faren'                 =>    $user['qy_faren'],
                'sex'                      =>    $user['sex'],
               
                'qy_lxname'                =>    $user['qy_lxname'],
                'tel'                      =>    $user['tel'],
                'mobile'                   =>    $user['mobile'],
                'fax'                      =>    $user['fax'],
                'user_url'                 =>    $user['user_url'],
                'qy_class'                 =>    $user['qy_class'],
                
                'qy_ssfw'                  =>    $user['qy_ssfw'],
               
                'qy_jianjie'               =>    $user['qy_jianjie'],
                'qy_anli'                  =>    $user['qy_anli'],
                'qy_rongyu'                =>    $user['qy_rongyu'],
                'coord'                    =>    $user['coord'],
                'fwqy'                     =>    json_encode($user['fwqy']),
                'last_login_time'          =>    time(),
                'score'                    =>    0,
                'avatar'                   =>    $user['jgavatar'],
                'create_time'              =>    time(),
                'user_status'              =>    $userStatus,
                'user_login'               =>    $qy_code,
                'user_pass'                =>    cmf_password($pass),
 

                'last_login_ip'            =>    get_client_ip(0, true),
                
                'daima'                    =>    $user['jgdaima'],
                'qy_shstat'                =>    0,

            ];
        

            $userId = Db::name("user")->insertGetId($data);
            $data   = Db::name("user")->where('id', $userId)->find();
            $subject="用户注册通知";
            $content="尊敬的机构用户".$user['user_nickname'].":<br>";
            $content=$content."您已成功注册，账号：".$qy_code."  密码：".$pass."<br>请牢记！";

            $result = cmf_send_email($user['user_email'], $subject, $content);
            cmf_update_current_user($data);
            return 0;
        }
        return 0;



        $userQuery = Db::name("user");
        $userQuery->where('user_email', $user['email']);
        $userQuery->where('mobile', $user['mobile']);
        $result    = $userQuery->find();

        $userStatus = 1;

        if (cmf_is_open_registration()) {
            $userStatus = 2;
        }

        if (empty($result)) {
            $data   = [
                'user_login'      => '',
                'user_email'      => $user['email'],
                'mobile'          => '',
                'user_nickname'   => '',
                'user_pass'       => cmf_password($user['user_pass']),
                'last_login_ip'   => get_client_ip(0, true),
                'create_time'     => time(),
                'last_login_time' => time(),
                'user_status'     => $userStatus,
                "user_type"       => 2,
            ];
            $userId = $userQuery->insertGetId($data);
            $date   = $userQuery->where('id', $userId)->find();
            cmf_update_current_user($date);
            return 0;
        }
        return 1;
    }

    public function registerMobile($user)
    {
        $userQuery = Db::name("user");
        $userQuery->where('user_email', $user['user_email']);
        $userQuery->where('mobile', $user['mobile']);
        $condition['_logic'] = 'OR';
        $result    = $userQuery->find();

        $userStatus = 1;

        if (cmf_is_open_registration()) {
            $userStatus = 2;
        }

        $pass    = rand_number(0,999999);
        $qy_code = encode('user',1,$user['qy_area']);

        if (empty($result)) {
            $data  =[
                'user_type'                =>    2,
                'user_nickname'            =>    $user['user_nickname'],
                'qy_xydm'                  =>    $user['qy_xydm'],
              
                'qy_code'                  =>    $qy_code,
                'qy_area'                  =>    $user['qy_area'],
                'qy_address'               =>    $user['qy_address'],
                'qy_sq'                    =>    $user['qy_sq'],
                'qy_zip'                   =>    '046000',
                'user_email'               =>    $user['user_email'],
                'qy_zczj'                  =>    $user['qy_zczj'],
                'qy_zcsj'                  =>    $user['qy_zcsj'],
                'qy_zxsj'                  =>    $user['qy_zxsj'],
                'qy_faren'                 =>    $user['qy_faren'],
                'sex'                      =>    $user['sex'],
                'birthday'                 =>    $user['birthday'],
                'qy_xueli'                 =>    $user['qy_xueli'],
                'qy_lxname'                =>    $user['qy_lxname'],
                'tel'                      =>    $user['tel'],
                'mobile'                   =>    $user['mobile'],
                'fax'                      =>    $user['fax'],
                'user_url'                 =>    $user['user_url'],
                'qy_class'                 =>    $user['qy_class'],
                'qy_zg_num'                =>    $user['qy_zg_num'],
                'qy_dz_num'                =>    $user['qy_dz_num'],
                'qy_kf_num'                =>    $user['qy_kf_num'],
                'qy_sshy'                  =>    $user['qy_sshy'],
                'qy_sme_class'             =>    $user['qy_sme_class'],
                'qy_jyfw'                  =>    $user['qy_jyfw'],
                'qy_jianjie'               =>    $user['qy_jianjie'],
                'coord'               =>    $user['coord'],
                'last_login_time'          =>    time(),
                'score'                    =>    0,
                'avatar'                   =>    $user['qyavatar'],
                'create_time'              =>    time(),
                'user_status'              =>    $userStatus,
                'user_login'               =>    $qy_code,
                'user_pass'                =>    cmf_password($pass),
 

                'last_login_ip'            =>    get_client_ip(0, true),
                
                'daima'                    =>    $user['qydaima'],
                'qy_shstat'                =>    0,

            ];
        

            $userId = Db::name("user")->insertGetId($data);
            $data   = Db::name("user")->where('id', $userId)->find();
            $subject="用户注册通知";
            $content="尊敬的企业用户".$user['user_nickname'].":<br>";
            $content=$content."您已成功注册，账号：".$qy_code."  密码：".$pass."<br>请牢记！";

            $result = cmf_send_email($user['user_email'], $subject, $content);
            cmf_update_current_user($data);
            return 0;
        }
        return 0;
    }

    /**
     * 通过邮箱重置密码s
     * @param $email
     * @param $password
     * @return int
     */
    public function emailPasswordReset($email, $password)
    {
        $result = $this->where('user_email', $email)->find();
        if (!empty($result)) {
            $data = [
                'user_pass' => cmf_password($password),
            ];
            $this->where('user_email', $email)->update($data);
            return 0;
        }
        return 1;
    }

    /**
     * 通过手机重置密码
     * @param $mobile
     * @param $password
     * @return int
     */
    public function mobilePasswordReset($mobile, $password)
    {
        $userQuery = Db::name("user");
        $result    = $userQuery->where('mobile', $mobile)->find();
        if (!empty($result)) {
            $data = [
                'user_pass' => cmf_password($password),
            ];
            $userQuery->where('mobile', $mobile)->update($data);
            return 0;
        }
        return 1;
    }

    public function editData($user)
    {
        $userId           = cmf_get_current_user_id();
     

        $userQuery        = Db::name("user");
        if ($userQuery->where('id', $userId)->update($user)) {
            $userInfo = $userQuery->where('id', $userId)->find();
            cmf_update_current_user($userInfo);
            return 1;
        }
        return 0;
    }

    public function supplyData($user)
    {
        $userId           = cmf_get_current_user_id();
     
        $user['user_id'] = $userId;
        $user['time'] =time();
        $userQuery        = Db::name("portal_gxfb");
        if ($userQuery->insertGetId($user)) {
            return 1;
        }
        return 0;
    }

    public function demandData($user)
    {
        $userId           = cmf_get_current_user_id();
     
        $user['user_id'] = $userId;
        // $user['time'] =time();
        $user['sh_state'] =0;
        $user['dj_state'] =0;
        $userQuery        = Db::name("portal_fwxq");
        if ($userQuery->insertGetId($user)) {
            return 1;
        }
        return 0;
    }

    public function productData($user)
    {
        $userId           = cmf_get_current_user_id();
     
        $user['user_id'] = $userId;
        $user['sh_state'] = 0;
        // $user['time'] =time();
        $userQuery        = Db::name("portal_fwcp");
        if ($userQuery->insertGetId($user)) {
            return 1;
        }
        return 0;
    }

    /**
     * 用户密码修改
     * @param $user
     * @return int
     */
    public function editPassword($user)
    {
        $userId    = cmf_get_current_user_id();
        $userQuery = Db::name("user");
        if ($user['password'] != $user['repassword']) {
            return 1;
        }
        $pass = $userQuery->where('id', $userId)->find();
        if (!cmf_compare_password($user['old_password'], $pass['user_pass'])) {
            return 2;
        }
        $data['user_pass'] = cmf_password($user['password']);
        $userQuery->where('id', $userId)->update($data);
        return 0;
    }

    public function comments()
    {
        $userId               = cmf_get_current_user_id();
        $userQuery            = Db::name("Comment");
        $where['user_id']     = $userId;
        $where['delete_time'] = 0;
        $favorites            = $userQuery->where($where)->order('id desc')->paginate(10);
        $data['page']         = $favorites->render();
        $data['lists']        = $favorites->items();
        return $data;
    }

    public function deleteComment($id)
    {
        $userId              = cmf_get_current_user_id();
        $userQuery           = Db::name("Comment");
        $where['id']         = $id;
        $where['user_id']    = $userId;
        $data['delete_time'] = time();
        $userQuery->where($where)->update($data);
        return $data;
    }

    /**
     * 绑定用户手机号
     */
    public function bindingMobile($user)
    {
        $userId      = cmf_get_current_user_id();
        $data ['mobile'] = $user['username'];
        Db::name("user")->where('id', $userId)->update($data);
        $userInfo = Db::name("user")->where('id', $userId)->find();
        cmf_update_current_user($userInfo);
        return 0;
    }

    /**
     * 绑定用户邮箱
     */
    public function bindingEmail($user)
    {
        $userId     = cmf_get_current_user_id();
        $data ['user_email'] = $user['username'];
        Db::name("user")->where('id', $userId)->update($data);
        $userInfo = Db::name("user")->where('id', $userId)->find();
        cmf_update_current_user($userInfo);
        return 0;
    }
}
