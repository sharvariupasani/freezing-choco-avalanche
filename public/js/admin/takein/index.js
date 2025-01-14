var oTable;
$(document).ready(function() {
	oTable = $('#takeinTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": admin_path ()+'takein/ajax_list/',
			"type": "POST"
		},
		aoColumnDefs: [
		  {
			 bSortable: false,
			 aTargets: [ -1,-2,2 ]
		  }
		]
	} ).columnFilter({sPlaceHolder:"head:after",aoColumns: [null,null,{ type: "text"},null,null,null,null,{ type: "select",values:["done","taken","repaired","rejected"] },null]});;

	$("#generateBill").on("click",function(){
		if ($("input[type='checkbox']:checked").length > 0)
		{
			takein = Array();
			$("input[type='checkbox']:checked").each(function(){
				takein.push($(this).attr("id"));
			});
			takein = takein.join("_");
			location.href = admin_path()+"invoice/add/"+takein;
		}
		else
		{
			alert("Please select at least one take in to generate bill.");
		}
	});
} );


function delete_takein (del_id) {
	var r = confirm("Are you sure you want to delete?");
	if (!r) {
		return false;
	}
	$.ajax({
		type: 'post',
		url: admin_path()+'takein/delete',
		data: 'id='+del_id,
		success: function (data) {
			if (data == "success") {
				oTable.fnClearTable(0);
				oTable.fnDraw();
				$("#flash_msg").html(success_msg_box ('Deleted successfully.'));
			}else{
				$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
			}
		}
	});
}

function update_status_popup(id,status){
	$("#passkey").val("");
	$("#reason").val("");
	$(".response").html("");
	try{
	$("#passkey").validationEngine('hideAll');
	}catch (e){}
	$("#rejectbtn").attr("onclick","update_status('"+id+"','"+status+"')");
	$('#status-modal').modal('toggle');
}

function update_status (id,status) {
	var url = admin_path()+'takein/updateStatus';
	var param = {id:id};
	if (status)
	{
		if (status == 'rejected')
		{
			if ($("#passkey").val() == '')
			{
				$("#passkey").validationEngine('showPrompt', 'Please select password.', 'error', true);
				return;
			}
			param.pass  = $("#passkey").val();
			param.reason = $("#reason").val();
		}
			
		param.status = status;
	}
	$.post(url,param,function(data){
			if (data == "success") {
				oTable.fnClearTable(0);
				oTable.fnDraw();
				$('#status-modal').modal('hide');
			}else
			{
				$(".response").html("Operation failed.");
			}
	});
}

function mergeTakein(obj)
{
	var exclass = $(obj).attr('class');
	console.log($(obj).is(":checked"))
	if ($(obj).is(":checked"))
	{
		$("input[type='checkbox']").not("."+exclass).prop("checked",false).prop("disabled",true);
	}
	else
	{
		if ($("."+exclass+":checked").length == 0 )
		{
			$("input[type='checkbox']").prop("disabled",false);
		}
	}
}