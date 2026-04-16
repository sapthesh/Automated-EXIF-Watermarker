<?php

class AEWMC_Taxonomy_Manager {

	public function aewmc_register_taxonomies() {
		$taxonomies = array(
			'aewmc_camera_make'  => __( 'Camera Make', 'automated-exif-watermarker' ),
			'aewmc_lens_model'   => __( 'Lens Model', 'automated-exif-watermarker' ),
			'aewmc_focal_length' => __( 'Focal Length', 'automated-exif-watermarker' ),
			'aewmc_iso'          => __( 'ISO', 'automated-exif-watermarker' ),
		);

		foreach ( $taxonomies as $slug => $label ) {
			register_taxonomy( $slug, 'attachment', array(
				'hierarchical'      => false,
				'labels'            => array( 
					'name'          => $label, 
					'singular_name' => $label 
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $slug ),
				'show_in_rest'      => true,
			) );
		}
	}

	public function aewmc_assign_terms( $attachment_id, $data ) {
		if ( ! empty( $data['make'] ) ) {
			wp_set_object_terms( $attachment_id, sanitize_text_field( $data['make'] ), 'aewmc_camera_make' );
		}
		if ( ! empty( $data['lens'] ) ) {
			wp_set_object_terms( $attachment_id, sanitize_text_field( $data['lens'] ), 'aewmc_lens_model' );
		}
		if ( ! empty( $data['focal_length'] ) ) {
			wp_set_object_terms( $attachment_id, sanitize_text_field( $data['focal_length'] ), 'aewmc_focal_length' );
		}
		if ( ! empty( $data['iso'] ) ) {
			wp_set_object_terms( $attachment_id, 'ISO ' . sanitize_text_field( $data['iso'] ), 'aewmc_iso' );
		}
	}
}
