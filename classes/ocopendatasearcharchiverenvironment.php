<?php

use Opencontent\Opendata\Api\EnvironmentSettings;
use Opencontent\Opendata\Api\Values\Content;
use Opencontent\Opendata\Api\Values\ContentData;

class OCOpenDataSearchArchiveEnvironment extends DefaultEnvironmentSettings
{
	private $adapterLoader;

    public function __construct(array $properties = array())
    {
        parent::__construct($properties);
        $this->adapterLoader = new OCOpenDataArchiverAdapterLoader();
    }

    public function filterContent(Content $content)
    {    	
        $classIdentifier = $content->metadata->classIdentifier;
        $contentData = $content->data;
   
        foreach ($contentData as $language => $values) {
            foreach ($values as $attributeIdentifier => $attributeContent) {
                $contentData[$language][$attributeIdentifier] = $this->adapterLoader->load(
                    $classIdentifier,
                    $attributeIdentifier,
                    $attributeContent['datatype']
                )->toSearch($attributeContent, $language);
            }
        } 	    	 
        $content->data = new ContentData($contentData);
    	$content = (array)parent::filterContent($content);
    	unset($content['metadata']['id']);
    	unset($content['metadata']['link']);
    	unset($content['metadata']['ownerName']);
    	unset($content['metadata']['ownerId']);
        unset($content['metadata']['mainNodeId']);
        unset($content['metadata']['name']);
        unset($content['metadata']['class']);

        $content['metadata']['published'] = date("U", strtotime($content['metadata']['published']));
        $content['metadata']['modified'] = date("U", strtotime($content['metadata']['modified']));
        
        return $content;	
    }    

    protected function flatData( Content $content )
    {
        $flatData = array();
        foreach( $content->data as $language => $data )
        {
            foreach( $data as $identifier => $value )
            {
                $valueContent = $value['content'];                
                $flatData[$language][$identifier] = $valueContent;
            }
        }
        $content->data = new ContentData( $flatData );
        return $content;
    }
} 