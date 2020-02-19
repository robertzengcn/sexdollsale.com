<?php
/**
 * required functions for jscript auto_loaders
 *
 * @author yellow1912 (RubikIntegration.com)
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 */

function array_merge_recursive2($array1, $array2)
{
    $arrays = func_get_args();
    $narrays = count($arrays);
   
    // check arguments
    // comment out if more performance is necessary (in this case the foreach loop will trigger a warning if the argument is not an array)
    /*
    for ($i = 0; $i < $narrays; $i ++) {
        if (!is_array($arrays[$i])) {
            // also array_merge_recursive returns nothing in this case
            trigger_error('Argument #' . ($i+1) . ' is not an array - trying to merge array with scalar! Returning null!', E_USER_WARNING);
            return;
        }
    }*/
   
    // the first array is in the output set in every case
    $ret = $arrays[0];
   
    // merege $ret with the remaining arrays
    for ($i = 1; $i < $narrays; $i ++) {
        foreach ($arrays[$i] as $key => $value) {
            if (((string) $key) === ((string) intval($key))) { // integer or string as integer key - append
                $ret[] = $value;
            }
            else { // string key - megre
                if (is_array($value) && isset($ret[$key])) {
                    // if $ret[$key] is not an array you try to merge an scalar value with an array - the result is not defined (incompatible arrays)
                    // in this case the call will trigger an E_USER_WARNING and the $ret[$key] will be null.
                    $ret[$key] = array_merge_recursive2($ret[$key], $value);
                }
                else {
                    $ret[$key] = $value;
                }
            }
        }   
    }
   
    return $ret;
}

function loadFiles($_jscripts, $jfiles){
		foreach($_jscripts as $files){
			foreach($files as $file=>$order)
			if(!isset($jfiles[$file]) || $jfiles[$file] > $order) $jfiles[$file] = $order;		
		}
		
		if(count($jfiles) > 0)
			asort($jfiles);	
		return $jfiles;
}

function getPath($file, $type='jscript'){
	$path_info = pathinfo($file);
	return array('extension' => $path_info['extension'], 'path' => DIR_WS_TEMPLATE.$type.'/'.$path_info['dirname'].$file_name);
}

	
function getMinifyfiles($files, $request_string, $folder){
	global $request_type, $current_page_base, $template;
	$relative_path = $request_type == 'NONSSL' ? DIR_WS_CATALOG : DIR_WS_HTTPS_CATALOG;
	$files_paths = '';$result = array();
	foreach($files as $file=>$order){
		// case 1: file is in server but full path not passed, assuming it is under corresponding template css/js folder
		if($file_exists = file_exists(DIR_FS_CATALOG.DIR_WS_TEMPLATE.$folder.$file)){
			$file_absolute_path = DIR_FS_CATALOG.DIR_WS_TEMPLATE.$folder.$file;
			$file_relative_path = $relative_path.DIR_WS_TEMPLATE.$folder.$file;
		}
		// case 2: file is in the default template
		elseif($file_exists = file_exists(DIR_FS_CATALOG.DIR_WS_TEMPLATES.'template_default/'.$folder.$file)){
			$file_absolute_path = DIR_FS_CATALOG.DIR_WS_TEMPLATES.'template_default/'.$folder.$file;
			$file_relative_path = $relative_path.DIR_WS_TEMPLATES.'template_default/'.$folder.$file;
		}
		// case 3: file is in the server, can be accessed via the same domain, full path passed
		elseif($file_exists = file_exists($file)){
			$file_absolute_path = DIR_FS_CATALOG.$file; 
			$file_relative_path = $relative_path.$file; 
		}
		// case 4: file is not even on the same domain
		if(substr($file, 0, 4) == 'http'){
			$file_relative_path = $file; 
			$file_exists = true;
		}
		
		if($file_exists){
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			// if we encounter php, unfortunately we will have to include it for now
			// another solution is to put everything into 1 file, but we will have to solve @import
			if($ext == 'php'){
				if($files_paths != ''){
					$result[] = array('string' => sprintf($request_string, trim($files_paths, ',')), 'include' => false);	
					$files_paths = '';		
				}
			
				$result[] = array('string' => $file_absolute_path, 'include' => true);
			}
			else{
				$files_paths .= $file_relative_path.',';
			}
		}
	}
	
	// one last time
	if($files_paths != '')
		$result[] = array('string' => sprintf($request_string, trim($files_paths, ',')), 'include' => false);
	return $result;
}

