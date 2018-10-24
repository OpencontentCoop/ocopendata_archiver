<?php

$module = $Params['Module'];
$itemId = $Params['ItemID'];
$item = OCOpenDataArchiveItem::fetchObject(OCOpenDataArchiveItem::definition(), null, array('id' => (int)$itemId));
$error = false;

if ($item instanceof OCOpenDataArchiveItem){
	try{
		$archiver = new OCOpenDataArchiver();
		$contentId = $archiver->unarchive($item);
		$module->redirectTo('openpa/object/'. $contentId);
		return;
	}catch(Exception $e){		
		$error = '/(error)/' . $e->getMessage();		
	}
}else{
	$error = '/(error)/Item+not+found';		
}

$module->redirectTo('archiver/list'. $error);