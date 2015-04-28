<section class="content-header">
    <h1>
        Dealer
        <small> <?=($this->router->fetch_method() == 'add')?'Add Dealer':'Edit Dealer'?></small>
    </h1>
</section>
<section class="content">
	<div class="row">
    	<div class="col-md-6">
    		<div class="box-body">
                <?php
                    if (@$flash_msg != "") {
                ?>
                    <div id="flash_msg"><?=$flash_msg?></div>
                <?php
                    }
                ?>
                <form role="form" action="" method="post" id='dealer_form' name='dealer_form'>
                    <div class="form-group <?=(@$error_msg['de_userid'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['de_userid'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_userid']?></label><br/>
                        <?php
                            }
                        ?>
						<label>Select User</label>
						<select class="form-control validate[required]" id="de_userid" name="de_userid">
                            <option value="">Select</option>
							<?php foreach ($users as $user) { ?>
								<option value='<?=$user->du_autoid; ?>' <?=(@$dealer[0]->de_userid == $user->du_autoid)?'selected':''?> ><?=$user->du_uname." (".$user->du_email.")"; ?></option>
							<?php } ?>
						</select>
                    </div>
                    <div class="form-group <?=(@$error_msg['de_name'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['de_name'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_name']?></label><br/>
                        <?php
                            }
                        ?>
                        <label for="de_name">Dealer Name:</label>
                        <input placeholder="Enter Dealer Name" id="de_name" class="form-control validate[required]" name="de_name" value="<?=@$dealer[0]->de_name?>" >
                    </div>
					<div class="form-group <?=(@$error_msg['de_email'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['de_email'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_email']?></label><br/>
                        <?php
                            }
                        ?>
                        <label for="de_email">Email address:</label>
                        <input type="email" placeholder="Enter email" id="de_email" class="form-control validate[required,custom[email]]" name="de_email" value="<?=@$dealer[0]->de_email?>" >
                    </div>
					<div class="form-group <?=(@$error_msg['de_contact'] != '')?'has-error':'' ?>">
						<?php
                            if(@$error_msg['de_contact'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_contact']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Contact:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required,custom[phone]]" name="de_contact" id="de_contact" value="<?=@$dealer[0]->de_contact?>" >
                    </div>
					<div class="form-group <?=(@$error_msg['de_address'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['de_address'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_address']?></label><br/>
                        <?php
                            }
                        ?>
                        <label for="de_address">Address:</label>
                        <input placeholder="Enter Address" id="de_address" class="form-control validate[required]" name="de_address" value="<?=@$dealer[0]->de_address?>" >
                    </div>
					<div class="row">
						<div class="col-xs-4 form-group <?=(@$error_msg['de_city'] != '')?'has-error':'' ?>">
							<?php
								if(@$error_msg['de_city'] != ''){
							?>
								<label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_city']?></label><br/>
							<?php
								}
							?>
							<label for="de_city">City:</label>
							<input placeholder="Enter City" id="de_city" class="form-control validate[required]" name="de_city" value='<?=@$dealer[0]->de_city?>'>
						</div>
						<div class="col-xs-4 form-group <?=(@$error_msg['de_state'] != '')?'has-error':'' ?>">
							<?php
								if(@$error_msg['de_state'] != ''){
							?>
								<label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_state']?></label><br/>
							<?php
								}
							?>
							<label for="de_state">State:</label>
							<input placeholder="Enter State" id="de_state" class="form-control validate[required]" name="de_state" value='<?=@$dealer[0]->de_state?>'>
						</div>
						<div class="col-xs-4 form-group <?=(@$error_msg['de_zip'] != '')?'has-error':'' ?>">
							<?php
								if(@$error_msg['de_zip'] != ''){
							?>
								<label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_zip']?></label><br/>
							<?php
								}
							?>
							<label for="de_zip">Zip:</label>
							<input placeholder="Enter Zip" id="de_zip" class="form-control validate[required]" name="de_zip" value="<?=@$dealer[0]->de_zip?>">
						</div>
					</div >
					<div class="form-group">
                        <input type='checkbox' id="de_disp_address" class="form-control" name="de_disp_address" 
							<?= (@$dealer[0]->de_disp_address == "1" || @$dealer[0]->de_disp_address == "")?"checked":""?>
						>
						<label for="de_disp_address"> Display Contact Section</label>
                    </div>
					<div class="form-group">
						<div id="locationHolder" style="width: 100%; height: 400px;"></div>
					</div>
					<div class="form-group">
						<div class="form-group">
							<label for="de_address">Find LatLong by Address:</label>
							<input placeholder="Enter Address" id="de_address_tmp" class="form-control" value="<?=@$dealer[0]->de_address_tmp?>" >
						</div>
					</div>
					<div class="row">
						<div class="col-xs-5 form-group <?=(@$error_msg['de_lat'] != '')?'has-error':'' ?>">
							<?php
								if(@$error_msg['de_lat'] != ''){
							?>
								<label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_lat']?></label><br/>
							<?php
								}
							?>
							<label for="de_lat">Latitude:</label>
							<input placeholder="Enter Latitude" id="de_lat" class="form-control" name="de_lat" readonly value="<?=@$dealer[0]->de_lat?>">
						</div>
						<div class="col-xs-5 form-group <?=(@$error_msg['de_long'] != '')?'has-error':'' ?>">
							<?php
								if(@$error_msg['de_long'] != ''){
							?>
								<label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_long']?></label><br/>
							<?php
								}
							?>
							<label for="de_long">Longitude:</label>
							<input placeholder="Enter Longitude" id="de_long" class="form-control" name="de_long" readonly value="<?=@$dealer[0]->de_long?>">
						</div>
                    </div>
                    <div class="form-group <?=(@$error_msg['de_url'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['de_url'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['de_url']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>URL</label>
                        <input placeholder="Enter URL" id="de_url" class="form-control validate[custom[url]]" name="de_url" value="<?=@$dealer[0]->de_url?>" >
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-flat" type="submit" id="submit">Submit</button>
                    </div>
                </form>
            </div>
    	</div>
    </div>
</section>
