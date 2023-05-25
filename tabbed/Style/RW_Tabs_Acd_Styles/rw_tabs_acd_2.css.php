.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
margin-bottom:var(--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_gap)) !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
margin-bottom: 0 !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 span.rw_tabs_act_st_r<?php echo esc_html($Rich_Web_Tabs); ?> {
width: calc(var(--rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-ri_s)) + 17px);
width: -webkit-calc(var(--rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-ri_s)) + 17px);
height: 100%;
display: inline-block;
position: absolute;
background: var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-bc));
top: 0;
right: 0;
border-radius: 0 var(--rw_tabs_menu_item-br-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-br))
var(--rw_tabs_menu_item-br-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-br))
var(--rw_tabs_menu_item-br-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-br));
z-index: 1;
}