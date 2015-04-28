<section class="content-header">
    <h1>
        Category
        <small> <?=($this->router->fetch_method() == 'add')?'Add Category':'Edit Category'?></small>
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
                <form role="form" action="" method="post" id='category_form' name='category_form' enctype="multipart/form-data">
                    <div class="form-group <?=(@$error_msg['dc_catname'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['dc_catname'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['dc_catname']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Category Name:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="dc_catname" id="dc_catname" value="<?=@$category[0]->dc_catname?>" >
                    </div>
					<div class='form-group'>
						<label for="username">Category Picture: </label> 
						<input type="file" name="category_picture">
					</div>
					<div class='form-group'>
						<?=@$error_msg['category_picture']?>
						<?php
							if (file_exists(DOC_ROOT_CATEGORY_IMG.@$category[0]->dc_catimg) && @$category[0]->dc_catimg != "") {
						?>
							<img src="<?=category_img_path().$category[0]->dc_catimg?>" style="height:50px; width:50px;">
						<?php
							}
						?>	
					</div>	
					<div class="form-group <?=(@$error_msg['dc_catdetails'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['dc_catdetails'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['dc_catdetails']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Category Detail:</label>
                        <textarea type="text" placeholder="Category detail here" class="form-control validate[required]" name="dc_catdetails" id="dc_catdetails"><?=@$category[0]->dc_catdetails?></textarea>
                    </div>
					<div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="dc_status" id="dc_status">
                            <option value="1" <?=(@$category[0]->dc_status == '1')?'selected="selected"':''?> >Active</option>
                            <option value="0" <?=(@$category[0]->dc_status == '0')?'selected="selected"':''?> >Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-flat" type="submit" id="submit">Submit</button>
                    </div>
                </form>
            </div>
    	</div>
    </div>
</section>
