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
                    <div class="form-group <?=(@$error_msg['name'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['name'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['name']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Category Name:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="name" id="name" value="<?=@$category[0]->name?>" >
                    </div>
						
					<div class="form-group <?=(@$error_msg['description'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['description'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['description']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Category Detail:</label>
                        <textarea type="text" placeholder="Category detail here" class="form-control validate[required]" name="description" id="description"><?=@$category[0]->description?></textarea>
                    </div>
					
                    <div class="form-group">
                        <button class="btn btn-primary btn-flat" type="submit" id="submit">Submit</button>
                    </div>
                </form>
            </div>
    	</div>
    </div>
</section>
