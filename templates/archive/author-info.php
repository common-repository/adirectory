<?php

/**
 * The template for displaying listing slider in the archive-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/archive/author-info.php
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */


if (!defined('ABSPATH')) {
   exit; // Exit if accessed directly
}
$author_id = get_query_var('author');
if (empty($author_id)) {
   return '';
}
$Helper = AD()->Helper;
$review_ratings = $Helper->get_author_ratings($author_id);
$review_count = $Helper->get_author_review_count($author_id);
$review_text = (1 < (int) $review_count) ?  esc_html__('Reviews', 'adirectory') : esc_html__('Review', 'adirectory');
?>
<section class="qsd-auther-profile-grid">
   <div class="adqs-admin-container">
      <div class="row">
         <div class="qsd-auther-profile-main">
            <div class="qsd-auther-profile-main-df">
               <div class="qsd-auther-profile-main-item">
                  <div class="qsd-auther-main-profile-item">
                     <div class="qsd-auther-main-profile-thumb">
                        <?php echo get_avatar($author_id, 162); ?>

                     </div>
                     <?php do_action('adqs_after_author', $author_id); ?>
                  </div>
                  <div class="qsd-auther-main-profile-txt-item">
                     <h2 class="qsd-profile-titel"><?php the_author(); ?></h2>

                     <ul class="qsd-profile-reviews-item">
                        <?php if (!empty($review_count) && !empty($review_ratings)) : ?>
                           <li> <span><i class="fa-solid fa-star"></i></span>
                              <span><?php echo esc_html($review_ratings); ?></span>
                              <?php echo esc_html("( {$review_count} {$review_text} )"); ?>
                           </li>
                        <?php endif; ?>


                        <li> <span><?php echo esc_html(count_user_posts($author_id, 'adqs_directory')); ?></span>
                           <?php echo esc_html__('Listing', 'adirectory'); ?></li>

                        <?php
                        $authorRegisteredDate = get_user_by('id', $author_id)->user_registered ?? '';
                        if (!empty($authorRegisteredDate)) :
                        ?>
                           <li> <?php echo esc_html__('Member Since', 'adirectory'); ?>:<span><?php echo esc_html(wp_date('d M, Y', strtotime($authorRegisteredDate))); ?></span>
                           </li>
                        <?php endif; ?>

                     </ul>

                     <ul class="qsd-profile-contact">
                        <?php if (get_the_author_meta('adqs_address_info', $author_id)) : ?>
                           <li class="qsd-address-info">
                              <div>
                                 <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                 </span>
                                 <?php the_author_meta('adqs_address_info', $author_id); ?>
                              </div>
                           </li>
                        <?php endif; ?>
                        <?php

                        if (!(AD()->Helper->get_setting('hide_author_email'))) : ?>
                           <li>
                              <a href="mailto:<?php echo esc_attr(get_the_author_meta('user_email', $author_id)); ?>">
                                 <span>
                                    <i class="fa-solid fa-envelope"></i>
                                 </span>
                                 <?php the_author_meta('user_email', $author_id); ?>
                              </a>
                           </li>
                        <?php endif; ?>
                        <?php if (get_the_author_meta('adqs_phone', $author_id)) : ?>
                           <li class="qsd-auth-phone">

                              <a href="tel:<?php the_author_meta('adqs_phone', $author_id); ?>">
                                 <span>
                                    <i class="fa-solid fa-phone"></i>
                                 </span>
                                 <?php the_author_meta('adqs_phone', $author_id); ?>
                              </a>
                           </li>
                        <?php endif; ?>
                     </ul>
                  </div>
               </div>

               <?php

               $authroSocials = [
                  'adqs_facebook_profile' => 'fa-facebook',
                  'adqs_twitter_profile' => 'fa-twitter',
                  'adqs_instagram_profile' => 'fa-instagram',
                  'adqs_linked_profile' => 'fa-linkedin',
               ];
               $authroSocials = array_filter($authroSocials, function ($key) use ($author_id) {
                  return get_the_author_meta($key, $author_id) ? true : false;
               }, ARRAY_FILTER_USE_KEY);
               if (!empty($authroSocials)) :

               ?>

                  <div class="qsd-auther-profile-social-icon-item">

                     <span><?php echo esc_html__('Find Me', 'adirectory'); ?>:</span>

                     <ul class="qsd-auther-profile-social-icon">
                        <?php
                        foreach ($authroSocials as $key_name => $icon) :
                        ?>
                           <?php if (get_the_author_meta($key_name, $author_id)) : ?>
                              <li>
                                 <a href="<?php echo esc_url(get_the_author_meta($key_name, $author_id)); ?>" target="_blank">
                                    <span><i class="fa-brands <?php echo esc_attr($icon); ?>"></i></span>
                                 </a>
                              </li>
                           <?php endif; ?>
                        <?php endforeach; ?>
                     </ul>
                  </div>
               <?php endif; ?>
            </div>
            <?php if (get_the_author_meta('description', $author_id)) : ?>
               <h5 class="qsd-agents-txt"><?php echo esc_html__('About Agents', 'adirectory'); ?></h5>

               <p class="qsd-agents-sub-txt">
                  <?php the_author_meta('description', $author_id); ?>

               </p>
            <?php endif; ?>
         </div>
      </div>
   </div>
</section>