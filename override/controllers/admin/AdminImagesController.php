<?php

class AdminImagesController extends AdminImagesControllerCore
{

public $progreso = '';

	public function _regenerateThumbnails_bash($type = 'products', $deleteOldImages = false, $id_imagen, $id_producto, $type_product = 'all')
	{
		$this->progreso .= date('H:i:s');
		$this->start_time = time();
		ini_set('max_execution_time', $this->max_execution_time); // ini_set may be disabled, we need the real value
		$this->max_execution_time = (int)ini_get('max_execution_time');
		$languages = Language::getLanguages(false);

		$process =
			array(
				array('type' => 'products', 'dir' => _PS_PROD_IMG_DIR_)
			);

			/*array('type' => 'categories', 'dir' => _PS_CAT_IMG_DIR_),
				array('type' => 'manufacturers', 'dir' => _PS_MANU_IMG_DIR_),
				array('type' => 'suppliers', 'dir' => _PS_SUPP_IMG_DIR_),
				array('type' => 'scenes', 'dir' => _PS_SCENE_IMG_DIR_),
				array('type' => 'products', 'dir' => _PS_PROD_IMG_DIR_),
				array('type' => 'stores', 'dir' => _PS_STORE_IMG_DIR_)
				*/
		
		// Launching generation process
		foreach ($process as $proc)
		{
			$this->progreso .= " - 2 dir:".$proc['dir'];
			if ($type != 'all' && $type != $proc['type']) {
				continue;
			}

			// Getting format generation
			$formats = ImageType::getImagesTypes($type); //$proc['type']
			if ($type != 'all')
			{				
				$format = $type_product;//strval(Tools::getValue('format_'.$type));
				if ($format != 'all') {					
					foreach ($formats as $k => $form) {
						//$this->progreso .= " - paso5";
						//$this->progreso .= " - key: ";print_r($k);
						//$this->progreso .= " - value: ";print_r($form);
						if ($form['id_image_type'] != $format) {							
							unset($formats[$k]);
						}
					}					
				}
			}

			if ($deleteOldImages) {
				$this->_deleteOldImages_bash($proc['dir'], $formats, ($proc['type'] == 'products' ? true : false), $id_imagen, $id_producto);
			}

			if (($return = $this->_regenerateNewImages_bash($proc['dir'], $formats, ($proc['type'] == 'products' ? true : false), $id_imagen, $id_producto)) === true) {
				
				if (!count($this->errors)) {
					$this->errors[] = sprintf(Tools::displayError('Cannot write %s images. Please check the folder\'s writing permissions %s.'), $proc['type'], $proc['dir']);
				}

			} elseif ($return == 'timeout') {
				$this->errors[] = Tools::displayError('Only part of the images have been regenerated. The server timed out before finishing.');
			} else {
				if ($proc['type'] == 'products') {
					//if ($this->_regenerateWatermark($proc['dir']) == 'timeout') {
						$this->errors[] = Tools::displayError('Server timed out. The watermark may not have been applied to all images.');
					//}
				}

				/*if (!count($this->errors)) {
					if ($this->_regenerateNoPictureImages($proc['dir'], $formats, $languages)) {
						$this->errors[] = sprintf(
							Tools::displayError('Cannot write "No picture" image to (%s) images folder. Please check the folder\'s writing permissions.'),
							$proc['type']
						);
					}
				}*/
			}
		}

		$handle = fopen(_ROUTE_FILE_."/modules/regenerate/log_reg_".date("Ymd").".txt", 'a+');
	    fwrite($handle,"\r\n".$this->progreso);
	    fclose($handle);
		return (count($this->errors) > 0 ? false : true);
	}

	public function editarCadena($cadena){

	   	for($i=0;$i<strlen($cadena);$i++){ 
	       	$miarray[$i]=$cadena[$i]; 
	   	} 
	   	return $miarray; 
	}

