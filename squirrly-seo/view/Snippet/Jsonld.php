<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php
$patterns = SQ_Classes_Helpers_Tools::getOption('patterns');

?>
<div class="sq-card sq-border-0">
	<div class="sq-card-body sq_tab_jsonld sq_tabcontent <?php echo ($view->post->sq_adm->doseo == 0) ? 'sq-d-none' : ''; ?>">
		<div class="sq-row sq-mx-0 sq-px-0">
			<div class="sq-col-sm sq-text-right sq-mb-2 sq-pb-2">
				<input type="button" class="sq_snippet_btn_refresh sq-btn sq-btn-sm sq-btn-link sq-px-3 sq-rounded-0 sq-font-weight-bold" value="<?php echo esc_html__("Refresh", 'squirrly-seo') ?>"/>
				<input type="button" class="sq_snippet_btn_save sq-btn sq-btn-sm sq-btn-primary sq-px-5 sq-mx-5 sq-rounded-0" value="<?php echo esc_html__("Save") ?>"/>
			</div>
		</div>

		<div class="sq-row sq-mx-0 sq-px-0">

			<div class="sq-col-12 sq-row sq-mx-0 sq-px-0 sq-my-1 sq-py-1">
				<?php if (!SQ_Classes_Helpers_Tools::getOption('sq_auto_jsonld')) { ?>
					<div class="sq_deactivated_label sq-col-12 sq-row sq-m-0 sq-p-2 sq-pr-3 sq_save_ajax">
						<div class="sq-col-12 sq-p-0 sq-text-center sq-small">
							<input type="hidden" id="activate_sq_auto_jsonld" value="1"/>
							<button type="button" class="sq-btn sq-btn-link sq-text-danger sq-btn-lg" data-input="activate_sq_auto_jsonld" data-action="sq_ajax_seosettings_save" data-name="sq_auto_jsonld"><?php echo esc_html__("Activate JSON-LD", 'squirrly-seo'); ?></button>
						</div>
					</div>
				<?php } elseif (!$view->post->sq->do_jsonld) { ?>
					<div class="sq_deactivated_label sq-col-12 sq-row sq-m-0 sq-p-2 sq-pr-3 sq_save_ajax">
						<div class="sq-col-12 sq-p-0 sq-text-center sq-small">
							<?php echo sprintf(esc_html__("JSON-LD is disable for this Post Type (%s). See %s Squirrly > Automation > Configuration %s.", 'squirrly-seo'), esc_attr($view->post->post_type), '<a href="' . SQ_Classes_Helpers_Tools::getAdminUrl('sq_automation', 'automation') . '#tab=nav-' . esc_attr($view->post->post_type) . '" target="_blank"><strong>', '</strong></a>') ?>
						</div>
					</div>
				<?php } ?>

				<?php
				SQ_Classes_ObjController::getClass('SQ_Models_Services_JsonLD');
				//Hook the json_ld
				$jsoncode = apply_filters('sq_json_ld', false);
				if ($jsoncode) {
					$jsoncode = str_replace(array("\n", "\r"), "", $jsoncode);
					$jsoncode = SQ_Classes_Helpers_Sanitize::normalizeChars($jsoncode);
					$jsoncode = wp_json_encode($jsoncode);
				}

				//normalize the chars for form submit
				if ($jsonld_data = (($view->post->sq_adm->jsonld <> '') ? $view->post->sq_adm->jsonld : $jsoncode)) {
					$jsonld_data = wp_unslash($jsonld_data);
					$jsonld_data = trim($jsonld_data, '""');
					$jsonld_data = strip_tags($jsonld_data);
				} else {
					$jsonld_data = '';
				}
				?>

				<div class="sq-col-12 sq-row sq-py-0 sq-m-0 sq-px-0  <?php echo((!SQ_Classes_Helpers_Tools::getOption('sq_auto_jsonld') || !$view->post->sq->do_jsonld) ? 'sq_deactivated' : ''); ?>">
					<div class="sq-col-12 sq-row sq-my-0 sq-mx-0 sq-px-0">

						<div class="sq-col-12 sq-row sq-my-2 sq-px-0 sq-mx-0 sq-py-1 sq-px-2">
							<div class="sq-col-4 sq-p-0 sq-pr-3 sq-font-weight-bold">
								<?php echo esc_html__("JSON-LD Schema Types", 'squirrly-seo'); ?>:<a href="https://howto12.squirrly.co/kb/bulk-seo/#bulk_seo_snippet_jsonld" target="_blank"><i class="fa-solid fa-question-circle sq-m-0 sq-px-1 sq-d-inline"></i></a>
								<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo esc_html__("JSON-LD will load the Schema for the selected types.", 'squirrly-seo'); ?></div>
								<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo sprintf(esc_html__("Setup JSON-LD for this Post Type by using %s SEO Automation %s", 'squirrly-seo'), '<a href="' . SQ_Classes_Helpers_Tools::getAdminUrl('sq_automation', 'automation') . '">', '</a>'); ?></div>
							</div>
							<?php
							$jsonld_types = json_decode(SQ_ALL_JSONLD_TYPES, true);
							$jsonld_types = apply_filters('sq_jsonld_types', $jsonld_types, $view->post->post_type);

							$sq_jsonld_types = array();
							$patterns = SQ_Classes_Helpers_Tools::getOption('patterns');
							if (isset($patterns[$view->post->post_type]['jsonld_types'])) {
								$sq_jsonld_types = $patterns[$view->post->post_type]['jsonld_types'];
								foreach ($sq_jsonld_types as &$jsonld_type) {
									$jsonld_type = ucwords($jsonld_type);
								}
							}else{
								$patterns[$view->post->post_type] = $patterns['custom'];
							}

							$view->post->sq_adm->jsonld_types = array_filter((array)$view->post->sq_adm->jsonld_types);

							?>
							<div class="sq-col-8 sq-p-0 sq-input-group">
								<select multiple name="sq_jsonld_types[]" class="sq_jsonld_types sq-form-control sq-bg-input sq-mb-1" style="min-height: <?php echo ((count($jsonld_types) + 2) * 20) . 'px !important;' ?>">
									<option <?php echo(empty((array)$view->post->sq_adm->jsonld_types) ? 'selected="selected"' : '') ?> value=""><?php echo esc_html__("SEO Automation", 'squirrly-seo') . ' (' . esc_html(join(', ', $sq_jsonld_types)) ?>)</option>
									<?php foreach ($jsonld_types as $post_type => $jsonld_type) { ?>
										<option <?php echo(in_array($jsonld_type, (array)$view->post->sq_adm->jsonld_types) ? 'selected="selected"' : '') ?> value="<?php echo esc_attr($jsonld_type) ?>">
											<?php echo esc_attr(ucwords($jsonld_type)) ?>
										</option>
									<?php } ?>
								</select>
								<div class="sq-small sq-text-primary sq-my-1 sq-pr-4"><?php echo esc_html__("Hold Control key (or Command on Mac) to select multiple types.", 'squirrly-seo'); ?></div>

							</div>

						</div>

						<div class="sq-col-12 sq-row sq-my-2 sq-px-0 sq-mx-0 sq-py-1 sq-px-2">

							<div class="sq-col-4 sq-p-0 sq-pr-3 sq-font-weight-bold">
								<?php echo esc_html__("JSON-LD Breadcrumbs Schema", 'squirrly-seo'); ?>
								<a href="https://howto12.squirrly.co/kb/json-ld-structured-data/#breadcrumbs_schema" target="_blank"><i class="fa-solid fa-question-circle sq-m-0 sq-px-1 sq-d-inline"></i></a>
								<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo sprintf(esc_html__("Manage BreadcrumbsList Schema from %s JSON-LD Settings %s.", 'squirrly-seo'), '<a href="' . SQ_Classes_Helpers_Tools::getAdminUrl('sq_seosettings', 'jsonld') . '">', '</a>'); ?></div>
							</div>

							<div class="sq-col-8 sq-p-0 sq-input-group">
								<?php if (SQ_Classes_Helpers_Tools::getOption('sq_jsonld_breadcrumbs')) { ?>
									<div class="sq-text-success sq-font-weight-bold"><?php echo esc_html__("Active", 'squirrly-seo'); ?></div>
								<?php } else { ?>
									<div class="sq-text-danger sq-font-weight-bold"><?php echo esc_html__("Not Active", 'squirrly-seo'); ?></div>
								<?php } ?>
							</div>

						</div>

						<?php if (SQ_Classes_Helpers_Tools::getOption('sq_jsonld_breadcrumbs') && $view->post->ID) {
							$allcategories = SQ_Classes_ObjController::getClass('SQ_Models_Domain_Categories')->getAllCategories($view->post->ID);
							if (!empty($allcategories) && count($allcategories) > 1) {
								?>
								<div class="sq-col-12 sq-row sq-my-2 sq-px-0 sq-mx-0 sq-py-1 sq-px-2">

									<div class="sq-col-4 sq-p-0 sq-pr-3 sq-font-weight-bold">
										<?php echo esc_html__("Primary Category", 'squirrly-seo'); ?>
										<a href="https://howto12.squirrly.co/kb/json-ld-structured-data/#breadcrumbs_schema" target="_blank"><i class="fa-solid fa-question-circle sq-m-0 sq-px-1 sq-d-inline"></i></a>
										<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo esc_html__("Set the Primary Category for Breadcrumbs.", 'squirrly-seo'); ?></div>
									</div>

									<div class="sq-col-8 sq-p-0 sq-input-group">
										<select name="sq_primary_category" class="sq_primary_category sq-form-control sq-bg-input sq-mb-1">
											<?php

											foreach ($allcategories as $id => $category) { ?>
												<option <?php echo(($id == $view->post->sq_adm->primary_category) ? 'selected="selected"' : '') ?> value="<?php echo (int)$id ?>">
													<?php echo esc_html(ucwords($category)) ?>
												</option>
											<?php }
											?>
										</select>
									</div>

								</div>
							<?php }
						} ?>

						<?php if ($view->post->post_type == 'product') { ?>
							<div class="sq-col-12 sq-row sq-my-2 sq-px-0 sq-mx-0 sq-py-1 sq-px-2">

								<div class="sq-col-4 sq-p-0 sq-pr-3 sq-font-weight-bold">
									<?php echo esc_html__("Woocommerce Product Support", 'squirrly-seo'); ?>
									<a href="https://howto12.squirrly.co/kb/json-ld-structured-data/#woocommerce" target="_blank"><i class="fa-solid fa-question-circle sq-m-0 sq-px-1 sq-d-inline"></i></a>
									<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo sprintf(esc_html__("Manage Woocommerce Support from %s JSON-LD Settings %s.", 'squirrly-seo'), '<a href="' . SQ_Classes_Helpers_Tools::getAdminUrl('sq_seosettings', 'jsonld') . '">', '</a>'); ?></div>
								</div>

								<div class="sq-col-8 sq-p-0 sq-input-group">
									<?php if (SQ_Classes_Helpers_Tools::getOption('sq_jsonld_product_defaults')) { ?>
										<div class="sq-text-success sq-font-weight-bold"><?php echo esc_html__("Active", 'squirrly-seo'); ?></div>
									<?php } else { ?>
										<div class="sq-text-danger sq-font-weight-bold"><?php echo esc_html__("Not Active", 'squirrly-seo'); ?></div>
									<?php } ?>
								</div>

							</div>
						<?php } ?>

						<?php if (SQ_Classes_Helpers_Tools::getOption('sq_seoexpert')) { ?>
							<div class="sq-col-12 sq-row sq-my-2 sq-px-0 sq-mx-0 sq-py-1 sq-px-2 ">

								<div class="sq-col-4 sq-p-0 sq-pr-3 sq-font-weight-bold">
									<?php echo esc_html__("Remove other JSON-LD Schema", 'squirrly-seo'); ?>
									<a href="https://howto12.squirrly.co/kb/json-ld-structured-data/#remove_duplicates" target="_blank"><i class="fa-solid fa-question-circle sq-m-0 sq-px-1 sq-d-inline"></i></a>
									<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo sprintf(esc_html__("Manage Duplicate Schema remover from %s JSON-LD Settings %s.", 'squirrly-seo'), '<a href="' . SQ_Classes_Helpers_Tools::getAdminUrl('sq_seosettings', 'jsonld') . '">', '</a>'); ?></div>
								</div>

								<div class="sq-col-8 sq-p-0 sq-input-group">
									<?php if (SQ_Classes_Helpers_Tools::getOption('sq_jsonld_clearcode')) { ?>
										<div class="sq-text-primary sq-font-weight-bold"><?php echo esc_html__("Active", 'squirrly-seo'); ?></div>
									<?php } else { ?>
										<div class="sq-text-primary sq-font-weight-bold"><?php echo esc_html__("Not Active", 'squirrly-seo'); ?></div>
									<?php } ?>
								</div>

							</div>

							<div class="sq-col-12 sq-row sq-my-2 sq-px-0 sq-mx-0 sq-py-1 sq-px-2">

								<div class="sq-col-12 sq-row sq-p-0 sq-m-0">
									<div class="sq-col-4 sq-p-0 sq-pr-3 sq-font-weight-bold">
										<?php echo esc_html__("JSON-LD Code", 'squirrly-seo'); ?>:<a href="https://howto12.squirrly.co/kb/bulk-seo/#jsonld_custom_code" target="_blank"><i class="fa-solid fa-question-circle sq-m-0 sq-px-1 sq-d-inline"></i></a>
										<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo esc_html__("Let Squirrly load the JSON-LD Schema for the selected types.", 'squirrly-seo'); ?></div>
									</div>
									<div class="sq-col-8 sq-p-0 sq-input-group">
										<select class="sq_jsonld_code_type sq-form-control sq-bg-input sq-mb-1" name="sq_jsonld_code_type">
											<option <?php echo(($view->post->sq_adm->jsonld == '') ? 'selected="selected"' : '') ?> value="auto"><?php echo esc_html__("(Auto)", 'squirrly-seo') ?></option>
											<option <?php echo(($view->post->sq_adm->jsonld <> '') ? 'selected="selected"' : '') ?> value="custom"><?php echo esc_html__("Custom Code", 'squirrly-seo') ?></option>
										</select>
										<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo sprintf(esc_html__("Use Advanced Custom Fields (ACF) plugin to add custom JSON-LD. %s Learn More %s", 'squirrly-seo'), '<a href="https://howto12.squirrly.co/kb/json-ld-structured-data/#ACF" class="sq-m-0 sq-p-0" target="_blank" style="font-weight: bold !important; font-size: 12px !important;">', '</a>'); ?></div>
									</div>

								</div>

							</div>

							<div class="sq_jsonld_custom_code sq-col-12 sq-row sq-my-2 sq-mx-0 sq-py-1 sq-px-2" <?php echo(($view->post->sq_adm->jsonld == '') ? 'style="display: none;"' : '') ?>>
								<div class="sq-col-4 sq-p-0 sq-pr-3 sq-font-weight-bold">
									<?php echo esc_html__("Custom JSON-LD Code", 'squirrly-seo'); ?>:
									<div class="sq-small sq-text-black-50 sq-my-3 sq-pr-4"><?php echo sprintf(esc_html__("Add JSON-LD code from %sSchema Generator Online%s.", 'squirrly-seo'), '<a href="https://technicalseo.com/seo-tools/schema-markup-generator/" class="sq-m-0 sq-p-0" target="_blank" style="font-weight: bold !important; font-size: 12px !important;">', '</a>'); ?></div>
								</div>
								<div class="sq-col-8 sq-p-0 sq-m-0">
									<textarea class="sq-form-control sq-m-0" name="sq_jsonld" rows="5" style="font-size: 12px !important;"><?php echo esc_textarea($jsonld_data) ?></textarea>
								</div>
							</div>

						<?php } ?>


						<div class="sq-col-12 sq-p-0 sq-py-1 sq-my-3 sq-small">
							<form method="post" target="_blank" action="https://search.google.com/test/rich-results">
								<button type="submit" class="sq-btn sq-btn-secondary sq-btn-block">
									<i class="fa-brands fa-google"></i> <?php echo esc_html__("Validate JSON-LD", 'squirrly-seo') ?>
								</button>
								<textarea name="code_snippet" style="display: none"><?php echo esc_textarea($jsonld_data); ?></textarea>
							</form>
						</div>

					</div>

				</div>
			</div>
		</div>

	</div>

	<div class="sq-card-footer sq-border-0 sq-py-0 sq-my-0 <?php echo ($view->post->sq_adm->doseo == 0) ? 'sq-mt-5' : ''; ?>">
		<div class="sq-row sq-mx-0 sq-px-0">
			<div class="sq-text-center sq-col-12 sq-my-4 sq-mx-0 sq-px-0 sq-text-danger" style="font-size: 18px; <?php echo ($view->post->sq_adm->doseo == 1) ? 'display: none' : ''; ?>">
				<?php echo esc_html__("To edit the snippet, you have to activate Squirrly SEO for this page first", 'squirrly-seo') ?>
			</div>
		</div>

	</div>
</div>
