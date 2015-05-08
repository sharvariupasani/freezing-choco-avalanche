<section class="content-header">
    <h1>
        Product
        <small> <?=($this->router->fetch_method() == 'add')?'Add Product':'Edit Product'?></small>
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
    		<div class="box-body">
                <form role="form" action="" method="post" id='product_form' name='product_form' enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Category</label>
						<select name="category" id="category" class="form-control">
							<option value="">Select</option>
							<?php
							foreach($category as $cat) {
								$selected = "";
								if($cat->id == $product[0]->cat_id) $selected  = "selected";
							?>
							<option value="<?=$cat->id?>" <?=$selected?>><?=$cat->name?> </option>
							<?php } ?>
						</select>
                        <!--<input type="text" placeholder="Enter ..." class="form-control validate[required]" name="name" id="name" value="<?//@$category[0]->name?>" -->
                    </div>
					
					<div class="form-group">
                        <label>Brand Name:</label>
                        <input type="text" placeholder="E.g., LG, SONY" class="form-control validate[required]" name="brand" id="brand" value="<?=@$product[0]->brand?>" >
                    </div>
					
					<div class="form-group">
                        <label>Product Name:</label>
                        <input type="text" placeholder=" E.g., D335" class="form-control validate[required]" name="name" id="name" value="<?=@$product[0]->name?>" >
                    </div>
					
					<div class="form-group">
                        <label>Product Price:</label>
                         (Rs.)<input type="text" placeholder="E.g., 10000" class="form-control validate[required,custom[integer]]" name="price" id="price" value="<?=@$product[0]->price?>" >
                    </div>
						
					<div class="form-group">
                        <label>Product Detail:</label>
                        <textarea type="text" placeholder="Eg., Color, Size" class="form-control validate[required]" name="description" id="description"><?=@$product[0]->description?></textarea>
                    </div>
					
                    <div class="form-group">
                        <button class="btn btn-primary btn-flat" type="submit" id="submit">Submit</button>
                    </div>
                </form>
            </div>
    	</div>
    </div>
</section>
