<section class="content-header">
  <h1>
	Invoice
  </h1>
</section>
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-globe"></i> <?=getSetting("firmname")?>.
              <small class="pull-right">Date: <?=date("jS M y'",strtotime($invoice[0]->sale_date))?></small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <!--div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            From
            <address>
              <strong>Admin, Inc.</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (804) 123-5432<br/>
              Email: info@almasaeedstudio.com
            </address>
          </div>
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong>John Doe</strong><br>
              795 Folsom Ave, Suite 600<br>
              San Francisco, CA 94107<br>
              Phone: (555) 539-1037<br/>
              Email: john.doe@example.com
            </address>
          </div>
          <div class="col-sm-4 invoice-col">
            <b>Invoice #007612</b><br/>
            <br/>
            <b>Order ID:</b> 4F3S8J<br/>
            <b>Payment Due:</b> 2/22/2014<br/>
            <b>Account:</b> 968-34567
          </div>
        </div --><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Qty</th>
                  <th>Product</th>
                  <th>Price for each</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
			  <?php $i=0; while(count(@$products)>$i){ $product = @$products[$i];?>
                <tr>
                  <td><?=$product->quantity?></td>
                  <td><?=$product->title?></td>
                  <td><?=$product->price?></td>
                  <td><?=$product->net_price?></td>
                </tr>
			 <?php $i++; }?>
              </tbody>
            </table>
			<p class="lead">Service </p>
			<table class="table table-striped">
              <thead>
                <tr>
                  <th>Service type</th>
                  <th>Price</th>
                </tr>
              </thead>
              <tbody>
			  <?php $i=0; while(count(@$services)>$i){ $service = @$services[$i];?>
                <tr>
                  <td><?=$service->service_name?></td>
                  <td><?=$service->net_price?></td>
                </tr>
			 <?php $i++; }?>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-12">
            <p class="lead">Amount </p>
            <div class="table-responsive">
              <table class="table">
                <tr>
                  <th style="width:50%">Subtotal:</th>
                  <td><?=$invoice[0]->amount?></td>
                </tr>
                <tr>
                  <th>Tax (9.3%)</th>
                  <td><?=($invoice[0]->amount * 9.3)/100?></td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td><?=$invoice[0]->total?></td>
                </tr>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->