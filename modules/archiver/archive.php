<?php

$module = $Params['Module'];
$http = eZHTTPTool::instance();
$objectId = (int)$Params['ObjectID'];

if ($objectId === 0) {

    if ($http->hasPostVariable('BrowseActionName') && $http->postVariable('BrowseActionName') == 'SelectArchiveObject') {
        $selectedObjectIDArray = $http->postVariable('SelectedObjectIDArray');
        $objectId = $selectedObjectIDArray[0];
    } else {
        eZContentBrowse::browse(
            array(
                'action_name' => 'SelectArchiveObject',
                'from_page' => '/archiver/archive/',
                'start_node' => eZINI::instance('content.ini')->variable('NodeSettings', 'RootNode'),
                'cancel_page' => '/archiver/list/',
                'selection' => 'single',
                'return_type' => 'ObjectID'
            ),
            $module
        );
        return;
    }
}

$object = eZContentObject::fetch($objectId);

$error = false;

if ($object instanceof eZContentObject) {
    try {
        $archiver = new OCOpenDataArchiver();
        $archiver->archive($object);
    } catch (Exception $e) {
        $error = '/(error)/' . $e->getMessage();
    }
} else {
    $error = '/(error)/Object+not+found';
}

$module->redirectTo('archiver/list' . $error);