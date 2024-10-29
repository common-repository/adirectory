<?php

/**
 * The template for displaying listing price in the globaly template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/global/meta.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
   exit; // Exit if accessed directly
}

$listing_id = get_the_ID();
$address = adqs_get_common_listing_meta($listing_id, '_address');
$phone = adqs_get_common_listing_meta($listing_id, '_phone');

$categories = adqs_render_repeated_tax($listing_id);
$author_id = get_the_author_meta('ID');
$author_posts_url = adqs_listing_author_url($author_id, get_post_type());


?>
<div class="qsd-prodcut-grid-list-item">
<?php adqs_get_template_part( 'global/listing','post-thumbnail' ); ?>
   <div class="qsd-prodcut-grid-list-inner">
     <div class="qsd-product-grid-article">
		 <?php if (!empty($categories)) : ?>
			 <ul class="qsd-prodcut-grid-list-inner-top-btn">
				 <?php
				 foreach ($categories as $category) :
					 ?>
					 <li>
						 <a href="<?php echo esc_url(get_term_link($category->term_id)); ?>" class="grid-list-inner-top-btn">
							 <?php echo esc_html($category->name); ?>
						 </a>
					 </li>
				 <?php
				 endforeach;
				 ?>
			 </ul>
		 <?php endif; ?>
		 <?php
		 $price = AD()->Helper->get_price(get_the_ID());
		 if (!empty($price)) :
			 ?>
			 <p class="qsd-grid-price"><?php
				 adqs_get_template_part( 'global/price' );
				 ?></p>
		 <?php endif; ?>
		 <?php if (!empty(get_the_title())) : ?>
			 <h3>
				 <a href="<?php the_permalink(); ?>" class="grid-list-inner-txt line-clamp-2">
					 <?php the_title(); ?>
				 </a>
			 </h3>
		 <?php endif; ?>

		 <?php if (!empty($address) || !empty($phone)) : ?>
			 <ul class="grid-list-inner-contact">

				 <?php if (!empty($address)) : ?>
					 <li>
						 <a  href="<?php the_permalink(); ?>" title="<?php echo esc_attr($address); ?>">
							 <svg width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								 <path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 20C12.875 20 18.5 13.7981 18.5 8.88889C18.5 3.97969 14.4706 0 9.5 0C4.52944 0 0.5 3.97969 0.5 8.88889C0.5 13.7981 6.125 20 9.5 20ZM9.5 12C11.1569 12 12.5 10.6569 12.5 9C12.5 7.34315 11.1569 6 9.5 6C7.84315 6 6.5 7.34315 6.5 9C6.5 10.6569 7.84315 12 9.5 12Z" fill="#2B69FA"/>
							 </svg>
							 <div class='line-clamp-1'><?php echo esc_html(wp_trim_words($address, 4, '')); ?></div>
						 </a>
					 </li>
				 <?php endif; ?>

				 <?php if (!empty($phone)) : ?>
					 <li>
						 <a href="tel:<?php echo esc_attr($phone); ?>">
							 <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								 <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5 0.25C10.0858 0.25 9.75 0.585786 9.75 1C9.75 1.41421 10.0858 1.75 10.5 1.75C11.4521 1.75 12.3948 1.93753 13.2745 2.30187C14.1541 2.66622 14.9533 3.20025 15.6265 3.87348C16.2997 4.5467 16.8338 5.34593 17.1981 6.22554C17.5625 7.10516 17.75 8.04792 17.75 9C17.75 9.41421 18.0858 9.75 18.5 9.75C18.9142 9.75 19.25 9.41421 19.25 9C19.25 7.85093 19.0237 6.71312 18.5839 5.65152C18.1442 4.58992 17.4997 3.62533 16.6872 2.81282C15.8747 2.0003 14.9101 1.35578 13.8485 0.916054C12.7869 0.476325 11.6491 0.25 10.5 0.25ZM18.5 17V15.3541C18.5 14.5363 18.0021 13.8008 17.2428 13.4971L15.2086 12.6835C14.2429 12.2971 13.1422 12.7156 12.677 13.646L12.5 14C12.5 14 10 13.5 8 11.5C6 9.5 5.5 7 5.5 7L5.85402 6.82299C6.78438 6.35781 7.20285 5.25714 6.81654 4.29136L6.00289 2.25722C5.69916 1.4979 4.96374 1 4.14593 1H2.5C1.39543 1 0.5 1.89543 0.5 3C0.5 11.8366 7.66344 19 16.5 19C17.6046 19 18.5 18.1046 18.5 17ZM9.75 5C9.75 4.58579 10.0858 4.25 10.5 4.25C11.1238 4.25 11.7414 4.37286 12.3177 4.61157C12.894 4.85028 13.4177 5.20016 13.8588 5.64124C14.2998 6.08232 14.6497 6.60596 14.8884 7.18225C15.1271 7.75855 15.25 8.37622 15.25 9C15.25 9.41421 14.9142 9.75 14.5 9.75C14.0858 9.75 13.75 9.41421 13.75 9C13.75 8.5732 13.6659 8.15059 13.5026 7.75628C13.3393 7.36197 13.0999 7.00369 12.7981 6.7019C12.4963 6.40011 12.138 6.16072 11.7437 5.99739C11.3494 5.83406 10.9268 5.75 10.5 5.75C10.0858 5.75 9.75 5.41421 9.75 5Z" fill="#2B69FA"/>
							 </svg>
							 <?php echo esc_html($phone); ?>
						 </a>
					 </li>
				 <?php endif; ?>

			 </ul>
		 <?php endif; ?>
	 </div>

   </div>
</div>
