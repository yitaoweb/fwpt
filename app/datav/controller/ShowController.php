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
        if ((!empty($session_admin_id)) or (!empty(cmf_get_current_user()))) {
            $user = Db::name('user')->where(['id' => $session_admin_id])->find();
            $role_user = Db::name('role_user')->where(['user_id' => $session_admin_id])->find();
            if ($role_user['id'] >= 1) {
               $role = Db::name('role')->where(['id' => $role_user['role_id']])->find();
            }
            else{
                $role = Db::name('role')->where(['id' => 1])->find();
            }

            $this->assign("role", $role);
            $this->assign("admin", $user);
            $title='长治市中小商贸流通企业公共服务数据分析平台';
            $this->assign('title',$title);
        } 
        else {
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
        $qysjfx = Db::query('select sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse from pt_qysjfx');
        $qysjfxs = Db::query('select fl,sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse from pt_qysjfx group by fl');
        $qysjfxss = Db::query('select DATE_FORMAT(date,"%m") as time,sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse,sum(lse) as lse from pt_qysjfx group by DATE_FORMAT(date,"%m")');


        $pfids = '0'.getChildrenids(1);
        $lsids = '0'.getChildrenids(2);



        $pf = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl in ('.$pfids.')');
        $ls = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl in ('.$lsids.')');

        $zs = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl = 5');

        $cy = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl = 6');

        $this->assign('pf',$pf);
        $this->assign('ls',$ls);
        $this->assign('zs',$zs);
        $this->assign('cy',$cy);
        
        $this->assign('qysjfx',$qysjfx);
        $this->assign('qysjfxs',$qysjfxs);
        $this->assign('qysjfxss',$qysjfxss);
        return $this->fetch(':xsqy');
    }

    public function xsqy_sj()
    {
        $sub_title='限额以上企业-数据汇总分析';
        $this->assign('sub_title',$sub_title);
        $mres = Db::query('select DATE_FORMAT(date,"%m") as time,sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx group by DATE_FORMAT(date,"%m")');
        $yres = Db::query('select DATE_FORMAT(date,"%Y") as time,sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx group by DATE_FORMAT(date,"%Y")');
        $heji = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx');
        $this->assign('mres',$mres);
        $this->assign('yres',$yres);
        $this->assign('heji',$heji);
        return $this->fetch(':xsqy_sj');
    }

    public function xsqy_xf()
    {
        $sub_title='限额以上企业-消费结构分析';
        $this->assign('sub_title',$sub_title);

 

        $pfids = '0'.getChildrenids(1);
        $lsids = '0'.getChildrenids(2);



        $pf = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl in ('.$pfids.')');
        $ls = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl in ('.$lsids.')');

        $zs = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl = 5');

        $cy = Db::query('select count(id) as num, sum(tq)as tq,sum(tb)as tb,sum(hb)as hb,sum(xse)as xse ,sum(lse) as lse,sum(kfsr) as kfsr,sum(cfsr) as cfsr,sum(spxse) as spxse,sum(qtsr) as qtsr,sum(yysr) as yysr from pt_qysjfx where fl = 6');

        $yt = Db::query('select j.name , sum(q.tq)as tq,sum(q.tb)as tb,sum(q.hb)as hb,sum(q.xse)as xse ,sum(q.lse) as value,sum(q.kfsr) as kfsr,sum(q.cfsr) as cfsr,sum(q.spxse) as spxse,sum(q.qtsr) as qtsr,sum(q.yysr) as yysr  from pt_qysjfx as q left join pt_user as u  on q.user_id = u.id left join pt_portal_qyjs as j on u.qy_js = j.id where j.parent_id = 24 group by j.name');
        $this->assign('pf',$pf);
        $this->assign('ls',$ls);
        $this->assign('zs',$zs);
        $this->assign('cy',$cy);

        $this->assign('yt',$yt);


        return $this->fetch(':xsqy_xf');
    }

    public function xsqy_fb()
    {
        $sub_title='限额以上企业-网点分布分析';
        $this->assign('sub_title',$sub_title);

        $map = Db::query("select * from pt_user where user_type = 4  and lat <> '' order by create_time");

        $this->assign('map',$map);

        return $this->fetch(':xsqy_fb');
    }

    public function xsqy_zd()
    {
        $sub_title='限额以上企业-重点企业分析';
        $this->assign('sub_title',$sub_title);
        $zdqy = Db::query('select u.user_nickname, a.name, u.qy_js, count(q.id) as num, sum(q.tq)as tq,sum(q.tb)as tb,sum(q.hb)as hb,sum(q.xse)as xse ,sum(q.lse) as lse,sum(q.kfsr) as kfsr,sum(q.cfsr) as cfsr,sum(q.spxse) as spxse,sum(q.qtsr) as qtsr,sum(q.yysr) as yysr from pt_qysjfx as q left join pt_user as u on u.id = q.user_id left join pt_portal_xzqy as a on u.qy_area = a.id  group by u.user_nickname ');
        $this->assign('zdqy',$zdqy);
        return $this->fetch(':xsqy_zd');
    }

    public function xsqy_zs()
    {
        $sub_title='限额以上企业-增速分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':xsqy_zs');
    }

    //数据分析 - 经济运行情况数据分析
    public function jj()
    {
        $sub_title='经济运行情况数据分析';
        $this->assign('sub_title',$sub_title);
        $get_jj = Db::query('select sum(jysr) as jysr,sum(lrze) as lrze,sum(shsr) as shsr ,sum(zsyzdwe) as zsyzdwe,sum(sjlyswzjtze) as sjlyswzjtze,sum(gdzctze) as gdzctze,sum(jcke) as jcke ,sum(yjysyfzjf) as yjysyfzjf from pt_jjyxqk');

        $get_jjs = Db::query(' select DATE_FORMAT(pt_jjyxqk.date,"%m") as time,  sum(jysr) as jysr,sum(lrze) as lrze,sum(shsr) as shsr ,sum(zsyzdwe) as zsyzdwe,sum(sjlyswzjtze) as sjlyswzjtze,sum(gdzctze) as gdzctze,sum(jcke) as jcke ,sum(yjysyfzjf) as yjysyfzjf  from pt_jjyxqk group by DATE_FORMAT(pt_jjyxqk.date,"%m")');
        $this->assign('get_jj',$get_jj);
        $this->assign('get_jjs',$get_jjs);
        return $this->fetch(':jj');
    }


    //数据分析 - 服务数据页面
    public function fw()
    {
        $i=0;
        $sub_title='服务数据分析';
        $this->assign('sub_title',$sub_title);
        $xctjcoun = Db::query('select count(a.id) as xctjcoun from  pt_portal_fwcp as a LEFT JOIN pt_portal_ssfw as b on a.ssfw_id=b.id where b.parent_id=1 or a.ssfw_id=1');
        $flwqcoun = Db::query('select count(a.id) as flwqcoun from  pt_portal_fwcp as a LEFT JOIN pt_portal_ssfw as b on a.ssfw_id=b.id where b.parent_id=2 or a.ssfw_id=2');
        $syrzcoun = Db::query('select count(a.id) as syrzcoun from  pt_portal_fwcp as a LEFT JOIN pt_portal_ssfw as b on a.ssfw_id=b.id where b.parent_id=3 or a.ssfw_id=3');
        $swzxcoun = Db::query('select count(a.id) as swzxcoun from  pt_portal_fwcp as a LEFT JOIN pt_portal_ssfw as b on a.ssfw_id=b.id where b.parent_id=4 or a.ssfw_id=4');
        $rcpxcoun = Db::query('select count(a.id) as rcpxcoun from  pt_portal_fwcp as a LEFT JOIN pt_portal_ssfw as b on a.ssfw_id=b.id where b.parent_id=5 or a.ssfw_id=5');
        $xxfwcoun = Db::query('select count(a.id) as xxfwcoun from  pt_portal_fwcp as a LEFT JOIN pt_portal_ssfw as b on a.ssfw_id=b.id where b.parent_id=6 or a.ssfw_id=6');
        $coun = Db::query('select count(id) as coun from  pt_portal_fwcp');

        $this->assign('xctjcoun',$xctjcoun);
        $this->assign('flwqcoun',$flwqcoun);
        $this->assign('syrzcoun',$syrzcoun);
        $this->assign('swzxcoun',$swzxcoun);
        $this->assign('rcpxcoun',$rcpxcoun);
        $this->assign('xxfwcoun',$xxfwcoun);
        $this->assign('coun',$coun);
        return $this->fetch(':fw');
    }


    //数据分析 - 开发区项目数据页面
    public function xm()
    {
        $sub_title='开发区项目数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':xm');
    }


    //数据分析 - 开发区项目数据页面
    public function xm_info()
    {
        $m = $this->request->param('m', 1, 'intval');
        $t = $this->request->param('t', 1, 'intval');

        $year = date('Y');

        if ($t > 0) {
            if ($m == 13) {
                $xmlist = Db::query("select * from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = '".$t."'");

                $heji = Db::query("select  count(id) as num ,sum(tzze) as tz,sum(yzze) as yz ,sum(ncz) as cz,sum(nss) as ss ,sum(dwzj) as dw from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = '".$t."'");
            }else{
                $xmlist = Db::query("select * from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = '".$t."' and  date_format(date, '%m') = ".$m);
                $heji = Db::query("select  count(id) as num ,sum(tzze) as tz,sum(yzze) as yz ,sum(ncz) as cz,sum(nss) as ss ,sum(dwzj) as dw from pt_xm where date_format(date,'%Y')='".$year."' and xm_qf = '".$t."' and  date_format(date, '%m') = ".$m);
            }
            
        }else{
            if ($m == 13) {
                $xmlist = Db::query("select * from pt_xm where date_format(date,'%Y')='".$year."'");
                $heji = Db::query("select  count(id) as num ,sum(tzze) as tz,sum(yzze) as yz ,sum(ncz) as cz,sum(nss) as ss ,sum(dwzj) as dw from pt_xm where date_format(date,'%Y')='".$year."'");
            }else{
                $xmlist = Db::query("select * from pt_xm where date_format(date,'%Y')='".$year."'  and  date_format(date, '%m') = ".$m);
                $heji = Db::query("select  count(id) as num ,sum(tzze) as tz,sum(yzze) as yz ,sum(ncz) as cz,sum(nss) as ss ,sum(dwzj) as dw  from pt_xm where date_format(date,'%Y')='".$year."'  and  date_format(date, '%m') = ".$m);
            }
        }
        
        $sshy=Db::name('portal_xzqy')->where('1=1')->order('id')->select();
        $this->assign("fuwu",$sshy);
        $this->assign('xmlist',$xmlist);
        $this->assign('heji',$heji);

        $this->assign('y',$year);
        $this->assign('m',$m);
        $this->assign('t',$t);

        $sub_title='开发区项目数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':xminfo');
    }



    //数据分析 - 企业机构 百度地图gis页面
    public function map()
    {
        $sub_title='企业机构GIS数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':map');
    }

    //数据分析 - 企业机构 百度地图gis详情页面
    public function map_info()
    {
        $id = $this->request->param('id', 1, 'intval');
        $jigou = Db::name('user')->where('id',$id)->find();

        $this->assign($jigou);   

        $sub_title='企业机构GIS数据分析';
        $this->assign('sub_title',$sub_title);

        return $this->fetch(':mapinfo');
    }



}
