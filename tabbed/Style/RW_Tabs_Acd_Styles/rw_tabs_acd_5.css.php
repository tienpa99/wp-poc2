.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
margin-bottom: var(--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_gap)) !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
margin-bottom: 0 !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
transition: all 0.3s !important;
-webkit-transition: all 0.3s !important;
-moz-transition: all 0.3s !important;
border-right: 1px solid transparent;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i {
background: var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-bc));
border-radius: 50px;
padding: 1px 1px 0 1px;
box-sizing: content-box;
transition: all 0.4s !important;
-webkit-transition: all 0.4s !important;
-moz-transition: all 0.4s !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover {
border-right: 9px solid var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-bc));
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i {
width: calc(var(--rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-ri_s)) + 2px);
width: -webkit-calc(var(--rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-ri_s)) + 2px);
height: calc(var(--rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-ri_s)) + 2px);
height: -webkit-calc(var(--rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-ri_s)) + 2px);
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i {
background: var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-bc));
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active {
border-right: 9px solid var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-bc));
}