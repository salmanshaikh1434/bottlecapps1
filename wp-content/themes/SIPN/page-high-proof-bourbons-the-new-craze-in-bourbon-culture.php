<?php
/**
 * Template Name: SIPN the-rise-of-high-proof-bourbons
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */

global $wpdb;
// $val=$_GET['q'];
// $str = str_replace("-", " ", $val);
$val='the-rise-of-high-proof-bourbons';
$query="SELECT * FROM wp_collections  WHERE collection_orgname='the rise of high proof bourbons'";
			$masterdata = $wpdb->get_results($query);
			$name=$masterdata[0]->collection_name;



 
 ?>
 <?php get_header();?>

  <style type="text/css">
 	body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0
}
.copied_coll{
	    display: block;
    position: absolute;
    top: -16px;
    right: -24px;
    width: 89px;
    background: white;
    color: #19191b;
    font-size: 13px;
}
.ptop25{ padding-top: 25px;}
 </style>

 <article class="col-md-10">
			<div class="wrapper-top">
            <div class="wrapper-bottom ptop25">
                <div class="container">
                    <div class="col-md-12">
                    	<span class="collections-back">
                    	<a href="/bourbon-collection"><img src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/icon-menu-slider.png"></a>
                    	</span>
                        
                        <h1 class="collections-heading"><?php echo $name; ?> <span class="collections-author">By: <?php echo $masterdata[0]->author; ?></span> </h1>
                        <h2 style="display: none;">Must-Have Bourbon Whiskeys for Thanksgiving Celebration</h2>

							<div class="share_collections">
								<span class="sharecoll"><i class="fas fa-share-alt"></i></span>
								<div class="share_collections_icons" id="share_collections_icons" style="display:none;">
									<ul>
											<li>
											<a href="https://www.facebook.com/sharer/sharer.php?text=Share Collection&amp;u=<?php echo site_url();?>/bourbon-collection/<?php echo $val; ?>" target="_blank">
											<i class="fa-brands fa-facebook-f" style="font-style: normal;"></i></a>
											</li>
											<li><a href="https://twitter.com/messages/compose?text=Share Collection <?php echo site_url();?>/bourbon-collection/<?php echo $val; ?>" target="_blank">
												<img src="/wp-content/themes/SIPN/assets/images/icon-twitter-gold.png"></a>
											</li>
											<li><a href="https://pinterest.com/pin/create/button/?url=<?php echo site_url();?>/bourbon-collection/<?php echo $val; ?>" target="_blank">
											<i class="fab fa-pinterest"></i></a>
											</li>
											<!-- <li><a href="https://api.whatsapp.com/send?text=<?php// echo site_url();?>/<?php// echo $val; ?>" data-action="share/whatsapp/share" target="_blank">
											<i class="fab fa-whatsapp"></i></a>
											</li>
											<li><a href="mailto:subject=Share Collection&amp;body=<?php //echo site_url();?>/<?php// echo $val; ?>" target="_blank">
											<i class="fa fa-envelope" aria-hidden="true"></i></a>
											</li> -->
											<li><a class="copy-cls_coll" href="javascript:void(0);" link="<?php echo site_url();?>/bourbon-collection/<?php echo $val; ?>"><i class="fas fa-copy"></i></a></li>
											<!-- <li><a class="copy-cls" href="javascript:void(0);" link="<?php //echo site_url();?>/bourbon-collections/<?php //echo $val; ?>"><i class="fas fa-share-alt"></i></a></li> -->

									</ul>
								</div>
							</div> 
					</div>
					
					<div class="col-md-12">
					<div class="col-md-8 colle">
					
						<ul>
							<?php  $pv=$masterdata[0]->collection_products;
							if($pv != 'NULL'){
							$p=explode(',', $pv);
							foreach ($p as $product) { 
							 	$the_product = wc_get_product( $product );
							 	//print_r($the_product);exit;
							 	$img=get_the_post_thumbnail_url( $product, 'medium' );
							 	if($img==''){
							 		$img=get_stylesheet_directory_uri()."/assets/images/default-bottle.jpg";
							 	}
							?> 
							
					<li><a href="<?php echo get_permalink($product);?>" title="<?php echo $the_product->name;?>"><img alt="<?php echo $the_product->name;?>" src="<?php echo $img;?>"><span class="prod-title"><?php echo $the_product->name; ?></span><p>$<?php echo $the_product->price; ?></p></a></li>
					
					<?php  }  } else { echo "No Products"; }?>
					
					</ul>

					</div>
					
					<div class="col-md-4">

						

					<div class="collection-room"><img src="<?php echo $masterdata[0]->collection_image; ?>" alt="Old Fashioned Cocktail"></div>
						<div class="about-collection">
							
							<p> <?php echo $masterdata[0]->collection_long_description; ?> </p>
						</div>
					
					</div>
				
					
					
                </div>
				
				
				
<?php sipn_footer();?>
