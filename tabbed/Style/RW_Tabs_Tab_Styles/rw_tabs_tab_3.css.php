	/* Underline */
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_3 li {
	padding: 18px 20px;
	-webkit-transition: color 0.2s;
	transition: color 0.2s;
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_3 li.active::after {
	-webkit-transform: translate3d(0,0,0);
	transform: translate3d(0,0,0);
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_3 li::after {
	position: absolute;
	bottom: 0;
	left: 0;
	width: 100%;
	height: 6px;
	background: var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-c));
	content: '';
	-webkit-transition: -webkit-transform 0.3s;
	transition: transform 0.3s;
	-webkit-transform: translate3d(0,150%,0);
	transform: translate3d(0,150%,0);
}