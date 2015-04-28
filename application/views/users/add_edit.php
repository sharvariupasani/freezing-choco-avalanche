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
    	<div class="col-md-6">
    		<div class="box-body">
                <?php
                    if (@$flash_msg != "") {
                ?>
                    <div id="flash_msg"><?=$flash_msg?></div>
                <?php
                    }
                ?>
                <form id='user_form' name='user_form' role="form" action="" method="post">
                    <div class="form-group <?=(@$error_msg['user_name'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['user_name'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['user_name']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>User Name:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="user_name" id="user_name" value="<?=@$user[0]->du_uname?>" >
                    </div>
                    <div class="form-group <?=(@$error_msg['email'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['email'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['email']?></label><br/>
                        <?php
                            }
                        ?>
                        <label for="email">Email address:</label>
                        <input type="email" placeholder="Enter email" id="email" class="form-control validate[required,custom[email]]" name="email" value="<?=@$user[0]->du_email?>" >
                    </div>
                    <div class="form-group">
                        <label>Contact:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required,custom[phone]]" name="contact" id="contact" value="<?=@$user[0]->du_contact?>">
                    </div>
                    <div class="form-group <?=(@$error_msg['role'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['role'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['role']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Role</label>
                        <select class="form-control" name="role" id="role">
                            <option value="">Select</option>
                            <option value="a" <?=(@$user[0]->du_role == 'a')?'selected':''?> >Admin</option>
                            <option value="m" <?=(@$user[0]->du_role == 'm')?'selected':''?> >Moderator</option>
                            <option value="d" <?=(@$user[0]->du_role == 'd')?'selected':''?> >Dealer</option>
                            <option value="u" <?=(@$user[0]->du_role == 'u')?'selected':''?> >User</option>
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
</section>
