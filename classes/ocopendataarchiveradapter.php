<?php

class OCOpenDataArchiverAdapter
{
	public function archive($content)
	{
		return $content;
	}

	public function unarchive($content)
	{
		return $content;
	}

	public function toSearch($content, $language)
	{
		return $content;
	}

	public function cleanup($content)
	{
		return false;
	}
}