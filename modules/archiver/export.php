<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$year = $http->getVariable('published_year');
$class = $http->getVariable('class');
$format = $http->getVariable('format');

if ($year && $class && $format){

	$repository = new OCOpenDataArchiveSearchableRepository();
	$parametes = OCCustomSearchParameters::instance()->setFilters(array(
		'published_year' => $year,
		'class' => $class
	))->setLimit(1);

	$resultCount = $repository->find($parametes);	
	$totalCount = $resultCount['totalCount'];

	$data = array();
	$lenght = 100;
	while(true){
		$parametes->setLimit($lenght)->setOffset($offset);
		$results = $repository->find($parametes);
		foreach ($results['searchHits'] as $item) {
			$data[] = $item->getData();
		}
		if (count($data) >= $totalCount){
			break 1;
		}
		$offset += $lenght;
	}

	$filename = $class . '.' . $year . '.' . $format;
	
	if ($format == 'json'){
		header('Content-Type: application/json');
		header( "Content-Disposition: attachment; filename=$filename" );
    	echo json_encode( $data );
    	eZexecution::cleanExit();

	}elseif ($format == 'csv'){

		function implodeRecursive($separator, $array)
		{
			if (is_array($array)){
				$output = " ";
				foreach ($array as $value){
					if (is_array($value)){
						$output .= implodeRecursive($separator, $value);	
					}
					else{
						$output .= $separator.$value;	
					} 
				}

				return trim($output, $separator);
			}

			return $array;
		}

		function toArrayOfString($array)
		{
			$stringArray = array();
			foreach ($array as $key => $value) {
				$stringArray[$key] = implodeRecursive(' ', $value);
			}

			return $stringArray;
		}

		ob_get_clean(); 
		$output = fopen('php://output', 'w');

        header( 'X-Powered-By: eZ Publish' );
        header( 'Content-Description: File Transfer' );
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( "Content-Disposition: attachment; filename=$filename" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );

        foreach ($data as $index => $item){
        	if ($index == 0){
        		fputcsv($output, array_keys($item));
        	}
        	fputcsv($output, toArrayOfString($item));
        	flush();
        }
        eZexecution::cleanExit();
	
	}else{
		return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
	}

}else{
	return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}