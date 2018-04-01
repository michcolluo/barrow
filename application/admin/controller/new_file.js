$(document).ready(function(){
	$('#table_id').DataTable({
		"iDisplayLength" : 10,//默认每页数量
		//"bPaginate": true, //翻页功能
		"bLengthChange" : false, //改变每页显示数据数量
		//"bFilter" : true, //过滤功能
		"ordering" : false,
		"bSort" : false, //排序功能
		//"bInfo" : true,//页脚信息
		//"bAutoWidth" : true,//自动宽度
		"stateSave" : true,
		"retrieve" : true,
		"processing" : true,
		"serverSide" : true,
		//"bPaginate" : true,
		//"bProcessing": true//服务器端进行分页处理的意思
		ajax : function(data, callback,settings) {//ajax配置为function,手动调用异步查询
			$.ajax({
				type : "GET",
				url : "../serviceConfig/getServiceConfigByModel.do",
				cache : false, //禁用缓存
				data : data, //传入已封装的参数
				dataType : "json",
				success : function(res) {
					    //setTimeout仅为测试遮罩效果
					setTimeout(function() {
						            //封装返回数据，这里仅演示了修改属性名
						var returnData = {};
						returnData.draw = res.data.draw;//这里直接自行返回了draw计数器,应该由后台返回
						returnData.recordsTotal = res.data.recordsTotal;
						returnData.recordsFiltered = res.data.recordsFiltered;//后台不实现过滤功能，每次查询均视作全部结果
						returnData.data = res.data.data;
						//关闭遮罩
						//$wrapper.spinModal(false);
						//调用DataTables提供的callback方法，代表数据已封装完成并传回DataTables进行渲染
						//此时的数据需确保正确无误，异常判断应在执行此回调前自行处理完毕
						callback(returnData);
						},
					200);
				},
				error : function(XMLHttpRequest,textStatus,errorThrown) {
					$.alert("查询失败");
				}
			});
		},
		columns : [
			{data : "id"},
			{data : "code"},
			{data : "srvName"},
			{data : "url"},
			{data : "description"},
			{data : "isAllow"},
			{data : "state"},
			{data : "frequency"},
			{data : "createTime"},
			{data : "remark"},
			{
				data : "isDelete",
				render : function(data, type,row) {
					if (data == 0) {
						return "未删除";
					} else {
						return "删除";
					}
				}
		}],
		"columnDefs" : [{
		// 定义操作列,######以下是重点########
			"targets" : 11,//操作按钮目标列
			"data" : null,
			"render" : function(data,type, row) {
			var id = '"' + row.id
			+ '"';
			//<a href='javascript:void(0);'  class='delete btn btn-default btn-xs'  ><i class='fa fa-times'></i> 查看</a>
			var html = "<button class='btn btn-xs btn-success' onclick='add()'><i class='icon-ok'></i> </button>"
			html += "<button class='btn btn-xs btn-warning' onclick='edit("
			+ id
			+ ")'><i class='icon-pencil'></i> </button>"
			html += "<button class='btn btn-xs btn-danger' onclick='del("
			+ id
			+ ")'><i class='icon-remove'></i> </button>"
			return html;
			}
		}]
	});
});