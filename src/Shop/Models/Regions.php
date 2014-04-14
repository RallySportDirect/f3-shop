<?php
namespace Shop\Models;

class Regions extends \Dsc\Mongo\Collection
{
    public $name = null;
    public $code = null;
    public $country_isocode_2 = null;
    
    protected $__collection_name = 'shop.regions';
    protected $__config = array(
        'default_sort' => array(
            'name' => 1 
        ) 
    );
    
    public static function byCountry( $country_isocode_2 )
    {
        return \Shop\Models\Regions::find(array(
        	'country_isocode_2' => $country_isocode_2
        )); 
    }

    /**
     * Helper method for creating select list options
     * 
     * @param array $query
     * @return multitype:multitype:string NULL
     */
    public static function forSelection(array $query=array())
    {
        if (empty($this)) {
            $model = new static();
        } else {
            $model = clone $this;
        }
    
        $cursor = $model->collection()->find($query);
        $cursor->sort(array(
            'country_isocode_2' => 1,
            'code' => 1
        ));
    
        $result = array();
        foreach ($cursor as $doc) {
            $array = array(
                'id' => $doc['country_isocode_2'] . '||' . $doc['code'],
                'text' => htmlspecialchars( $doc['country_isocode_2'] . ': ' . $doc['code'] . ' - ' . $doc['name'], ENT_QUOTES ),
            );
            $result[] = $array;
        }
    
        return $result;
    }

    /**
     * Converts an array of country_isocode_2||region_code strings into an array of select list options
     *  
     * @param array $selected
     * @return array
     */
    public static function initSelection( array $selected=array() )
    {
        // $selected = an array of region_code strings in the format country_isocode_2||region_code
        if (empty($this)) {
            $model = new static();
        } else {
            $model = clone $this;
        }
                 
        $ids = array();
        foreach ($selected as $rc) {
            $exploded = explode('||', $rc);
            $record = $model->collection()->findOne( array('country_isocode_2'=>$exploded[0], 'code'=>$exploded[1] ), array('_id'=>1) );
            if (!empty($record['_id'])) {
            	$ids[] = $record['_id'];
            }
        }

        if (!empty($ids)) {
        	return self::forSelection( array('_id'=>array('$in'=>$ids ) ) ); 
        }
        
        return array();
    }
}