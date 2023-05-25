<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php
//Check if the patterns are loaded for this post
$loadpatterns = true;
if (!SQ_Classes_Helpers_Tools::getOption('sq_auto_pattern') || !$view->post->sq->do_pattern) {
	$loadpatterns = false;
}

$socials = json_decode(wp_json_encode(SQ_Classes_Helpers_Tools::getOption('socials')));
$codes = json_decode(wp_json_encode(SQ_Classes_Helpers_Tools::getOption('codes')));

?>
<div class="sq-card sq-border-0">
	<?php if (!$view->post->sq->do_twc) { ?>
		<div class="sq-row sq-mx-0 sq-px-0">
			<div class="sq-text-center sq-col-12 sq-my-5 sq-mx-0 sq-px-0 sq-text-danger"><?php echo sprintf(esc_html__("Post Type (%s) was excluded from %s Squirrly > Automation %s. Squirrly SEO will not load for this post type on the frontend.", 'squirrly-seo'), '<strong>' . esc_attr($view->post->post_type) . '</strong>', '<a href="' . SQ_Classes_Helpers_Tools::getAdminUrl('sq_automation', 'automation') . '#tab=nav-' . esc_attr($view->post->post_type) . '" target="_blank"><strong>', '</strong></a>') ?></div>
		</div>
	<?php } else { ?>
		<div class="sq-card-body sq_tab_twitter sq_tabcontent <?php echo ($view->post->sq_adm->doseo == 0) ? 'sq-d-none' : ''; ?>">
			<?php if (!SQ_Classes_Helpers_Tools::getOption('sq_auto_twitter')) { ?>
				<div class="sq_deactivated_label sq-col-12 sq-row sq-m-0 sq-p-2 sq-pr-3 sq_save_ajax">
					<div class="sq-col-12 sq-p-0 sq-text-center sq-small">
						<input type="hidden" id="activate_sq_auto_twitter" value="1"/>
						<button type="button" class="sq-btn sq-btn-link sq-text-danger sq-btn-lg" data-input="activate_sq_auto_twitter" data-action="sq_ajax_seosettings_save" data-name="sq_auto_twitter"><?php echo esc_html__("Activate Twitter Card", 'squirrly-seo'); ?></button>
					</div>
				</div>
			<?php } ?>
			<div class="<?php echo((!SQ_Classes_Helpers_Tools::getOption('sq_auto_twitter')) ? 'sq_deactivated' : ''); ?>">

				<div class="sq_tab_preview">
					<div class="sq-row sq-mx-0 sq-px-0">
						<div class="sq-col-sm sq-text-right sq-mb-2 sq-pb-2">
							<div class="sq-refresh"></div>
							<input type="button" class="sq_snippet_btn_refresh sq-btn sq-btn-sm sq-btn-link sq-px-3 sq-rounded-0 sq-font-weight-bold" value="<?php echo esc_html__("Refresh", 'squirrly-seo') ?>"/>
							<input type="button" class="sq_snippet_btn_edit sq-btn sq-btn-sm sq-btn-primary sq-px-5 sq-mx-5 sq-rounded-0" value="<?php echo esc_html__("Edit Twitter Card", 'squirrly-seo') ?>"/>
						</div>
					</div>
					<div class="sq-row sq-mx-0 sq-px-0">
						<div class="sq-col-12 sq-m-0 sq-p-0">
							<div class="sq_message"><?php echo esc_html__("How this page appears on Twitter", 'squirrly-seo') ?>:</div>

						</div>
						<?php
						if ($view->post->sq->tw_media <> '') {
							try {
								if (defined('WP_CONTENT_DIR') && $imagepath = str_replace(content_url(), WP_CONTENT_DIR, $view->post->sq->tw_media)) {

									if (file_exists($imagepath)) {
										list($width, $height) = @getimagesize($imagepath);

										if ((int)$width > 0 && (int)$width < 500) { ?>
											<div class="sq-col-12">
												<div class="sq-alert sq-alert-danger"><?php echo esc_html__("The image size must be at least 500 pixels wide", 'squirrly-seo') ?></div>
											</div>
											<?php
										}
									}
								}

							} catch (Exception $e) {
							}
						}
						?>

					</div>
					<?php if ($view->post->post_title <> esc_html__("Auto Draft") && $view->post->post_title <> esc_html__("AUTO-DRAFT")) { ?>
						<div class="sq_snippet_preview sq-mb-2 sq-p-0 sq-mx-auto sq-border">
							<ul class="sq-p-3 sq-m-0" style="min-height: 125px;">
								<?php if ($view->post->sq->tw_media <> '') { ?>
									<li class="sq_snippet_image <?php echo((($view->post->sq_adm->tw_type == '' && $socials->twitter_card_type == 'summary') || $view->post->sq_adm->tw_type == 'summary') ? 'sq_snippet_smallimage' : '') ?>">
										<img src="<?php echo esc_attr($view->post->sq->tw_media) ?>">
									</li>
								<?php } elseif ($view->post->post_attachment <> '') { ?>
									<li class="sq_snippet_image sq_snippet_post_atachment <?php echo((($view->post->sq_adm->tw_type == '' && $socials->twitter_card_type == 'summary') || $view->post->sq_adm->tw_type == 'summary') ? 'sq_snippet_smallimage' : '') ?>">
										<img src="<?php echo esc_attr($view->post->post_attachment) ?>" title="<?php echo esc_html__("This is the Featured Image. You can change it if you edit the snippet and upload another image.", 'squirrly-seo') ?>">
									</li>
								<?php } ?>

								<li class="sq_snippet_title sq-text-primary sq-font-weight-bold"><?php echo($view->post->sq->tw_title <> '' ? esc_html($view->post->sq->tw_title) : SQ_Classes_Helpers_Sanitize::truncate(esc_html($view->post->sq->title), 10, (int)$view->post->sq->tw_title_maxlength)) ?></li>
								<li class="sq_snippet_description sq-text-black-50"><?php echo($view->post->sq->tw_description <> '' ? esc_html($view->post->sq->tw_description) : SQ_Classes_Helpers_Sanitize::truncate(esc_html($view->post->sq->description), 10, (int)$view->post->sq->tw_description_maxlength)) ?></li>
								<li class="sq_snippet_sitename sq-text-black-50"><?php echo parse_url(get_bloginfo('url'), PHP_URL_HOST) ?></li>
							</ul>
						</div>
					<?php } else { ?>
						<div class="sq_snippet_preview sq-mb-2 sq-p-0 sq-border">
							<div style="padding: 20px"><?php echo esc_html__("Please save the post first to be able to edit the Squirrly SEO Snippet", 'squirrly-seo') ?></div>
						</div>
					<?php } ?>
				</div>
				<div class="sq_tab_edit">
					<div class="sq-row sq-mx-0 sq-px-0">
						<div class="sq-col-sm sq-text-right sq-mb-2 sq-pb-2">
							<input type="button" class="sq_snippet_btn_cancel sq-btn sq-btn-sm sq-btn-link sq-rounded-0" value="<?php echo esc_html__("Cancel") ?>"/>
							<input type="button" class="sq_snippet_btn_save sq-btn sq-btn-sm sq-btn-primary sq-px-5 sq-mx-5 sq-rounded-0" value="<?php echo esc_html__("Save") ?>"/>
						</div>
					</div>

					<div class="sq-row sq-mx-0 sq-px-0">
						<div class="sq-col-12 sq-row sq-mx-0 sq-px-0 sq-my-1 sq-py-1">

							<div class="sq-col-12 sq-row sq-p-0 sq-m-0">
								<div class="sq-col-3 sq-p-0 sq-pr-3 sq-font-weight-bold">
									<?php echo esc_html__("Media Image", 'squirrly-seo'); ?>:
									<div class="sq-small sq-text-black-50 sq-my-3"></div>
								</div>
								<div class="sq-col-9 sq-p-0 sq-input-group sq-input-group-lg">
									<button class="sq_get_tw_media sq-btn sq-btn-primary sq-form-control sq-input-lg"><?php echo esc_html__("Upload", 'squirrly-seo') ?></button>
									<span><?php echo esc_html__("Image size must be at least 500 pixels wide", 'squirrly-seo') ?></span>
								</div>

							</div>

							<div class="sq-col-12 sq-row sq-p-0 sq-m-0">
								<input type="hidden" name="sq_tw_media" value="<?php echo esc_attr($view->post->sq_adm->tw_media) ?>"/>
								<div style="max-width: 470px;" class="sq-position-relative sq-offset-sm-3">
									<span class="sq_tw_image_close">x</span>
									<img class="sq_tw_media_preview" src=""/>
								</div>
							</div>
						</div>

						<div class="sq-col-12 sq-row sq-mx-0 sq-px-0 sq-my-1 sq-py-1">

							<div class="sq-col-12 sq-row sq-p-0 sq-m-0">
								<div class="sq-col-3 sq-p-0 sq-pr-3 sq-font-weight-bold">
									<?php echo esc_html__("Title", 'squirrly-seo'); ?>:
									<div class="sq-small sq-text-black-50 sq-my-3"><?php echo sprintf(esc_html__("Tips: Length %s-%s chars", 'squirrly-seo'), 10, $view->post->sq_adm->og_title_maxlength); ?></div>
								</div>
								<div class="sq-col-9 sq-p-0 sq-input-group sq-input-group-lg <?php echo(($loadpatterns === true) ? 'sq_pattern_field' : '') ?>" data-patternid="<?php echo esc_attr($view->post->hash) ?>">
									<textarea autocomplete="off" rows="1" name="sq_tw_title" class="sq-form-control sq-input-lg sq-toggle" placeholder="<?php echo($loadpatterns ? esc_html__("Pattern", 'squirrly-seo') . ': ' . esc_attr($view->post->sq_adm->patterns->title) : esc_attr($view->post->sq->tw_title)) ?>"><?php echo SQ_Classes_Helpers_Sanitize::clearTitle($view->post->sq_adm->tw_title) ?></textarea>
									<input type="hidden" id="sq_title_preview_<?php echo esc_attr($view->post->hash) ?>" name="sq_title_preview" value="<?php echo esc_attr($view->post->sq->tw_title) ?>">

									<div class="sq-col-12 sq-px-0">
										<div class="sq-text-right sq-small">
											<span class="sq_length" data-maxlength="<?php echo (int)$view->post->sq_adm->tw_title_maxlength ?>"><?php echo (isset($view->post->sq_adm->tw_title) ? strlen($view->post->sq_adm->tw_title) : 0) ?>/<?php echo (int)$view->post->sq_adm->tw_title_maxlength ?></span>
										</div>
									</div>

									<div class="sq-actions">
										<div class="sq-action">
											<span style="display: none" class="sq-value sq-title-value" data-value="<?php echo esc_attr($view->post->sq->tw_title) ?>"></span>
											<span class="sq-action-title" title="<?php echo esc_attr($view->post->sq->tw_title) ?>"><?php echo esc_html__("Current Title", 'squirrly-seo') ?>: <span class="sq-title-value"><?php echo esc_html($view->post->sq->tw_title) ?></span></span>
										</div>
										<?php if (isset($view->post->post_title) && $view->post->post_title <> '') { ?>
											<div class="sq-action">
												<span style="display: none" class="sq-value" data-value="<?php echo esc_attr($view->post->post_title) ?>"></span>
												<span class="sq-action-title" title="<?php echo esc_attr($view->post->post_title) ?>"><?php echo esc_html__("Default Title", 'squirrly-seo') ?>: <span><?php echo esc_html($view->post->post_title) ?></span></span>
											</div>
										<?php } ?>

										<?php if ($loadpatterns && $view->post->sq_adm->patterns->title <> '') { ?>
											<div class="sq-action">
												<span style="display: none" class="sq-value" data-value="<?php echo esc_attr($view->post->sq_adm->patterns->title) ?>"></span>
												<span class="sq-action-title" title="<?php echo esc_attr($view->post->sq_adm->patterns->title) ?>"><?php echo(($loadpatterns === true) ? esc_html__("Pattern", 'squirrly-seo') . ': ' . '<span>' . esc_html($view->post->sq_adm->patterns->title) . '</span>' : '') ?></span>
											</div>
										<?php } ?>

									</div>
								</div>

							</div>

						</div>

						<div class="sq-col-12 sq-row sq-mx-0 sq-px-0 sq-my-1 sq-py-1">

							<div class="sq-col-12 sq-row sq-p-0 sq-m-0">
								<div class="sq-col-3 sq-p-0 sq-pr-3 sq-font-weight-bold">
									<?php echo esc_html__("Description", 'squirrly-seo'); ?>:
									<div class="sq-small sq-text-black-50 sq-my-3"><?php echo sprintf(esc_html__("Tips: Length %s-%s chars", 'squirrly-seo'), 10, $view->post->sq_adm->og_description_maxlength); ?></div>
								</div>
								<div class="sq-col-9 sq-p-0 sq-input-group sq-input-group-lg <?php echo(($loadpatterns === true) ? 'sq_pattern_field' : '') ?>" data-patternid="<?php echo esc_attr($view->post->hash) ?>">
									<textarea autocomplete="off" rows="3" name="sq_tw_description" class="sq-form-control sq-input-lg sq-toggle" placeholder="<?php echo(($loadpatterns === true) ? esc_html__("Pattern", 'squirrly-seo') . ': ' . esc_attr($view->post->sq_adm->patterns->description) : esc_attr($view->post->sq->tw_description)) ?>"><?php echo SQ_Classes_Helpers_Sanitize::clearDescription($view->post->sq_adm->tw_description) ?></textarea>
									<input type="hidden" id="sq_description_preview_<?php echo esc_attr($view->post->hash) ?>" name="sq_description_preview" value="<?php echo esc_attr($view->post->sq->tw_description) ?>">

									<div class="sq-col-12 sq-px-0">
										<div class="sq-text-right sq-small">
											<span class="sq_length" data-maxlength="<?php echo (int)$view->post->sq_adm->tw_description_maxlength ?>"><?php echo (isset($view->post->sq_adm->tw_description) ? strlen($view->post->sq_adm->tw_description) : 0)?>/<?php echo (int)$view->post->sq_adm->tw_description_maxlength ?></span>
										</div>
									</div>

									<div class="sq-actions">
										<?php if (isset($view->post->sq->tw_description) && $view->post->sq->tw_description <> '') { ?>
											<div class="sq-action">
												<span style="display: none" class="sq-value sq-description-value" data-value="<?php echo esc_attr($view->post->sq->tw_description) ?>"></span>
												<span class="sq-action-title" title="<?php echo esc_attr($view->post->sq->tw_description) ?>"><?php echo esc_html__("Current Description", 'squirrly-seo') ?>: <span><?php echo esc_html($view->post->sq->tw_description) ?></span></span>
											</div>
										<?php } ?>

										<?php if (isset($view->post->post_excerpt) && $view->post->post_excerpt <> '') { ?>
											<div class="sq-action">
												<span style="display: none" class="sq-value" data-value="<?php echo esc_attr($view->post->post_excerpt) ?>"></span>
												<span class="sq-action-title" title="<?php echo esc_attr($view->post->post_excerpt) ?>"><?php echo esc_html__("Default Description", 'squirrly-seo') ?>: <span><?php echo esc_html($view->post->post_excerpt) ?></span></span>
											</div>
										<?php } ?>

										<?php if ($loadpatterns && $view->post->sq_adm->patterns->description <> '') { ?>
											<div class="sq-action">
												<span style="display: none" class="sq-value" data-value="<?php echo esc_attr($view->post->sq_adm->patterns->description) ?>"></span>
												<span class="sq-action-title" title="<?php echo esc_attr($view->post->sq_adm->patterns->description) ?>"><?php echo(($loadpatterns === true) ? esc_html__("Pattern", 'squirrly-seo') . ': ' . '<span>' . esc_html($view->post->sq_adm->patterns->description) . '</span>' : '') ?></span>
											</div>
										<?php } ?>

									</div>
								</div>

							</div>

						</div>


						<div class="sq-col-12 sq-row sq-mx-0 sq-px-0 sq-my-1 sq-py-1">

							<div class="sq-col-12 sq-row sq-p-0 sq-m-0">
								<div class="sq-col-3 sq-p-0 sq-pr-3 sq-font-weight-bold">
									<?php echo esc_html__("Card Type", 'squirrly-seo'); ?>:
									<div class="sq-small sq-text-black-50 sq-my-3"><?php echo sprintf(esc_html__("Every change needs %sTwitter Card Validator%s", 'squirrly-seo'), '<br /><a href="https://cards-dev.twitter.com/validator?url=' . esc_url($view->post->url) . '" target="_blank" ><strong>', '</strong></a>'); ?></div>
								</div>
								<div class="sq-col-6 sq-p-0 sq-input-group">
									<select name="sq_tw_type" class="sq-form-control sq-bg-input sq-mb-1">
										<option <?php echo(($view->post->sq_adm->tw_type == '') ? 'selected="selected"' : '') ?> value=""><?php echo esc_html__("(Auto)", 'squirrly-seo') ?></option>
										<option <?php echo(($view->post->sq_adm->tw_type == 'summary') ? 'selected="selected"' : '') ?> value="summary"><?php echo esc_html__("summary", 'squirrly-seo') ?></option>
										<option <?php echo(($view->post->sq_adm->tw_type == 'summary_large_image') ? 'selected="selected"' : '') ?> value="summary_large_image"><?php echo esc_html__("summary_large_image", 'squirrly-seo') ?></option>

									</select>
								</div>

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
	<?php } ?>
</div>
