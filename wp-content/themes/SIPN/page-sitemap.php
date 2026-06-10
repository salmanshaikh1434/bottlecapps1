<?php
/**
 * Template Name: SIPN sitemap
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
  ?>
<?php get_header();                                    
$products_query = $wpdb->prepare("SELECT * from wp_posts   WHERE post_type = 'product' And post_status='publish' ORDER BY id ASC");
			$prods = $wpdb->get_results($products_query);
                    
                                        $key=1;?>
                                        <span>All products</span>
                                       <?php  foreach($prods as $product){ ?>

<span ><center>
<?php echo $key; ?>. <a href="<?php echo get_permalink($product->ID);?>" title="<?php echo $product->post_title;?>"><?php  echo $product->post_title;?></a>
</center></span>
<br>

<?php $key++; } ?>
 <?php sipn_footer();?>