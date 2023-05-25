.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
margin-bottom:var(--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_gap)) !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
margin-bottom: 0 !important;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
border-bottom: 1px solid transparent;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover {
border-bottom: 5px solid var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-bc));
box-shadow: none;
opacity: 1;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active {
border-bottom: 5px solid var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-bc));
box-shadow: none;
opacity: 1;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover i#rich-web-acd-icon<?php echo esc_html($Rich_Web_Tabs); ?> {
opacity: 1;
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 i#rich-web-acd-icon<?php echo esc_html($Rich_Web_Tabs); ?> {
left: 15px;
opacity: 0.3;
box-shadow: 1px 1px 40px 1px #fff;
border-radius: 50px;
background: var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-bc));
padding: 5px;
width: calc(var(--rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-li_s)) + 10px);
width: -webkit-calc(var(--rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-li_s)) + 10px);
height: calc(var(--rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-li_s)) + 10px);
height: -webkit-calc(var(--rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>,var(--rw_tabs_menu_item-li_s)) + 10px);
}
.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover span.rw_tabs_act_st_l<?php echo esc_html($Rich_Web_Tabs); ?> {
opacity: 1 !important;
box-shadow: none !important;
}