function getFiles($files, $request_string, $folder){
	global $request_type;
	$result = array();
	$relative_path = $request_type == 'NONSSL' ? DIR_WS_CATALOG : DIR_WS_HTTPS_CATALOG;
	foreach($files as $file=>$order){
		// case 1: file is in server but full path not passed, assuming it is under corresponding template css/js folder
		if($file_exists = file_exists(DIR_FS_CATALOG.DIR_WS_TEMPLATE.$folder.$file)){
			$file_absolute_path = DIR_FS_CATALOG.DIR_WS_TEMPLATE.$folder.$file;
			$file_relative_path = $relative_path.DIR_WS_TEMPLATE.$folder.$file;
		}
		// case 2: file is in the default template
		elseif($file_exists = file_exists(DIR_FS_CATALOG.DIR_WS_TEMPLATES.'template_default/'.$folder.$file)){
			$file_absolute_path = DIR_FS_CATALOG.DIR_WS_TEMPLATES.'template_default/'.$folder.$file;
			$file_relative_path = $relative_path.DIR_WS_TEMPLATES.'template_default/'.$folder.$file;
		}
		// case 3: file is in the server, can be accessed via the same domain, full path passed
		elseif($file_exists = file_exists($file)){
			$file_absolute_path = DIR_FS_CATALOG.$file; 
			$file_relative_path = $relative_path.$file; 
		}
		// case 4: file is not even on the same domain
		if(substr($file, 0, 4) == 'http'){
			$file_relative_path = $file; 
			$file_exists = true;
		}
		
		if($file_exists){
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			// if we encounter php, unfortunately we will have to include it for now
			// another solution is to put everything into 1 file, but we will have to solve @import
			if($ext == 'php')
				$result[] = array('string' => $file_absolute_path, 'include' => true);
			else
				$result[] = array('string' => sprintf($request_string, $file_relative_path), 'include' => false);
		}
	}
	return $result;
}

function loadCssJsFiles($css_files, $js_files){
	global $loaders, $current_page_base, $minify_cache_time_latest;
	if(isset($loaders) && count($loaders) > 0){
		$_jscripts = $_css_files = array();
		foreach($loaders as $j){
			if(in_array('*', $j['conditions']['pages']) || in_array($current_page_base, $j['conditions']['pages'])){
				if(isset($j['jscript_files']))
					$_jscript_files[] = $j['jscript_files'];
				if(isset($j['css_files']))
					$_css_files[] = $j['css_files'];
			}
			else{
				$load = false;	
				if(isset($j['conditions']['call_backs']))
				foreach($j['conditions']['call_backs'] as $function){
					$f = explode(',',$function);
					if(count($f) == 2){
						$load = call_user_func(array($f[0], $f[1]));
					}
					else $load = $function();
					
					if($load){
						if(isset($j['jscript_files']))
							$_jscript_files[] = $j['jscript_files'];
						if(isset($j['css_files']))
							$_css_files[] = $j['css_files'];
						break;
					}
				}
			}
		}
	
		if(count($_css_files) > 0){
			$css_files = loadFiles($_css_files, $css_files);	
		}
	
		if(count($_jscript_files) > 0){
			$js_files = loadFiles($_jscript_files, $js_files);
		}
	}
	
	$files = array();
	if(MINIFY_STATUS == 'true'){
		$files['js'] = getMinifyfiles($js_files, "<script type=\"text/javascript\" src=\"min/?f=%s&$minify_cache_time_latest\"></script>\n", 'jscript/');
		$files['css'] = getMinifyfiles($css_files, "<link rel=\"stylesheet\" type=\"text/css\" href=\"min/?f=%s&$minify_cache_time_latest\" />\n", 'css/');
	}
	else{
		$files['js'] = getFiles($js_files, '<script type="text/javascript" src="%s"></script>' . "\n", 'jscript/');
		$files['css'] = getFiles($css_files, '<link rel="stylesheet" type="text/css" href="%s" />' . "\n", 'css/');
	}
	return $files;
}