<?php
/**
 * Include and setup custom metaboxes and fields for WC PIGO.
 *
 * @package  WooCommerce_Product_Image_Gallery_Options
 */

/**
 * Hook in and add our book metabox
 */
function wc_pigo_register__metabox() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_wc_pigo_';
	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Product Image Gallery Options', 'woocommerce-product-image-gallery-options' ),
		'object_types'  => array( 'product', ), 
		'context'      => 'side',
	) );

	$cmb->add_field( array(
		'name' => __( 'Hide Image Zoom', 'woocommerce-product-image-gallery-options' ),
		'desc' => __( 'Hides the Image Zoom feature for this product', 'woocommerce-product-image-gallery-options' ),
		'id'   => $prefix . 'hide_image_zoom',
		'type' => 'checkbox',
	) );
	$cmb->add_field( array(
		'name' => __( 'Hide Image Lightbox', 'woocommerce-product-image-gallery-options' ),
		'desc' => __( 'Hides the Lightbox feature for this product', 'woocommerce-product-image-gallery-options' ),
		'id'   => $prefix . 'hide_image_lightbox',
		'type' => 'checkbox',
	) );
	$cmb->add_field( array(
		'name' => __( 'Hide Image Slider', 'woocommerce-product-image-gallery-options' ),
		'desc' => __( 'Hides the Slider feature for this product', 'woocommerce-product-image-gallery-options' ),
		'id'   => $prefix . 'hide_image_slider',
		'type' => 'checkbox',
	) );
}
add_action( 'cmb2_init', 'wc_pigo_register__metabox' );