	public function _deleteOldImages_bash($dir, $type, $product = false, $id_imagen, $id_producto)
	{
		$this->progreso .= " - DEL_I im:".$id_imagen."|p:".$id_producto;
		if (!is_dir($dir)) {
			$this->progreso .= " - no dir";
			return false;
		}
		
		//$this->progreso .= "\r\n".$ruta_dir = $dir.implode("/", $this->editarCadena($id_imagen))."/";
		//$this->progreso .= "- ruta ".
		$ruta_dir = $dir.implode("/", $this->editarCadena($id_imagen))."/";
		//print_r($type);
		//exit;
		$cant_del=0;
		$cant_no_del=0;
		foreach ($type as $imageType) {			
				//$this->progreso .= " - ruta: ".$ruta_dir.$id_imagen."-".$imageType['name'].".jpg";
				if (file_exists($ruta_dir.$id_imagen."-".$imageType['name'].".jpg")) {
					$cant_del++;
					//$this->progreso .= " - BORRADO";
					unlink($ruta_dir.$id_imagen."-".$imageType['name'].".jpg");
				} else {
					$cant_no_del++;
					//$this->progreso .= " - NO BORRADO  ";
				}
		}
		$this->progreso .= " - SI=".$cant_del." | NO=".$cant_no_del."| DEL_F ";
		
		
		/*
		// delete product images using new filesystem.
		if ($product)
		{
			$productsImages = Image::getAllImages();
			foreach ($productsImages as $image)
			{
				$this->progreso .= " - a - ";
				print_r($image);

				
				$imageObj = new Image($image['id_image']);
				$imageObj->id_product = $image['id_product'];
				if (file_exists($dir.$imageObj->getImgFolder()))
				{
					$toDel = scandir($dir.$imageObj->getImgFolder());
					foreach ($toDel as $d) {
						foreach ($type as $imageType) {
							if (preg_match('/^[0-9]+\-'.$imageType['name'].'\.jpg$/', $d)) {
								if (file_exists($dir.$imageObj->getImgFolder().$d)) {
									unlink($dir.$imageObj->getImgFolder().$d);
								}
							}
						}
					}
				}
			}
		}

		exit;*/

	}


	public  function _regenerateNewImages_bash($dir, $type, $productsImages = false, $id_imagen = 0, $id_producto = 0)
	{
		$this->progreso .= " - REG_I ";
		if (!is_dir($dir))
			return false;

		$errors = false;
		if ($productsImages) {
			//foreach (Image::getAllImages() as $image)
			//{
				if ($id_imagen != 0) {

				$imageObj = new Image($id_imagen);
				$existing_img = $dir.$imageObj->getExistingImgPath().'.jpg';
				$this->progreso .= " - dir : ".$existing_img;
				$cant_reg=0;
				$cant_no_reg=0;
				if (file_exists($existing_img) && filesize($existing_img))
				{
					$this->progreso .= " - IMG OK";
					foreach ($type as $imageType) {	
					//$this->progreso .= " - regenerando imagen tipo ".$imageType['name'];			
						if (!file_exists($dir.$imageObj->getExistingImgPath().'-'.stripslashes($imageType['name']).'.jpg')) {
							if (!ImageManager::resize($existing_img, $dir.$imageObj->getExistingImgPath().'-'.stripslashes($imageType['name']).'.jpg', (int)($imageType['width']), (int)($imageType['height']))) {
								//$this->progreso .= " - error regenerando tipo imagen ".$imageType['name']
								$cant_no_reg++;
								$this->progreso = " ** ". $this->progreso;
								$errors = true;
								$this->errors[] = Tools::displayError(sprintf('Original image is corrupt (%s) or bad permission on folder', $existing_img));
							} else {
								$cant_reg++;
								//$this->progreso .= " - OK regenerando tipo imagen ".$imageType['name'];
							}
						} else {
							$cant_no_reg++;
							$this->progreso = " ** ". $this->progreso;
							//$this->progreso .= " - error imagen existe en directorio - tipo imagen ".$imageType['name'];
						}
					}
				}
				else
				{	
					$this->progreso .= " - NO IMG FND ";
					$this->progreso = " ** ". $this->progreso;
					$errors = true;
					$this->errors[] = Tools::displayError(sprintf('Original image is missing or empty (%s)', $existing_img));
				}
			}
		}
		$this->progreso .= " - SI ".$cant_reg." NO ".$cant_no_reg." REG_F :";
		//print_r($errors);
		//exit;
		return $errors;
	}


}

?>