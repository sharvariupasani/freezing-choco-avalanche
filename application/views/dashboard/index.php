<section class="content-header">
    <h1>
        Dashboard
    </h1>
</section>
 <!-- Main content -->
<section class="content">
  <!-- Info boxes -->
  <div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Customers</span>
		  <span class="info-box-number" id='cust_count'></span>
		</div><!-- /.info-box-content -->
	  </div><!-- /.info-box -->
	</div><!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Products</span>
		  <span class="info-box-number" id='prod_count'></span>
		</div><!-- /.info-box-content -->
	  </div><!-- /.info-box -->
	</div><!-- /.col -->

	<!-- fix for small devices only -->
	<div class="clearfix visible-sm-block"></div>

	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Takeins</span>
		  <span class="info-box-number" id='tak_count'></span>
		</div><!-- /.info-box-content -->
	  </div><!-- /.info-box -->
	</div><!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
	  <div class="info-box">
		<span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
		<div class="info-box-content">
		  <span class="info-box-text">Bill Generated</span>
		  <span class="info-box-number" id='inv_count'></span>
		</div><!-- /.info-box-content -->
	  </div><!-- /.info-box -->
	</div><!-- /.col -->
  </div><!-- /.row -->

  <div class="row">
	<div class="col-md-8">
	  <!-- TABLE: LATEST ORDERS -->
	  <div class="box box-info">
		<div class="box-header with-border">
		  <h3 class="box-title">Latest Takein update</h3>
		  <div class="box-tools pull-right">
			<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		  </div>
		</div><!-- /.box-header -->
		<div class="box-body">
		  <div class="table-responsive">
			<table class="table no-margin">
			  <thead>
				<tr>
				  <th>Name</th>
				  <th>Contact</th>
				  <th>IMEI</th>
				  <th>Mobile</th>
				  <th>Status</th>
				</tr>
			  </thead>
			  <tbody id='takein-list'>
			  </tbody>
			</table>
		  </div><!-- /.table-responsive -->
		</div><!-- /.box-body -->
		<div class="box-footer text-center">
		  <a href="<?=base_url()."takein"?>" class="uppercase">View All Takein</a>
		</div><!-- /.box-footer -->
	  </div><!-- /.box -->
	</div><!-- /.col -->
	<div class="col-md-4">
	  <!-- PRODUCT LIST -->
	  <div class="box box-primary">
		<div class="box-header with-border">
		  <h3 class="box-title">Recently Added Products</h3>
		  <div class="box-tools pull-right">
			<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		  </div>
		</div><!-- /.box-header -->
		<div class="box-body">
		  <ul id="products-list" class="products-list product-list-in-box">
		  </ul>
		</div><!-- /.box-body -->
		<div class="box-footer text-center">
		  <a href="<?=base_url()."product"?>" class="uppercase">View All Products</a>
		</div><!-- /.box-footer -->
	  </div><!-- /.box -->
	</div><!-- /.col -->
  </div><!-- /.row -->

</section><!-- /.content -->