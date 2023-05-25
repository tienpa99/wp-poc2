<li class="customize-control <?php echo esc_attr( 'happyforms-' . $control['type'] . '-control' ); ?>" data-target="<?php echo esc_attr( $field['target'] ); ?>" id="customize-control-<?php echo $control['field']; ?>">
    <label class="customize-control-title" for="<?php echo $control['field']; ?>"><?php echo $control['label']; ?></label>
    <div class="customize-control-content" data-pointer-target>
        <input type="text" name="<?php echo $control['field']; ?>" id="<?php echo $control['field']; ?>" data-attribute="<?php echo $control['field']; ?>" value="<%- <?php echo $control['field']; ?> %>">
    </div>
</li>
