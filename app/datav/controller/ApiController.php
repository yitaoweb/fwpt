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

    public function sdmap()
    {
        $get_ly = Db::query('select a.name,cast(sum(case when u.user_type = 2 then 1 else 0 end ) as signed) as qynum ,cast(sum(case when u.user_type = 3 then 1 else 0 end ) as signed) as jgnum ,count(l.id) as lxdnum , count(f.id) as fwnum , count(p.id) as cpnum from pt_portal_xzqy  as a left join pt_user as u on u.qy_area = a. id left join pt_portal_lxd as l on l.lxd_xzqy = a.id left join pt_portal_fwxq as f on f.user_id = u.id left join pt_portal_fwcp as p on p.user_id = u.id  where 1=1 group by a.name order by a.list_order desc');
        $data = array();
        foreach ($get_ly as $key => $value) {
            $data[$key]['name'] = $value['name'];
            $data[$key]['value'] =$value;
        }
        //$get_ly = Db::query('select a.name,convert(sum(case when u.user_type = 2 then 1 else 0 end ), as signed) as value from pt_portal_xzqy  as a left join pt_user as u on u.qy_area = a. id  where 1=1 group by a.name order by a.list_order desc');
        return ($data);
      
    }


    public function tongji(){
        $type = $this->request->param('type', 4, 'intval');
       
        $tjdata = tongji($type);//1 上月 2 本月 3 近15天 4 近30天 5 当天

        return $tjdata;
    }

    public function xm(){
        $get_ly = Db::query('select cast(sum(case when xm_qf = 1 then 1 else 0 end ) as signed) as zt ,cast(sum(case when xm_qf = 2 then 1 else 0 end ) as signed) as xq ,cast(sum(case when xm_qf = 3 then 1 else 0 end ) as signed) as nkg ,cast(sum(case when xm_qf = 4 then 1 else 0 end ) as signed) as xkg ,cast(sum(case when xm_qf = 5 then 1 else 0 end ) as signed) as zj ,cast(sum(case when xm_qf = 6 then 1 else 0 end ) as signed) as zd ,cast(sum(case when xm_qf = 7 then 1 else 0 end ) as signed) as tc from pt_xm');
  
        return $get_ly;
    }

    public function dj(){
        $get_dj = Db::query('select DATE_FORMAT(a.time,"%m") as time,count(case when a.stat=1 then 1 end)cgcoundj,count(case when a.stat=2 then 1 end)jjcoundj,count(case when a.stat=4 then 1 end)wccoundj from pt_portal_dj as a group by a.time,a.stat');
        return $get_dj;
    }

    public function xq(){
        $get_ly = Db::query('select b.name as fwxqname,count(a.id) as coun,count(case when a.dj_state=0 then 1 end)ddcoun,count(case when a.dj_state=1 then 1 end)zzcoun,count(case when a.dj_state=2 then 1 end)wccoun from pt_portal_fwxq as a left join pt_portal_ssfw as b on a.ssfw_id = b.id group by b.name,a.dj_state'); 
        return $get_ly;
    }

    public function cp(){
        $get_cp = Db::query('select b.name as fwname,count(a.id) as cpcoun from pt_portal_fwcp as a left join pt_portal_ssfw as b on a.ssfw_id = b.id group by b.name');
        return $get_cp;
    }


}
