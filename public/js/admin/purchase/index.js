var oTable;
$(document).ready(function() {
	oTable = $('#purchaseTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": admin_path ()+'purchase/ajax_list/',
			"type": "POST"
		},
		aoColumnDefs: [
		  {
			 bSortable: false,
			 aTargets: [ -1 ]
		  }
		]
	} );
} );


function delete_purchase (del_id,flag) {
	var r = confirm("Are you sure you want to delete?");
	if (!r) {
		return false;
	}
	var url = admin_path()+'purchase/delete';
	flag = (typeof(flag)!="undefined")?flag:"";
	var data = {id:del_id,flag:flag};
	$.post(admin_path()+'purchase/delete', data,function(data){
		data  = JSON.parse(data);
		if (data.status == "success") {
				oTable.fnClearTable(0);
				oTable.fnDraw();
				$("#flash_msg").html(success_msg_box (data.message));
			}else{
				$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
			}
	})
}