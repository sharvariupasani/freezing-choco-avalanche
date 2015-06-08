<section class="content-header">
    <h1>
        Take In
        <small> <?=($this->router->fetch_method() == 'add')?'Add':'Edit'?></small>
    </h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php
				if (@$flash_msg != "") {
			?>
				<div id="flash_msg"><?=$flash_msg?></div>
			<?php
				}
			?>

			<?php
				if (@$error_msg != "") {
			?>
				<div id="error_msg" class='alert alert-warning alert-dismissable'>
						<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
						<h4><i class="icon fa fa-warning"></i>Alert!</h4>
						<?=$error_msg?>
				</div>
			<?php
				}
			?>
		</div>
    	<div class="col-md-6">
			<div class='box box-solid'>
    		<div class="box-body">
                <form role="form" action="" method="post" id='takein_form' name='product_form' enctype="multipart/form-data">
	
					<div class="form-group">
                        <label>Takein id:</label>
                        <input type="text" class="form-control" name="takein_id" id="takein_id" value="<?=(@$takein[0]->s_takeinid)?@$takein[0]->s_takeinid:@$takeinid ?>" disabled>
                    </div>

					<div class="form-group">
                        <label>Customer:</label>
                        <input type="text" placeholder="Search using mobile" class="form-control validate[required]" name="customer" id="customer" value="<?=@$customer->customer?>" <?=($is_dealer)?"disabled":""?>>
                        <input type="hidden" name="cust_id" id="cust_id" value="<?=@$customer->c_id?>" >
                    </div>
					
					<div class="form-group">
                        <label>Mobile Info:</label>
                        <input type="text" placeholder=" E.g., Nokia 3315" class="form-control validate[required]" name="phonename" id="phonename" value="<?=@$takein[0]->s_phonename?>" >
                    </div>

					<div class="form-group">
                        <label>IMEI:</label>
                        <input type="text" placeholder="Mobile IMEI 15 digit" class="form-control validate[required,minSize[15],maxSize[15]]" name="imei" id="imei" value="<?=@$takein[0]->s_imei?>" >
                    </div>
					
					<div class="form-group">
                        <label>Remark:</label>
						<div class='row'>
						<?php 
							$s_remarks = @$takein[0]->s_remark;
							$s_remarks = explode("||",$s_remarks);
							$remarks = json_decode(getSetting("takein_remark"));
							foreach ($remarks as $remark)
							{
								$checked = "";
								if (($key = array_search($remark,$s_remarks)) !== false) { 
									unset($s_remarks[$key]);
									$checked  = "checked";
								}
						?>
							<div class='col-md-5' style='margin:5px;'>
								<input type="checkbox" <?=$checked ?> value="<?=$remark?>" name="remark[]">
								<span style='margin-left:5px;'><?=$remark?></span>
							</div>
						<?php }?>
						</div>
                         <textarea type="text" placeholder="Eg.,Damage note" class="form-control" name="remark[]" id="remark"><?=implode(",",$s_remarks)?></textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-flat" type="submit" id="submit">Submit</button>
                    </div>
                </form>
            </div>
			</div>
    	</div>
    </div>
</section>