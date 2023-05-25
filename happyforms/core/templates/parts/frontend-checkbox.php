<div class="<?php happyforms_the_part_class( $part, $form ); ?>" id="<?php happyforms_the_part_id( $part, $form ); ?>-part" <?php happyforms_the_part_data_attributes( $part, $form ); ?>>
	<div class="happyforms-part-wrap">
		<?php happyforms_the_part_label( $part, $form ); ?>

		<?php happyforms_print_part_description( $part ); ?>

		<?php
		$checkmark_content = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path fill="currentColor" d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>';
		?>

		<div class="happyforms-part__el">
			<?php do_action( 'happyforms_part_input_before', $part, $form ); ?>

			<?php
			$options = happyforms_get_part_options( $part['options'], $part, $form );
			$value = happyforms_get_part_value( $part, $form );

			foreach( $options as $o => $option ) : ?>
				<?php
				$option_classes = 'happyforms-part__option happyforms-part-option';

				if ( happyforms_is_truthy( $option['is_heading'] ) ) {
					$option_classes .= ' option-heading';
				?>
			<div class="<?php echo $option_classes; ?>" id="<?php echo esc_attr( $option['id'] ); ?>">
				<label class="heading-label"><?php echo esc_attr( $option['label'] ); ?></label>
			</div>
				<?php
					continue;
				}

				$checked = in_array( $o, $value ) ? 'checked="checked"' : '';

				if ( '' != $option['limit_submissions_amount'] && 0 == $option['submissions_left'] ) {
					$option_classes .= ' disabled-option';
					$checked = '';
				}
				?>
			<div class="<?php echo $option_classes; ?>" id="<?php echo esc_attr( $option['id'] ); ?>">
				<label class="option-label">
					<input type="checkbox" class="happyforms-visuallyhidden happyforms-checkbox" name="<?php happyforms_the_part_name( $part, $form ); ?>[]" value="<?php echo $o; ?>" data-serialize <?php echo $checked; ?> <?php happyforms_the_part_attributes( $part, $form ); ?> <?php echo ( empty( $option['submissions_left'] ) && '' != $option['limit_submissions_amount'] ) ? 'disabled' : '' ?> >
					<span class="checkmark"><?php echo $checkmark_content; ?></span>
					<span class="label-wrap">
						<span class="label"><?php echo esc_attr( $option['label'] ); ?></span><?php echo $option['submissions_left_label']; ?>
					</span>
				</label>
				<?php if ( ! empty(  $option['description'] ) ) : ?>
				<div class="happyforms-part-option__description"><?php echo esc_attr( $option['description'] ); ?></div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>

			<?php do_action( 'happyforms_part_input_after', $part, $form ); ?>

			<?php happyforms_part_error_message( happyforms_get_part_name( $part, $form ) ); ?>
		</div>
	</div>
</div>
