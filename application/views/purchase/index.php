<section class="content-header">
    <h1>
        Purchase
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
            <i class="fa fa-plus"></i>&nbsp;Add Purchase</a>
            <div id="list">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Purchase list</h3>                                    
					</div><!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="purchaseTable" class="table table-bordered table-striped">
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
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div>
    	</div>
    </div>
</section>