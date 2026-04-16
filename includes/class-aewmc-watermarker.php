<?php

class AEWMC_Watermarker {

	private $image_path;
	private $exif_data;

	public function __construct( $image_path, $exif_data ) {
		$this->image_path = $image_path;
		$this->exif_data  = $exif_data;
	}

	public function aewmc_apply() {
		if ( ! extension_loaded('gd') || ! function_exists('imagecreatefromjpeg') ) {
			return;
		}

		$info = getimagesize( $this->image_path );
		if ( ! $info ) return;

		$mime = $info['mime'];

		switch ( $mime ) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg( $this->image_path );
				break;
			case 'image/png':
				$image = imagecreatefrompng( $this->image_path );
				break;
			default:
				return;
		}

		if ( ! $image ) return;

		$white = imagecolorallocatealpha( $image, 255, 255, 255, 25 );
		$font  = AEWMC_ASSETS_PATH . 'fonts/cinematic-font.ttf';
		$use_ttf = file_exists( $font );

		$text = sprintf( 
			"%s | %s | %s | ISO %s", 
			$this->exif_data['model'], 
			$this->exif_data['lens'], 
			$this->exif_data['focal_length'], 
			$this->exif_data['iso'] 
		);

		$width  = imagesx( $image );
		$height = imagesy( $image );

		if ( $use_ttf ) {
			$font_size = round( $width * 0.015 );
			$padding   = round( $width * 0.02 );
			$bbox = imagettfbbox( $font_size, 0, $font, $text );
			$text_width = $bbox[2] - $bbox[0];
			
			imagettftext( $image, $font_size, 0, $width - $text_width - $padding, $height - $padding, $white, $font, $text );
		} else {
			imagestring( $image, 5, $width - 350, $height - 40, $text, $white );
		}

		switch ( $mime ) {
			case 'image/jpeg':
				imagejpeg( $image, $this->image_path, 90 );
				break;
			case 'image/png':
				imagepng( $image, $this->image_path );
				break;
		}

		imagedestroy( $image );
	}
}
