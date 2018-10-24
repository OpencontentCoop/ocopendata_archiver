<?php

class OCOpenDataArchiverImageAdapter extends OCOpenDataArchiverBinaryAdapter
{
	public function archive($content)
	{		
		if (isset($content['content']['filename'])){			
			$originalFilePath = ltrim($content['content']['url'], '/');
			unset($content['content']['url']);
			$content['content']['archived_filepath'] = $this->archiveBinaryFile($content['id'], $content['content']['filename'], $originalFilePath);			
		}
		return $content;
	}

	public function unarchive($content)
	{
		$unarchiveContent = array();
		if (isset($content['content']['archived_filepath'])){
			$unarchiveContent['filename'] = $content['content']['filename'];
			$fileContent = eZClusterFileHandler::instance($content['content']['archived_filepath'])->fetchContents();
			$unarchiveContent['image'] = base64_encode($fileContent);
			$unarchiveContent['file'] = $unarchiveContent['image'];
			$unarchiveContent['alt'] = $content['content']['alt'];
		}
		$content['content'] = $unarchiveContent;

		return $content;
	}
}