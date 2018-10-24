<?php

class OCOpenDataArchiveSearchableRepository extends OCCustomSearchableRepositoryAbstract
{	
    public function getIdentifier()
    {
        return 'opendata_archive';
    }

    public function availableForClass()
    {
        return 'OCOpenDataArchiveSearchableObject';
    }

    public function fetchSearchableObjectList($limit, $offset)
    {
    	$data = array();
    	$archiveItemList = OCOpenDataArchiveItem::fetchObjectList( 
			OCOpenDataArchiveItem::definition(), null, null,
			array('requested_time' => 'asc'), 
			array('offset' => $offset, 'length' => $limit)
		);
		foreach ($archiveItemList as $archiveItem) {
			$content = $archiveItem->attribute('archived_content');
			foreach ($content['metadata']['languages']  as $language) {
				$data[] = new OCOpenDataArchiveSearchableObject($archiveItem, $language);
			}			
		}

        return $data;
    }

    public function fetchSearchableObject($identifier)
    {
    	list($selectId, $selectLanguage) = explode('.', $identifier);
    	$archiveItem = OCOpenDataArchiveItem::fetch($selectId);
    	if ($archiveItem instanceof OCOpenDataArchiveItem){
    		$content = $archiveItem->attribute('archived_content');
			foreach ($content['metadata']['languages']  as $language) {
				if ($language == $selectLanguage){
					return new OCOpenDataArchiveSearchableObject($archiveItem, $language);
				}				
			}			
    	}

    	return null;
    }

    public function countSearchableObjects()
    {
        return (int)OCOpenDataArchiveItem::count(OCOpenDataArchiveItem::definition());        
    }
}