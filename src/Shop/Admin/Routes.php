<?php
namespace Shop\Admin;

/**
 * Group class is used to keep track of a group of routes with similar aspects (the same controller, the same f3-app and etc)
 */
class Routes extends \Dsc\Routes\Group
{

    /**
     * Initializes all routes for this group
     * NOTE: This method should be overriden by every group
     */
    public function initialize()
    {
        $this->setDefaults( array(
            'namespace' => '\Shop\Admin\Controllers',
            'url_prefix' => '/admin/shop' 
        ) );
        
        $this->addSettingsRoutes();
        
        $this->addCrudGroup( 'Orders', 'Order' );
        
        $this->addCrudGroup( 'Products', 'Product' );
        $this->add( '/products/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Products',
            'action' => 'forSelection'
        ) );        
        
        $this->addCrudGroup( 'Categories', 'Category', array(
            'datatable_links' => true,
            'get_parent_link' => true 
        ) );
        
        $this->addCrudGroup( 'Manufacturers', 'Manufacturer', array(
            'datatable_links' => true,
            'get_parent_link' => true 
        ) );
        
        $this->addCrudGroup( 'Collections', 'Collection', array(
            'datatable_links' => true,
            'get_parent_link' => true 
        ) );
        
        $this->add( '/categories/checkboxes [ajax]', array(
            'GET',
            'POST' 
        ), array(
            'controller' => 'Categories',
            'action' => 'getCheckboxes' 
        ) );
        
        $this->add( '/manufacturers/checkboxes [ajax]', array(
            'GET',
            'POST' 
        ), array(
            'controller' => 'Manufacturers',
            'action' => 'getCheckboxes' 
        ) );
        
        $this->addCrudList( 'Assets' );
        
        $this->addCrudGroup( 'Countries', 'Country' );
        $this->add( '/countries/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Countries',
            'action' => 'forSelection'
        ) );
                
        $this->addCrudGroup( 'Regions', 'Region' );
        $this->add( '/regions/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Regions',
            'action' => 'forSelection'
        ) );
                
        $f3 = \Base::instance();
        $f3->route('GET /admin/shop/testing/@task', '\Shop\Admin\Controllers\Testing->@task');
        
        $this->addCrudGroup( 'Coupons', 'Coupon' );
        
        $this->addCrudGroup( 'Tags', 'Tag' );
    }
}