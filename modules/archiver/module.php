<?php

$Module = array( 'name' => 'OpenDataArchiver' );

$ViewList = array();

$ViewList['view'] = array(
    'functions' => array( 'view' ),
    'script' => 'view.php',
    'params' => array('ItemID'),
    'unordered_params' => array()
);

$ViewList['search'] = array(
    'functions' => array( 'search' ),
    'script' => 'search.php',
    'params' => array(),
    'unordered_params' => array()
);

$ViewList['list'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'functions' => array( 'list' ),
    'script' => 'list.php',
    'params' => array('Timestamp', 'UserID'),
    'unordered_params' => array('offset' => 'Offset')
);

$ViewList['archive'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'functions' => array( 'archive' ),
    'script' => 'archive.php',
    'params' => array('ObjectID'),
    'unordered_params' => array()
);

$ViewList['unarchive'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'functions' => array( 'unarchive' ),
    'script' => 'unarchive.php',
    'params' => array('ItemID'),
    'unordered_params' => array()
);

$ViewList['download'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'functions' => array( 'download' ),
    'script' => 'download.php',
    'params' => array('AttributeID','FileName'),
    'unordered_params' => array()
);

$ViewList['findmodule'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'functions' => array( 'view' ),
    'script' => 'findmodule.php',
    'params' => array(),
    'unordered_params' => array()
);

$ViewList['export'] = array(
    'default_navigation_part' => 'ezsetupnavigationpart',
    'functions' => array( 'list' ),
    'script' => 'export.php',
    'params' => array(),
    'unordered_params' => array()
);

$FunctionList = array();
$FunctionList['view'] = array();
$FunctionList['search'] = array();
$FunctionList['list'] = array();
$FunctionList['archive'] = array();
$FunctionList['unarchive'] = array();
$FunctionList['download'] = array();
