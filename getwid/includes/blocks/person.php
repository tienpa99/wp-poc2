<?php

namespace Getwid\Blocks;

class Person extends \Getwid\Blocks\AbstractBlock {

	protected static $blockName = 'getwid/person';

	public function __construct() {

		parent::__construct( self::$blockName );

		register_block_type(
			'getwid/person',
			array(
				'render_callback' => [ $this, 'render_callback' ]
			)
		);

	}

	public function getLabel() {
		return __('Person', 'getwid');
	}

    public function block_frontend_assets() {

        if ( is_admin() ) {
            return;
        }

		if ( FALSE == getwid()->assetsOptimization()->load_assets_on_demand() ) {
			return;
		}

		$rtl = is_rtl() ? '.rtl' : '';

		wp_enqueue_style(
			self::$blockName,
			getwid_get_plugin_url( 'assets/blocks/person/style' . $rtl . '.css' ),
			[],
			getwid()->settings()->getVersion()
		);
    }

	public function render_callback( $attributes, $content ) {

        $this->block_frontend_assets();

        return $content;
    }

}

getwid()->blocksManager()->addBlock(
	new \Getwid\Blocks\Person()
);
