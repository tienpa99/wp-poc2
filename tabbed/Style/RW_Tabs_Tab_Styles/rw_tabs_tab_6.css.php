		/* Falling Icon */
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_6 li {
		display: inline-block;
		overflow: visible;
		padding: 1em 2em;
		-webkit-transition: color 0.3s cubic-bezier(0.7,0,0.3,1);
		transition: color 0.3s cubic-bezier(0.7,0,0.3,1);
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_6 li::before {
		position: absolute;
		display: block !important;
		bottom: 0;
		width: 100%;
		left: 0;
		height: 4px;
		background: var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-c));
		content: '';
		opacity: 0;
		-webkit-transition: -webkit-transform 0.2s ease-in;
		transition: transform 0.2s ease-in;
		-webkit-transform: scale3d(0,1,1);
		transform: scale3d(0,1,1);
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_6 li.active::before {
		opacity: 1;
		-webkit-transform: scale3d(1,1,1);
		transform: scale3d(1,1,1);
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_6 li i::before {
		display: block;
		position: relative;
		left: 0%;
		opacity: 0;
		-webkit-transition: -webkit-transform 0.2s, opacity 0.2s;
		transition: transform 0.2s, opacity 0.2s;
		-webkit-transform: translate3d(0,-100px,0);
		transform: translate3d(0,-100px,0);
		pointer-events: none;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_6 li i::before {
		text-align: center;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_6 li.active i::before {
		opacity: 1;
		-webkit-transform: translate3d(0,0,0);
		transform: translate3d(0,0,0);
	}