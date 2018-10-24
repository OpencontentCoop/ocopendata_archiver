<?php

$module = $Params['Module'];
$uri = eZURI::instance();

$repository = new OCOpenDataArchiveSearchableRepository();
$parametes = OCCustomSearchParameters::instance()->setFilters(array(
	'url_alias' => '/' . $uri->URI,
	'language' => eZLocale::currentLocaleCode()
));

$result = $repository->find($parametes);
if ($result['totalCount'] > 0){
	$archiveItem = $result['searchHits'][0]->toArray();
	$module->redirectTo('archiver/view/' . $archiveItem['id']);
	return;
}

$module->redirectTo('error/view/kernel/2');