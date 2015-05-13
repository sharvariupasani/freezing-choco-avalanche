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
                        <label>Customer:</label>
                        <input type="text" placeholder="Search using mobile" class="form-control validate[required]" name="customer" id="customer" value="<?=@$takein[0]->brand?>" >
                    </div>
					
					<div class="form-group">
                        <label>Mobile Info:</label>
                        <input type="text" placeholder=" E.g., D335" class="form-control validate[required]" name="phonename" id="phonename" value="<?=@$takein[0]->s_phonename?>" >
                    </div>

					<div class="form-group">
                        <label>IMEI:</label>
                        <input type="text" placeholder=" Mobile IMEI" class="form-control validate[required]" name="imei" id="imei" value="<?=@$takein[0]->s_imei?>" >
                    </div>
					
					<div class="form-group">
                        <label>Remark:</label>
                         <textarea type="text" placeholder="Eg., Color, Size" class="form-control validate[required]" name="remark" id="remark"><?=@$takein[0]->s_remark?></textarea>
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