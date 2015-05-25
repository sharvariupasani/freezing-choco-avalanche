<section class="content-header">
    <h1>
        Take In
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
    		<a class="btn btn-default pull-right" href="<?=base_url()."takein/add"?>">
            <i class="fa fa-plus"></i>&nbsp;Add</a>
			<a class="btn btn-default pull-right" href="javascript:void(0);" id='generateBill'>
            <i class="fa fa-plus"></i>&nbsp;Generate Bill</a>
            <div id="list">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Take in list</h3>                                    
					</div><!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="takeinTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Name</th>
									<th>Contact</th>
									<th>IMEI</th>
									<th>Mobile</th>
									<th>Remark</th>
									<th>In</th>
									<th>Out</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Name</th>
									<th>Contact</th>
									<th>IMEI</th>
									<th>Mobile</th>
									<th>Remark</th>
									<th>In</th>
									<th>Out</th>
									<th>Status</th>
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