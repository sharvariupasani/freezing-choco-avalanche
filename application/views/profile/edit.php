<section class="content-header">
    <h1>
        Profile
         <small>Edit Profile</small>
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
	    		<form role="form" action="" method="post" id='profile_form' >
	    			<div class="form-group <?=(@form_error('du_uname') != '')?'has-error':'' ?>">
                        <?=form_error('du_uname') ?>
                        <label>Name:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="du_uname" id="du_uname" value="<?=$profile_data[0]->du_uname?>" >
                    </div>

                    <div class="form-group <?=(@form_error('du_email') != '')?'has-error':'' ?>">
                        <?=form_error('du_email') ?>
                        <label>Email:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="du_email" id="du_email" value="<?=$profile_data[0]->du_email?>" >
                    </div>

                    <div class="form-group <?=(@form_error('du_contact') != '')?'has-error':'' ?>">
                       <?=form_error('du_contact') ?>
                        <label>Contact:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="du_contact" id="du_contact" value="<?=$profile_data[0]->du_contact?>" >
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-flat" type="submit" id="submit">Submit</button>
                    </div>
	    		</form>
    		</div>
    	</div>
    </div>
</section>
