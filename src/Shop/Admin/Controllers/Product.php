<?php 
namespace Shop\Admin\Controllers;

class Product extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/products';
    protected $create_item_route = '/admin/shop/product/create';
    protected $get_item_route = '/admin/shop/product/read/{id}';    
    protected $edit_item_route = '/admin/shop/product/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \Shop\Models\Products;
        return $model; 
    }
    
    protected function getItem() 
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        
        if (empty($id)) {
        	return $this->getModel();
        }

        try {
            $item = $this->getModel()->setState('filter.id', $id)->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $f3->reroute( $this->list_route );
            return;
        }

        return $item;
    }
    
    protected function displayCreate() 
    {
        $f3 = \Base::instance();
        
        $model = new \Shop\Models\Categories;
        $categories = $model->getList();
        \Base::instance()->set('categories', $categories );
        \Base::instance()->set('selected', 'null' );

        $selected = array();
        $flash = \Dsc\Flash::instance();

        $use_flash = \Dsc\System::instance()->getUserState('use_flash.' . $this->create_item_route);
        if (!$use_flash) {
            // this is a brand-new create, so store the prefab data
            $flash->store( $this->getModel()->cast() );
        }        
        
        $input = $flash->old('category_ids');

        if (!empty($input)) 
        {
            foreach ($input as $id)
            {
                $id = $this->inputfilter->clean( $id, 'alnum' );
                $selected[] = array('id' => $id);
            }
        }
        
        $flash->store( $flash->get('old') + array('categories'=>$selected));        

        $all_tags = $this->getModel()->getTags();
        \Base::instance()->set('all_tags', $all_tags );
        
        $this->app->set('meta.title', 'Create Product | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopProductsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        
        echo $view->render('Shop\Admin\Views::products/create.php');
    }
    
    protected function displayEdit()
    {
        $f3 = \Base::instance();

        $flash = \Dsc\Flash::instance();
        $variants = array();
        if ($flashed_variants = $flash->old('variants')) {
        	foreach ($flashed_variants as $variant) 
        	{
        	    $key = implode("-", (array) $variant['attributes']);
        	    if (empty($key)) {
        	        $key = $variant['id'];
        	    }
        		$variants[$key] = $variant;
        	}
        }
        $old = array_merge( $flash->get('old'), array( 'variants' => $variants ) );
        $flash->store( $old );
        
        $model = new \Shop\Models\Categories;
        $categories = $model->getList();
        \Base::instance()->set('categories', $categories );
        \Base::instance()->set('selected', 'null' );
        
        $all_tags = $this->getModel()->getTags();
        \Base::instance()->set('all_tags', $all_tags );
        
        $this->app->set('meta.title', 'Edit Product | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopProductsEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        
        echo $view->render('Shop\Admin\Views::products/edit.php');
    }
    
    /**
     * This controller doesn't allow reading, only editing, so redirect to the edit method
     */
    protected function doRead(array $data, $key=null) 
    {
        $f3 = \Base::instance();
        $id = $this->getItem()->get( $this->getItemKey() );
        $route = str_replace('{id}', $id, $this->edit_item_route );
        $f3->reroute( $route );
    }
    
    protected function displayRead() {}
}