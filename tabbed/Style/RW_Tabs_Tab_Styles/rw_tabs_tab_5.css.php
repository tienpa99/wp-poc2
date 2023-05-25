			/* Top Line */
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_5 li {
		padding:1em;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_5 li.active {
		background: none;
		box-shadow: inset 0 3px 0 var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-c)) !important;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_5 li i::before {
		display: block;
		position: relative;
		left: 0%;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_5 li i::before {
		text-align: center;
	}
