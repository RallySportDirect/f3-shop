<script>
Dsc.refreshParents = function() {
    var request = jQuery.ajax({
        type: 'get', 
        url: './admin/shop/categories/all'
    }).done(function(data){
        var lr = jQuery.parseJSON( JSON.stringify(data), false);
        if (lr.result) {
            jQuery('#parents').html(lr.result);
        }
    });
}
</script>

<div class="row">
    <div class="col-md-9">
        <form id="categories" class="searchForm" action="./admin/shop/categories" method="post">
        
            <?php echo $this->renderLayout('Shop/Admin/Views::categories/list_datatable.php'); ?>
        
        </form>
    </div>
    <div class="col-md-3">
    
    	<?php echo \Dsc\Request::internal( "\Shop\Admin\Controllers\Category->quickadd" ); ?>
		
    </div>
</div>