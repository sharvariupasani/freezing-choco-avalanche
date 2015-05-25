$(document).ready(function() {
	var url = admin_path()+'dashboard/statistics';
	$.post(url,{},function(e){
		e = JSON.parse(e);

		var customer = e.customers;
		$("#cust_count").html(e.customers);

		var products = e.products;
		$("#prod_count").html(e.products);

		var takeins = e.takeins;
		var in_html = "";
		for(key in takeins)
		{
			in_html+=takeins[key].type.toUpperCase()+":"+takeins[key].cnt;
		}
		$("#tak_count").html(in_html);

		var invoices = e.invoices;
		$("#inv_count").html(invoices);
	});

	var url = admin_path()+'dashboard/latestProduct'; 
	$.post(url,{},function(e){
		e = JSON.parse(e);
		$("#products-list").html("");
		if (e.length > 0)
		{
			for(key in e)
			{
			var html = '<li class="item">';
			  html += '<div class="product-img">';
			  html += '<span class="label label-warning pull-right">'+e[key].brand+'</span>';
			  html += '</div>';
			  html += '<div class="product-info">';
				html += '<a href="javascript:void(0);" class="product-title">'+e[key].name+'<span class="pull-right">'+e[key].creation_date.split(" ")[0]+'</span></a>';
				html += '<span class="product-description">';
				  html += e[key].description;
				html += '</span>';
			  html += '</div>';
			html += '</li>';
			$("#products-list").append(html);
			}
		}
	});

	var url = admin_path()+'dashboard/latestTakein'; 
	$.post(url,{},function(e){
		e = JSON.parse(e);
		$("#takein-list").html("");
		if (e.length > 0)
		{
			for(key in e)
			{
			var html = '<tr>';
				  html += '<td>'+e[key].name+'</td>';
				  html += '<td>'+e[key].contact+'</td>';
				  html += '<td>'+e[key].s_imei+'</td>';
				  html += '<td>'+e[key].s_phonename+'</td>';
				  html += '<td><span class="label '+e[key].s_status+'">'+e[key].s_status+'</span></td>';
				html += '</tr>';
			$("#takein-list").append(html);
			}
		}
	});
});
