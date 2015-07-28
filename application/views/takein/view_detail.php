<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Authentication</h4>
</div>
<div class="modal-body clearfix">
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Takein Id</label>
		<div class="col-sm-7">
			<p><?=@$takein[0]->s_takeinid?></p>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Customer</label>
		<div class="col-sm-7">
			<p><?=@$customer->customer?></p>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Mobile</label>
		<div class="col-sm-7">
			<p><?=@$takein[0]->s_phonename?></p>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">IMEI</label>
		<div class="col-sm-7">
			<p><?=@$takein[0]->s_imei?></p>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Remark</label>
		<div class="col-sm-7">
			<p><?=str_replace("||",", ",@$takein[0]->s_remark)?></p>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Estimation</label>
		<div class="col-sm-7">
			<p><?=@$takein[0]->s_estimated_amt?></p>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Status</label>
		<div class="col-sm-7">
			<p><?=@$takein[0]->s_status?></p>
		</div>
	</div>
	<?php if(@$takein[0]->s_status == 'rejected'){?>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Reason</label>
		<div class="col-sm-7">
			<p><?=@$takein[0]->s_reason?></p>
		</div>
	</div>
	<?php }?>
</div>
<div class="modal-footer">
	<button id="printbtn" data-invid='<?=@$takein[0]->s_imei?>' class="btn btn-primary btn-flat" onclick="">Print</button>
	<button  data-dismiss="modal" class="btn btn-primary btn-flat" onclick="">Close</button>
</div>