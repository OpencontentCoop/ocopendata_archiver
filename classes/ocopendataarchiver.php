<?php

use Opencontent\Opendata\Api\ClassRepository;
use Opencontent\Opendata\Api\PublicationProcess;
use Opencontent\Opendata\Api\Values\Content;
use Opencontent\Opendata\Rest\Client\PayloadBuilder;

class OCOpenDataArchiver
{    
    private $type;

    private $user;

    private $timestamp;

    public function __construct($type = OCOpenDataArchiveItem::TYPE_IMMEDIATE, $timestamp = null)
    {
        $this->type = $type;
        $this->user = eZUser::currentUser();
        $this->timestamp = $timestamp ? $timestamp : time();
    }

    public function archive(eZContentObject $object)
    {
        $classRepository = new ClassRepository();
        $apiClass = (array)$classRepository->load($object->attribute('class_identifier'));
        $environment = new OCOpenDataArchiveEnvironment();
        $apiContent = (array)$environment->filterContent(Content::createFromEzContentObject($object));        
        $dataText = array(
            'class' => $apiClass,
            'content' => $apiContent,
        );

		$nodes = $object->assignedNodes();
        $urlAliasList = $nodeIdList = array();        
        foreach ($nodes as $node) {

            if ($node->childrenCount(false) > 0){
            	throw new Exception("Can not remove node " . $node->attribute('node_id') . " with children", 1);
            }

            if (!$node->attribute( 'can_remove' )){
            	throw new Exception("Current user can not remove node " . $node->attribute('node_id'), 1);
            }

            $nodeIdList[] = $node->attribute('node_id');

            $urlAliasList[] = '/content/view/full/' . $node->attribute('node_id');

            // Fetch generated names of node
            $filter = new eZURLAliasQuery();
            $filter->actions = array('eznode:' . $node->attribute('node_id'));
            $filter->type = 'name';
            $filter->limit = false;
            $elements = $filter->fetchAll();
            foreach ($elements as $element) {
                $urlAlias = '';
                $pathArray = $element->attribute('path_array');
                foreach ($pathArray as $item) {
                    $urlAlias .= '/' . $item->attribute('text');
                }
                $urlAliasList[] = $urlAlias;
            }

            // Fetch custom aliases for node
            $filter->prepare(); // Reset SQLs from previous calls
            $filter->actions = array('eznode:' . $node->attribute('node_id'));
            $filter->type = 'alias';
            $filter->offset = 0;
            $filter->limit = 25;
            $elements = $filter->fetchAll();
            foreach ($elements as $element) {
                $urlAlias = '';
                $pathArray = $element->attribute('path_array');
                foreach ($pathArray as $item) {
                    $urlAlias .= '/' . $item->attribute('text');
                }
                $urlAliasList[] = $urlAlias;
            }
        }

        $archiveItem = new OCOpenDataArchiveItem(array(
        	'type' => $this->type,
            'class_identifier' => $object->attribute('class_identifier'),
        	'url_alias_list' => json_encode($urlAliasList),
        	'data_text' => json_encode($dataText),
        	'node_id_list' => json_encode($nodeIdList),
        	'object_id' => $object->attribute('id'),
        	'user_id' => $this->user->id(),
        	'requested_time' => $this->timestamp,
        	'status' => OCOpenDataArchiveItem::STATUS_COMPLETED,
        ));
        $archiveItem->store();

        eZContentOperationCollection::deleteObject($nodeIdList);

        $this->registerSearchItem($archiveItem);

    }

    public function unarchive(OCOpenDataArchiveItem $archiveItem)
    {
    	$environment = new OCOpenDataUnArchiveEnvironment();        
    	$content = $environment->filterContent(new Content($archiveItem->attribute('archived_content')));

    	$payload = new PayloadBuilder($content);        
    	$createStruct = $environment->instanceCreateStruct($payload);
        $createStruct->validate();
        $publicationProcess = new PublicationProcess($createStruct);
    	$contentId = $publicationProcess->publish();
    	
        $environment->cleanup(new Content($archiveItem->attribute('archived_content')));
    	$this->removeSearchItem($archiveItem);
        $archiveItem->remove();
    	
    	return $contentId;
    }

    private function registerSearchItem(OCOpenDataArchiveItem $archiveItem)
    {
        $data = array();
        $content = $archiveItem->attribute('archived_content');
        foreach ($content['metadata']['languages']  as $language) {
            $data[] = new OCOpenDataArchiveSearchableObject($archiveItem, $language);
        }
        $repository = new OCOpenDataArchiveSearchableRepository();
        foreach ($data as $item) {
            $repository->index($item);
        }
    }

    private function removeSearchItem(OCOpenDataArchiveItem $archiveItem)
    {
        $data = array();
        $content = $archiveItem->attribute('archived_content');
        foreach ($content['metadata']['languages']  as $language) {
            $data[] = new OCOpenDataArchiveSearchableObject($archiveItem, $language);
        }
        $repository = new OCOpenDataArchiveSearchableRepository();
        foreach ($data as $item) {
            $repository->remove($item);
        }
    }
}