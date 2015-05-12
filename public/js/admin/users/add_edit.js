$(document).ready(function(){
	$("#user_form").validationEngine();
	$("#role").on("change",function(){
		if ($(this).val() == 'd')
		{
			$("#dealer").show();
			$("input#customer").autocomplete({
				source: admin_path()+"customer/autocomplete",
				select: function( event, ui ) {
					$("#cust_id").val(ui.item.c_id);
					return true;
				},
				minLength: 1,
			});
		}
		else
		{
			$("#cust_id").val("");
			$("#customer").val("");
		}
	});

	if ($("#role").val() == "d")
	{
		$("#role").trigger("change");
	}

	$("#user_form").on("submit",function(e){
		if ($("#role").val() == "d" && $("#cust_id").val() == "")
		{
			e.preventDefault();
			$("#customer").validationEngine('showPrompt', 'Please select customer.', 'error', true);
		}
	});
});