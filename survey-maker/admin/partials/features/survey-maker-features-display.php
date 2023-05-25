<?php
/**
 * Created by PhpStorm.
 * User: biggie18
 * Date: 7/30/18
 * Time: 12:08 PM
 */
// $url = "https://ays-pro.com/wordpress/survey-maker";
// wp_redirect( $url );
// exit;
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo __(esc_html(get_admin_page_title()), "survey-maker"); ?>
    </h1>

    <div class="ays-survey-features-wrap">
        <div class="comparison">
            <table>
                <thead>
                    <tr>
                        <th class="tl tl2"></th>
                        <th class="product" style="background:#69C7F1; border-top-left-radius: 5px; border-left:0px;">
                            <span style="display: block"><?php echo __('Personal',"survey-maker")?></span>
                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) . '/images/avatars/personal_avatar.png'; ?>" alt="Free" title="Free" width="100"/>
                        </th>
                        <th class="product" style="background:#69C7F1;">
                            <span style="display: block"><?php echo  __('Business',"survey-maker")?></span>
                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) . '/images/avatars/business_avatar.png'; ?>" alt="Business" title="Business" width="100"/>
                        </th>
                        <th class="product" style="border-top-right-radius: 5px; background:#69C7F1;">
                            <span style="display: block"><?php echo __('Developer',"survey-maker")?></span>
                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) . '/images/avatars/pro_avatar.png'; ?>" alt="Developer" title="Developer" width="100"/>
                        </th>
                        <th class="product" style="border-top-right-radius: 5px; border-right:0px; background:#69C7F1;">
                            <span style="display: block"><?php echo __('Agency', "survey-maker")?></span>
                            <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) . '/images/avatars/agency_avatar.png'; ?>" alt="Agency" title="Agency" width="100"/>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="price-info">
                            <div class="price-now">
                                <span><?php echo __('Free',"survey-maker")?></span>
                            </div>
                        </th>
                        <th class="price-info">
                            <div class="price-now"><span>$49</span></div>
                            <!-- <div class="price-now"><span style="text-decoration: line-through; color: red;">$49</span></div> -->
                            <!-- <div class="price-now"><span>$39</span></div>  -->
                            <!-- <div class="price-now"><span style="color: red; font-size: 12px;">Until December 31</span></div> -->
                        </th>
                        <th class="price-info">
                            <div class="price-now"><span>$129</span></div>
                            <!-- <div class="price-now"><span span style="text-decoration: line-through; color: red;">$129</span></div> -->
                            <!-- <div class="price-now"><span>$103</span></div>  -->
                            <!-- <div class="price-now"><span style="color: red; font-size: 12px;">Until December 31</span></div>  -->
                        </th>
                        <th class="price-info">
                            <!-- <div class="price-now"><span span style="text-decoration: line-through; color: red;">$129</span>
                            </div> -->
                            <div class="price-now"><span>$249</span>
                            </div>
                            <!-- <div class="price-now"><span style="color: red; font-size: 12px;">Until November 27</span>
                            </div> -->
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo __('Support for',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Support for',"survey-maker")?></td>
                        <td><?php echo __('1 site',"survey-maker")?></td>
                        <td><?php echo __('5 site',"survey-maker")?></td>
                        <td><?php echo __('Unlimited sites',"survey-maker")?></td>
                        <td><?php echo __('Unlimited sites',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="3"><?php echo __('Upgrade for',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Upgrade for',"survey-maker")?></td>
                        <td><?php echo __('1 months',"survey-maker")?></td>
                        <td><?php echo __('12 months',"survey-maker")?></td>
                        <td><?php echo __('Lifetime',"survey-maker")?></td>
                        <td><?php echo __('Lifetime',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="4"><?php echo __('Support for',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Support for',"survey-maker")?></td>
                        <td><?php echo __('1 months',"survey-maker")?></td>
                        <td><?php echo __('12 months',"survey-maker")?></td>
                        <td><?php echo __('Lifetime',"survey-maker")?></td>
                        <td><?php echo __('Lifetime',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Reports in dashboard',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Reports in dashboard',"survey-maker")?></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Unlimited Surveys',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Unlimited Surveys',"survey-maker")?></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Unlimited Questions',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Unlimited Questions',"survey-maker")?></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Responsive design',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Responsive design',"survey-maker")?></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Extra question types',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Extra question types',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Send mail to user',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Send mail to user',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Send mail to admin',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Send mail to admin',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Email configuration',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Email configuration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Schedule survey',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Schedule survey',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Mailchimp integration',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Mailchimp integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Campaign Monitor integration',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Campaign Monitor integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Slack integration',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Slack integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('ActiveCampaign integration',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('ActiveCampaign integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('User history shortcode',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('User history shortcode',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Make questions required',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Make questions required',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>                    
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Permissions by user role',"survey-maker")?></td>
                    </tr>
                    <tr >
                        <td><?php echo __('Permissions by user role',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Limit attemps count',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Limit attemps count',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Results with charts',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Results with charts',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Export and import surveys',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Export and import surveys',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Submissions summary export',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Submissions summary export',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Individual submission export',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Individual submission export',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('PDF export',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('PDF export',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Password protected survey',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Password protected survey',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Google sheet integration',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Google sheet integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Zapier integration',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Zapier integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('SendGrid integration',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('SendGrid integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('GamiPress integration',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('GamiPress integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('MadMimi integration',"survey-maker")?></td>
                    </tr>
                    <tr >
                        <td><?php echo __('MadMimi integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('GetResponse integration',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('GetResponse integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('ConvertKit integration',"survey-maker")?></td>
                    </tr>
                    <tr >
                        <td><?php echo __('ConvertKit integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Access by user role',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Access by user role',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Popup Survey',"survey-maker")?></td>
                    </tr>
                    <tr >
                        <td><?php echo __('Popup Survey',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Conversational surveys',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Conversational surveys',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Logic jump',"survey-maker")?></td>
                    </tr>
                    <tr >
                        <td><?php echo __('Logic jump',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Conditional Results',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('Conditional Results',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Summary emails',"survey-maker")?></td>
                    </tr>
                    <tr>
                        <td><?php echo __('Summary emails',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('PayPal integration',"survey-maker")?></td>
                    </tr>
                    <tr class="compare-row">
                        <td><?php echo __('PayPal integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Stripe integration',"survey-maker")?></td>
                    </tr>
                    <tr class="">
                        <td><?php echo __('Stripe integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Klaviyo Integration',"survey-maker")?></td>
                    </tr>
                    <tr class="">
                        <td><?php echo __('Klaviyo Integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('MyCred Integration',"survey-maker")?></td>
                    </tr>
                    <tr class="">
                        <td><?php echo __('MyCred Integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Aweber Integration',"survey-maker")?></td>
                    </tr>
                    <tr class="">
                        <td><?php echo __('Aweber Integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Mailpoet Integration',"survey-maker")?></td>
                    </tr>
                    <tr class="">
                        <td><?php echo __('Mailpoet Integration',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Multilingual Surveys',"survey-maker")?></td>
                    </tr>
                    <tr class="">
                        <td><?php echo __('Multilingual Surveys',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td colspan="4"><?php echo __('Text To Speech',"survey-maker")?></td>
                    </tr>
                    <tr class="">
                        <td><?php echo __('Text To Speech',"survey-maker")?></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><span>-</span></td>
                        <td><i class="ays_fa ays_fa_check"></i></td>
                    </tr>
                    <tr>
                        <td> </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><a href="https://wordpress.org/plugins/survey-maker/" target="_blank" class="price-buy"><?php echo __('Download',"survey-maker")?><span class="hide-mobile"></span></a></td>
                        <td><a href="https://ays-pro.com/wordpress/survey-maker?utm_source=wordpress&utm_medium=ays-plugins&utm_campaign=survey" target="_blank" class="price-buy"><?php echo __('Buy now',"survey-maker")?><span class="hide-mobile"></span></a></td>
                        <td><a href="https://ays-pro.com/wordpress/survey-maker?utm_source=wordpress&utm_medium=ays-plugins&utm_campaign=survey" target="_blank" class="price-buy"><?php echo __('Buy now',"survey-maker")?><span class="hide-mobile"></span></a></td>
                        <td><a href="https://ays-pro.com/wordpress/survey-maker?utm_source=wordpress&utm_medium=ays-plugins&utm_campaign=survey" target="_blank" class="price-buy"><?php echo __('Buy now',"survey-maker")?><span class="hide-mobile"></span></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

