.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
margin-bottom: var(--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_gap)) !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
margin-bottom: 0 !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 span.rw_tabs_act_st_l<?php echo esc_html($Rich_Web_Tabs); ?> {
width: calc(var(--rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-li_s)) + 23px);
width: -webkit-calc(var(--rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-li_s)) + 23px);
height: 100%;
display: inline-block;
position: absolute;
background: var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-c));
top: 0;
left: 0;
border-radius: var(--rw_tabs_menu_item-br-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-br)) 0
var(--rw_tabs_menu_item-br-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-br))
var(--rw_tabs_menu_item-br-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-br));
z-index: 1;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> .active .arrowDown<?php echo esc_html($Rich_Web_Tabs); ?> {
opacity: 0;
}