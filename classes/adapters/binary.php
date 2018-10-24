<?php

class OCOpenDataArchiverBinaryAdapter extends OCOpenDataArchiverAdapter
{
	public function archive($content)
	{		
		if (isset($content['content']['filename'])){			
			unset($content['content']['url']);
			$binaryFile = eZBinaryFile::fetch($content['id'], $content['version']);
			if ($binaryFile instanceof eZBinaryFile){
				$content['content']['archived_filepath'] = $this->archiveBinaryFile($content['id'], $content['content']['filename'], $binaryFile->filePath());			
			}						
		}
		
		return $content;
	}

	public function unarchive($content)
	{
		$unarchiveContent = array();
		if (isset($content['content']['archived_filepath'])){
			$unarchiveContent['filename'] = $content['content']['filename'];
			$fileContent = eZClusterFileHandler::instance($content['content']['archived_filepath'])->fetchContents();
			$unarchiveContent['file'] = base64_encode($fileContent);
		}
		$content['content'] = $unarchiveContent;
		
		return $content;
	}

	public function toSearch($content, $language)
	{
		if (isset($content['content']['filename'])){			
			$content['content'] = '/archiver/download/' . $content['id'] . '/' . $content['content']['filename'];
		}
		
		return $content;
	}

	protected function archiveBinaryFile($attributeId, $fileName, $originalFilePath)
	{		
		$path = eZDir::filenamePath($attributeId, 0);
		$archiveFilePath = eZDir::path(array(eZSys::storageDirectory(), 'ocopendata_archive', $path, $fileName));
		$fileHandler = eZClusterFileHandler::instance();
		$fileHandler->fileCopy($originalFilePath, $archiveFilePath);

		return $archiveFilePath;
	}

	public function cleanup($content)
	{
		if (isset($content['content']['archived_filepath'])){
			eZClusterFileHandler::instance($content['content']['archived_filepath'])->purge();			
		}
	}
}