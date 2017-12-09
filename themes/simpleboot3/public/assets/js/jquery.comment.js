(function($){
	function crateCommentInfo(obj){
		if(typeof(obj.time) == "undefined" || obj.time == ""){
			obj.time = getNowDateFormat();
		}
		var el='';
		for(var g=0;g<obj.length;g++){

		
		el = el+ "<div class='comment-info'><header><img src='"+obj[g]['b_img']+"'></header><div class='comment-right'><h3>"+obj[g]['b_name']+"</h3>"
				+"<div class='comment-content-header'><span><i class='glyphicon glyphicon-time'></i>"+obj[g]['a_time']+"</span><span qf='"+obj[g]['qf']+"' ss='"+obj[g]['id']+"' class='reply-btn right'>回复</span>";
		
		el = el+"</div><p class='content'>"+obj[g]['a_nr']+"</p><div class='comment-content-footer'><div class='row'><div class='col-md-10'>";
		
		el = el + "</div><div class='col-md-2'></div></div></div><div class='reply-list'>";

		if(obj[g].replyBody != "" && obj[g].replyBody.length > 0){
			var arr = obj[g].replyBody;
			for(var j=0;j<arr.length;j++){
				var replyObj = arr[j];
				el = el+createReplyComment(replyObj,obj[g]['b_name']);
			}
		}
		el = el+"</div></div></div>";
		}
		return el;
	}
	
	//返回每个回复体内容
	function createReplyComment(reply,j){
		var replyEl = "<div class='reply'><div><a href='javascript:void(0)' class='replyname'>"+reply.pb_name+"</a>:<span>"+reply.pa_nr+"</span></div>"
						+ "<p><span>"+reply.pa_time+"</span> </p></div>";
		return replyEl;
	}
	function getNowDateFormat(){
		var nowDate = new Date();
		var year = nowDate.getFullYear();
		var month = filterNum(nowDate.getMonth()+1);
		var day = filterNum(nowDate.getDate());
		var hours = filterNum(nowDate.getHours());
		var min = filterNum(nowDate.getMinutes());
		var seconds = filterNum(nowDate.getSeconds());
		return year+"-"+month+"-"+day+" "+hours+":"+min+":"+seconds;
	}
	function filterNum(num){
		if(num < 10){
			return "0"+num;
		}else{
			return num;
		}
	}
	function replyClick(el,pid,qf){
		el.parent().parent().append("<div class='replybox'><textarea placeholder='来说几句吧......' class='mytextarea' ></textarea><span class='send'>发送</span></div>")
		.find(".send").click(function(){
			var content = $(this).prev().val();
			if(content != ""){
				var parentEl = $(this).parent().parent().parent().parent();
				var obj = new Object();
				obj.replyName="匿名";
				if(el.parent().parent().hasClass("reply")){
					console.log("1111");
					obj.beReplyName = el.parent().parent().find("a:first").text();
				}else{
					console.log("2222");
					obj.beReplyName=parentEl.find("h3").text();
				}
				obj.content=content;
				obj.time = getNowDateFormat();
				var replyString = createReplyComment(obj);
				$(".replybox").remove();
				parentEl.find(".reply-list").append(replyString).find(".reply-list-btn:last").click(function(){alert("不能回复自己");});
			}else{
				alert("空内容");
			}
		        $.post('/portal/Demand/pyb',{content:content,pid:pid,qf:qf},function(data){
		                if(data == 1){
		                     alert("评论成功");
		                     window.location.reload()
		                }else{
		                     alert("评论失败");
		                }
		        })
		});
	}
	
	
	$.fn.addCommentList=function(options){
		var defaults = {
			data:[],
			add:""
		}
		var option = $.extend(defaults, options);
		//加载数据
		if(option.data.length > 0){
			var dataList = option.data;
			var totalString = "";
			for(var i=0;i<dataList.length;i++){
				var obj = dataList[i];
				var objString = crateCommentInfo(obj);
				totalString = totalString+objString;
			}
			$(this).append(totalString).find(".reply-btn").click(function(){
				if($(this).parent().parent().find(".replybox").length > 0){
					$(".replybox").remove();
				}else{
					$(".replybox").remove();
					replyClick($(this),$(this).attr('ss'),$(this).attr('qf'));
				}
			});
			$(".reply-list-btn").click(function(){
				if($(this).parent().parent().find(".replybox").length > 0){
					$(".replybox").remove();
				}else{
					$(".replybox").remove();
					replyClick($(this),$(this).attr('ss'));
				}
			})
		}
		
		//添加新数据
		if(option.add != ""){
			obj = option.add;
			var str = crateCommentInfo(obj);
			$(this).prepend(str).find(".reply-btn").click(function(){
				replyClick($(this));
			});
		}
	}
	
	
})(jQuery);