<?php
class ShopBootstrap extends \Dsc\BaseBootstrap
{
    protected $dir = __DIR__;
    protected $namespace = 'Shop';

    protected function runAdmin()
    {
        parent::runAdmin();
        try
        {
            $service = \Dsc\System::instance()->get( 'massupdate' );
            if (! empty( $service ))
            {
                $service->registerGroup( new \Shop\MassUpdateGroup() );
            }
        }
        catch ( \Exception $e )
        {
            
        }
    }
    
    protected function preSite()
    {
        // add the css & js files to the minifier
        \Minify\Factory::registerPath( $this->dir . "/src/");
        
        $files = array(
            'Shop/Assets/js/site.js'
        );
        
        foreach ($files as $file)
        {
            \Minify\Factory::js($file);
        }        
    }
}

$app = new ShopBootstrap();