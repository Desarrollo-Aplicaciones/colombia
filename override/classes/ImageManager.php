<?php
/**
 * Override This class includes functions for image manipulation
 *
 * @since 1.5.0
 */
class ImageManager extends ImageManagerCore
{
	/**
	 * Generate a cached thumbnail for object lists (eg. carrier, order states...etc)
	 *
	 * @param string $image Real image filename
	 * @param string $cache_image Cached filename
	 * @param int $size Desired size
	 * @param string $image_type Image type
	 * @param bool $disable_cache When turned on a timestamp will be added to the image URI to disable the HTTP cache
	 * @return string
	 */
	public static function thumbnail($image, $cache_image, $size, $image_type = 'jpg', $disable_cache = false)
	{
		// AWS Back-office Productos imagen portada, Pedidos imagen producto
		if ($image) {
			$path = explode('/', $image);
			if (in_array("img", $path) 
				&& in_array("p", $path)) {
				$image_id = explode('.', end($path));
				return '<img src="/img/p/' . $image_id[0] .'-small_default.jpg" alt="" class="imgm" />';
			}
		}

		if (!file_exists($image))
			return '';

		if (!file_exists(_PS_TMP_IMG_DIR_.$cache_image))
		{
			$infos = getimagesize($image);

			// Evaluate the memory required to resize the image: if it's too much, you can't resize it.
			if (!ImageManager::checkImageMemoryLimit($image))
				return false;

			$x = $infos[0];
			$y = $infos[1];
			$max_x = $size * 3;

			// Size is already ok
			if ($y < $size && $x <= $max_x)
				copy($image, _PS_TMP_IMG_DIR_.$cache_image);
			// We need to resize */
			else
			{
				$ratio_x = $x / ($y / $size);
				if ($ratio_x > $max_x)
				{
					$ratio_x = $max_x;
					$size = $y / ($x / $max_x);
				}

				ImageManager::resize($image, _PS_TMP_IMG_DIR_.$cache_image, $ratio_x, $size, $image_type);
			}
		}
		// Relative link will always work, whatever the base uri set in the admin
		if (Context::getContext()->controller->controller_type == 'admin')
			return '<img src="../img/tmp/'.$cache_image.(!$disable_cache ? '?time='.time() : '').'" alt="" class="imgm" />';
		else
			return '<img src="'._PS_TMP_IMG_.$cache_image.(!$disable_cache ? '?time='.time() : '').'" alt="" class="imgm" />';
	}

	/**
	 * Generate and write image
	 *
	 * @param string $type
	 * @param resource $resource
	 * @param string $filename
	 * @return bool
	 */
	public static function write($type, $resource, $filename)
	{
		switch ($type)
		{
			case 'gif':
				$success = imagegif($resource, $filename);
			break;

			case 'png':
				$quality = (Configuration::get('PS_PNG_QUALITY') === false ? 7 : Configuration::get('PS_PNG_QUALITY'));
				$success = imagepng($resource, $filename, (int)$quality);
			break;

			case 'jpg':
			case 'jpeg':
			default:
				$quality = (Configuration::get('PS_JPEG_QUALITY') === false ? 90 : Configuration::get('PS_JPEG_QUALITY'));
				imageinterlace($resource,1); /// make it PROGRESSIVE
				$success = imagejpeg($resource, $filename, (int)$quality);
			break;
		}
		imagedestroy($resource);
		@chmod($filename, 0664);

		// Sube las imágenes en AWS S3
		$awsObj = new Aws();
		if ($filename) {
			$oriPath = str_replace(_PS_IMG_DIR_, "", $filename);
			preg_match('/^([a-zA-Z]+).*(\/[_a-zA-Z0-9-]+\.jpg)$/i', $oriPath, $matches);
			array_shift($matches);
			$objAws = implode('', $matches);
			if ($success 
				&& $objAws 
				&& $awsObj->setObjectImage($filename, $objAws)) {
				error_log($type . " <> " . $resource . " <> " . $filename . " <> " . $objAws);
				// Elimina las imágenes del local
				// CRON 
				//unlink($filename);
			} else {
				$success = false;
			}
		}

		return $success;
	}

}