<section class="content-header">
    <h1>
        Users
        <small>
            <?=($this->router->fetch_method() == 'add')?'Add User':'Edit User'?>
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
                <form id='user_form' name='user_form' role="form" action="" method="post">
                    <div class="form-group">
                        <label>User Name:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="user_name" id="user_name" value="<?=@$user[0]->name?>" >
                    </div>

                    <div class="form-group">
                        <label for="email">Email address:</label>
                        <input type="email" placeholder="Enter email" id="email" class="form-control validate[required,custom[email]]" name="email" value="<?=@$user[0]->email?>" >
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role" id="role">
                            <option value="">Select</option>
                            <option value="a" <?=(@$user[0]->role == 'a')?'selected':''?> >Admin</option>
                            <option value="m" <?=(@$user[0]->role == 'm')?'selected':''?> >Moderator</option>
                            <option value="d" <?=(@$user[0]->role == 'd')?'selected':''?> >Dealer</option>
                        </select>
                    </div>
					<div class="form-group">
                        <label>Password:</label>
                        <input type="password" placeholder="Password" class="form-control validate[minSize[5],maxSize[15]]" name="password" id="password">
                    </div>
					<div class="form-group">
                        <label>Repeat Password:</label>
                        <input type="password" placeholder="Repeat Password" class="form-control validate[equals[password]]" name="re_password" id="re_password">
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
