<section class="content-header">
    <h1>
        Purchase
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
			<div class='box box-solid'>
    		<div class="box-body">
                <form role="form" action="" method="post" id='product_form' name='product_form' enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Category</label>
						<label class="form-control"><?=@$purchase[0]->cat_name?></label>
                    </div>
					
					<div class="form-group">
                        <label>Brand Name:</label>
						<label class="form-control"><?=@$purchase[0]->brand?></label>
                    </div>
					
					<div class="form-group">
                        <label>Product Name</label>
                        <label class="form-control"><?=@$purchase[0]->name?></label>
                    </div>
					
					<div class="form-group">
                        <label>Operation</label>
                        <select name='op' id='op' class='form-control validate[required]'>
								<option value='plus' <?=(@$purchase[0]->quantity > 0)?"selecter='selected'":""?>>Plus</option>
								<option value='minus'  <?=(@$purchase[0]->quantity > 0)?"":"selecter='selected'"?>>Minus</option>
						 </select>
                    </div>
						
					<div class="form-group">
                        <label>Quantity</label>
                        <input class="validate[required,custom[integer]] form-control" placeholder="Quantity" name="qty" type="text" value="<?=@$purchase[0]->quantity?>" />
                    </div>

					<div class="form-group">
                        <label>Vendor</label>
                        <input class="validate[required] form-control" placeholder="Vendor Name" name="vendor" type="text"  value='<?=@$purchase[0]->vendor?>'/>
                    </div>

					<div class="form-group">
                        <label>Description</label>
                        <input class="form-control" placeholder="Purchase Note" name="description" type="description" value='<?=@$purchase[0]->pp_description?>'/> 
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