$(document).ready(function() {
	$("#takein_form").validationEngine();

	$("input#customer").autocomplete({
			source: admin_path()+"customer/autocomplete",
			select: function( event, ui ) {
				$("#cust_id").val(ui.item.c_id);
				return true;
			},
			minLength: 1,
			change: function (event, ui) {
				if (ui.item == null || ui.item == undefined) {
					$("input#customer").val("");
				}
			}
	});

	$("#takein_form").on("submit",function(e){
		var len = $("input[type='checkbox'][name='remark[]']:checked").length;
		if (len == 0 && $("#remark").val() == "")
		{
			e.preventDefault();
			$("#remark").validationEngine('showPrompt', 'Please select or enter remark.', 'error', true);
		}
	})
});