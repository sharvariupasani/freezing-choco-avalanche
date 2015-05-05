var oTable;
$(document).ready(function() {
	oTable = $('#productTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": admin_path ()+'product/ajax_list/',
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


function delete_product (del_id) {
	var r = confirm("Are you sure you want to delete?");
	if (!r) {
		return false;
	}
	$.ajax({
		type: 'post',
		url: admin_path()+'product/delete',
		data: 'id='+del_id,
		success: function (data) {
			if (data == "success") {
				oTable.fnClearTable(0);
				oTable.fnDraw();
				$("#flash_msg").html(success_msg_box ('Product deleted successfully.'));
			}else{
				$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
			}
		}
	});
}