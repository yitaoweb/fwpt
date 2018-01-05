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
        $get_dj = Db::query('select DATE_FORMAT(a.time,"%m") as time,count(case when a.stat=1 then 1 end)cgcoundj,count(case when a.stat=2 then 1 end)jjcoundj,count(case when a.stat=4 then 1 end)wccoundj from pt_portal_dj as a group by DATE_FORMAT(a.time,"%m")');
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

    public function xsqy(){
        $pfids = '0'.getChildrenids(1);
        $lsids = '0'.getChildrenids(2);



        $pf = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl in ('.$pfids.')');
        $ls = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl in ('.$lsids.')');

        $zs = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl = 5');

        $cy = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl = 6');

        $xsqy = array();

        $xsqy['pf'] = $pf;
        $xsqy['ls'] = $ls;
        $xsqy['zs'] = $zs;
        $xsqy['cy'] = $cy;



        $get_xsqy = Db::query('select cast(sum(case when fl = 1 then xse end) as signed )as ls ,cast(sum(case when fl = 2 then xse end) as signed ) as pf, cast(sum(case when fl = 3 then xse end ) as signed ) as zs, cast(sum(case when fl = 4 then xse end ) as signed ) as cy from pt_qysjfx');

        return $xsqy;
    }

    public function jjyxqk(){
        $jjyxqk = Db::query('select a.fl as fl,sum(a.gmzcz) as gmzcz from pt_jjyxqkmx as a group by a.fl');
        return $jjyxqk;
    }


    public function site_qs(){
       
        $tjdata = tongji2(5);//1 上月 2 本月 3 近15天 4 近30天 5 当天

        return $tjdata;
    }

    public function site_xm(){
        $year = date('Y');

        $zt = Db::query("select date_format(date,'%m') as m , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = 1 group by 
date_format(date, '%m')");
        
        $xqy = Db::query("select date_format(date,'%m') as m , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = 2 group by 
date_format(date, '%m')");
        $nkg = Db::query("select date_format(date,'%m') as m , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = 3 group by 
date_format(date, '%m')");
        $xkg = Db::query("select date_format(date,'%m') as m , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = 4 group by 
date_format(date, '%m')");
        $zj = Db::query("select date_format(date,'%m') as m , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = 5 group by 
date_format(date, '%m')");
        $zd = Db::query("select date_format(date,'%m') as m , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = 6 group by 
date_format(date, '%m')");

        $tc = Db::query("select date_format(date,'%m') as m , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = 7 group by 
date_format(date, '%m')");

        $thz = Db::query("select xm_qf , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' group by xm_qf ");

        $mhz = Db::query("select date_format(date,'%m') as m , count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' group by 
date_format(date, '%m')");

        $hz = Db::query("select  count(id) as num, sum(tzze) as tz,sum(yzze) as yz from pt_xm where date_format(date,'%Y')='".$year."' ");
        
        $data =array();

        for ($i=1; $i <= 8; $i++) { 
            for ($j=1; $j <= 13; $j++) { 
                $data[$i][$j][0]=$i;
                $data[$i][$j][1]=$j;
                $data[$i][$j][2]=0;
                $data[$i][$j][3]=0;
                $data[$i][$j][4]=0;

            }
        } 

        foreach ($mhz as $key => $value) {
            $data[1][(int)$value['m']][2]=(int)$value['num'];
            $data[1][(int)$value['m']][3]=(int)$value['tz'];
            $data[1][(int)$value['m']][4]=(int)$value['yz'];
        }

        foreach ($zt as $key => $value) {
            $data[2][(int)$value['m']][2]=(int)$value['num'];
            $data[2][(int)$value['m']][3]=(int)$value['tz'];
            $data[2][(int)$value['m']][4]=(int)$value['yz'];
        }

        foreach ($xqy as $key => $value) {
            $data[3][(int)$value['m']][2]=(int)$value['num'];
            $data[3][(int)$value['m']][3]=(int)$value['tz'];
            $data[3][(int)$value['m']][4]=(int)$value['yz'];
        }

        foreach ($nkg as $key => $value) {
            $data[4][(int)$value['m']][2]=(int)$value['num'];
            $data[4][(int)$value['m']][3]=(int)$value['tz'];
            $data[4][(int)$value['m']][4]=(int)$value['yz'];
        }

        foreach ($xkg as $key => $value) {
            $data[5][(int)$value['m']][2]=(int)$value['num'];
            $data[5][(int)$value['m']][3]=(int)$value['tz'];
            $data[5][(int)$value['m']][4]=(int)$value['yz'];
        }

        foreach ($zj as $key => $value) {
            $data[6][(int)$value['m']][2]=(int)$value['num'];
            $data[6][(int)$value['m']][3]=(int)$value['tz'];
            $data[6][(int)$value['m']][4]=(int)$value['yz'];
        }

        foreach ($zd as $key => $value) {
            $data[7][(int)$value['m']][2]=(int)$value['num'];
            $data[7][(int)$value['m']][3]=(int)$value['tz'];
            $data[7][(int)$value['m']][4]=(int)$value['yz'];
        }

        foreach ($tc as $key => $value) {
            $data[8][(int)$value['m']][2]=(int)$value['num'];
            $data[8][(int)$value['m']][3]=(int)$value['tz'];
            $data[8][(int)$value['m']][4]=(int)$value['yz'];
        }

        foreach ($thz as $key => $value) {
            $data[(int)$value['xm_qf']+1][13][2]=(int)$value['num'];
            $data[(int)$value['xm_qf']+1][13][3]=(int)$value['tz'];
            $data[(int)$value['xm_qf']+1][13][4]=(int)$value['yz'];
        }

        foreach ($hz as $key => $value) {
            $data[1][13][2]=(int)$value['num'];
            $data[1][13][3]=(int)$value['tz'];
            $data[1][13][4]=(int)$value['yz'];
        }



        $datas= array();

        for ($i=1; $i <= 8; $i++) { 
            for ($j=1; $j <= 13; $j++) { 
                $datas[]=$data[$i][$j];
            }
        } 

        return $datas;
    }


    public function site_map(){
        
        $map = Db::query("select * from pt_user where (user_type = 2 or user_type = 3 ) and lat <> '' order by create_time");

        return $map;
    }

    public function site_xsmap(){
        
        $map = Db::query("select * from pt_user where  user_type = 4 and lat <> '' order by create_time");

        return $map;
    }

}
