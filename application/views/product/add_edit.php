<section class="content-header">
    <h1>
        Product
        <small> <?=($this->router->fetch_method() == 'add')?'Add Product':'Edit Product'?></small>
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
                <form role="form" action="" method="post" id='product_form' name='product_form' enctype="multipart/form-data">
                    <div class="form-group <?=(@$error_msg['category'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['category'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['category']?></label><br/>
                        <?php
                            }
                        ?>
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
					
					<div class="form-group <?=(@$error_msg['brand'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['brand'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['brand']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Brand Name:</label>
                        <input type="text" placeholder="E.g., LG, SONY" class="form-control validate[required]" name="brand" id="brand" value="<?=@$product[0]->brand?>" >
                    </div>
					
					<div class="form-group <?=(@$error_msg['name'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['name'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['name']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Product Name:</label>
                        <input type="text" placeholder=" E.g., D335" class="form-control validate[required]" name="name" id="name" value="<?=@$product[0]->name?>" >
                    </div>
					
					<div class="form-group <?=(@$error_msg['price'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['price'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['price']?></label><br/>
                        <?php
                            }
                        ?>
                        <label>Product Price:</label>
                         (Rs.)<input type="text" placeholder="E.g., 10000" class="form-control validate[required]" name="price" id="price" value="<?=@$product[0]->price?>" >
                    </div>
						
					<div class="form-group <?=(@$error_msg['description'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['description'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['description']?></label><br/>
                        <?php
                            }
                        ?>
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
