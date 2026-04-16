<?php

class AEWMC_EXIF_Extractor {

	private $file_path;

	public function __construct( $file_path ) {
		$this->file_path = $file_path;
	}

	public function aewmc_get_data() {
		$exif = @exif_read_data( $this->file_path );

		if ( ! $exif ) {
			return array();
		}

		return array(
			'make'         => isset( $exif['Make'] ) ? trim( $exif['Make'] ) : '',
			'model'        => isset( $exif['Model'] ) ? trim( $exif['Model'] ) : '',
			'lens'         => isset( $exif['UndefinedTag:0xA434'] ) ? trim( $exif['UndefinedTag:0xA434'] ) : ( isset( $exif['LensModel'] ) ? $exif['LensModel'] : '' ),
			'focal_length' => isset( $exif['FocalLength'] ) ? $this->aewmc_format_focal_length( $exif['FocalLength'] ) : '',
			'iso'          => isset( $exif['ISOSpeedRatings'] ) ? $exif['ISOSpeedRatings'] : '',
		);
	}

	private function aewmc_format_focal_length( $value ) {
		if ( strpos( $value, '/' ) !== false ) {
			$parts = explode( '/', $value );
			if ( count( $parts ) == 2 && $parts[1] != 0 ) {
				return ( $parts[0] / $parts[1] ) . 'mm';
			}
		}
		return $value . 'mm';
	}
}
