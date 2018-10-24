<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$offset = (int)$Params['Offset'];
$tpl = eZTemplate::factory();
$limit = 20;
$timestamp = (int)$Params['Timestamp'];
$userID = (int)$Params['UserID'];
$isDetail = false;

if ($timestamp > 0 && $userID > 0){
	$isDetail = true;
	$currentURI = '/' . $Module->currentModule() . '/' . $Module->currentView() . '/' . $timestamp . '/' . $userID;	
	$archiveItemList = OCOpenDataArchiveItem::fetchObjectList( 
		OCOpenDataArchiveItem::definition(), null,
		array('requested_time' => $timestamp, 'user_id' => $userID),
		array('requested_time' => 'desc'), 
		array('offset' => $offset, 'length' => $limit),
		true,
		array('id', 'requested_time')
	);
	$archiveItemCount = OCOpenDataArchiveItem::count( 
		OCOpenDataArchiveItem::definition(),
		array('requested_time' => $timestamp, 'user_id' => $userID)		
	);
	$userName = OCOpenDataArchiveItem::getUserNameById($userID);
	$tpl->setVariable('timestamp', $timestamp);
	$tpl->setVariable('user_name', $userName);

}else{
	$currentURI = '/' . $Module->currentModule() . '/' . $Module->currentView();

	$repository = new OCOpenDataArchiveSearchableRepository();
	$parametes = OCCustomSearchParameters::instance()->setLimit(1)->setFacets(array(
		array('field' => 'published_year', 'limit' => 100),
		array('field' => 'class', 'limit' => 100),
	));
	$result = $repository->find($parametes);	
	$tpl->setVariable('facets', $result['facets']);

	$archiveItemList = array();
	$query = "SELECT DISTINCT requested_time, type, user_id, status, count(object_id) as object_count FROM ocopendata_archive_item GROUP BY requested_time, type, user_id, status ORDER BY requested_time DESC";
	$countQuery = "SELECT COUNT(DISTINCT requested_time) as count FROM ocopendata_archive_item";
	$data = eZDB::instance()->arrayQuery(
		$query, 
		array('offset' => $offset, 'limit' => $limit)
	);
	$count = eZDB::instance()->arrayQuery($countQuery);	
	$archiveItemCount = $count[0]['count'];
	foreach ($data as $item) {
		$item['type_name'] = OCOpenDataArchiveItem::getTypeNameByType($item['type']);
		$item['status_name'] = OCOpenDataArchiveItem::getStatusNameByStatus($item['status']);
		$item['user_name'] = OCOpenDataArchiveItem::getUserNameById($item['user_id']);
		$archiveItemList[] = $item;
	}
}

$tpl->setVariable('is_detail', $isDetail);
$tpl->setVariable('list', $archiveItemList);
$tpl->setVariable('list_count', $archiveItemCount);
$tpl->setVariable('limit', $limit);
$tpl->setVariable('module_uri', $currentURI);
$tpl->setVariable('view_parameters', $Params['UserParameters']);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:archiver/list.tpl' );
$Result['path'] =  array( 
	array( 
		'url'  => $isDetail ? $Module->currentModule() . '/' . $Module->currentView() : false,
		'text' => ezpI18n::tr('extension/ocopendata_archiver', 'Archive list')
	)
);
if($isDetail){
	$Result['path'][] = array(
		'url' => false,
		'text' => date('d/m/Y H:i', $timestamp)
	);
}
