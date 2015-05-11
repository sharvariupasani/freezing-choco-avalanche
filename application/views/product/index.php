<section class="content-header">
    <h1>
        Product
    </h1>
</section>
<section class="content">
	<div class="row">
    	<div class="col-md-12">
    		<div id="flash_msg">
	    		<?php
					if ($this->session->flashdata('flash_type') == "success") {
						echo success_msg_box($this->session->flashdata('flash_msg'));
					}

					if ($this->session->flashdata('flash_type') == "error") {
						echo error_msg_box($this->session->flashdata('flash_msg'));
					}
				?>
			</div>	
    		<a class="btn btn-default pull-right" href="<?=base_url()."product/add"?>">
            <i class="fa fa-plus"></i>&nbsp;Add Product</a>
            <div id="list">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Product list</h3>                                    
					</div><!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="productTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Category</th>
									<th>Brand</th>
									<th>Name</th>
									<th>Description</th>
									<th>On-hand Stock</th>
									<th>Price</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Category</th>
									<th>Brand</th>
									<th>Name</th>
									<th>Description</th>
									<th>On-hand Stock</th>
									<th>Price</th>
									<th>Action</th>
								</tr>
							</tfoot>
						</table>
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div>
    	</div>
    </div>
</section>


<div id="modals">
    <div id="add-purchase" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="ajax form-horizontal" action='<?=base_url()."purchase/add"?>' method='post' data-success="true" data-validate="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add Purchase</h4>
                    </div>
                    <div class="modal-body">
						<div class="form-group">
                            <label class="control-label col-sm-4">Operation</label>
                            <div class="col-sm-7">
                                <select name='op' id='op' class='form-control validate[required]'>
									<option value='plus'>Plus</option>
									<option value='minus'>Minus</option>
								</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Quantity</label>
                            <div class="col-sm-7">
                                <input class="validate[required,custom[integer]] form-control" placeholder="Quantity" name="qty" type="text" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Vendor</label>
                            <div class="col-sm-7">
                                <input class="validate[required] form-control" placeholder="Vendor Name" name="vendor" type="text" />
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-sm-4">Description</label>
                            <div class="col-sm-7">
                                <input class="form-control" placeholder="Purchase Note" name="description" type="description" /> 
                            </div>
                        </div>
                        <p class="response" style=""></p>
                    </div>
                    <div class="modal-footer">
                        <input type='hidden' name='product_id' id='product_id' />
						<button id="submit" class="btn btn-primary btn-flat" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

	 <div id="show-purchase" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
					<div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Purchase history</h4>
                    </div>
					<div class="modal-body">
						<table id="productPurchaseTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Category</th>
									<th>Brand</th>
									<th>Name</th>
									<th>Vendor</th>
									<th>Qty</th>
									<th>Note</th>
									<th>Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Category</th>
									<th>Brand</th>
									<th>Name</th>
									<th>Vendor</th>
									<th>Qty</th>
									<th>Note</th>
									<th>Date</th>
									<th>Action</th>
								</tr>
							</tfoot>
						</table>
					</div>
			</div>
		</div>
	</div>
</div>