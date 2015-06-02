<section class="content-header">
    <h1>
        Bill
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
    		<a class="btn btn-default pull-right" href="<?=base_url()."invoice/add"?>">
            <i class="fa fa-plus"></i>&nbsp;Add Bill</a>
            <div id="list">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Bill list</h3>                                    
					</div><!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="invoiceTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Name</th>
									<th>Contact</th>
									<th>Amount</th>
									<th>Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Name</th>
									<th>Contact</th>
									<th>Amount</th>
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