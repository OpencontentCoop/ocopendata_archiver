<?php

class OCOpenDataArchiverAdapterLoader
{
	private static $adapters;

	final public function load(
        $classIdentifier,
        $attributeIdentifier,
        $dataTypeString
    )
    {
        $className = null;
        $adapters = $this->getAdapters();        
        if ( isset( $adapters["{$classIdentifier}/{$attributeIdentifier}"] ) )
        {
            $className = $adapters["{$classIdentifier}/{$attributeIdentifier}"];
        }
        elseif ( isset( $adapters[$attributeIdentifier] ) )
        {
            $className = $adapters[$attributeIdentifier];
        }
        elseif ( isset( $adapters[$dataTypeString] ) )
        {
            $className = $adapters[$dataTypeString];
        }
        elseif ( isset( $adapters['*'] ) )
        {
            $className = $adapters['*'];
        }
        if ( class_exists( $className ) )
        {
            return new $className(
                $classIdentifier,
                $attributeIdentifier
            );
        }
        else
        {
            if ( $className !== null ){
                \eZDebug::writeError( "$className not found", __METHOD__ );
            }
            
            return new OCOpenDataArchiverAdapter();
        }
    }

    private function getAdapters()
    {
    	if (self::$adapters === null){
    		self::$adapters = array();
	    	if (eZINI::instance('ocopendata_archive.ini')->hasVariable('AdapterSettings', 'Adapters')){
	    		self::$adapters = (array)eZINI::instance('ocopendata_archive.ini')->variable('AdapterSettings', 'Adapters');
	    	}
	    }

	    return self::$adapters;
    }
}