$(document).ready(function() {
	$("#invoice_form").validationEngine();

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

	$('.product_div').each(function(){
		productAutocomp($(this).find("#p_name"));
	});

	$('.addproduct,.addservice').on('click',function(e){
		e.preventDefault();
		var type = $(this).closest('.box').attr("type");
		$clone = $('.'+type+'_div:eq(0)').clone();
		$clone.find("input").val("");
		productAutocomp($clone.find("#p_name"));
		$clone.insertAfter("."+type+"_div:last");
	});

	$(document).delegate('.removeproduct,.removeservice','click',function(e){
		e.preventDefault();
		var type = $(this).closest('.box').attr("type");
		if($('.remove'+type).length <= 1)
		{
			return;
		}
		$(this).closest('.'+type+'_div').remove();
	});
});

function productAutocomp(obj){
	$(obj).autocomplete({
			source: admin_path()+"product/autocomplete",
			select: function( event, ui ) {
				$(event.target).closest(".row").data("info",ui.item);
				return true;
			},
			minLength: 1,
			change: function (event, ui) {
				if (ui.item == null || ui.item == undefined) {
					$(event.target).val("");
				}
			}
	});
}