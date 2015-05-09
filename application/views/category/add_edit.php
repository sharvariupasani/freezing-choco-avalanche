<section class="content-header">
    <h1>
        Category
        <small> <?=($this->router->fetch_method() == 'add')?'Add Category':'Edit Category'?></small>
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
                <form role="form" action="" method="post" id='category_form' name='category_form' enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Category Name:</label>
                        <input type="text" placeholder="Enter ..." class="form-control validate[required]" name="name" id="name" value="<?=@$category[0]->name?>" >
                    </div>
						
					<div class="form-group">
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
    </div>
</section>
