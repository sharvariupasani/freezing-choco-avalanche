<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=8">
<TITLE>Created by BCL easyConverter SDK 3 (HTML Version)</TITLE>
<META name="generator" content="BCL easyConverter SDK 3.0.60">
<STYLE type="text/css">

body {margin-top: 0px;margin-left: 0px;}

#page_1 {margin: 10px auto;width: 759px;}

#page_1 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;}
#page_1 #img1 {height:70px;}




.dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}

.ft0{font: bold 35px 'Arial';line-height: 41px;}
.ft1{font: 16px 'Arial';line-height: 18px;}
.ft2{font: bold 16px 'Arial';line-height: 19px;}
.ft3{font: bold 14px 'Arial';line-height: 16px;}
.ft4{font: bold 18px 'Arial';line-height: 21px;}
.ft5{font: 13px 'Arial';line-height: 16px;}
.ft6{font: 1px 'Arial';line-height: 1px;}
.ft7{font: 15px 'Arial';line-height: 17px;}
.ft8{font: bold 15px 'Arial';line-height: 18px;}
.ft9{font: bold 19px 'Arial';line-height: 22px;}
.ft10{font: 14px 'Arial';line-height: 16px;}
.ft11{font: 20px 'Arial';line-height: 23px;}
.ft12{font: bold 17px 'Arial';line-height: 19px;}
.ft13{font: bold 20px 'Arial';line-height: 24px;}

.p0{text-align: left;padding-left: 456px;padding-right: 67px;margin-top: 37px;margin-bottom: 0px;text-indent: -172px;}
.p1{text-align: left;margin-top: 0px;margin-bottom: 0px;}
.p2{text-align: left;padding-left: 4px;margin-top: 45px;margin-bottom: 0px;}
.p3{text-align: left;padding-left: 4px;margin-top: 18px;margin-bottom: 0px;}
.p4{text-align: right;padding-right: 2px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p5{text-align: left;padding-left: 2px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p6{text-align: left;margin-top: 0px;margin-bottom: 0px;}
.p7{text-align: left;margin-top: 2px;margin-bottom: 0px;}

.td0{padding: 0px;margin: 0px;width: 352px;vertical-align: bottom;}
.td1{padding: 0px;margin: 0px;width: 343px;vertical-align: top;}
.td2{padding: 0px;margin: 0px;width: 369px;vertical-align: bottom;}
.td3{padding: 0px;margin: 0px;width: 232px;vertical-align: bottom;}
.td4{padding: 0px;margin: 0px;width: 19px;vertical-align: bottom;}
.td5{padding: 0px;margin: 0px;width: 350px;vertical-align: bottom;}

.tr0{height: 31px;}
.tr1{height: 19px;}
.tr2{height: 39px;}
.tr3{height: 20px;}
.tr4{height: 26px;}
.tr5{height: 44px;}
.tr6{height: 23px;}
.tr7{height: 25px;}

.t0{width: 695px;margin-left: 4px;margin-top: 49px;font: 15px 'Arial';}
.t1{width: 601px;margin-top: 53px;font: 16px 'Arial';}

</STYLE>
</HEAD>

<BODY>

<DIV id="page_1">
<table style='border:1px solid black;padding:3px;'>
<tr>
	<td><IMG src="<?=base_url()?>public/img/logo.png" id="img1"></td>
	<td><p class='ft0'>Voucher</p></td>
	<td>
		<p>
			<SPAN class="ft3">Code :</SPAN> <SPAN class='ft10'><?=$deal_detail[0]->db_uniqueid?></span>
			<br>
			<SPAN class="ft3">Date :</SPAN> <SPAN class='ft10'><?=format_date($deal_detail[0]->db_date)?></span>
		</P>
	</td>
</tr>
<tr>
	<td colspan=3><hr>
	</td>
</tr>
<tr>
	<td colspan=3>
		<div style='width:50%;float:left;'>
			<IMG src="<?=base_url().'/uploads/'.$deal_detail[0]->dl_url?>" style="width:95%;">
		</div>
		<div style='width:50%;float:left;'>
			<P class="p1 ft5"><SPAN class="ft8">Offer Purchased: </SPAN><?=$deal_detail[0]->do_offertitle?></P>
			<br>
			<P class="p1 ft10"><SPAN class="ft8">Dealer Name: </SPAN><?=$deal_detail[0]->de_name?></P>
			<br>
			<P class="p1 ft7"><SPAN class="ft11">Price </SPAN><?=$deal_detail[0]->do_originalprice?></P>
			<br>
			<P class="p1 ft7"><SPAN class="ft8">Valid till: </SPAN><?=format_date($deal_detail[0]->dd_validtilldate)?></P>
		</div>
	</td>
</tr>
<tr>
	<td colspan=3><hr>
	</td>
</tr>
<tr>
	<td colspan=3>
		<?php if ($deal_detail[0]->de_disp_address == "1" || $deal_detail[0]->de_disp_address == "") {?>
		<P class="p3 ft1"><SPAN class="ft2">Dealer Contact Details: </SPAN><?=$deal_detail[0]->de_name." ".$deal_detail[0]->de_address." ".$deal_detail[0]->de_city." ".$deal_detail[0]->de_state." ".$deal_detail[0]->de_zip?></P>
		<P class="p3 ft1"><SPAN class="ft2">Dealer link: </SPAN><?=$deal_detail[0]->de_url?></P>
		<?php } ?>
		<P class="p3 ft1"><SPAN class="ft2">Validity: </SPAN> <?=$deal_detail[0]->dd_conditions?></P>
		<P class="p3 ft1"><SPAN class="ft2">Policy: </SPAN> <?=$deal_detail[0]->dd_policy?></P>
	</td>
</tr>
<TR>
	<TD colspan=3 class="tr4 td2"><P class="p2 ft2">How it Works<SPAN class="ft1">:</SPAN></P></TD>
</TR>
<TR>
	<td colspan=3>
		<div style='width:50%;float:left;'><P class="p5 ft1">1) Print Voucher.</P></div>
		<div style='width:50%;float:left;'><P class="p1 ft7">2) Arrange appointment with dealer.</P></div>
	</TD>
</TR>
<TR>
	<td colspan=3>
		<div style='width:50%;float:left;'><P class="p5 ft1">3) Bring your voucher.</P></div>
		<div style='width:50%;float:left;'><P class="p1 ft7">4) Redeem and enjoy.</P></div>
	</TD>
</TR>
<tr>
	<td colspan=3><hr>
	</td>
</tr>
<tr>
	<td colspan=3><P class="p6 ft1"><SPAN class="ft2">Any Questions? </SPAN>Email us to connect2django@gmail.com</P>
	</td>
</tr>
<tr>
	<td colspan=3><hr>
	</td>
</tr>
</table>
</DIV>
</BODY>
</HTML>


