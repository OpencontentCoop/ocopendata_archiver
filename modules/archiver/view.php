<?php

use Opencontent\Opendata\Api\Values\Content;

$module = $Params['Module'];
$itemId = $Params['ItemID'];
$archiveItem = OCOpenDataArchiveItem::fetchObject(OCOpenDataArchiveItem::definition(), null, array('id' => (int)$itemId));
$error = false;

if ($archiveItem instanceof OCOpenDataArchiveItem){
	$tpl = eZTemplate::factory();
	
	$class = $archiveItem->attribute('archived_class');
	$content = new Content($archiveItem->attribute('archived_content'));   
	$env = new OCOpenDataSearchArchiveEnvironment();
    $flatContent = (array)$env->filterContent($content);
	$tpl->setVariable('archive_item', $archiveItem);	
	$tpl->setVariable('class', $class);	
	$tpl->setVariable('data', $flatContent['data'][eZLocale::currentLocaleCode()]);
	$tpl->setVariable('locale', eZLocale::currentLocaleCode());
	
	$keyArray = array( 
		array('id', $archiveItem->attribute('id')),		
		array('class_identifier', $archiveItem->attribute('class_identifier')),	
		array('object_id', $archiveItem->attribute('object_id')),	
		array('user_id', $archiveItem->attribute('user_id')),	
	);
	$res = eZTemplateDesignResource::instance();
    $res->setKeys($keyArray);

	$Result = array();
	$Result['content'] = $tpl->fetch( 'design:archiver/view.tpl' );
	$Result['path'] =  array( 
		array( 
			'url'  => $module->currentModule() . '/list',
			'text' => ezpI18n::tr('extension/ocopendata_archiver', 'Archive list'), 
		)
	);
}else{
	$error = '/(error)/Item+not+found';	
	$module->redirectTo('archiver/list'. $error);	
}

