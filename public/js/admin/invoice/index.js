var oTable;
var hTable;
$(document).ready(function() {
	oTable = $('#invoiceTable').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": admin_path ()+'invoice/ajax_list/',
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


function delete_invoice (del_id) {
	var r = confirm("Are you sure you want to delete?");
	if (!r) {
		return false;
	}
	$.ajax({
		type: 'post',
		url: admin_path()+'invoice/delete',
		data: 'id='+del_id,
		success: function (data) {
			if (data == "success") {
				oTable.fnClearTable(0);
				oTable.fnDraw();
				$("#flash_msg").html(success_msg_box ('Bill deleted successfully.'));
			}else{
				$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
			}
		}
	});
}