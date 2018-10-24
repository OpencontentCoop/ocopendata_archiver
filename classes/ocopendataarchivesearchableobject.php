<?php

use Opencontent\Opendata\Api\Values\Content;

class OCOpenDataArchiveSearchableObject extends OCCustomSearchableObjectAbstract
{
    protected $attributes = array();

    private $archiveItem;
    
    private $language;
    
    public function __construct(OCOpenDataArchiveItem $archiveItem = null, $language = null)
    {                
        if ($archiveItem && $language){
            
            $this->archiveItem = $archiveItem;
            
            $this->language = $language;
            
            $content = new Content($archiveItem->attribute('archived_content'));        

            $this->attributes['id'] = (int)$archiveItem->attribute('id');
            $this->attributes['language'] =  $language;
            $this->attributes['class'] =  $archiveItem->attribute('class_identifier');
            $this->attributes['section'] =  $content->metadata->sectionIdentifier;
            
            $ownerName = (array)$content->metadata->ownerName;
            if (isset($ownerName[$language])){
                $this->attributes['author'] =  $ownerName[$language];
            }else{
                $this->attributes['author'] =  current($ownerName);
            }

            $this->attributes['url_alias'] =  $archiveItem->attribute('url_alias_list_decoded');
            $this->attributes['archived'] =  ezfSolrDocumentFieldBase::preProcessValue((int)$archiveItem->attribute('requested_time'), 'date');
            $this->attributes['archived_year'] = (int)date('Y', $archiveItem->attribute('requested_time'));

            $name = (array)$content->metadata->name;
            if (isset($name[$language])){
                $this->attributes['name'] =  $name[$language];
            }else{
                $this->attributes['name'] =  current($name);
            }
            
            $timestamp = date("U", strtotime($content->metadata->published));
            $this->attributes['published'] =  ezfSolrDocumentFieldBase::preProcessValue((int)$timestamp, 'date');
            $this->attributes['published_year'] = (int)date('Y', $timestamp);
            
            if (isset($content->data[$language]['from_time']) 
                && !empty($content->data[$language]['from_time']['content'])){

                $timestamp = date("U", strtotime($content->data[$language]['from_time']['content']));
                $this->attributes['from_time'] =  ezfSolrDocumentFieldBase::preProcessValue((int)$timestamp, 'date');
            }else{
                $this->attributes['from_time'] =  null;    
            }
            if (isset($content->data[$language]['to_time']) 
                && !empty($content->data[$language]['to_time']['content'])){

                $timestamp = date("U", strtotime($content->data[$language]['to_time']['content']));
                $this->attributes['to_time'] =  ezfSolrDocumentFieldBase::preProcessValue((int)$timestamp, 'date');
            }else{
                $this->attributes['to_time'] = null;
            }

            $this->attributes['repository'] = 'opendata_archive';

            $env = new OCOpenDataSearchArchiveEnvironment();
            $flatContent = $env->filterContent($content);
            $this->attributes['data'] = isset($flatContent['data'][$this->language]) ? base64_encode(json_encode($flatContent['data'][$this->language])) : null;
        }
    }

    private function setAttributes($attributes)
    {
        foreach($attributes as $key => $value){
            if ($this->hasFieldByName($key)){
                $this->attributes[$key] = $value;                
            }
        }
    }

    public function getGuid()
    {  
        return 'opendata_archive_' . $this->archiveItem->attribute('id') . '_' . $this->language;
    }
    
    /**
     * @return OCCustomSearchableFieldInterface[]
     */
    public static function getFields()
    {
        return array(
            OCCustomSearchableField::create('id', 'int'),
            OCCustomSearchableField::create('language', 'string'),
            OCCustomSearchableField::create('class', 'string'),
            OCCustomSearchableField::create('section', 'string'),
            OCCustomSearchableField::create('author', 'string'),
            OCCustomSearchableField::create('url_alias', 'string[]'),
            OCCustomSearchableField::create('archived', 'date'),
            OCCustomSearchableField::create('archived_year', 'int'),
            OCCustomSearchableField::create('name', 'text'),
            OCCustomSearchableField::create('published', 'date'),
            OCCustomSearchableField::create('published_year', 'int'),
            OCCustomSearchableField::create('from_time', 'date'),
            OCCustomSearchableField::create('to_time', 'date'),
            OCCustomSearchableField::create('repository', 'string'),
            OCCustomSearchableField::create('data', 'binary'),
        );
    }

    public function getData()
    {
        return $this->attributes['data'];
    }

    public static function fromArray($array)
    {        
        $instance = new static();
        $instance->setAttributes($array);

        return $instance;
    }

    public function toArray()
    {
        $attributes = $this->attributes;
        $attributes['data'] = ezfSolrStorage::unserializeData($attributes['data']);
        return $attributes;
    }
}