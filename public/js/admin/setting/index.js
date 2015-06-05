$(document).ready(function(){
	$("#add_remark").on("click",function(e){
		e.preventDefault();
		var count = $(".remarks_container").length;
		var remarkObj = $(".remarks_container:eq(0)").clone();
		remarkObj.find("#takein_remark").attr("name","takein_remark["+count+"]");
		remarkObj.find("#takein_remark").val("");
		$('.remarks').append(remarkObj);
	});

	$(".remarks").delegate(".rem_remark","click",function(){
		if ($(".remarks_container").length > 1)
			$(this).closest(".remarks_container").remove();
		else
			$("#takein_remark").val("");
	});
});