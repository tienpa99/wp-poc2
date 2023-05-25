<?php if(SQ_Classes_Helpers_Tools::getValue('page') == 'sq_automation'){ ?>
<div class="sq_sub_nav d-flex flex-column bd-highlight m-0 p-0 border-right">
    <?php
    $page = SQ_Classes_Helpers_Tools::getValue('page');
    $tabs = SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getTabs($page);
    $patterns = SQ_Classes_Helpers_Tools::getOption('patterns');

    if (!empty($tabs)) {
        $current = (SQ_Classes_Helpers_Tools::getValue('tab') ? $page.'/'.SQ_Classes_Helpers_Tools::getValue('tab') : SQ_Classes_Helpers_Tools::arrayKeyFirst($tabs));

        if(isset($tabs[$current]['tabs']) && !empty($tabs[$current]['tabs'])){
            foreach ($tabs[$current]['tabs'] as $index => $tab) {
                if(isset($tab['show']) && !$tab['show']) continue;

                $pattern = str_replace('sq_','',$tab['tab']);
                ?>
                <div class="position-relative">
                    <a href="#<?php echo esc_attr($tab['tab']) ?>" class="m-0 p-3 pr-4 font-dark sq_sub_nav_item <?php echo esc_attr($tab['tab'])?> <?php echo ($index == 0 ? 'active' : '') ?>" data-tab="<?php echo esc_attr($tab['tab'])?>"><?php echo wp_kses_post($tab['title']) ?></a>
                    <div style="display: inline-block;position: absolute;right: 5px;top:15px;z-index: 1;">
                        <div class="sq-switch sq-switch-xxs sq-switch-gray">
                            <input type="hidden" name="patterns[<?php echo esc_attr($pattern) ?>][doseo]" value="0"/>
                            <input type="checkbox" id="sq_patterns_<?php echo esc_attr($pattern) ?>_doseo"  name="patterns[<?php echo esc_attr($pattern) ?>][doseo]" onclick="if(jQuery(this).is(':checked')) { jQuery('.sq_doseo_<?php echo esc_attr($pattern) ?>').hide(); }else{ jQuery('.sq_doseo_<?php echo esc_attr($pattern) ?>').show(); } jQuery('form').submit()" class="sq-switch" <?php echo((!isset($patterns[$pattern]['doseo']) || $patterns[$pattern]['doseo'] == 1) ? 'checked="checked"' : '') ?> value="1"/>
                            <label for="sq_patterns_<?php echo esc_attr($pattern) ?>_doseo"></label>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>

</div>
<?php }else{?>
    <div class="sq_sub_nav d-flex flex-column bd-highlight m-0 p-0 border-right">
        <?php
        $page = SQ_Classes_Helpers_Tools::getValue('page');
        $tabs = SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getTabs($page);

        if (!empty($tabs)) {
            $current = (SQ_Classes_Helpers_Tools::getValue('tab') ? $page.'/'.SQ_Classes_Helpers_Tools::getValue('tab') : SQ_Classes_Helpers_Tools::arrayKeyFirst($tabs));

            if(isset($tabs[$current]['tabs']) && !empty($tabs[$current]['tabs'])){
                foreach ($tabs[$current]['tabs'] as $index => $tab) {
                    if(isset($tab['show']) && !$tab['show']) continue;
                    ?>
                    <a href="#<?php echo esc_attr($tab['tab']) ?>" class="m-0 pl-3 pr-1 py-3 font-dark sq_sub_nav_item <?php echo esc_attr($tab['tab'])?> <?php echo ($index == 0 ? 'active' : '') ?>" data-tab="<?php echo esc_attr($tab['tab'])?>"><?php echo wp_kses_post($tab['title']) ?></a>
                    <?php
                }
            }
        }
        ?>

    </div>
<?php } ?>