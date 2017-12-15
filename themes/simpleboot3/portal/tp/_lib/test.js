$(document).ready(function(){	
	var i=0;
	var sld=0;
	var res=0;
	var len=$("#issue").find("div.cnt").length;	
	var prog=100;  //$("#prog").css("width");
	var ht=420;
	var speed=500;
	var page="/themes/simpleboot3/portal/tp/data/result.html";
	
	function setporogress(j){			
		i+=j;
		i=(i<0)? 0:i;
		checkbtn();
		if(!$("#info").is(":animated")){			
			$("#tips span").html((i+1>len?len:i+1)+"\/"+len+"题");		
			var wh=$("#tips").get(0).offsetWidth;		
			var ress=Math.round(i*prog/len);		
			$("#ress").css({"width": ress+"px"});		
			$("#tips").css({"left":ress-Math.round(wh/2)+"px"});
			$("#issue").animate({"top": -i*ht + "px"},500);
			}
		}
		
	function selec(ele){		
		sld++;
		//alert(sld);
		ele= $(ele)? $(ele):ele;
		ele.parents("div.cnt").addClass("selected");
		ele.addClass("sel");
		res+=parseInt (ele.find("input").val());
		if(sld<len){
			// $("#temp").html("您目前得分是 "+res);		
			}else if(sld==len){
				// $("#temp").html("您最终得分是 "+res);		
				}
		}
		
	$("#prev").click(function(){
		checkbtn();					  
		if(i>0) {
			setporogress(-1)
			}
		})	
	$("#next").click(function(){
		checkbtn();					  
		if(sld>i) {
			setporogress(1)
			}
		})	
	
	$("#issue").find("li").click(function(){	
					var pss = $(this).attr("pss");
					var idss = $(this).attr("idss");
					$.post('tp',{pss:pss,idss:idss},function(data){
                          if(data == 2){
                                alert("您已投过票了");
                          }
                          if(data != 1 && data != 2){
                               alert("出错");
                          }
					})	  
			if(!$(this).parents("div.cnt").hasClass("selected")){					
				selec(this);				
				}			
			setporogress(1);	
			if(i==len){	
				    $.post('tps',{pss:pss,idss:idss},function(data){
                          if(data == 2){

                          }
                          if(data != 1 && data != 2){
                               alert("出错");
                          }
					})	
				result(res);
				}	
			return false;	
		})
	.hover(
		function(){$(this).addClass("hover")},
		function(){$(this).removeClass("hover")}
		)
	
	function result(k){
		var toload;
		if(k>=12 && k<=18) toload=page+" #re0";
		if(k>18 && k<=24) toload=page+" #re1";
		if(k>24 && k<=69) toload=page+" #re2";		
		$("#result").load(toload);		
		}
/*	$("div.selected").click(function(){
		$(this).find("li").click();
		})
	*/
	function warn(txt){
		$("#war").html(txt).fadeIn(500,function(){$("#war").html("").fadeOut(500)});		
		}
	
	
	function checkbtn(){
		if(i<=0){
			$("#prev").addClass("noprev");
			}else{
				$("#prev").removeClass("noprev");
				}
		if(sld<=i){
			$("#next").addClass("nonext");
			}else{
				$("#next").removeClass("nonext");
				}	
		}
	
	setporogress(i);
	
	

})