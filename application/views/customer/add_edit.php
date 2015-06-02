<section class="content-header">
    <h1>
        Users
        <small>
            <?=($this->router->fetch_method() == 'add')?'Add Customer':'Edit Customer'?>
        </small>
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
                <form id='customer_form' name='customer_form' role="form" action="" method="post">
                    <div class="form-group">
                        <label>First Name:</label>
                        <input type="text" placeholder="Enter first name" class="form-control validate[required]" name="fname" id="fname" value="<?=@$customer[0]->c_fname?>" >
                    </div>

                    <div class="form-group">
                        <label>Last Name:</label>
                        <input type="text" placeholder="Enter last name" id="lname" class="form-control" name="lname" value="<?=@$customer[0]->c_lname?>" >
                    </div>

                    <div class="form-group">
                        <label >Mobile:</label>
                        <input type="text" placeholder="Enter mobile number" id="phone" class="form-control validate[required,custom[integer],minSize[10],maxSize[10]]" name="phone" value="<?=@$customer[0]->c_phone?>" >
                    </div>

                    <div class="form-group">
                        <label >Email:</label>
                        <input type="email" placeholder="Enter email" id="email" class="form-control validate[required,custom[email]]" name="email" value="<?=@$customer[0]->c_email?>" >
                    </div>

					<div class="form-group">
                        <label >Address:</label>
                        <input type="text" placeholder="Enter address" id="address" class="form-control validate[required]" name="address" value="<?=@$customer[0]->c_address?>" >
                    </div>

					<div class="form-group">
                        <label >City:</label>
                        <input type="text" placeholder="Enter city" id="city" class="form-control" name="city" value="<?=@$customer[0]->c_city?>" >
                    </div>
					
					<div class="form-group">
                        <label >Is dealer:</label>
                        <input type="checkbox" <?=(@$customer[0]->c_type == "dealer")?"checked":""?> name='is_dealer' id='is_dealer'>
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
