	/* Icon Box */
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_2 {
		border: 2px solid var(--rw_tabs_menu_bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_bc));;
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_2 li {
	overflow: visible !important;
	position: relative;
	padding: 1em;
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_2 li.active {
	z-index: 100;
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_2 li.active::after {
	position: absolute;
	width: 0;
	height: 0;
	border: solid transparent;
	border-width: 10px;
	content: '';
	pointer-events: none;
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] .Rich_Web_Tabs_tabs_2 li.active::after{
	top: 50%;
	left: 100%;
	margin-top: -10px;
	border-left-color: var(--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-bgc));
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_2 li.active::after{
	top: 100%;
	left: 50%;
	margin-left: -10px;
	border-top-color: var(--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-bgc));
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_2 li i::before {
	position: relative;
	display: block;
	left: 0%;
}
.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_2 li i::before{
	text-align: center;
}