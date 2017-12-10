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

class ApiController extends HomeBaseController
{
    public function _initialize()
    {
        header('Content-type: application/json');
    }
    public function title()
    {
        
        $title='长治市中小商贸流通企业公共服务数据分析平台';
        $ret[0]['value'] = $title;
        return json_encode($ret);
      
    }

    public function xq(){
        $get_ly = Db::query('select c.name as xzname,count(a.id) as coun,a.dj_state as djsta from pt_portal_fwxq as a left join pt_user as b on a.user_id = b.id LEFT JOIN pt_portal_xzqy as c on b.qy_area = c.id group by c.name,a.dj_state');
        return $get_ly;
    }

}
