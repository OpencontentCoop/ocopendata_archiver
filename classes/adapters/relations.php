<?php

class OCOpenDataArchiverRelationsAdapter extends OCOpenDataArchiverAdapter
{
	public function unarchive($content)
	{
		$newContentList = array();
		if (!empty($content['content'])){
			foreach ($content['content'] as $relatedItem) {				
				if ($this->contentExists($relatedItem['remoteId'])){
					$newContentList[] = $relatedItem['remoteId'];
				}
			}
		}		
		$content['content'] = $newContentList;

		return $content;
	}

	public function toSearch($content, $language)
	{
		$newContentList = array();
		if (!empty($content['content'])){
			foreach ($content['content'] as $relatedItem) {				
				if (isset($relatedItem['name'][$language])){
					$newContentList[] = $relatedItem['name'][$language];
				}
			}
		}		
		$content['content'] = $newContentList;

		return $content;
	}

	private function contentExists($remoteID)
	{
		$db = eZDB::instance();
        $remoteID = $db->escapeString($remoteID);
        $resultArray = $db->arrayQuery( 'SELECT id FROM ezcontentobject WHERE remote_id=\'' . $remoteID . '\'' );

        return count($resultArray) > 0;
	}
}