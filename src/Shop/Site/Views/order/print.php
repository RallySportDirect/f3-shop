<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
    <?php echo $this->renderView('Theme/Views::head.php'); ?>
</head>

<body class="print">

<div class="container order-detail order-print">

    <div class="order-print-header form-group">
        // TODO Include store custom print header HTML from Shop config
    </div>

    <div class="clearfix">
        <div class="pull-right hidden-print">
            Return to <a href="./shop/order/<?php echo $order->id; ?>">Order Summary</a>
        </div>
    </div>

    <div class="form-group well well-sm">
        
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div><label>Order placed:</label> <?php echo (new \DateTime($order->{'metadata.created.local'}))->format('F j, Y'); ?></div>
                <div><label>Order total:</label> <?php echo $order->{'grand_total'}; ?></div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div><label>Order #</label><?php echo $order->{'number'}; ?></div>
                <div><label>Order status:</label> <?php echo $order->{'status'}; ?></div>
            </div>
        </div>        
    </div>
    
    <div class="form-group">
        <legend>
            <small>Shipping Information</small>
        </legend>
        <?php if (!$order->{'shipping_required'}) { ?>
            <p>Shipping not required.</p>
        <?php } else { ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <?php if ($order->{'shipping_address'}) { ?>
                    <address>
                        <?php echo $order->{'shipping_address.name'}; ?><br/>
                        <?php echo $order->{'shipping_address.line_1'}; ?><br/>
                        <?php echo !empty($order->{'shipping_address.line_2'}) ? $order->{'shipping_address.line_2'} . '<br/>' : null; ?>
                        <?php echo $order->{'shipping_address.city'}; ?> <?php echo $order->{'shipping_address.region'}; ?> <?php echo $order->{'shipping_address.postal_code'}; ?><br/>
                        <?php echo $order->{'shipping_address.country'}; ?><br/>
                    </address>
                    <?php if (!empty($order->{'shipping_address.phone_number'})) { ?>
                    <div>
                        <label>Phone:</label> <?php echo $order->{'shipping_address.phone_number'}; ?>
                    </div>
                    <?php } ?>
                
                <?php } ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <?php if ($method = $order->shippingMethod()) { ?>
                    <div>
                        <label>Method:</label> <?php echo $method->{'name'}; ?> &mdash; $<?php echo $method->total(); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        
        <?php foreach ($order->shipments as $shipment) { ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div>Shipping Vendor (UPS/USPS/Fedex/etc)</div>
                <div>Tracking number + link</div>
                <div>Address</div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div>Items in shipment</div>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <div class="form-group">
        <legend>
            <small>Payment Information</small>
        </legend>
        
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <?php if ($order->{'billing_address'}) { ?>
                    <address>
                        <?php echo $order->{'billing_address.name'}; ?><br/>
                        <?php echo $order->{'billing_address.line_1'}; ?><br/>
                        <?php echo !empty($order->{'billing_address.line_2'}) ? $order->{'billing_address.line_2'} . '<br/>' : null; ?>
                        <?php echo $order->{'billing_address.city'}; ?> <?php echo $order->{'billing_address.region'}; ?> <?php echo $order->{'billing_address.postal_code'}; ?><br/>
                        <?php echo $order->{'billing_address.country'}; ?><br/>
                    </address>
                    <?php if (!empty($order->{'billing_address.phone_number'})) { ?>
                    <div>
                        <label>Phone:</label> <?php echo $order->{'billing_address.phone_number'}; ?>
                    </div>
                    <?php } ?>
                
                <?php } ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <?php if ($method = $order->paymentMethod()) { ?>
                    <div>
                        <label>Method:</label> <?php echo $method->{'name'}; ?>
                    </div>
                <?php } ?>
            </div>
        </div>        
        
        <?php foreach ($order->payments as $payment) { ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div>Payment method(s) (if CC, last 4)</div>
                <div>Address (if different from primary)</div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div>Amount paid via payment method</div>
            </div>
        </div>
        <?php } ?>    
    </div>
    
    <div class="form-group">
        <legend>
            <div class="row">
                <div class="col-xs-10 col-sm-10 col-md-10">
                    <small>Items</small>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div class="pull-right">
                        <small>Price</small>
                    </div>
                </div>
            </div>
        </legend>        
        
        <?php foreach ($order->items as $item) { ?>
        <div class="row">
            <div class="col-xs-10 col-sm-10 col-md-10">
                <div class="title">
                    <?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>
                    <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                    <div>
                        <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                    </div>
                    <?php } ?>                        
                </div>
                <div class="details">

                </div>
                <div>
                    <span class="quantity"><?php echo $quantity = \Dsc\ArrayHelper::get($item, 'quantity'); ?></span>
                    x
                    <span class="price">$<?php echo $price = \Dsc\ArrayHelper::get($item, 'price'); ?></span> 
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="pull-right">$<?php echo $quantity * $price; ?></div>
            </div>
        </div>        
        <?php } ?>
    </div>
    
    <div class="text-center">
        // TODO Include store contact info from Shop config
    </div>

</div>

</body>

</html>