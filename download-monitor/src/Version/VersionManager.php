<?php

class DLM_Version_Manager {

	/**
	 * Get version IDs of given Download ID
	 *
	 * @param int $download_id
	 *
	 * @return array
	 */
	public function get_version_ids( $download_id ) {

		// After import or in some situations, dlm_download_version has post parent set to 0, which is not correct.
		// Versions should always have a valid  dlm_download parent.
		if ( 0 !== $download_id && 'dlm_download' === get_post_type( $download_id ) ) {
			return get_posts( 'post_parent=' . $download_id . '&post_type=dlm_download_version&orderby=menu_order&order=ASC&fields=ids&post_status=publish&numberposts=-1' );
		} else {
			return array();
		}
	}
}
