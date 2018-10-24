<?php

use Opencontent\Opendata\Api\EnvironmentSettings;
use Opencontent\Opendata\Api\Values\Content;

class OCOpenDataArchiveEnvironment extends EnvironmentSettings
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
        $content = (array)$content->jsonSerialize();
        foreach ($content['data'] as $language => $values) {
        	foreach ($values as $attributeIdentifier => $attributeContent) {
        		$content['data'][$language][$attributeIdentifier] = $this->adapterLoader->load(
        			$classIdentifier,
        			$attributeIdentifier,
        			$attributeContent['datatype']
        		)->archive($attributeContent);
        	}
        }
        
        return $content;
    }
}