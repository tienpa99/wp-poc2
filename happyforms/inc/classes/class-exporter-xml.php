<?php

class HappyForms_Exporter_XML {

	public $form_id;
	public $filename;

	public function __construct( $form_id, $filename ) {
		$this->form_id = $form_id;
		$this->filename = $filename;
	}

	public function export() {
		global $wpdb;

		$form = $wpdb->get_row( $wpdb->prepare( "
			SELECT * FROM $wpdb->posts WHERE ID = %d AND post_type = 'happyform';
		", $this->form_id ) );

		if ( ! $form ) {
			$error = __( 'Form not found', 'happyforms' );
			return new WP_Error( $error );
		}

		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $this->filename );
		header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

		?><?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?><root xmlns:hf="happyforms"><?php

		$this->export_form( $form );

		?></root><?php
	}

	private function export_form( $form ) {
		?><hf:form><?php
		?><hf:post_title><?php echo $this->cdata( $form->post_title ); ?></hf:post_title><?php
		?><hf:post_status><?php echo $this->cdata( $form->post_status ); ?></hf:post_status><?php

		$this->export_metas( $form );

		?></hf:form><?php
	}

	private function export_metas( $post ) {
		global $wpdb;

		$metas = $wpdb->get_results( $wpdb->prepare( "
			SELECT m.meta_key, m.meta_value
			FROM $wpdb->postmeta m
			JOIN $wpdb->posts p ON p.ID = m.post_id
			AND m.meta_key LIKE '_happyforms%%'
			WHERE p.ID = %d;
		", $post->ID ) );

		foreach( $metas as $meta ) {
			$meta_value = $this->value( $meta->meta_value );
			?><hf:meta name="<?php echo $meta->meta_key; ?>"><?php echo $meta_value; ?></hf:meta><?php
		}
	}

	private function value( $value ) {
		$value = (
			'string' === gettype( $value ) ?
			$this->cdata( $value ) : $value
		);

		return $value;
	}

	private function cdata( $str ) {
		if ( ! seems_utf8( $str ) ) {
			$str = utf8_encode( $str );
		}

		$str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

		return $str;
	}

}