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
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\model\PortalSsfwModel;
use app\portal\model\PortalXzqyModel;
use app\portal\model\PortalDjModel;
use think\Db;
class JghomeController extends HomeBaseController
{
    public function index()
    {

        $id = $this->request->param('id', 0, 'intval');
      
        $jigou = Db::name('user')->where('id',$id)->find();
        $product = Db::name('portal_fwcp')->where('user_id',$id)->order('time desc')->paginate(10);

		$this->assign($jigou);                 //机构简介
        $this->assign("page", $product->render());
        $this->assign("lists", $product->items());

        $listTpl ='jghome';

        return $this->fetch('/' . $listTpl);
    }

    public function product()
    {

        $id = $this->request->param('id', 0, 'intval');
        $pid = $this->request->param('pid', 0, 'intval');
        $jigou = Db::name('user')->where('id',$id)->find();
        $product = Db::name('portal_fwcp')->where('id',$pid)->find();
        $dd=Db::name('portal_dj');
        $portal_dj  = $dd->where('cq_id',$pid)->paginate(10);
        $fwxq=Db::name('portal_fwxq')->select();

        $get_ly = Db::query('select a.id as id,b.avatar as b_img,b.user_nickname as b_name,a.nr as a_nr,a.time as a_time from pt_ly as a left join pt_user as b on a.user_id = b.id where pid=0 and qf=3');
        $i = 0;
        foreach ($get_ly as $s) {          
            $get_ly[$i]['replyBody'] = Db::query('select a.id as pid,b.avatar as pb_img,b.user_nickname as pb_name,a.nr as pa_nr,a.time as pa_time from pt_ly as a left join pt_user as b on a.user_id = b.id where pid='.$s['id']);
            $i=$i+1;
        }

        $this->assign('get_ly', $get_ly);



        $this->assign("jigou",$jigou);                 //机构简介
        $this->assign("product",$product);               //产品详情
        $this->assign("fwxq",$fwxq); 
        $this->assign("page", $portal_dj->render());
        $this->assign("lists", $portal_dj->items()); 

        $listTpl ='product';

        return $this->fetch('/' . $listTpl);
    }
    public function dj()
    {
        $redirect = $this->request->post("redirect");
        $users = cmf_get_current_user();
        if (cmf_is_user_login()) {
            $id = $this->request->param('id', 0, 'intval');
            $jid = $this->request->param('jid', 0, 'intval');
         
            $xq = Db::name('portal_fwxq')->where('user_id',$users['id'])->select();

            $jigou = Db::name('user')->where('id',$jid)->find();

            $product = Db::name('portal_fwcp')->where('id',$id)->find();
            $this->assign("time", date('y-m-d h:i:s',time()));
            $this->assign("jigou",$jigou);                 //机构简介
            $this->assign("product",$product);               //产品详情
            $this->assign("xq",$xq);
            $this->assign("qy",$users);
            $listTpl ='jgdj';

            return $this->fetch('/' . $listTpl);
        } else {
            $this->error("此用户未登录!");
        }
        
    }
    public function post_dj()
    {
        $arrData = $this->request->param();
        $arrData['stat'] = 0;
        $portalTagModel = new PortalDjModel();
        $portalTagModel->isUpdate(false)->allowField(true)->save($arrData);

        $this->success("申请已提交");
    }

    public function ly(){
        $users = cmf_get_current_user();
        $ly = Db::name('ly');
        if(isset($users['id'])){
            $ret = $ly->insert(['fb_id'=>$users['id'],'js_id'=>$_POST['a'],'qf'=>3,'nr'=>$_POST['nr'],'time'=>date('y-m-dh:i:s',time())]);
            return $ret;
        }else{
            return '2';
        }
        
    }

}
