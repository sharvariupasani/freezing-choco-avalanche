var oTable;
$(document).ready(function() {
	oTable = $('#categoryTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": admin_path ()+'category/ajax_list/',
			"type": "POST"
		},
		aoColumnDefs: [
		  {
			 bSortable: false,
			 aTargets: [ -1 ]
		  }
		]
	} );

	$("body").delegate(".deal-category-status","click",function(e){
		e.preventDefault();
		var r = confirm("Are you sure you want to change status?");
		if (!r) {
			return false;
		}
		flag = ($(this).hasClass("active"))?0:1;
		var url = admin_path()+'category/categorystatusupdate/';
		var param = {id:$(this).data("dc_catid"),flag:flag};
		var span =$(this);
		$.post(url,param,function(e){
			if (e == "1")
			{
				if(flag)
				{	
					$(span).addClass("active").removeClass("inactive");
					$(span).attr('title',"active").attr("atl","active")
				}
				else
				{
					$(span).addClass("inactive").removeClass("active");
					$(span).attr('title',"inactive").attr("atl","inactive")
				}
			}
		})
	});
} );


function delete_category (del_id) {
	var r = confirm("Are you sure you want to delete?");
	if (!r) {
		return false;
	}
	$.ajax({
		type: 'post',
		url: admin_path()+'category/delete',
		data: 'id='+del_id,
		success: function (data) {
			if (data == "success") {
				oTable.fnClearTable(0);
				oTable.fnDraw();
				$("#flash_msg").html(success_msg_box ('Category deleted successfully.'));
			}else{
				$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
			}
		}
	});
}