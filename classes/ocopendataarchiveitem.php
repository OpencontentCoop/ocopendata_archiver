<?php

class OCOpenDataArchiveItem extends eZPersistentObject
{
    const TYPE_IMMEDIATE = 1,
          TYPE_CRONJOBBED = 2;

    const STATUS_PENDING = 0,
          STATUS_RUNNING = 1,
          STATUS_FAILED = 2,
          STATUS_COMPLETED = 3,
          STATUS_CANCELED = 4,
          STATUS_INTERRUPTED = 5;

    public static function definition()
    {
        return array(
            'fields' => array(
                'id' => array(
                    'name' => 'id',
                    'datatype' => 'integer',
                    'default' => null,
                    'required' => true
                ),
                'type' => array(
                    'name' => 'type',
                    'datatype' => 'string',
                    'default' => null,
                    'required' => true
                ),
                'class_identifier' => array(
                    'name' => 'class_identifier',
                    'datatype' => 'string',
                    'default' => null,
                    'required' => true
                ),
                'url_alias_list' => array(
                    'name' => 'url_alias_list',
                    'datatype' => 'string',
                    'default' => null,
                    'required' => false
                ),
                'data_text' => array(
                    'name' => 'data_text',
                    'datatype' => 'string',
                    'default' => null,
                    'required' => false
                ),
                'node_id_list' => array(
                    'name' => 'node_id_list',
                    'datatype' => 'string',
                    'default' => null,
                    'required' => false
                ),
                'object_id' => array(
                    'name' => 'object_id',
                    'datatype' => 'integer',
                    'default' => null,
                    'required' => true
                ),
                'user_id' => array(
                    'name' => 'user_id',
                    'datatype' => 'integer',
                    'default' => null,
                    'required' => true
                ),
                'requested_time' => array(
                    'name' => 'requested_time',
                    'datatype' => 'integer',
                    'default' => time(),
                    'required' => false
                ),
                'status' => array(
                    'name' => 'status',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => false
                )
            ),
            'keys' => array('id'),
            'increment_key' => 'id',
            'class_name' => 'OCOpenDataArchiveItem',
            'name' => 'ocopendata_archive_item', 
            'function_attributes'  => array( 
            	'url_alias_list_decoded' => 'getDecodedUrlAliasList',
            	'type_name' => 'getTypeName',
            	'status_name' => 'getStatusName',
            	'user_name' => 'getUserName',
            	'archived_class' => 'getArchivedClass',
            	'archived_content' => 'getArchivedContent',
            )
        );
    }

    public static function fetch( $id )
    {
        return self::fetchObject( self::definition(), null, array( 'id' => $id ) );        
    }

	public function getArchivedClass()
	{
		$data = json_decode($this->attribute('data_text'), 1);
		return $data['class'];
	}

	public function getArchivedContent()
	{
		$data = json_decode($this->attribute('data_text'), 1);
		return $data['content'];
	}

	public function getUserName()
	{		
		return self::getUserNameById($this->attribute('user_id'));
	}

	public static function getUserNameById($id)
	{
		$object = eZContentObject::fetch((int)$id);
		if ($object instanceof eZContentObject){
			return $object->attribute('name');
		}

		return $id;
	}

	public function getTypeName()
	{		
		return self::getTypeNameByType($this->attribute('type'));
	}

	public static function getTypeNameByType($type)
	{
		if ($type == self::TYPE_IMMEDIATE){
			return 'immediate';
		}

		return 'scheduled';
	}

	public function getStatusName()
	{
		return self::getStatusNameByStatus($this->attribute('status'));
	}

	public static function getStatusNameByStatus($status)
	{
		if ($status == self::STATUS_PENDING){
			return 'pending';
		}
		if ($status == self::STATUS_RUNNING){
			return 'running';
		}
		if ($status == self::STATUS_FAILED){
			return 'failed';
		}
		if ($status == self::STATUS_COMPLETED){
			return 'completed';
		}
		if ($status == self::STATUS_INTERRUPTED){
			return 'interrupted';
		}

		return '?';
	}
    
    public function getDecodedUrlAliasList()
    {
    	return (array)json_decode($this->attribute('url_alias_list'), 1);
    }
}