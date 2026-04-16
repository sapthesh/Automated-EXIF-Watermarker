<?php
/**
 * Plugin Name:       Automated EXIF-to-Taxonomy Cinematic Watermarker
 * Plugin URI:        https://github.com/sapthesh/Automated-EXIF-to-Taxonomy-Cinematic-Watermarker
 * Description:       Automatically extracts EXIF data into searchable taxonomies and adds a cinematic watermark to the 'large' image size.
 * Version:           1.0.1
 * Author:            Sapthesh V
 * Author URI:        https://github.com/sapthesh
 * License:           GPL v2 or later
 * Text Domain:       automated-exif-watermarker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants with AEWMC_ prefix
define( 'AEWMC_PATH', plugin_dir_path( __FILE__ ) );
define( 'AEWMC_ASSETS_PATH', AEWMC_PATH . 'assets/' );

// Load Classes
require_once AEWMC_PATH . 'includes/class-aewmc-taxonomy-manager.php';
require_once AEWMC_PATH . 'includes/class-aewmc-exif-extractor.php';
require_once AEWMC_PATH . 'includes/class-aewmc-watermarker.php';

/**
 * Main Plugin Class: AEWMC_Main
 */
class AEWMC_Main {

	public function __construct() {
		$this->aewmc_init_hooks();
	}

	private function aewmc_init_hooks() {
		$taxonomy_manager = new AEWMC_Taxonomy_Manager();
		
		add_action( 'init', array( $taxonomy_manager, 'aewmc_register_taxonomies' ) );
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'aewmc_process_image_metadata' ), 10, 2 );
	}

	public function aewmc_process_image_metadata( $metadata, $attachment_id ) {
		$file_path = get_attached_file( $attachment_id );

		if ( ! $file_path || ! file_exists( $file_path ) ) {
			return $metadata;
		}

		// 1. Extract EXIF Data
		$extractor = new AEWMC_EXIF_Extractor( $file_path );
		$exif_data = $extractor->aewmc_get_data();

		if ( empty( $exif_data ) ) {
			return $metadata;
		}

		// 2. Assign Taxonomies (Sanitized)
		$taxonomy_manager = new AEWMC_Taxonomy_Manager();
		$taxonomy_manager->aewmc_assign_terms( $attachment_id, $exif_data );

		// 3. Apply Watermark to 'large' size
		if ( isset( $metadata['sizes']['large'] ) ) {
			$upload_dir = wp_upload_dir();
			$base_dir   = trailingslashit( $upload_dir['basedir'] );
			$file_dir   = dirname( $metadata['file'] );
			$large_file = $metadata['sizes']['large']['file'];
			
			$large_path = $base_dir . trailingslashit( $file_dir ) . $large_file;

			if ( file_exists( $large_path ) ) {
				$watermarker = new AEWMC_Watermarker( $large_path, $exif_data );
				$watermarker->aewmc_apply();
			}
		}

		return $metadata;
	}
}

new AEWMC_Main();
