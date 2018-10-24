<?php

$module = $Params['Module'];
$attributeId = $Params['AttributeID'];
$fileName = $Params['FileName'];

$path = eZDir::filenamePath($attributeId, 0);
$archiveFilePath = eZDir::path(array(eZSys::storageDirectory(), 'ocopendata_archive', $path, $fileName));
$fileHandler = eZClusterFileHandler::instance($archiveFilePath);
if ($fileHandler->exists()){
	$fileHandler->fetch();
	eZFile::downloadHeaders($fileHandler->filePath);
	$fileHandler->deleteLocal();
	$fileHandler->passthrough();
}else{
	return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}