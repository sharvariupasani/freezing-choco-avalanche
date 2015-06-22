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
			<?if ($is_dealer) {?>
			<a class="btn btn-default pull-right" href="javascript:void(0);" id='generateBill'>
            <i class="fa fa-plus"></i>&nbsp;Generate Bill</a>
			<? }?>
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

<div id="modals">
    <div id="status-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Authentication</h4>
                    </div>
                    <div class="modal-body clearfix">
						<div class="form-group clearfix">
                            <label class="control-label col-sm-4">Password</label>
                            <div class="col-sm-7">
                                <input class="form-control" placeholder="Password" name="passkey" id='passkey' type="password" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Reason</label>
                            <div class="col-sm-7">
                                <input class="form-control" placeholder="Reason" name="reason" id='reason' type="text" value="" />
                            </div>
                        </div>
						<p class="response" style=""></p>
                    </div>
                    <div class="modal-footer">
						<button id="rejectbtn" class="btn btn-primary btn-flat" onclick="">Reject</button>
                    </div>
            </div>
        </div>
    </div>
	<div id="detail-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                    
            </div>
        </div>
    </div>
</div>