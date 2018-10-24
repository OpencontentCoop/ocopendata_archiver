<?php

$Module = $Params['Module'];
$tpl = eZTemplate::factory();
$Result = array();
$Result['content'] = $tpl->fetch( 'design:archiver/search.tpl' );
$Result['path'] =  array( 
	array( 
		'url'  => $Module->currentModule() . '/list',
		'text' => ezpI18n::tr('extension/ocopendata_archiver', 'Archive list')
	)
);