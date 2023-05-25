		/* Moving Line */
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_7 li::before {
		position: absolute;
		display: block !important;
		bottom: 0;
		left: 0;
		width: 100% !important;
		height: 4px;
		background: var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-c));
		content: '';
		-webkit-transition: -webkit-transform 0.3s !important;
		transition: transform 0.3s !important;
		-webkit-transform: translate3d(101%,0,0);
		transform: translate3d(101%,0,0);
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] .Rich_Web_Tabs_tabs_7 li::before {
		position: absolute;
		display: block !important;
		top: 0;
		right: 0;
		width: 4px !important;
		height: 100%;
		background: var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-c));
		content: '';
		-webkit-transition: -webkit-transform 0.3s !important;
		transition: transform 0.3s !important;
		-webkit-transform: translate3d(0,101%,0);
		transform: translate3d(0,101%,0);
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_7 li.active::before {
		-webkit-transform: translate3d(0%,0,0);
		transform: translate3d(0%,0,0);
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_7 li.active{
		-webkit-transform: translate3d(0,4px,0);
		transform: translate3d(0,4px,0);
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] .Rich_Web_Tabs_tabs_7 li.active{
		-webkit-transform: translate3d(-4px,0,0);
		transform: translate3d(-4px,0,0);
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_7 li {
		padding: 1em 0.5em;
		margin: 0 0 5px -2px !important;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] .Rich_Web_Tabs_tabs_7 li {
		padding: 1em 0.5em;
		margin: 2px 5px 0px 0px !important;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_7 li:nth-child(1) {
		margin: 0 0 5px 0px !important;
	}