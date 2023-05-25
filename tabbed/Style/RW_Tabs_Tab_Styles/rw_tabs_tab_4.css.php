	/* Triangle and Line */
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_4
	{
		margin-bottom: 3px !important;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_4 li {
		padding: 18px 20px;
		overflow: visible !important;
		border-bottom: 1px solid var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-c));
		-webkit-transition: color 0.2s;
		transition: color 0.2s;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_4 li::after {
		position: absolute;
		width: 0;
		height: 0;
		border: solid transparent;
		content: '';
		pointer-events: none;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] .Rich_Web_Tabs_tabs_4 li::after,	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] .Rich_Web_Tabs_tabs_4 li::before{
		top: 50%;
		left: 100%;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_4 li::after,	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_4 li::before{
		top: 100%;
		left: 50%;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> .Rich_Web_Tabs_tabs_4 li::before {
		position: absolute;
		width: 0;
		height: 0;
		display: block !important;
		border: solid transparent;
		content: '' !important;
		pointer-events: none;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_4 li.active::after {
		margin-left: -10px;
		border-width: 10px;
		border-top-color: var(--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-bgc));
		z-index: 100;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] .Rich_Web_Tabs_tabs_4 li.active::after {
		margin-top: -10px;
		border-width: 10px;
		border-left-color: var(--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-bgc));
		z-index: 100;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] .Rich_Web_Tabs_tabs_4 li.active::before {
		margin-left: -11px;
		border-width: 11px;
		border-top-color:var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-c));
		z-index: 100;
	}
	.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] .Rich_Web_Tabs_tabs_4 li.active::before {
		margin-top: -11px;
		border-width: 11px;
		border-left-color:var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-a-c));
		z-index: 100;
	}
