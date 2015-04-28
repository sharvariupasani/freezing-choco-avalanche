<h2>Thank you for purchasing the deal @ django deals.</h2>
<p style='height:15px;'></p>
<p style='text-align:left;color:#999999;font-size:14px;font-weight:normal;line-height:19px;'>
	Thanks for giving us an opportunity to serve you! You're all set up. You can check <a class='link1' class='color:#382F2E;' href='<?=base_url()?>/how_it_works'>How it works</a> section to know more about working of the site.
	<br>
	<br>
	<span style="margin:0;font-family:Georgia,Time,sans-serif;font-size:25px;color:#222222;">Below are the details of the deal</span>
	<br>
	<br>
	<span>Dealer: <?=$dealer?></span>
	<br>
	<span>Offer: <?=$offer?></span>
	<br>
	<?php if ($valid_till != ""){?>
	<span>Validity till: <?=format_date($valid_till)?></span>
	<br>
	<?php }?>
	<span>MRP:(Original Price) <?=$price?></span>
	<br>
	<span>Qty: <?=$qty?></span>
	<br>
	<br>
	<?php foreach ($uniqueId as $key=>$val) {?>
	<a href="<?=base_url()?>deals/getprint/<?=$uid?>/<?=$val?>" target="_blank">Get Print</a>
	<br>
	<?php } ?>
	<br>
	<br>
	<?php /*if(is_array($address)) {?>
	<span style="margin:0;font-family:Georgia,Time,sans-serif;font-size:25px;color:#222222;">Below are the details shipping address</span>
	<br>
	<br>
	<span>First Name: <?=$address['da_firstname']?></span>
	<br>
	<span>Last Name: <?=$address['da_lastname']?></span>
	<br>
	<span>Address: <?=$address['da_address']?></span>
	<br>
	<span>CIty: <?=$address['da_city']?></span>
	<br>
	<span>State: <?=$address['da_state']?></span>
	<br>
	<span>Zip: <?=$address['da_pincode']?></span>
	<br>
	<span>Phone: <?=$address['da_phone']?></span>

	<?php }*/?>
</p>
