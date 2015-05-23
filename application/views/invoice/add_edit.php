<section class="content-header">
    <h1>
        Invoice
        <small> <?=($this->router->fetch_method() == 'add')?'Add':'Edit'?></small>
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
		<div class='col-md-12'>
		<form role="form" action="" method="post" id='invoice_form' name='product_form' enctype="multipart/form-data">
    	<div class="col-md-8">
				<div class='box box-solid'>
				<div class='box-header'>
						<h3 class='box-title'>Customer</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
                        <label>Customer:</label>
                        <input type="text" placeholder="Search using mobile" class="form-control validate[required]" name="customer" id="customer" value="<?=@$customer->customer?>" >
                        <input type="hidden" name="cust_id" id="cust_id" value="<?=@$customer->c_id?>" >
                    </div>
            </div>
			</div>

			<div class='box' type="product">
						<div class='box-header'>
							<h3 class='box-title'>Add Product</h3>
						</div>
						<div class='box-body'>
							<?php $i=0; do{ $product = @$products[$i];
							$disable = (@$product->p_id != "")?"disabled":"";
							?>
							<div class='product_div'>
								<div class="row">
									<div class="col-xs-7 form-group">
										<label>Product:</label>
										<input type="text" placeholder="Search product" id="p_name" class="product form-control" <?=$disable?> value="<?=@$product->title?>" >
										<input type="hidden" id="p_id" value="<?=@$product->p_id?>" name='product[<?=$i?>][p_id]'>
										<input type="hidden" id="p_oid" value="<?=@$product->id?>" name='product[<?=$i?>][p_oid]'>
									</div>

									<div class="col-xs-2 form-group">
										<label>Qty:</label>
										<input type="text" <?=$disable?> placeholder="Enter ..." id="p_qty" class="form-control" data-price="<?=@$product->price?>"  
										data-qty="<?=@$product->stock_onhand?>"value="<?=@$product->quantity?>" name='product[<?=$i?>][p_qty]'>
									</div>

									<div class="col-xs-2 form-group">
										<label>Price:</label>
										<input type="text" <?=$disable?> placeholder="Enter ..." id="p_price" class="form-control" value="<?=@$product->net_price?>" name='product[<?=$i?>][p_price]'>
									</div>

									<div class="col-xs-1 form-group">
										<label>&nbsp</label>
										<span class="form-control form-del"><a class="fa fa-trash-o removeproduct"href="#"></a></span>
									</div>
								</div>
							</div>
							<?php $i++; }while(count(@$products)>$i)?>
						</div>
						<div class='box-footer clearfix'>
							<button class="btn btn-default pull-right addproduct"><i class="fa fa-plus"></i>Add Another Product</button>
						</div>
					</div>
					<div class='box' type="service">
						<div class='box-header'>
							<h3 class='box-title'>Add Service</h3>
						</div>
						<div class='box-body'>
							<?php $i=0; do{ $service = @$services[$i];?>
							<div class='service_div'>
								<div class="row">
									<div class="col-xs-9 form-group">
										<label>Service:</label>
										<input type="text" placeholder="Service detail" id="s_name" class="service form-control" value="<?=@$service->service_name?>" name='service[<?=$i?>][s_name]' >
										<input type="hidden" id="s_oid" value="<?=@$service->id?>" name='service[<?=$i?>][s_oid]'>
									</div>

									<div class="col-xs-2 form-group">
										<label>Price:</label>
										<input type="text" placeholder="Rate" id="s_price" class="form-control" value="<?=@$service->net_price?>" name='service[<?=$i?>][s_price]'>
									</div>

									<div class="col-xs-1 form-group">
										<label>&nbsp</label>
										<span class="form-control form-del"><a class="fa fa-trash-o removeservice"href="#"></a></span>
									</div>
								</div>
							</div>
							<?php $i++; }while(count(@$services)>$i)?>
						</div>
						<div class='box-footer clearfix'>
							<button class="btn btn-default pull-right addproduct"><i class="fa fa-plus"></i>Add Another Service</button>
						</div>
					</div>
    	</div>

		<div class="col-md-4">
				<div class='box box-solid'>
					<div class='box-header'>
						<h3 class='box-title'>Summery</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label>Date </label>
							<input type='text' name='sale_date' id="sale_date" class='form-control validate[required]' value="<?=(@$invoice[0]->sale_date != "")?date('m/d/Y', strtotime(@$invoice[0]->sale_date)):"" ?>"/>
						</div>
						<div class="form-group" id='summery'>
							<table class="table">
								<tbody>
									<tr>
										<th style="width:50%">Subtotal:</th>
										<td><span>&#8377;</span><span id="subtotal">0</span></td>
									</tr>
									<tr>
										<th>Tax (9.3%)</th>
										<td><span>&#8377;</span><span id="tax">0</span></td>
									</tr>
									<tr>
										<th>Total:</th>
										<td><span>&#8377;</span><span id="total">0</span></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class='box-footer'>
						<div class="form-group">
							<input type="hidden" name="op" id="op" value="">
							<input type="hidden" name="takein_id" id="takein_id" value="<?=@$takein[0]->s_id?>">
							<button class="btn btn-primary btn-flat" type="submit" id="save">Save</button>
							<button class="btn btn-primary btn-flat" type="submit" id="print">Print</button>
						</div>
					</div>
				</div>
			</div>
			</form>
			</div>
    </div>
</section>