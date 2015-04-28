
function doOrderImage(){
	var order = {};
	$('.newimg').each(function(k,v){
		imageid = $(this).attr('imgid');
		imageorder = k;
		order[k] = ({"dl_autoid":imageid,"dl_order":imageorder});
	});
	orderStr = JSON.stringify(order);
	$('#sortOrder').val(orderStr);
}

$(document).ready(function(){
	$(".textarea").wysihtml5({"font-styles": false,
	"emphasis": true,
	"lists": true,
	"html": true,
	"link": true,
	"image": false,
	"color": false
	});
	$("#deal_form").validationEngine();
	$('#deal_form').on('submit',function(e){
		var flag = $("#deal_form").validationEngine("validate");
		if (!flag)
			e.preventDefault();
		/*if ($("#dd_mainphoto").val() == "") {
			$('.dealuploaddiv').validationEngine('showPrompt', 'Please select main photo.', 'error', true);
			return false;
		}*/

		$('.offers_div').each(function(k,v){
			var offer_data = {};
			offer_data['do_autoid'] = $(this).find("#do_autoid").val();
			offer_data['do_offertitle'] = $(this).find("#do_offertitle").val();
			offer_data['do_listprice'] = $(this).find("#do_listprice").val();
			offer_data['do_originalprice'] = $(this).find("#do_originalprice").val();
			offer_data['do_discount'] = $(this).find("#do_discount").val();
			//console.log(JSON.stringify(offer_data));
			$(this).find('#offer_data').val(JSON.stringify(offer_data));
		});
	});

	$('#dd_validtilldate').datepicker({
		format: 'mm/dd/yyyy'
	});

	$('#dd_timeperiod').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
	//$('#dd_validtilldate').daterangepicker({singleDatePicker:true,format: 'MM/DD/YYYY',startDate:"-0m",endDate:"-0m"});


	$( "#img-container" ).sortable({stop: function( event, ui ) {doOrderImage();}});

	$( ".dd_tags").tagedit({
		//autocompleteURL: 'server/autocomplete.php',
	});

	if ($("#my-awesome-dropzone").length > 0)
	{
		setTimeout(function(){
				var myDropzone = Dropzone.forElement("#my-awesome-dropzone");
				myDropzone.on("success", function(file, res) {
					if (res.indexOf("Error:") === -1)
					{
						var file = JSON.parse(res);
						var html = "<li class='pull-left'>";
						html += "<img src='"+file.path+"' class='newimg' imgid = '"+file.id+"'>";
						html += "<br>";
						html += "<center><a class='removeimage' dl_autoid='"+file.id+"' href='#'><i class='fa fa-trash-o'></i></a></center></li>";
						$("#img-container").append(html);
						$('#newimages').val($('#newimages').val() +"," +file.id);
						doOrderImage();
					}
				});
		},1000)
	}

	$('#img-container').delegate("img",'click',function(){
		$('#img-container img').removeClass('selected');
		$(this).addClass('selected');
		$('#dd_mainphoto').val($(this).attr('imgid'));
	});

	var mainimgid = $('#dd_mainphoto').val();
	$('#img-container img[imgid="'+mainimgid+'"]').addClass('selected');

	$(document).delegate(".changeprice","blur",function(){
		var do_originalprice = parseInt($(this).closest("div.row").find(".do_originalprice").val());
		var do_listprice = parseInt($(this).closest("div.row").find(".do_listprice").val());
		if (!isNaN(do_originalprice) && do_originalprice != "" && !isNaN(do_listprice) && do_listprice != "")
		{
			discount = do_originalprice - do_listprice;
			if (discount >= 0)
				$(this).closest("div.row").find(".do_discount").val(discount);
		}
	});

	$('.addoffer').on('click',function(e){
		e.preventDefault();
		$clone = $('.offers_div:eq(0)').clone();
		$clone.find("input").val("");
		$clone.find(".removeoffer").attr("do_autoid","");
		$clone.find(".deal-offer-status").remove();
		$clone.insertAfter(".offers_div:last");
	});

	$(document).delegate('.removeoffer','click',function(e){
		e.preventDefault();
		if($('.removeoffer').length <= 1)
		{
			//$(this).closest('.offers_div').find('input').val();
			return;
		}
		var do_autoid = $(this).attr('do_autoid');
		if (do_autoid != "")
		{
			var r = confirm("Are you sure you want to delete?");
			if (!r) {
				return false;
			}
			remove_div = $(this);
			url = admin_path()+'deal/removeOffer',
			data = {id:do_autoid};
			$.post(url,data,function(e){
				if (e == "success") {
					$(remove_div).closest('.offers_div').remove();
					$("#flash_msg").html(success_msg_box ('Offer deleted successfully.'));
				}else{
					$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
				}
			});
		}
		else
		{
			$(this).closest('.offers_div').remove();
		}
	});

	$('#img-container').delegate(".removeimage","click",function(e){
		e.preventDefault();
		atag = $(this);
		dl_autoid = $(atag).attr('dl_autoid');
		url = admin_path()+'deal/removeImage',
		data = {id:dl_autoid};
		$.post(url,data,function(e){
			if (e == "success") {
				if ($('#dd_mainphoto').val() == dl_autoid)
				{
					$('#dd_mainphoto').val("");
				}
				$(atag).closest('li.pull-left').remove();
				$("#flash_msg").html(success_msg_box ('Image deleted successfully.'));
			}else{
				$("#flash_msg").html(error_msg_box ('An error occurred while processing.'));
			}
		});
	});

	$("body").delegate(".deal-offer-status","click",function(e){
		e.preventDefault();
		var r = confirm("Are you sure you want to change status?");
		if (!r) {
			return false;
		}
		flag = ($(this).hasClass("active"))?0:1;
		var url = admin_path()+'deal/offerstatusupdate/';
		var param = {id:$(this).data("do_autoid"),flag:flag};
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

	doOrderImage();
});
