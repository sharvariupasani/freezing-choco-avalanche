<section class="content-header">
    <h1>
        Setting
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
		<div class='col-md-6'>
		<div class='box box-solid'>
    		<div class="box-body">
				<form role="form" action="" method="post" id='setting_form' >
	    			<div class="form-group">
                        <label>Firm Name:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="firmname" id="firmname" value="<?=$setting_data->firmname?>" >
                    </div>

                    <div class="form-group">
                        <label>Vat:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="firmvat" id="firmvat" value="<?=$setting_data->firmvat?>" >
                    </div>

                    <div class="form-group">
                       <label>Tax:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="firmtax" id="firmtax" value="<?=$setting_data->firmtax?>" >
                    </div>

					<div class="form-group">
                       <label>Remark options (takein):</label>
						<div class='remarks clearfix'>
							<?php $i=0; do { $remark = @$setting_data->takein_remark[$i]; ?>
							<div class="remarks_container ">
								<div class="col-md-10">
									<input type="text" placeholder="Enter ..." class="form-control validate[required] takein_remark" name="takein_remark[<?=$id?>]" id="takein_remark" value="<?=$remark?>" >
								</div>
								<div class="col-md-2">
									<a class='rem_remark' href="javascript:void(0)"><i class='fa fa-times'></i></a>
								</div>
							</div>
							<?php $i++;}while(count($setting_data->takein_remark)>$i) ?>
						</div>
						<div style='padding:10px;'>
						<center><button class="btn btn-primary btn-flat add_remark"  id="add_remark">Add More</button></center>
						</div>
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
