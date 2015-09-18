<?php

/*
 * This file is a part of the BenStore PHP Framework.
* All rights reserved.
* (C) Bence Szabo 2013
*
*/

/**
 *
 * Image functions
 */
class BaseFileHelper {

	/**
	 *
	 * Application instance.
	 */
	private $app;

	/**
	 *
	 * Setting application instance.
	 */
	public function setApp($value) {
		$this->app = $value;
	}

	/**
	 *
	 * Creates resized, converted image file.
	 */
	public function createThumbnail($source, $dest, $desttype = 'png', $maxwidth = null, $maxheight = null) {

		if (!in_array($desttype, array('png', 'jpg')))
			return false;

		$imgdata = getimagesize($source);
		if ($imgdata === false)
			return false;

		list($width, $height, $type) = $imgdata;
		if ($type == 2 || $type == 3) {
			if ($type == 2) {
				$img_src = imagecreatefromjpeg($source);
			}
			if ($type == 3) {
				$img_src = imagecreatefrompng($source);
			}
			$newwidth = $width;
			$newheight = $height;
			if ($maxwidth != null) {
				if ($newwidth > $maxwidth)
					$newwidth = $maxwidth;
				$rate = $width / $height;
				$newheight = $newwidth / $rate;
			}
			if ($maxheight != null) {
				if ($newheight > $maxheight) {
					$newheight = $maxheight;
					$newwidth = $rate * $newheight;
				}
			}
			$img_dest = imagecreatetruecolor($newwidth, $newheight);
			if ($type == 3) {
				$black = imagecolorallocate($img_dest, 0, 0, 0);
				imagecolortransparent($img_dest, $black);
				imagealphablending($img_dest, false);
			}
			imagecopyresampled($img_dest, $img_src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			imagesavealpha($img_dest, true);
			if (!is_dir(dirname($dest)))
				mkdir(dirname($dest));
			if ($desttype == 'png')
				imagepng($img_dest, $dest);
			if ($desttype == 'jpg')
				imagejpeg($img_dest, $dest);
			return true;
		} else {
			return false;
		}
	}

	function deleteDir($target) {
		if(is_dir($target)){
			$files = glob( $target . '*', GLOB_MARK );

			foreach( $files as $file )
			{
				$this->deleteDir( $file );
			}

			@rmdir( $target );
		} elseif(is_file($target)) {
			echo 'file<br/>';
			unlink( $target );
		}
	}

}

?>