var oTable;
$(document).ready(function() {
    oTable = $('#dealsTable').dataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": admin_path ()+'dashboard/deal_list/',
            "type": "POST"
        },
		"order": [[ 4, "desc" ]],
        aoColumnDefs: [
          {
			 bSearchable: false,
             aTargets: [ -1 ]
          }
        ]
    } ).columnFilter({sPlaceHolder:"head:after",aoColumns: [{ type: "text" },null,null,null,null,null,null,null,null]});

	$("body").delegate(".deal-buy-status","click",function(e){
		e.preventDefault();
		var r = confirm("Are you sure you want to change status?");
		if (!r) {
			return false;
		}

		flag = ($(this).hasClass("active"))?0:1;
		var url = admin_path()+'deal/dealstatusupdate/';
		var param = {id:$(this).data("db_autoid"),flag:flag};
		var span =$(this);
		$.post(url,param,function(e){
			if (e == "1")
			{
				if(flag)
					$(span).addClass("active").removeClass("inactive");
				else
					$(span).addClass("inactive").removeClass("active");
			}
		})
	});
} );
