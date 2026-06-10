<?php
/**
 * Template Name: SIPN Home
 *
 * The template for the page builder full-width.
 *
 * It contains header, footer and 100% content width.
 *
 * @package Neve
 */
 ?>
 <?php get_header();?>
 
<?php
global $current_user; wp_get_current_user();
if(is_user_logged_in()){
    $cur_user_id = get_current_user_id();
    $current_user_details = get_user_by('id', $cur_user_id);
    $curemail=$current_user_details->user_email;
    $current_user_meta = get_user_meta($cur_user_id);   
    $cur_user_avatar = wp_get_attachment_image_url($current_user_meta['wp_user_avatar'][0], 'thumbnail');

    if(!$cur_user_avatar){
    $cur_user_avatar = get_avatar_url($cur_user_id);
    }
}
?>
<style type="text/css">
.mcarousel-inner .active.left  { left: -33%;             }
.mcarousel-inner .active.right { left: 33%;              }
.mcarousel-inner .next         { left: 33%               }
.mcarousel-inner .prev         { left: -33%              }
.mcarousel-control.left        { background-image: none; }
.mcarousel-control.right       { background-image: none; }
.mcarousel-inner .item         { background: white;      }

 .mySlides { display: none; padding: 0px; text-align: center; }
 .mySlides a{ display:block;}
 .mySlides1 { display: none; padding: 0px; text-align: center; }
 .mySlides1 a{ display:block;}
  /* Next & previous buttons */
 .prev, .next { cursor: pointer; position: absolute; top: 50%; width: auto; margin-top: -30px; padding: 16px; color: #888; font-weight: bold; font-size: 20px; border-radius: 0 3px 3px 0; user-select: none; }
 /* Position the "next button" to the right */
 .next {position: absolute; right: 0;border-radius: 3px 0 0 3px; }
 /* On hover, add a black background color with a little bit see-through */
 .prev:hover, .next:hover {background-color: rgba(0, 0, 0, 0.8); color: white; }
  /* The dot/bullet/indicator container */
  .dot-container { text-align: center; padding: 5px 0; background: #2d2d2d !important;}
  .dot-container1 { text-align: center; padding: 5px 0; background: #2d2d2d !important;}
  /* The dots/bullets/indicators */
  .dot, .dot1 { cursor: pointer; height: 10px; width: 10px; margin: 0 2px; background-color: #2d2d2d; border:solid 1px #b7a968; border-radius: 50%; display: inline-block; transition: background-color 0.6s ease;}
  /* Add a background color to the active dot/circle */
  .dot-container span.active, span .dot:hover, span .dot:active,  span .dot1:active, span .dot1:hover { background-color: #b7a968; border-color:#b7a968; }
  /* Add an italic font style to all quotes */
</style>

<!-- Async script executes immediately and must be after any SDOM elements used in callback. -->

<script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/buy-now-location.js"></script>
<script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/buy-now-location2.js"></script>
<div id="popup" class="install-app" style="display:none;">
    <div>
        <div id="popup-close">
<img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/logo-app.png" alt="logo" width="80" height="80" >
        </div>
    
<p>For Better Experience, <br>Download The   <b>SIPN BOURBON</b> App.</p>


<a href = "javascript:void(0);" class="closeinstall"> Not Now! </a>
<a href = "http://onelink.to/sipnbourbon" class="addinstall"> Switch to App. </a>
                                     
    </div>
</div> 
<h1 style="display:none;">SIPN</h1>
<h2 style="display:none;">Buy bourbon online</h2>
<section class="page-sec col-xs-12 col-sm-10 home-page">
                <div class="main-section row">
                    <div class="main-content col-sm-7">

                        <!-- Sample slider -->
                     <!-- <div id="container" class="mchat">
                    <div id="slider-container"> -->
                      <!-- <span onclick="slideRight()" class="btn"></span> -->
                      <!-- <span onclick="slideRight()" class="btn chat-left"><i class="fa fa-chevron-left"></i></span>
                        <div id="slider"> -->
                          
                          <?php
                                        // $chat_msgs = get_timeline_chatbubbles(); 
                                        // //print_r($chat_msgs);
                                        // $colors = array('gr', 'pe', 'pu', 'ru');

                                        // foreach($chat_msgs as $key => $chat){
                                        ?>
                                       <!--  <div class="slide"> -->

                                            <!-- <div class="unread-msgs"> -->
                                           <!-- <div class="<?php //echo $key;?> chat-<?php //echo $colors[$key];?>"> -->
                                                       <!--  <div class="chat_1"> -->
                                                            <?php // if($chat['new_flag'] == '1'){?><!-- <span class="status"></span> --><?php //} ?>
                                                               <!--  <h4><a href="<?php //echo get_permalink($chat['topic_id']);?>"> --><?php //echo $chat['topic'];?> <!-- </a></h4> -->
                                                                 <!--    <div class="user-info"> -->
                                                                        <?php //if($chat['avatar'] == '' || !$chat['avatar']){ $chat['avatar'] = get_stylesheet_directory_uri().'/assets/images/chat/img-profile1.jpg';}?>
                                                                     <!--    <img src="<?php //echo $chat['avatar'];?>" alt="user-image" width="50" height="50" class="img-circle">
                                                                        <div class="user-review"> -->
                                                                            <!-- edited by sumeeth for chatbubble author name bar link -->
                                                                            <?php //if(is_user_logged_in()){ ?>
                                                                            <!-- <a href="<?php //echo bbp_get_user_profile_url($chat['author_id']); ?>"><h5><?php //echo $chat['author'];?></h5></a> -->
                                                                             <?php // } else { ?>
                                                                              <!-- <a href="/login">  <h5><?php //echo $chat['author'];?></h5></a> -->
                                                                            <?php //} ?>
                                                                           <!--  <p><?php //echo $chat['reply'];?></p> -->
                                                                          <!--   <a href="<?php //echo get_permalink($chat['topic_id']);?>">Read More...</a> -->
                                                                      <!--   </div>
                                                                    </div> -->
                                                                <!-- <span class="shape"><i class="fas fa-comment-alt"></i></span> -->
                                      <!--                   </div>
                                                    </div>
                                            
                                     </div>
                                   
                                </div> -->
                                        <?php // } ?>
                                       
                               
                         
                     <!--  </div> -->
                      <!-- <span onclick="slideLeft()" class="btn"></span> -->
                    <!--   <span onclick="slideLeft()" class="btn chat-right"><i class="fa fa-chevron-right"></i></span>
                    </div>
                    <div class="clearfix"></div>
                  </div> -->
                  <div class="clearfix"></div>
                    <?php
                                            $product_visibility_term_ids1 = wc_get_product_visibility_term_ids();
                                            $args1 = [
                                                'post_type' => 'product',
                                                'post_status' => 'publish',
                                                'order' => 'ASC',
                                                'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
                                                    'relation' => 'AND',
                                                    array(
                                                        'taxonomy' => 'product_visibility',
                                                        'field'    => 'term_taxonomy_id',
                                                        'terms'    => array( $product_visibility_term_ids1['featured'] ),
                                                    ),
                                                    array(
                                                        'taxonomy' => 'product_visibility',
                                                        'field'    => 'term_taxonomy_id',
                                                        'terms'    => array( $product_visibility_term_ids1['exclude-from-catalog'] ),
                                                        'operator' => 'NOT IN',
                                                    ),
                                                    ),
                                                    'posts_per_page' => -1
                                                ];
                                                
                                                

                                            $products2 = get_posts($args1);
                                            $products = array_chunk($products2, 3);
                                                        ?>
                                                    
                  <div class="mtrending">
                    <div class="trending">
                        <h2>TRENDING</h2>
                    </div>
 
  <div id="myCarousel" class="mcarousel slide">
  
  <div class="mcarousel-inner">
      <?php
            if(count($products)>0){
            $cnt = 0;
            foreach($products as $key=>$product){
           
            ?>
              <div class="item <?php  if($key == 0){ echo "active";} ?>">
                 <?php foreach ($product as $key => $product1){ 
                     $the_product = wc_get_product( $product1->ID );
            $prod_url = get_the_post_thumbnail_url( $product1->ID, 'full' );?>

              <div class="col-xs-4  col-xl-4 grow" style="padding-left: 0;padding-right: 8px;">

               <div class="tcb-product-item">
                    <div class="tcb-product-info">
                        <div class="tcb-product-title">
                           <h4><a href="<?php echo get_permalink($product1->ID);?>" title="<?php echo $product1->post_title;?>"><?php echo $product1->post_title;?></a></h4>
                        </div>
                    </div>
                </div>
                <div class="tcb-product-photo">
                     <a href="<?php echo get_permalink($product1->ID);?>"><img loading="lazy" src="<?php echo $prod_url;?>" alt="<?php echo $product1->post_title;?>" width="100" height="100" class="img-responsive"></a>

                </div>
                                                                        
                </div>
                <?php } ?>
                 
              </div>

      <?php $cnt++;}}else{ echo "No products found.";} ?> 
  
  </div>

  <!-- Controls -->
  <a class="left mcarousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right mcarousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>  
<div class="view-all">
                                <a href="/featured-products">View all</a>
                            </div> 
</div>

                        <!-- Sample Slider -->
                             <div class="inner-content-feeds">
                            <div class="feeds">
                                <div class="add-your-feeds">
                                   
                                    <div class="plus-symbol">
                                        <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                    <a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"><i class="fa fa-plus"></i></a>

                                  <?php  }else{ ?>
                                    <?php if(is_user_logged_in()){ ?>
                                        <a href="javascript:void(0);" id="myBtn"><i class="fa fa-plus"></i></a>
                                        <?php }else{ ?>
                                        <a href="/login/?redirect_to=msg-12345"><i class="fa fa-plus"></i></a>
                                        <?php } ?>
                                   <?php } ?>
                                    </div>
                                    <h5>Add Your Post </h5>
                                    <div id="myModal" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Create Post</h2>
                                          <span class="close openpost">&times;</span>
                                        </header>
                                        <div class="">
                                           <div class="user-chat">
                                                <div class="msg-opt-in">
                                                    <div class="profile-pic">
                                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar;?>" alt="nature-image" width="60" height="60"></a>
                
                                                    </div>
                                                    <div class="more-user-info">

                                                        <div class="write-block">
                                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                                    <input type="button"  class="colorbttn" value="Tag Product" id="tagproduct" disabled="true" >
                                                                    <input class="form-control headerpostsearch" name="s" required type="text" for="search" placeholder="Search Bourbons" id="headerpostsearch" autocomplete="off" style="color: #000; display:none;" /><span class="closeproduct1" style="display:none;">×</span>
                                                                </div>
                                                                <div class="col-md-6 col-sm-12 col-xs-12">
                                                                    <input type="button"  class="taglocpost colorbttn" value="Tag Location" id="taglocpost" disabled="true" ><span class="closeloc1" style="position: absolute;right: 27px;color: #baa86d;top: 80px;font-size: 25px;cursor: pointer;display: none;">×</span>
                                                                    <input id="search_input" class="form-control pac-target-input bn_address taglocpostsearch" placeholder="Enter Address" type="text" autocomplete="off" style="display:none;"> 
                                                                </div>
                                                                    <div class="headerpost-result-sec" style="display:none;"></div>
                                                                    <input type="hidden" id="fpid" />
                                                                    <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                                    <textarea placeholder="Write your post" class="text-area add" id="comment_0"></textarea>
                                                                    
                                                        </div>
                                                        <div class="createimageholder emojis">
                                                            <div class="inputWrapper1">
                                                                  <!--   added by sumeeth -->
                                                                  <span class="createclose edicloseimage2" style="display:none;">&times;</span>
                                                                <input accept="image/*" onchange="readURL(this.files);" class="fileInput commentInput" rid="0"  id="profile-pic" name="pImage[]" type="file" multiple="">
                                                                
                                                                <ul id="addimage" class="viewonly"  style="display:none;"> </ul>

                                                            
                                                                <!-- <input accept="image/*" capture="camera" class="fileInput commentInput" rid="0" name="pImage" type="file"> -->
                                                                <label for="profile-pic"><span class="glyphicon glyphicon-paperclip"></span></label>
                                                                <div id="mulimg"></div>
                                                                <input type="hidden" id="comment_img_0" value="">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 btn-postnow">
                                                      <input type="button" class="post submitWrapper colorbttn"  rid="0" value="POST NOW!" disabled="disabled">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->
                                      
                                      
                                      <div id="editModal" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Edit Post</h2>
                                          <span class="close">&times;</span>
                                        </header>
                                        <div class="">
                                           
                                           <div class="user-chat">
                                                <div class="msg-opt-in">
                                                    <div class="profile-pic">
                                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar;?>" alt="nature-image" width="60" height="60"></a>
                
                                                    </div>
                                                    <div class="more-user-info">
                                                        <div class="write-block">
                                                            <div class="col-md-6">
                                                            <input type="button"  class="" value="Tag Product" id="tagproduct1" disabled="true">
                                                                <input class="form-control headerpostsearch1" name="s" required type="text" for="search" placeholder="Search Bourbons" id="headerpostsearch1" autocomplete="off" style="color: #000; display:none;" ><span class="closeproduct">×</span>
                                                            </div>
                                                                <div class="headerpost-result-sec" style="display:none;"></div>
                                                                <input type="hidden" id="fpid1" value="" />
                                                                <div class="col-md-6">
                                                                <input type="button"  class="tageditlocpost" value="Tag Location" id="tageditlocpost" disabled="true" ><span class="closeloc"  style="position: absolute;right: 30px;color: #baa86d;top: 82px;font-size: 25px;cursor: pointer;display: none;">×</span>
                                                                <input id="search_input" class="form-control pac-target-input bn_address2 tagloceditpostsearch" placeholder="Enter Address" type="text" autocomplete="off">
                                                            </div>

                                                                <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                                <textarea placeholder="Edit your post" class="text-area comment" id="comment_01"></textarea>
                                                                <!-- <div id="addeimage" class="viewonly1 editaddprev" style="display:none;"></div> -->

                                                        </div>
                                                        <div class="editimageholder">
                                                                <ul id="addeimage" class="viewonly1 editaddprev"  style="display:none;"> </ul>
                                                                <div class="imageholder">
                                                                <div class="view-gallery">
                                                                    <ul class="viewonly"></ul>
                                                                    <span class="fa fa-times deletepimages" ></span>
                                                                    <input type="hidden" name="delete_image" id="delete_image" value="0">
                                                                    
                                                                </div>
                                                                
                                                                <div class="emojis">
                                                                <div class="inputWrapper1">
                                                                    <!-- added by sumeeth -->
                                                                <input accept="image/*" onchange="readURL2(this.files);"   class="fileInput commentInput" rid="0" name="pImage" id="profile-pic1" type="file"  multiple="">
                                                                    <label for="profile-pic1"><span class="glyphicon glyphicon-paperclip"></span></label>
                                                                    <div id="mulimg1"></div>
                                                                    <span class="cancelclose edicloseimage" style="display:none;">&times;</span>
                                                                    <!-- <input accept="image/*" capture="camera" class="fileInput commentInput" rid="0" name="pImage" type="file"><span class="fa fa-camera"></span> -->
                                                                    <input type="hidden" class="commentImg" id="comment_img_0" value="">
                                                                    <input type="hidden" class="sumee" value="" > 
                                                                    </div>
                                                                    
                                                                </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 btn-postnow">
                                              
                                                <input type="button" class="post submitEditWrapper"  rid="0" value="POST NOW!">
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->

                                     


                                      
                                      
                                      <div id="replyModal" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Reply Post</h2>
                                          <span class="close">&times;</span>
                                        </header>
                                        <div class="">
                                           
                                           <div class="user-chat">
                                                <div class="msg-opt-in">
                                                    <div class="profile-pic">
                                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar;?>" alt="nature-image" width="60" height="60"></a>
                
                                                    </div>
                                                    <div class="more-user-info">
                                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                        <textarea placeholder="Add your post" class="text-area comment " id="comment_0"></textarea>
                                                        <div class="emojis">
                                                        <div class="inputWrapper1">
                                                            <input accept="image/*"  class="fileInput commentInput" rid="0" name="pImage" type="file"><span class="glyphicon glyphicon-paperclip"></span>
                                                            <input type="hidden" class="commentImg" id="comment_img_0" value="">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12"><a href="javascript:void(0);" class="post submitReplyWrapper" rid="0" type="button">Post</a></div>
                                            </div>
                                        </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->
                                      
                                      
                                      <div id="repliesModal" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Comments</h2>
                                                <span class="close">&times;</span>
                                            </header>
                                            <div class="replies-body">
                                            <div class="result-replies"></div>
                                            <div class="more-user-info">
                                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                        <textarea placeholder="Comment your post" class="text-area comment replsum" id="comment_0"></textarea>
                                                        <div class="emojis repliesholder">
                                                            <div class="inputWrapper1">
                                                                <input accept="image/*" onchange="readURL1(this);"  class="fileInput commentInput" rid="0" name="pImage" type="file">
                                                                <p id="editimage" style="display:none;"> <img id="blah1" src="#" alt="your image" style="display:none;" /></p>
                                                                <span class="commentcloseimage" style="display:none;">&times;</span>
                                                                <!-- <input accept="image/*" capture="camera" class="fileInput commentInput" rid="0" name="pImage" type="file">--><span class="glyphicon glyphicon-paperclip"></span> 
                                                                <input type="hidden" class="commentImg replsum" id="comment_img_0" value="">
                                                            </div>
                                                            
                                                        </div>
                                            </div>
                                            <div class="col-md-12 btn-postnow">
                                                <input type="button" class="post submitRepliesWrapper colorbttn"  rid="0" value="Post" disabled="disabled">
                                                
                                            </div>
                                            </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->
                                      
                                      <div id="reportModal" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Report</h2>
                                          <span class="close">&times;</span>
                                        </header>
                                        <div class="report">
                                            <p><strong>Why are you reporting this post?</strong></p>
                                            <p>Your report is confidential, this will keep the SIPN community cleaner for all the users. </p>
                                            <ul>
                                                <li><a class="report_post" href="javascript:void(0);" rep="It's Spam">It's Spam<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="Hate Speech">Hate Speech<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="It's inappropriate">It's inappropriate<span><i class="fa fa-chevron-right"></i></span></a></li>
                                                <li><a class="report_post" href="javascript:void(0);" rep="Prohibited Content">Prohibited Content<span><i class="fa fa-chevron-right"></i></span></a></li>

                                            </ul>
                                           
                                        </div>
                                        </div>
                                      
                                      </div>

                                      <div id="editModal1" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Edit Comment</h2>
                                          <span class="close">&times;</span>
                                        </header>
                                        <div class="">
                                           
                                           <div class="user-chat">
                                                <div class="msg-opt-in">
                                                    <div class="profile-pic">
                                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar;?>" alt="nature-image" width="60" height="60"></a>
                
                                                    </div>
                                                    <div class="more-user-info">
                                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                        <textarea placeholder="Edit your comment" class="text-area comment" id="comment_0"></textarea>
                                                        <div class="emojis">
                                                        <div class="inputWrapper1">
                                                            <!-- added by sumeeth -->
                                                            <input accept="image/*"  onchange="loadFile1(event)" class="fileInput commentInput" rid="0" name="pImage" id="profile-pic2" type="file" style="display:none;">
                                                            <label for="profile-pic2"><span class="glyphicon glyphicon-paperclip"></span></label>
                                                            <p id="editpostoutputimage1" style="display:none;"><img id="output1" src="" height="100" width="100" style="display:none;" /></p>
                                                            <span class="edicloseimage1" style="display:none;">&times;</span>
                                                            <!-- <input accept="image/*" capture="camera" class="fileInput commentInput" rid="0" name="pImage" type="file"><span class="fa fa-camera"></span> -->
                                                            <input type="hidden" class="commentImg" id="comment_img_0" value="">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    
                                                    <input type="button" class="post submitEditWrapper1"  rid="0" value="Post">
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->

                                      <div id="repliesModal1" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2 id="headd"> Comments</h2>
                                                <span class="close">&times;</span>
                                            </header>
                                            <div class="replies-body">
                                            <div class="result-replies"></div>
                                            <div class="more-user-info">
                                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                        <textarea placeholder="Comment your post" class="text-area comment" id="comment_0"></textarea>
                                                        <div class="emojis">
                                                        <div class="inputWrapper1">
                                                            <!-- <input accept="image/*"  class="fileInput commentInput" rid="0" name="pImage" type="file"> -->
                                                            <!-- <span class="fa fa-camera"></span> -->
                                                            <!-- <label for="profile-pic"><span class="fa fa-paperclip"></span></label> -->
                                                            <input type="hidden" class="commentImg" id="comment_img_0" value="">
                                                            </div>
                                                            
                                                        </div>
                                            </div>
                                            <div class="col-md-12">
                                                
                                                <input type="button" class="post submitRepliesWrapper"  rid="0" value="Post" >
                                            </div>
                                            </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->
                                      <div id="sponsoredModal" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Sponsored Comments</h2>
                                                <span class="close">&times;</span>
                                            </header>
                                            <div class="replies-body">
                                            <div class="result-replies"></div>
                                            <div class="more-user-info">
                                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                        <textarea placeholder="Comment your post" class="text-area comment replsum" id="comment_0"></textarea>
                                                        <div class="emojis repliesholder">
                                                            <div class="inputWrapper1">
                                                                <input accept="image/*" onchange="readURL3(this);"  class="fileInput commentInput" rid="0" name="pImage" type="file">
                                                                <p id="editimage2" style="display:none;"> <img id="blah2" src="#" alt="your image" style="display:none;" /></p>
                                                                <span class="commentcloseimage2" style="display:none;">&times;</span>
                                                                <!-- <input accept="image/*" capture="camera" class="fileInput commentInput" rid="0" name="pImage" type="file">--><span class="glyphicon glyphicon-paperclip"></span> 
                                                                <input type="hidden" class="commentImg replsum" id="comment_img_0" value="">
                                                            </div>
                                                            
                                                        </div>
                                            </div>
                                            <div class="col-md-12 btn-postnow">
                                                <input type="button" class="post submitsponsRepliesWrapper colorbttn"  rid="0" value="Post" disabled="disabled">
                                                
                                            </div>
                                            </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->
                                      <div id="editsponsModal1" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Edit Comment</h2>
                                          <span class="close">&times;</span>
                                        </header>
                                        <div class="">
                                           
                                           <div class="user-chat">
                                                <div class="msg-opt-in">
                                                    <div class="profile-pic">
                                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar;?>" alt="nature-image" width="60" height="60"></a>
                
                                                    </div>
                                                    <div class="more-user-info">
                                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                        <textarea placeholder="Edit your comment" class="text-area comment" id="comment_0"></textarea>
                                                        <div class="emojis">
                                                        <div class="inputWrapper1">
                                                            <!-- added by sumeeth -->
                                                            <input accept="image/*"  onchange="loadFile1spons(event)" class="fileInput commentInput" rid="0" name="pImage" id="profile-pic2" type="file" >
                                                            <label for="profile-pic2"><span class="glyphicon glyphicon-paperclip"></span></label>
                                                            <p id="editpostoutputimage123" style="display:none;"><img id="output123" src="" height="100" width="100" style="display:none;" /></p>
                                                            <span class="edicloseimage1" style="display:none;">&times;</span>
                                                            <!-- <input accept="image/*" capture="camera" class="fileInput commentInput" rid="0" name="pImage" type="file"><span class="fa fa-camera"></span> -->
                                                            <input type="hidden" class="commentImg" id="comment_img_0" value="">
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    
                                                    <input type="button" class="post submitsponsEditWrapper1"  rid="0" value="Post">
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                      
                                    </div> <!-- end of modal -->

                                     <div id="repliesModal2" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2 id="headd"> Comments</h2>
                                                <span class="close subclose">&times;</span>
                                            </header>
                                            <div class="replies-body">
                                            <div class="result-replies"></div>
                                            <div class="more-user-info">
                                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                        <textarea placeholder="Comment your post" class="text-area comment" id="comment_0"></textarea>
                                                        <div class="emojis">
                                                        <div class="inputWrapper1">
                                                            
                                                            </div>
                                                            
                                                        </div>
                                            </div>
                                            <div class="col-md-12">
                                                
                                                <input type="button" class="post submitsponsRepliesWrapper"  rid="0" value="Post" >
                                            </div>
                                            </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->

                                       <div id="editsponsModal2" class="modal">

                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <header>
                                                <h2>Edit Comment</h2>
                                          <span class="close edscmnt">&times;</span>
                                        </header>
                                        <div class="">
                                           
                                           <div class="user-chat">
                                                <div class="msg-opt-in">
                                                    <div class="profile-pic">
                                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar;?>" alt="nature-image" width="60" height="60"></a>
                
                                                    </div>
                                                    <div class="more-user-info">
                                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                                        <textarea placeholder="Edit your comment" class="text-area comment" id="comment_0"></textarea>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    
                                                    <input type="button" class="post submitsponsEditWrapper1"  rid="0" value="Post">
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                      
                                      </div> <!-- end of modal -->

                                      
                                </div>
                                <div class="feed-buttons">
                                <?php
                                    //global $current_user; wp_get_current_user();
                                    if ( is_user_logged_in() ){
                                        $url = site_url().'/wp-json/users/v2/bar'; 
                                        $body = array('user_id'=>$current_user->data->ID);
                                        $body = wp_json_encode( $body );
                                        $response = wp_remote_post( $url, array(
                                            'body'    => $body,
                                            'headers'     => [
                                                'Content-Type' => 'application/json',
                                            ],
                                        ) );

                                        $bar_res = json_decode( wp_remote_retrieve_body( $response ) );
                                        if($bar_res->message == 'Bar doesnt exist'){
                                            $no_bar = 1;
                                        }
                                        else{
                                            $no_bar = 0;
                                        }
                                    }
                                    
                                    if(is_user_logged_in() && $current_user->data->validate_email=='0' ){
                                    ?>
                                    <a href="<?php echo bbp_get_user_profile_url($current_user->data->ID);?>">
                                    <?php echo "MY BAR"; } else if(is_user_logged_in() && $current_user->data->validate_email=='1' ){ ?>
                                     <a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup">
                                      <?php echo "MY BAR";  } else{ ?></a>
                                    <a href="/login?redirect_to=bar">
                                    <?php echo "BUILD A BAR"; } ?></a>
                                    
                                    <?php if(is_user_logged_in()){ ?>
                                    <a href="/wishlist">WISHLIST</a>
                                    <?php } else { ?>
                                    <a href="/login?redirect_to=wishlist">WISHLIST</a>
                                    <?php } ?>
                                </div>
                            </div>
                         </div> 
                         <?php 
                        $timeline_res_sponsored = get_timeline_list('1', '10');
                     //   echo "<pre>";print_r($timeline_res_sponsored);exit;
                        foreach($timeline_res_sponsored['sponsored_ads'] as  $spons){
                          ?>
                        <div class="inner-content" id="sponsmsg-<?php echo $spons['spons_id']; ?>">
                            <div class="user-feed">
                                <div class="user-profile">
                                <div class="dropdown">
                                            <img class="threedots" src="https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/vertical-dots.png">
                                            <div class="dropdown-content">
                                                    <!--    added by sumeeth -->
                                                      <?php if (is_user_logged_in()) {
                                                      $lin="javascript:void(0);";
                                                    } else if(is_user_logged_in() && $current_user->data->validate_email=='0'){
                                                      $lin="/login?redirect_to=sponsmsg-".$spons['spons_id']."";
                                                    }else{
                                                      $lin="/login";
                                                    } ?>
                                                    <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                 

                                    <a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>


                                  <?php  }else{ ?>
                                   <a href="<?php echo $lin; ?>" class="report-tl-post" rid="<?php echo $spons['spons_id']; ?>" post_url="https://sipnbourbon.com/timeline_sponsads/?q=<?php echo $spons['spons_id']; ?>"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>
                                   <?php } ?>

                                                    <!--    added by sumeeth -->
                                            </div>
                                        </div>
                                    <div class="profile-in">
                                        <div class="profile-pic">
                                            <a class="profile-spons-add" href="javascript:void(0);"><img src="<?php echo $spons['company_logo']; ?>" width="60" height="60"></a>
                                         <!--    added by sumeeth -->
                                            <div class="user-name">
                                                                                           <a href="javascript:void(0);"><?php echo $spons['company_name']; ?></a>
                                                                          <span class="company_verified"><img src="<?php echo $spons['spons_verified']; ?>"></span><br>
                                           <small class="">
                                                 Sponsored</small>
                                                                                            
                                                                                                                                         <br>
                                                <?php if($spons['product_title'] !='') { 
                                                    $the_product = wc_get_product($spons['product_id']); ?>
                                                <span class="sumss"><a href="<?php  echo get_permalink($spons['product_id']);?>" title="<?php echo $spons['product_title'];?>"><?php $p=$spons['product_title']; if ($p=="Top Bourbons for Father's Day") {
                                                  $spons['product_title']='';
                                                } echo $spons['product_title'];?></a></span>
                                              <?php } ?>
                                                
                                                 <?php if($spons['product_title'] != '') { ?>
                                                 <div><a href="<?php echo esc_url( add_query_arg( array('prod_id' => $the_product->sku, 'prid' => $the_product->id), site_url( '/buy-now/' ) ) )?>" class="buynow post-buynow"><button class="search">Buy Now</button></a></div>
                                                <?php } ?>
                                                    
                                              </div>
                                        </div>
                                    </div>
                                    <div class="user-msg">
                                      <?php echo $spons['description']; ?>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="upload-image " oncontextmenu="return false;">
                              <div class="sectionss">
                                  <div class="slideshow-container1234">
                                   
                                   <?php  if(is_user_logged_in() && $current_user->data->validate_email=='0' ){
                                    ?>
                                     <a  href="<?php if(is_user_logged_in()){echo bbp_get_user_profile_url($current_user->data->ID);} else{ echo "/login?redirect_to=bar"; }?>" > <!--  <?php //echo $spons['link']; ?> -->
                                  <?php  if($spons['image']!='' ) {  ?>
                                  <img src="<?php echo $spons['image']; ?>" loading="lazy" width="100%" alt=""> 

                                <?php } else if($spons['image']!='' && $spons['product_image']!='' ) {  ?>
                                 <img src="<?php echo $spons['product_image'];?>" loading="lazy" width="100%" alt="">
                            <?php } else if($spons['product_image']!='') {?>
                               <img src="<?php echo $spons['product_image'];?>" loading="lazy" width="100%" alt="">
                            <?php } ?></a> 

                                <?php  } else if(is_user_logged_in() && $current_user->data->validate_email=='1' ){ ?>

                                   <a  href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"> <!--  <?php //echo $spons['link']; ?> -->
                                  <?php  if($spons['image']!='' ) {  ?>
                                  <img src="<?php echo $spons['image']; ?>" loading="lazy" width="100%" alt=""> 

                                <?php } else if($spons['image']!='' && $spons['product_image']!='' ) {  ?>
                                 <img src="<?php echo $spons['product_image'];?>" loading="lazy" width="100%" alt="">
                            <?php } else if($spons['product_image']!='') { ?>
                               <img src="<?php echo $spons['product_image'];?>" loading="lazy" width="100%" alt="">
                            <?php } ?></a> 

                                <?php     } else{ ?>
                                  <a  href="/login?redirect_to=bar"> <!--  <?php //echo $spons['link']; ?> -->
                                  <?php  if($spons['image']!='' ) {  ?>
                                  <img src="<?php echo $spons['image']; ?>" loading="lazy" width="100%" alt=""> 

                                <?php } else if($spons['image']!='' && $spons['product_image']!='' ) {  ?>
                                 <img src="<?php echo $spons['product_image'];?>" loading="lazy" width="100%" alt="">
                            <?php } else if($spons['product_image']!='') {?>
                               <img src="<?php echo $spons['product_image'];?>" loading="lazy" width="100%" alt="">
                            <?php } ?></a> 

                                  <?php  } ?>




                              
                                                         
                             
                          </div>
                        </div>                             
                      </div>
                      <div class="img-options">
                        <div class="options1 options <?php if($spons['is_liked'] == '1'){?>active<?php } ?>">
                                    <?php if(is_user_logged_in()){ ?>
                    <a href="javascript:void(0);" class="spons_like_timeline" id="like" liked="<?php echo $spons['is_liked'];?>" rid="<?php echo $spons['spons_id'];?>"> Like </a>
                    <?php }else{ ?>
                    <a href="/login?redirect_to=sponsmsg-<?php echo $spons['spons_id']?>" id="like"> Like </a>
                    <?php } ?>
                                </div>

                               
                                <div class="options2 options">
                                  <?php if (is_user_logged_in()) {
                                    $lin="javascript:void(0);";
                                  } else{
                                    $lin="/login?redirect_to=sponsmsg-".$spons['spons_id']."";
                                  } ?>
                                                                             <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                    <a href="javascript:void(0);"  id="comment" data-toggle="modal" data-backdrop="static" data-target="#openpopup"> Comment</a>

                                  <?php  }else{ ?>
                                    <a href="<?php echo $lin; ?>" class="replies_sponslist rlist_<?php echo $spons['spons_id'];?>" rid="<?php echo $spons['spons_id'];?>" id="comment"> Comment</a>
                                   <?php } ?>
                                                                      </div>
                                <div class="options3 options"> 
                                 <!--    added by sumeeth -->
                                    <a href="javascript:void(0);" class="copy-share-link" id="share" link="https://sipnbourbon.com/timeline_sponsads/?q=<?php echo $spons['spons_id']; ?>"> Share</a>
                                   <!--  <a href="javascript:void(0);" class="copy-share-link" id="share" link="https://sipnbourbon.com/timeline/?q=62958"> Share</a> -->
                                </div>
                            </div>

                            <div class="user-chat">
                            
                                <?php foreach($spons['replies'] as $sub_reply){?>
                                <div class="main-post"  id="msg-<?php echo $sub_reply['reply_id']; ?>" style="position: relative;">
                                <div class="get-msg">
                                    <div class="profile-pic">
                                        <a href="<?php if(is_user_logged_in()){  echo bbp_get_user_profile_url($sub_reply['author_id']); } else { echo "/login";} ?>"><img class="img-circle" src="<?php echo $sub_reply['avatar'];?>" alt="<?php echo $sub_reply['author'];?>" width="60" height="60"></a>
                                        <!--  added by sumeeth for bar link -->
                                        <div class="user-name sender-chat">
                                            <?php if(is_user_logged_in()){ ?>
                                            <a href="<?php echo bbp_get_user_profile_url($sub_reply['author_id']); ?>"><?php echo $sub_reply['author'];?></a>
                                            <?php } else { ?>
                                           <a href="/login"> <?php echo $sub_reply['author'];?></a>
                                            <?php } ?>
                                            <br><span class="user-msg rl-msg-text"><?php echo strip_tags($sub_reply['reply']);?></span><?php if($sub_reply['reply_image']){ ?>
                               <span class="upload-image"><img src="<?php echo $sub_reply['reply_image'];?>" width="100%" alt=""></span>
                            <?php } ?></div>
                                    </div>
                                </div>
                                <div class="msg-opt">
                                    <ul class="list-inline">
                                        <?php if(count($sub_reply['replies'])>0){?>
                                       <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                   
                                    <li class="list-item">
                                        <a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#openpopup"  ><?php if( $sub_reply['total_replies_count']>1) {   ?>Replies  <?php } else{ ?> Reply  <?php } ?> (<?php echo $sub_reply['total_replies_count'];?>) </a>

                                        .</li>

                                  <?php  }else if(is_user_logged_in() && $current_user->data->validate_email=='0'){ ?>
                                    <?php if(count($sub_reply['replies'])>0){?>
                                        <li class="list-item">
                                        <a href="javascript:void(0);" class="replies_sponslist rlist_<?php echo $sub_reply['reply_id'];?>" rid="<?php echo $sub_reply['reply_id'];?>" ><?php if( $sub_reply['total_replies_count']>1) {   ?>Replies  <?php } else{ ?> Reply  <?php } ?> (<?php echo $sub_reply['total_replies_count'];?>) </a>

                                        .</li>
                                        <?php } ?>
                                   <?php }else { ?>
                                      <li class="list-item">
                                      <a href="/login"  ><?php if( $sub_reply['total_replies_count']>1) {   ?>Replies  <?php } else{ ?> Reply  <?php } ?> (<?php echo $sub_reply['total_replies_count'];?>) </a>
                                       .</li>
                                   <?php } ?>

                                    <?php } else { ?>
                                   <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                   
                                    <li class="list-item"><a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#openpopup" ><span></span>Reply</a>.</li>

                                  <?php  }else if(is_user_logged_in() && $current_user->data->validate_email=='0'){?>
                                    <li class="list-item"><a href="javascript:void(0);" class="replies_sponslist rlist_<?php echo $sub_reply['reply_id'];?>" rid="<?php echo $sub_reply['reply_id'];?>"><span></span>Reply</a>.</li>
                                   <?php } else { ?>
                                    <li class="list-item"><a href="/login"  ><span></span>Reply</a>.</li>
                                   <?php } ?>
                                        
                                        
                                        <?php } ?>
                                        
                                        
                                        <?php if ( !current_user_can( 'edit_reply', $sub_reply['reply_id'] ) ) { ?>
                                        <li class="list-item">

                                          <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                 

                                    <a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"><span></span>Report</a>


                                  <?php  }else if(is_user_logged_in() && $current_user->data->validate_email=='0'){ ?>
                                   <a href="javascript:void(0);" class="report-tl-post" rid="<?php echo $sub_reply['reply_id'];?>" post_url="<?php echo $sub_reply['url'];?>"><span></span>Report</a>.
                                   <?php } else { ?>
                                      <a href="/login"><span></span>Report</a>
                                   <?php } ?>




                                          



                                        </li>
                                        <?php } ?>
                                        
                                    </ul>
                                </div>
                                 </div>
                                <?php } ?>
                                <div class="msg-replies-cnt" id="comment-<?php echo $spons['spons_id'];?>">
                                <span class="likecomment"><?php if(($spons['likes_count']!='0')) { echo $spons['likes_count']; echo "&nbsp";  if($spons['likes_count']>'1'){ echo "Likes&nbsp;&nbsp;";} else { echo "Like&nbsp;&nbsp;"; }    } ?>
                                   <?php if($spons['total_replies_count']>0) { ?> 

                                       <?php if (is_user_logged_in()) {
                                    $lin="javascript:void(0);";
                                  } else{
                                    $lin="/login?redirect_to=sponsmsg-".$spons['spons_id']."";
                                  } ?>


                                    <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                    <a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"> <?php echo $spons['total_replies_count'];?> <?php if( $spons['total_replies_count']>1) {   ?>Comments  <?php } else{ ?> Comment  <?php } ?> </a>

                                  <?php  }else{ ?>
                                    <a href="<?php echo $lin; ?>" class="replies_sponslist rlist_<?php echo $spons['spons_id'];?>" rid="<?php echo $spons['spons_id'];?>" ><?php echo $spons['total_replies_count'];?> <?php if( $spons['total_replies_count']>1) {   ?>Comments  <?php } else{ ?> Comment  <?php } ?> </a>
                                   <?php } ?>


                                   
                                    <?php } ?></span>
                                  <span class="timespan"><?php echo $spons['spons_date'];?></span>
                                </div>
                                
                          </div>
                     
                       
                       
                        </div>
                      <?php } ?> 
                        <?php
                        //get timeline messages
                        
                        $timeline_res = get_timeline_list('1', '10');
                       // echo "<pre>";print_r($timeline_res);exit;
                        $tmlcount=1;
                        $tmp=array();
                        foreach($timeline_res['replies'] as $reply){ 
                        ?>
                        <div class="inner-content" id="msg-<?php echo $reply['reply_id']?>">
                            <div class="user-feed">
                                <div class="user-profile">

                                <div class="dropdown">
                                            <!-- <button class="dropbtn">...</button> -->
                                            <img class="threedots" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/vertical-dots.png">
                                            <div class="dropdown-content">
                                                    <?php if ( current_user_can( 'edit_reply', $reply['reply_id'] ) ) { ?>
                                                    <a href="javascript:void(0);" class="edit-tl-post" rptitle="<?php echo $reply['product_title']; ?>" rimage="<?php  echo $reply['reply_image'];  ?>" rid="<?php echo $reply['reply_id'];?>" pid="<?php echo $reply['product_id'];?>" locid="<?php echo $reply['tagged_location'];?>"><span><i class="far fa-edit bar-edit"></i></span>Edit</a>
                                                    <a href="javascript:void(0);" class="delete-tl-post" rid="<?php echo $reply['reply_id'];?>"><span><i class="fa fa-trash"></i></span>Delete</a>
                                                    <?php } ?>
                                                    <!--   <a href="javascript:void(0);" class="report-tl-post" rid="<?php echo $reply['reply_id'];?>"post_url="<?php echo $reply['url'];?>"><span><i class="fa fa-exclamation-circle"></i></span>Report</a> -->
                                                        <!--    added by sumeeth -->
                                                    <?php if (is_user_logged_in() && !current_user_can( 'edit_reply', $reply['reply_id'] ) ) { ?>


                                                      <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                 

                                    <a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>


                                  <?php  }else{ ?>
                                    <a href="javascript:void(0);" class="report-tl-post" rid="<?php echo $reply['reply_id'];?>"post_url="<?php echo $reply['url'];?>"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>
                                   <?php } ?>



                                            
                                                       



                                                        <?php }else if(!is_user_logged_in()){ ?>

                                                          <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                 

                                    <a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>


                                  <?php  }else{ ?>
                                    <a href="/login?redirect_to=msg-<?php echo $reply['reply_id']?>" class="report-tl-post" rid="<?php echo $reply['reply_id'];?>"post_url="<?php echo $reply['url'];?>"><span><i class="fa fa-exclamation-circle"></i></span>Report</a>
                                   <?php } ?>

                                                            
                                                            
                                                        <?php } ?>
                                                    <!--    added by sumeeth -->
                                            </div>
                                        </div>


                                    <div class="profile-in">
                                        <div class="profile-pic">
                                            <a href="<?php if(is_user_logged_in()){  echo bbp_get_user_profile_url($reply['author_id']); } else { echo "/login"; } ?>"><img loading="lazy" src="<?php echo $reply['avatar'];?>" width="60" height="60"></a>
                                         <!--    added by sumeeth -->
                                            <div class="user-name">
                                                <?php if(is_user_logged_in()){ ?>
                                            <a href="<?php echo bbp_get_user_profile_url($reply['author_id']); ?>">  <?php echo $reply['author'];?></a>
                                            <?php } else {?>
                                           <a href="/login"><?php echo $reply['author']; } ?></a>
                                            <br>
                                                <?php if($reply['product_title'] !="Top Bourbons for Father's Day") { 
                                                    $the_product = wc_get_product($reply['product_id']); ?>
                                                <span class="sumss"><a href="<?php echo get_permalink($reply['product_id']);?>" title="<?php echo $reply['product_title'];?>"><?php echo $reply['product_title'];?></a></span><br>

                                                <div><a href="<?php echo esc_url( add_query_arg( array('prod_id' => $the_product->sku, 'prid' => $the_product->id), site_url( '/buy-now/' ) ) )?>" class="buynow post-buynow"><button class="search">Buy Now</button></a></div>
                                                    <?php } else {  ?>

                                                    <?php }  ?>
                                                      <span class="sumloc">
                                                 <?php echo $reply['tagged_location'];  ?>
                                                </span>
                                              </div>
                                            <!-- <div class="user-name"> <?php //echo $reply['author'];?><br><span><?php //echo $reply['reply_date'];?></span></div> -->
                                        </div>
                                        
                                        
                                    </div>
                                    <div class="user-msg">
                                        
       
                                                  <?php  echo $reply['reply']; ?>  
                                        
                                    </div>
                                </div>
                            </div>
                             <div class="upload-image  <?php if( ($reply['reply_image']=='' && $reply['product_image']!='')    ) { echo "imgpro"; } ?>" <?php if($reply['product_image']!='') { ?> oncontextmenu="return false;" <?php } ?> >
                              

                            <?php if($reply['reply_image']!=''){ ?>
                                  <div class="section">
                                  <div class="slideshow-container">

                            <?php if($reply['reply_image']){ $a=$reply['reply_image']; $b=explode(',', $a); $coui=count($b);  foreach ($b as $key => $value) { ?>
                              
                                    <div class="mySlides">
                                <a href="javascript:void(0);"><img src="<?php echo $value;?>" loading="lazy" width="100%" alt=""> </a> </div> <?php  }  ?>
                               
                               <a class="prev" onclick="slide[<?php echo $tmlcount; ?>].plusSlides(-1)" <?php if ($coui==1) { ?>
                               style="display: none;"
                           <?php  } ?> >❮</a>
                              <a class="next" onclick="slide[<?php echo $tmlcount; ?>].plusSlides(1)" <?php if ($coui==1) { ?>
                               style="display: none;"
                           <?php  } ?> >❯</a>
                              <?php  $tmp[]=$tmlcount; ?>
                           
                             <div class="dot-container" <?php if ($coui==1) { ?>
                               style="display: none;"
                           <?php  } ?> >
                                <?php if($reply['reply_image']){ $a=$reply['reply_image']; $b=explode(',', $a); $imgcnt=1;  foreach ($b as $key => $value) { ?> 
                                    
                                     <span class="dot" onclick="slide[<?php echo $tmlcount; ?>].currentSlide(<?php echo $imgcnt; ?>)"></span>
                                    <?php $imgcnt++; } } ?>
                             
                            </div>

                            
                       


                            <?php } ?>
                             </div>
                        </div> <?php  } else if($reply['reply_image']!='' && $reply['product_image']!='' ) {  ?>
                                 <a href="javascript:void(0);"><img loading="lazy" src="<?php echo $reply['product_image'];?>" width="100%" alt=""></a>
                            <?php } else if($reply['product_image']!='') {?>
                                <a href="javascript:void(0);"><img loading="lazy" src="<?php echo $reply['product_image'];?>" width="100%" alt=""></a>
                            <?php } ?>
                            
                            
                      </div>
                            
                            <div class="img-options">
                                <div class="options1 options <?php if($reply['is_liked'] == '1'){?>active<?php } ?>">
                                    <?php if(is_user_logged_in()){ ?>
                    <a href="javascript:void(0);" class="like_timeline" id="like" liked="<?php echo $reply['is_liked'];?>" rid="<?php echo $reply['reply_id'];?>"> Like </a>
                    <?php }else{ ?>
                    <a href="/login?redirect_to=msg-<?php echo $reply['reply_id']?>" id="like"> Like </a>
                    <?php } ?>
                                </div>
                                <div class="options2 options">
                                  <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                    
                                    
                                     <a href="javascript:void(0);" id="comment" data-toggle="modal" data-backdrop="static" data-target="#openpopup" > Comment</a>

                                     


                                  <?php  } else{ ?>
                                    <?php if(is_user_logged_in()){?>
                                     <a href="javascript:void(0);" id="comment" class="replies_list rlist_<?php echo $reply['reply_id'];?>" rid="<?php echo $reply['reply_id'];?>"> Comment</a>

                                      <?php }else{ ?>
                                        <a href="/login?redirect_to=msg-<?php echo $reply['reply_id']?>" id="comment"> Comment</a>
                                      <?php } ?>

                                  <?php } ?>
                                     
                                </div>
                                <div class="options3 options"> 
                                 <!--    added by sumeeth -->
                                    <a href="javascript:void(0);" class="copy-share-link"  id="share" link="<?php echo site_url();?>/timeline/?q=<?php echo $reply['reply_id'];?>"> Share</a>
                                   <!--  <a href="javascript:void(0);" class="copy-share-link" id="share" link="<?php echo site_url();?>/timeline/?q=<?php echo $reply['reply_id'];?>"> Share</a> -->
                                </div>
                            </div>

                            
                            <div class="user-chat">
                            
                                <?php foreach($reply['replies'] as $sub_reply){?>
                                <div class="main-post"  id="msg-<?php echo $sub_reply['reply_id']; ?>" style="position: relative;">
                                <div class="get-msg">
                                    <div class="profile-pic">
                                        <a href="<?php if(is_user_logged_in()){  echo bbp_get_user_profile_url($sub_reply['author_id']); } else { echo "/login";} ?>"><img class="img-circle" src="<?php echo $sub_reply['avatar'];?>" alt="<?php echo $sub_reply['author'];?>" width="60" height="60"></a>
                                        <!--  added by sumeeth for bar link -->
                                        <div class="user-name sender-chat">
                                            <?php if(is_user_logged_in()){ ?>
                                            <a href="<?php echo bbp_get_user_profile_url($sub_reply['author_id']); ?>"><?php echo $sub_reply['author'];?></a>
                                            <?php } else { ?>
                                           <a href="/login"> <?php echo $sub_reply['author'];?></a>
                                            <?php } ?>
                                            <br><span class="user-msg rl-msg-text"><?php echo $sub_reply['reply'];?></span><?php if($sub_reply['reply_image']){ ?>
                               <span class="upload-image"><img loading="lazy" src="<?php echo $sub_reply['reply_image'];?>" width="100%" alt=""></span>
                            <?php } ?></div>
                                    </div>
                                </div>
                                <div class="msg-opt">
                                    <ul class="list-inline">
                                        <?php if(count($sub_reply['replies'])>0){?>
                                        <li class="list-item"><!--<a href="javascript:void(0);" class="list-tl-replies sub_replies_<?php //echo $sub_reply['reply_id'];?>" rid="<?php echo $sub_reply['reply_id'];?>"><span></span>Replies (<?php //echo count($sub_reply['replies']);?>)</a>-->

                                          <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>


                                    <a href="javascript:void(0);" data-toggle="modal" data-backdrop="static" data-target="#openpopup" ><?php if( $sub_reply['total_replies_count']>1) {   ?>Replies  <?php } else{ ?> Reply  <?php } ?> (<?php echo $sub_reply['total_replies_count'];?>) </a>



                                  <?php  }else if(is_user_logged_in() && $current_user->data->validate_email=='0'){ ?>
                                   <a href="javascript:void(0);" class="replies_list rlist_<?php echo $sub_reply['reply_id'];?>" rid="<?php echo $sub_reply['reply_id'];?>" ><?php if( $sub_reply['total_replies_count']>1) {   ?>Replies  <?php } else{ ?> Reply  <?php } ?> (<?php echo $sub_reply['total_replies_count'];?>) </a>
                                   <?php } else { ?>
                                    <a href="/login" ><?php if( $sub_reply['total_replies_count']>1) {   ?>Replies  <?php } else{ ?> Reply  <?php } ?> (<?php echo $sub_reply['total_replies_count'];?>) </a>
                                   <?php } ?>

                                        

                                        .</li>
                                        <?php } else { ?>


                                         
                                        
                                        
                                        <li class="list-item">
                                           <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                    <a href="javascript:void(0);"   data-toggle="modal" data-backdrop="static" data-target="#openpopup"> Reply</a>

                                  <?php  }else if(is_user_logged_in() && $current_user->data->validate_email=='0'){ ?>
                                    <a href="javascript:void(0);" class="replies_list rlist_<?php echo $sub_reply['reply_id'];?>" rid="<?php echo $sub_reply['reply_id'];?>"><span></span>Reply</a>
                                   <?php } else{ ?>
                                     <a href="/login" > Reply</a>
                                   <?php } ?>



                                          


                                        .</li>
                                      <?php } ?>
                                        
                                        <?php if ( !current_user_can( 'edit_reply', $sub_reply['reply_id'] ) ) { ?>
                                        <li class="list-item">


                                          <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                 

                                    <a href="javascript:void(0);"  data-toggle="modal" data-backdrop="static" data-target="#openpopup"><span></span>Report</a>


                                  <?php  }else if(is_user_logged_in() && $current_user->data->validate_email=='0'){ ?>
                                    <a href="javascript:void(0);" class="report-tl-post" rid="<?php echo $sub_reply['reply_id'];?>" post_url="<?php echo $sub_reply['url'];?>"><span></span>Report</a>
                                   <?php } else{ ?>
                                      <a href="/login" ><span></span>Report</a>
                                   <?php } ?>


                                         





                                        .</li>
                                        <?php } ?>
                                    <!--    <?php //if ( current_user_can( 'edit_reply', $reply['reply_id'] ) ) { ?>
                                        <li class="list-item"><a href="javascript:void(0);"  class="edit-tl-comment" rimagecomment="<?php // echo $sub_reply['reply_image'];  ?>" rid="<?php //echo $sub_reply['reply_id'];?>"><span></span>Edit</a>.</li>
                                        <li class="list-item"><a href="javascript:void(0);"  class="delete-tl-post" rid="<?php //echo $sub_reply['reply_id'];?>"><span></span>Delete</a>.</li>
                                        <?php //} ?> by sumeeth -->
                                        
                                        <li class="list-item">Commented <?php echo $sub_reply['reply_date'];?></li>
                                        
                                    </ul>
                                </div>
                                 </div>
                                <?php } ?>
                                <div class="msg-replies-cnt" id="comment-<?php echo $reply['reply_id'];?>">
                                <span class="likecomment"><?php if(($reply['likes']!='0')) { echo $reply['likes']; echo "&nbsp";  if($reply['likes']>'1'){ echo "Likes&nbsp;&nbsp;";} else { echo "Like&nbsp;&nbsp;"; }    } ?>
                                 <?php if (is_user_logged_in()) {
                                    $lin="javascript:void(0);";
                                  } else{
                                    $lin="/login?redirect_to=msg-".$reply['reply_id']."";
                                  } ?>
                           <?php if($reply['total_replies_count']>0) { ?>


                            <?php if(is_user_logged_in() && $current_user->data->validate_email=='1'){ ?>

                                   
                                     <a href="javascript:void(0);"   data-toggle="modal" data-backdrop="static" data-target="#openpopup"  ><?php echo $reply['total_replies_count'];?> <?php if( $reply['total_replies_count']>1) {   ?>Comments  <?php } else{ ?> Comment  <?php } ?> </a>

                                  <?php  }else{ ?>
                                     <a href="<?php echo $lin; ?>" class="replies_list rlist_<?php echo $reply['reply_id'];?>" rid="<?php echo $reply['reply_id'];?>" ><?php echo $reply['total_replies_count'];?> <?php if( $reply['total_replies_count']>1) {   ?>Comments  <?php } else{ ?> Comment  <?php } ?> </a>
                                   <?php } ?>


                           
                            <?php } ?></span>
                            <span class="timespan"><?php echo $reply['reply_date'];?></span>
                        </div>
                                <?php /*if(is_user_logged_in()) { 
                                
                                ?>
                                <div class="msg-opt-in" >
                                    <div class="profile-pic">
                                        <a href="#"><img class="img-circle" src="<?php echo $cur_user_avatar;?>" alt="nature-image" width="60" height="60"></a>

                                    </div>
                                    <div class="more-user-info">
                                        <!-- <input type="text" placeholder="Write a public comment..."> -->
                                        <textarea placeholder="Write a public comment..." class="text-area" id="comment_<?php echo $reply['reply_id'];?>"></textarea>
                                        <div class="emojis">
                                            <!--<a href="#"><img src="<?php //echo get_stylesheet_directory_uri();?>/assets/images/smile.png" alt="smile-emoji" width="22" height="22"></a>
                                            <a href="#"><img src="<?php //echo get_stylesheet_directory_uri();?>/assets/images/icon-camera.png" alt="camera-icon" width="22" height="22"></a>-->
                                            <div class="inputWrapper1">
                                            <input accept="image/*" capture="camera" class="fileInput commentInput" rid="<?php echo $reply['reply_id'];?>" name="pImage" type="file"><span class="fa fa-camera"></span>
                                            <input type="hidden" class="cimg" id="comment_img_<?php echo $reply['reply_id'];?>" value="">
                                            </div>
                                            <div class="submitWrapper" rid="<?php echo $reply['reply_id'];?>">
                                            <span class="fa fa-arrow-circle-right"></span>
                                            </div>
                                            <!--<a href="#"><img src="<?php //echo get_stylesheet_directory_uri();?>/assets/images/gif.png" alt="camera-icon" width="22" height="22"></a>
                                            <a href="#"><img src="<?php //echo get_stylesheet_directory_uri();?>/assets/images/page.png" alt="camera-icon" width="22" height="22"></a>-->
                                        </div>
                                    </div>
                                </div>
                                <?php }*/ ?>
                            </div>
                       
                       
                       
                       
                       
                        </div>
                        
                        <?php $tmlcount++; } ?>
                        
                    </div>
                    <input type="hidden" name="totalcountim[]" id="totalcountim" value="<?php echo implode(',', $tmp); ?>">
                    <div class="col-sm-5 right-slider">        
                        <div class="side-content  right-side-bar">
                            <div class="inner-right-slider">        
                                
                                    
                                        <?php
                                            $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                                            $args = [
                                                'post_type' => 'product',
                                                'post_status' => 'publish',
                                                'order' => 'ASC',
                                                'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
                                                    'relation' => 'AND',
                                                    array(
                                                        'taxonomy' => 'product_visibility',
                                                        'field'    => 'term_taxonomy_id',
                                                        'terms'    => array( $product_visibility_term_ids['featured'] ),
                                                    ),
                                                    array(
                                                        'taxonomy' => 'product_visibility',
                                                        'field'    => 'term_taxonomy_id',
                                                        'terms'    => array( $product_visibility_term_ids['exclude-from-catalog'] ),
                                                        'operator' => 'NOT IN',
                                                    ),
                                                    ),
                                                    'posts_per_page' => -1
                                                ];
                                                
                                                

                                            $products = get_posts($args);
                                                        ?>
                                                    <div class="trending">
                                                        <h2>TRENDING</h2>
                                                    </div>
                                                    <div class="tcb-product-slider">
                                                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                                            <!-- Wrapper for slides -->
                                                            <div class="carousel-inner" role="listbox">
                                                                <?php
                                                                if(count($products)>0){
                                                                $cnt = 0;
                                                                foreach($products as $product){
                                                                $the_product = wc_get_product( $product->ID );
                                                                $prod_url = get_the_post_thumbnail_url( $product->ID, 'full' );
                                                                ?>
                                                                <?php if($cnt != 0 && $cnt%3 == 0){ ?>
                                                                </div>
                                                                </div>
                                                                <?php } ?>
                                                                <?php if($cnt == 0 || $cnt%3 == 0){ ?>
                                                                <div class="item <?php if($cnt==0){ echo 'active';}?>">
                                                                    <div class="row" style="margin:0;">
                                                                <?php } ?>
                                                                <div class="col-xs-4  col-xl-4 grow" style="padding-left: 0;padding-right: 8px;">
                                                                    <div class="tcb-product-item">
                                                                        <div class="tcb-product-info">
                                                                            <div class="tcb-product-title">
                                                                                <h4><a href="<?php echo get_permalink($product->ID);?>" title="<?php echo $product->post_title;?>"><?php echo $product->post_title;?></a></h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tcb-product-photo">
                                                                        <a href="<?php echo get_permalink($product->ID);?>"><img loading="lazy" src="<?php echo $prod_url;?>" alt="<?php echo $product->post_title;?>" width="100" height="100" class="img-responsive"></a>
                                                                    <!-- added by sumeeth -->
                                                    <!--  <div class="price">$<?php //echo $the_product->price;?></div>
                                                        <div class="rating"><ul>
                                                        <?php //for($i=1; $i<=round($the_product->average_rating);$i++){ ?>
                                                            <li><img src="<?php //echo get_stylesheet_directory_uri();?>/assets/images/rating-after.png"></li>
                                                            <?php //} ?>
                                                            <?php //for($j=1; $j<=5-round($the_product->average_rating);$j++){ ?>
                                                            <li><img src="<?php //echo get_stylesheet_directory_uri();?>/assets/images/rating-before.png"></li>
                                                            <?php// } ?>
                                                            </ul>
                                                        </div> -->
                                                        <!-- added by sumeeth -->
                                                                    </div>
                                                                
                                                                </div>
                                                                        
                                                                <?php $cnt++;}}else{ echo "No products found.";} ?> 
                                                                    </div>
                                                                </div>
                                                            
                                                                
                                                            </div>
                                                            <div class="view-all">
                                                                 <a href="/featured-products">View all</a>
                                                            </div>
                                                       
                                                        <!-- Controls -->
                                                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>

                                                     </div>
                                                     <div class="clearfix"></div>
                                                <?php
                                                    global $wpdb;
                                                    //$pageID = get_option('page_on_front');
                                                    $pageID='1354';
                                                    $home_videos = get_post_meta($pageID, 'videos');
                                                    $home_videos_arr = array_filter(explode( "\n", str_replace( "\r", "", $home_videos[0] ) ));
                                                ?>
                                          

                                         </div>
                                        <div class="clearfix"></div>
<!-- Videos Events Copyright -->

<div class=" bar-block video homepage-video-section">
                                                    <div class="row">
                                                       <!--  <div class="slideshow-container"> -->
                                                          <div id="myCarousel1" class="carousel slide">
                                                            <div class="carousel-inner">
                                                            <?php
                                                            $v_cnt = 0;

                                                            foreach($home_videos_arr as $home_video){
                                                            if($home_video != ''){
                                                            $video_dets = explode('|', $home_video);
                                                            ?>
                                                                                <div class="<?php if($v_cnt==0){ echo 'active';}?> item">
                                                                                    <iframe loading="lazy" src="<?php echo $video_dets[0].'&autopause=0&mute=1';?>" allow="autoplay" allowfullscreen width="420" height="280">
                                                                                      
                                                                                   </iframe>
                                                                                </div>
                                                            <?php $v_cnt++;}} ?>
                                                            </div>
                                                             <!-- <a class="carousel-control left" href="#myCarousel1" onclick="callPlayer('current','pauseVideo')" data-slide="prev">&lsaquo;</a>
                                                                <a class="carousel-control right" href="#myCarousel1" onclick="callPlayer('current','pauseVideo')" data-slide="next">&rsaquo;</a> -->
                                                                <ul class="carousel-indicators video-dot">
                                                                  <?php
                                                            $v_cnt1 = 0;
                                                            foreach($home_videos_arr as $home_video){ if($home_video != ''){ ?>
                                                                <li data-target="#myCarousel1" data-slide-to="<?php echo $v_cnt1;?>" class="<?php if($v_cnt1==0){ echo 'active';}?>"></li>
                                                                <?php $v_cnt1++;}} ?>
                                                                
                                                              </ul>
                                                              </div>
                                                        <!-- </div> -->

                                                        
                                                                            <br>

                                                        <div style="text-align:center;display: none;">
                                                            <?php for($i=0;$i<=$v_cnt;$i++){?>
                                                                                <span class="dot"></span>
                                                            <?php } ?>
                                       
                                                        </div>
                                                                            <div class="clearfix"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="clearfix"></div>
                                            <div class="col-md-12 events">
                                                <a href="/sipn-bourbon-events"><img loading="lazy" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/img-events.png" height="100%" width="100%"></a>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                             <input type="hidden" value="sipn,sipn bourbon,bourbons,blanton's bourbon,bulleit bourbon,weller bourbon,eagle rear bourbon,buy bourbon,liquor store,buy liquor,best bourbon,buy bourbon online,rare bourbon,bourbon store,bourbon liquor,best bourbon whiskey">
                                            <div class="col-md-12 allrights">
                                                                &copy; Sipn Bourbon 2021-<?php echo date('Y'); ?>. All Rights Reserved.<br>Powered By <a href="http://www.bottlecapps.com" target="_blank"><img loading="lazy" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/logo-poweredby.png"> </a>
                                                                <div class="clearfix"></div>
                                            </div>
                                            <p style="display: none;"><a href="https://sipnbourbon.com/sitemap" >sitemap</a></p>





     <link rel="stylesheet"  href="<?php echo get_stylesheet_directory_uri();?>/assets/css/bootstrapnew.min.css" type="text/css">                                  
     <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/bootstrapnew.min.js"></script>                           
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Tqc2j-Mft6DYnShazAFD5QQdMvhNKpk&callback=initAutocomplete&libraries=places&v=weekly" async ></script>
  <!--   <script src="https://player.vimeo.com/api/player.js"></script> -->
    <script type="text/javascript">
     
      
      
var slide=[];

var str=$('#totalcountim').val();

var str_array = str.split(',');
//alert(str_array);
//alert(str_array.length);
for(var s = 0; s < str_array.length; s++) {
 // alert(s);
 // alert(str_array[s]);
   // Trim the excess whitespace.
  
   // Add additional code here, such as:
 slide[str_array[s]] = new CreateSlide(s);
   // console.log( 'var slide'+[str_array[s]]+'=new CreateSlide('+s+')');
   
   
}
// for (var i = 0; i < 11; i++) {

// var j=i+1;
//  slide[j] = new CreateSlide(i);
 
// }
      
// var slide1 = new CreateSlide(0);
//  var slide2 = new CreateSlide(1);
// var slide3 = new CreateSlide(2);
// var slide4 = new CreateSlide(3);
// var slide5 = new CreateSlide(4);
// var slide6 = new CreateSlide(5);
// var slide7 = new CreateSlide(6);
// var slide8 = new CreateSlide(7);
// var slide9 = new CreateSlide(8);
//  var slide10 = new CreateSlide(9);


function CreateSlide(index) {
    this.slideContainer = document.getElementsByClassName("section")[index];
    this.slideIndex = 1;
  //  console.log(this.slideContainer);
    this.plusSlides = function(n) {
        this.showSlides(this.slideIndex += n);
    };
    this.currentSlide = function(n) {
        this.showSlides(this.slideIndex = n);
    };
    this.showSlides = function(n) {
        var i;
        var slides = this.slideContainer.getElementsByClassName("mySlides");
       // alert(slides.length);
        var dots = this.slideContainer.getElementsByClassName("dot");
        if (n > slides.length) {
            this.slideIndex = 1
        }
        if (n < 1) {
            this.slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[this.slideIndex - 1].style.display = "block";
        dots[this.slideIndex - 1].className += " active";
    }
    this.showSlides(1);
}
    </script>
                            <script>

                             

                            /* AUTOPLAY NAV HIGHLIGHT */

// bind 'slid' function
$('#myCarousel1').bind('slid', function() {

    // remove active class
    $('.carousel-linked-nav .active').removeClass('active');

    // get index of currently active item
    var idx = $('#myCarousel1 .item.active').index();

    // select currently active item and add active class
    $('.carousel-linked-nav li:eq(' + idx + ')').addClass('active');

});

//Youtube
$(function ($) {
  $('div.carousel-inner div.active').attr('id', 'current');
});

function callPlayer(frame_id, func, args='') {
  //alert(func);
  if (window.$ && frame_id instanceof $) frame_id = frame_id.get(0).id;
//alert(frame_id);
    var iframe = document.getElementById(frame_id);

    if (iframe && iframe.tagName.toUpperCase() != 'IFRAME') {

        iframe = iframe.getElementsByTagName('iframe')[0];
       
       iframe='[object HTMLIFrameElement]';
    }
    //alert(iframe);
    if (iframe) {
     //alert(func);
        iframe.contentWindow.postMessage(JSON.stringify({
            "event": "command",
            "func": func,
            "args": args || [],
            "id": frame_id
        }), "*");
    }

    jQuery(function ($) {
      $('div.carousel-inner div.item').attr('id', '');
      $('div.carousel-inner div.active').attr('id', 'current');
  });
}
                              
                                         localStorage.setItem('timeline_page',1);
                                            jQuery('.videoSlide a').colorbox({"allow":"autoplay",
                                                onLoad:function(){
                                                    var iframe = document.querySelector('iframe');
                                                    //iframe.setAttributeNode("allow","autoplay");
                                                },
                                                onComplete:function(){
                                                    var iframe = document.querySelector('iframe');
                                                    //iframe.setAttributeNode("allow","autoplay");
                                                    var player = new Vimeo.Player(iframe);
                                                    document.querySelector('body').click();
                                                    player.play();
                                                    player.play().then(function() {
                                                    // The video is playing
                                                    }).catch(function(error) {
                                                    switch (error.name) {
                                                        case 'PasswordError':
                                                            // The video is password-protected
                                                            break;

                                                        case 'PrivacyError':
                                                            // The video is private
                                                            break;

                                                        default:
                                                            // Some other error occurred
                                                            break;
                                                    }
                                                    });
                                                    player.on('play', function() {
                                                    console.log('Played the video');
                                                    });
                                                }
                                            });
                            </script>

                            </script>
                            <script>

                                    // var icon = document.querySelector(".fa-search");
                                    //         var search = document.querySelector('#header-search');
                                    //         var form = document.querySelector('.form'); 
                                    //         icon.onclick = function() {
                                    //             search.classList.toggle('active')
                                    //             form.classList.toggle('active')

                                    //         }

                                    //         var mob_icon = document.querySelector(".fa-mob");
                                    //         var mob_search = document.querySelector('#mob_header_search');
                                    //         var mob_form = document.querySelector('.mob_form'); 
                                    //         mob_icon.onclick = function() {
                                    //             mob_search.classList.toggle('active')
                                    //             mob_form.classList.toggle('active')

                                    //         }


                                    //     var button = document.querySelector('#nav-icon4');
                                    //     button.addEventListener('click', function(){
                                    //         document.querySelector('.left-slide-bar').classList.toggle('open-menu');
                                    //         button.classList.toggle('open');
                                            
                                    //     });
                                            // added by sumeeth 
                                        $(document).ready(function () {
                                        $('body').on('click', '.copy-share-link', function () {
                                            var copied_url = $(this).attr('link')
                                            copyFormatted(copied_url);
                                            $(this).append('<span class="copiedee">Link copied to clipboard.</span>');
                                            
                                            setTimeout(function() {
                                                $(".copiedee").remove();
                                            }, 2000); 
                                        });

                                         if(localStorage.getItem('popState') =='shown'){
                                            $("#popup").attr('style',  'display:none !important');
                                            
                                        }
                                        $('body').on('click', '#popup', function () {
                                       
                                            $("#popup").attr('style',  'display:none !important');// Now the pop up is hidden.
                                        });
                                     $('body').on('click', '.closeinstall', function () {
                                       
                                          //alert('hi');
                                          localStorage.setItem('popState','shown')
                                            $("#popup").attr('style',  'display:none !important'); // Now the pop up is hidden.
                                        });

                                        });
                                        // added by sumeeth 
                                        //   close.addEventListener('click', function(){
                                        //      document.querySelector('.open').classList.remove('active');
                                        //       bar2.style.display = "none";

                                        //   })

                            </script>

                        <script>
                            // Get the modal
                                                var modal = document.getElementById("myModal");
                                                var modal2 = document.getElementById("editModal");
                                                var modal3 = document.getElementById("replyModal");
                                                var modal4 = document.getElementById("repliesModal");
                                                var modal5 = document.getElementById("reportModal");
                                                var modal6 = document.getElementById("editModal1");
                                                var modal7 = document.getElementById("repliesModal1");
                                                var modal8 = document.getElementById("sponsoredModal"); 
                                                var modal9 = document.getElementById("editsponsModal1");
                                                var modal10 = document.getElementById("repliesModal2");
                                                var modal11 = document.getElementById("editsponsModal2");
                                                //var modal12 = document.getElementById("openpopup");
                                                
                                                // Get the button that opens the modal
                                                var btn = document.getElementById("myBtn");
                                                
                                                // Get the <span> element that closes the modal
                                                var span = document.getElementsByClassName("close")[0];
                                                var span2 = document.getElementsByClassName("close")[1];
                                                var span3 = document.getElementsByClassName("close")[2];
                                                var span4 = document.getElementsByClassName("close")[3];
                                                var span5 = document.getElementsByClassName("close")[4];
                                                var span6 = document.getElementsByClassName("close")[5];
                                                var span7 = document.getElementsByClassName("close")[6];
                                                var span8 = document.getElementsByClassName("close")[7];
                                                var span9 = document.getElementsByClassName("close")[8];
                                                var span10 = document.getElementsByClassName("close")[9];
                                                var span11 = document.getElementsByClassName("close")[10];
                                               // var span12 = document.getElementsByClassName("close")[11];
                                                
                                                // When the user clicks the button, open the modal 
                                                // btn.onclick = function() {
                                                // modal.style.display = "block";
                                                // }
                                                
                                                // When the user clicks on <span> (x), close the modal
                                                span.onclick = function() {
                                                modal.style.display = "none";
                                                }
                                                span2.onclick = function() {
                                                modal2.style.display = "none";
                                                }
                                                span3.onclick = function() {
                                                modal3.style.display = "none";
                                                }
                                                span4.onclick = function() {
                                                modal4.style.display = "none";
                                                }
                                                span5.onclick = function() {
                                                modal5.style.display = "none";
                                                }
                                                span6.onclick = function() {
                                                modal6.style.display = "none";
                                                }
                                                span7.onclick = function() {
                                                  modal7.style.display = "none";
                                                }
                                                span8.onclick = function() {
                                                  modal8.style.display = "none";
                                                }
                                                span9.onclick = function() {
                                                  modal9.style.display = "none";
                                                }
                                                span10.onclick = function() {
                                                  modal10.style.display = "none";
                                                }
                                                span11.onclick = function() {
                                                  modal11.style.display = "none";
                                                }
                                                // span12.onclick = function() {
                                                //   modal12.style.display = "none";
                                                // }
                                                
                                                // When the user clicks anywhere outside of the modal, close it
                                                window.onclick = function(event) {
                                                if (event.target == modal) {
                                                    modal.style.display = "none";
                                                    modal2.style.display = "none";
                                                    modal3.style.display = "none";
                                                    modal4.style.display = "none";
                                                    modal5.style.display = "none";
                                                    modal6.style.display = "none";
                                                    modal7.style.display = "none";
                                                    modal8.style.display = "none";
                                                    modal9.style.display = "none";
                                                    modal10.style.display = "none";
                                                    modal11.style.display = "none";
                                                   // modal12.style.display = "none";
                                                }
                                                }


                                            //added by sumeeth 
                                            // function readURL(input) {
                                            // if (input.files && input.files[0]) {
                                            //     var reader = new FileReader();

                                            //     reader.onload = function (e) {
                                            //     //  alert(e.target.result);
                                            //     $('#blah').attr('src', e.target.result).width(100).height(100);
                                            //     };

                                            //     reader.readAsDataURL(input.files[0]);
                                            //    $('#blah').show();
                                            //    $('.edicloseimage2').show();
                                            //    $('#addimage').show();
                                            //    $('.post').prop('disabled', false);
                                            //    $('.post').removeClass('colorbttn');
                                            //    $('#tagproduct').prop('disabled', false); //for tag a product
                                            //     $('#tagproduct').removeClass('colorbttn'); //for tag a product
                                            // }
                                            // }




                                        function readURL(input) {
                                              $('#addimage').html('');
                                              $('#mulimg').html('');
                                              if(input.length>3) {
                                                      alert("please upload only 3 images");
                                                   $('.post').prop('disabled', true);
                                                  $('.post').addClass('colorbttn');
                                              window.location.href='https://sipnbourbon.com/login/?redirect_to=msg-12345';
                                                  } else{
                                                     $('.post').prop('disabled', false);
                                               $('.post').removeClass('colorbttn');
                                               // var reader = new FileReader();

                                                

                                                var preview = document.getElementById("addimage");
                                                 var fileInput = document.querySelector("input[type=file]");
                                                  $j=0;
                                                 for (var i = 0; i < fileInput.files.length; i++) {
                                                     var reader = new FileReader();
                                                     reader.onload = function(readerEvent) {
                                                         var listItem = document.createElement("li");
                                                         //alert(readerEvent.target.result);
                                                         listItem.innerHTML = "<img src='" + readerEvent.target.result + "'  style='height:100%;width:100%;' />";

                                                         $('#mulimg').append("<input type='hidden' id='img"+$j+"' value='" + readerEvent.target.result + "' >");
                                                         $j++;
                                                         preview.append(listItem);
                                                     }
                                                     reader.readAsDataURL(fileInput.files[i]);
                                                 }

                                               $('.edicloseimage2').show();
                                               $('#addimage').show();
                                               $('.post').prop('disabled', false);
                                               $('.post').removeClass('colorbttn');
                                               $('#tagproduct').prop('disabled', false); //for tag a product
                                                $('#tagproduct').removeClass('colorbttn'); //for tag a product

                                                $('.taglocpost').prop('disabled', false); //for tag a location
                                                $('.taglocpost').removeClass('colorbttn'); //for tag a location

                                                  }
                                            
                                            }

                                               function readURL2(input) {
                                                //alert('hi');
                                              $('#addeimage').html('');
                                              $('#mulimg1').html('');
                                            if(input.length>3) {
                                                      alert("please upload only 3 images");
                                                   $('.post').prop('disabled', true);
                                                  $('.post').addClass('colorbttn');
                                                  location.reload();
                                                  } else{
                                                     $('.post').prop('disabled', false);
                                               $('.post').removeClass('colorbttn');
                                               var preview1 = document.getElementById("addeimage");
                                                 var fileInput1 = document.querySelector("input[type=file]");
                                                  $j=0;
                                                 for (var i = 0; i < input.length; i++) {
                                                     var reader = new FileReader();
                                                     reader.onload = function(readerEvent) {
                                                         //var listItem = document.createElement("span");
                                                         var listItem = document.createElement("li");
                                                        // alert(readerEvent.target.result);
                                                         listItem.innerHTML = "<img src='" + readerEvent.target.result + "'  style='height:30%;width:30%;' />";

                                                         $('#mulimg1').append("<input type='hidden' id='img1"+$j+"' value='" + readerEvent.target.result + "' >");
                                                         $j++;
                                                         preview1.append(listItem);
                                                     }
                                                     reader.readAsDataURL(input[i]);
                                                 }
                                             



                            

                                               //$('#blah').show();
                                               $('.edicloseimage').show();
                                               $('#addeimage').show();
                                               $('.post').prop('disabled', false);
                                               $('.post').removeClass('colorbttn');
                                               $('.view-gallery').hide();
                                               // $('#tagproduct').prop('disabled', false); //for tag a product
                                               //  $('#tagproduct').removeClass('colorbttn'); //for tag a product
                                                  }
                                            }





                                            function readURL1(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function (e) {
                                                //  alert(e.target.result);
                                                $('#blah1').attr('src', e.target.result).width(100).height(100);
                                                };

                                                reader.readAsDataURL(input.files[0]);
                                                $('#blah1').show();
                                                $('#editimage').show();
                                                $('.post').prop('disabled', false);
                                                $('.post').removeClass('colorbttn');
                                                $('#tagproduct1').prop('disabled', false); //for tag a product
                                                $('#tagproduct1').removeClass('colorbttn'); //for tag a product
                                                $('.commentcloseimage').show();

                                            }
                                            }

                                            function readURL3(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();

                                                reader.onload = function (e) {
                                                //  alert(e.target.result);
                                                $('#blah2').attr('src', e.target.result).width(100).height(100);
                                                };

                                                reader.readAsDataURL(input.files[0]);
                                                $('#blah2').show();
                                                $('#editimage2').show();
                                                $('.post').prop('disabled', false);
                                                $('.post').removeClass('colorbttn');
                                                // $('#tagproduct1').prop('disabled', false); //for tag a product
                                                // $('#tagproduct1').removeClass('colorbttn'); //for tag a product
                                                $('.commentcloseimage2').show();

                                            }
                                            }



                                           //  var loadFile = function(event) {
                                           //  // $epid=$(this).data("rid");
                                           //  //alert($epid);
                                           //  var image = document.getElementById('output');
                                           //  image.src = URL.createObjectURL(event.target.files[0]);
                                           //  $('#output').show();
                                           //  $('#editpostoutputimage').show();
                                           // // $('.edicloseimage').show();
                                            


                                           //  $('.post').prop('disabled', false);
                                           //     $('.post').removeClass('colorbttn');
                                           //     $('#tagproduct1').prop('disabled', false); //for tag a product
                                           //      $('#tagproduct1').removeClass('colorbttn'); //for tag a product


                                           //  };
                                            var loadFile1 = function(event) {
                                            // $epid=$(this).data("rid");
                                            //alert($epid);
                                            var image = document.getElementById('output1');
                                            image.src = URL.createObjectURL(event.target.files[0]);
                                            $('#output1').show();
                                            $('#editpostoutputimage1').show();
                                            $('.edicloseimage1').show();
                                            };
                                            var loadFile1spons = function(event) {
                                            // $epid=$(this).data("rid");
                                            //alert($epid);
                                            var image = document.getElementById('output123');
                                            image.src = URL.createObjectURL(event.target.files[0]);
                                            $('#editsponsModal1 #output123').show();
                                            $('#editsponsModal1 #editpostoutputimage123').show();
                                            $('#editsponsModal1 .edicloseimage1').show();
                                            };
                                            $(document).ready(function () {
                                            if(window.location.href==='https://sipnbourbon.com/?#msg-12345'){
                                            $('#myModal').show();
                                            //$('.post').prop('disabled', false);
                                            //$('.post').removeClass('colorbttn');
                                            //window.location.href='';
                                            }

                                            });

                                          $("#comment_0").on("keyup", function(){
                                           //  alert('hi');
                                            var addcommenttext = $(this).val();
                                           $a= $('#comment_img_0').val();
                                            if(addcommenttext.trim().length>0 || $a!=''){
                                                $('.post').prop('disabled', false);
                                                $('.post').removeClass('colorbttn');
                                                $('#tagproduct').prop('disabled', false); //for tag a product
                                                $('#tagproduct').removeClass('colorbttn'); //for tag a product
                                                 $('.taglocpost').prop('disabled', false); //for tag a location
                                                $('.taglocpost').removeClass('colorbttn'); //for tag a location
                                                
                                                
                                            }else if(addcommenttext.length<=0 && $a==''){
                                              //alert('hi');
                                                $('.post').prop('disabled', true);
                                                $('.post').addClass('colorbttn');
                                                $('#tagproduct').prop('disabled', true); //for tag a product
                                                $('#tagproduct').addClass('colorbttn');

                                                $('.taglocpost').prop('disabled', true); //for tag a location
                                                $('.taglocpost').addClass('colorbttn');

                                                
                                                $('#headerpostsearch').hide();
                                                $('.closeproduct1').hide();

                                                $('.taglocpostsearch').hide();
                                                $('.closeloc1').hide();

                                                
                                                
                                            }else{
                                                $('.post').prop('disabled', false);
                                                $('.post').removeClass('colorbttn');
                                                $('#tagproduct').prop('disabled', true); //for tag a product
                                                $('#tagproduct').addClass('colorbttn'); //for tag a product

                                                $('.taglocpost').prop('disabled', true); //for tag a location
                                                $('.taglocpost').addClass('colorbttn'); //for tag a location

                                                
                                                $('#headerpostsearch').hide();
                                                $('.closeproduct1').hide();

                                                $('.taglocpostsearch').hide();
                                                $('.closeloc1').hide();
                                            }

                                            });
//26-07



                                          

                                          $(document).ready(function () {
                                            $(".text-area add").on("keyup", function(){
                                            var addcommenttext = $(this).val();
                                           
                                           // alert(addcommenttext.trim().length);
                                           $a= $('#comment_img_0').val();
                                          // alert($a);
                                            if(addcommenttext.trim().length>0 || $a!=''){
                                                $('.post').prop('disabled', false);
                                                $('.post').removeClass('colorbttn');
                                                $('#tagproduct').prop('disabled', false); //for tag a product
                                                $('#tagproduct').removeClass('colorbttn'); //for tag a product
                                                $('.taglocpost').prop('disabled', false); //for tag a location
                                                $('.taglocpost').removeClass('colorbttn'); //for tag a location

                                                
                                                $('#headerpostsearch').show();
                                                $('.taglocpostsearch').show();
                                                $('.closeloc1').show();
                                            }else if(addcommenttext.length<=0 && $a==''){
                                              //alert('hi');
                                                $('.post').prop('disabled', true);
                                                $('.post').addClass('colorbttn');
                                                $('#tagproduct').prop('disabled', true); //for tag a product
                                                $('#tagproduct').addClass('colorbttn');

                                                $('.taglocpost').prop('disabled', true); //for tag a location
                                                $('.taglocpost').addClass('colorbttn');

                                                
                                            }else{
                                                $('.post').prop('disabled', false);
                                                $('.post').removeClass('colorbttn');
                                                $('#tagproduct').prop('disabled', true); //for tag a product
                                                $('#tagproduct').addClass('colorbttn'); //for tag a product

                                                 $('.taglocpost').prop('disabled', true); //for tag a location
                                                $('.taglocpost').addClass('colorbttn'); //for tag a location

                                                

                                            }

                                            });
                                          });

                                            $("#comment_01").on("keyup", function(){
                                            var addcommenttext = $(this).val();
                                           $a= $('.commentImg').val();
                                           $b=$('.sumee').val();
                                         //  alert($a);
                                         //  alert($b);
                                            if(addcommenttext.trim().length>0 || $a!='' || $b!='' ){
                                                $('.post').prop('disabled', false);
                                                $('.post').removeClass('colorbttn');
                                                $('#tagproduct1').prop('disabled', false); //for tag a product
                                                $('#tagproduct1').removeClass('colorbttn'); //for tag a product
                                                
                                            }else if(addcommenttext.length<=0 && $a=='' || $b==''){
                                              //alert('hi');
                                                $('.post').prop('disabled', true);
                                                $('.post').addClass('colorbttn');
                                                $('#tagproduct1').prop('disabled', true); //for tag a product
                                                $('#tagproduct1').addClass('colorbttn');
                                                $('#headerpostsearch1').hide();
                                                $('.closeproduct').hide();
                                                $('.taglocpostsearch').hide();
                                                $('.closeloc1').hide();
                                            }else{
                                                $('.post').prop('disabled', false);
                                                $('.post').removeClass('colorbttn');
                                                $('#tagproduct1').prop('disabled', true); //for tag a product
                                                $('#tagproduct1').addClass('colorbttn'); //for tag a product
                                                $('#headerpostsearch1').hide();
                                                $('.taglocpostsearch').hide();
                                                $('.closeloc1').hide();
                                            }

                                            });
                                           // submitEditWrapper

                                                // Slider mchat
// var container = document.getElementById('container')
// var slider = document.getElementById('slider');
// var slides = document.getElementsByClassName('slide').length;
// var buttons = document.getElementsByClassName('btn');


// var currentPosition = 0;
// var currentMargin = 0;
// var slidesPerPage = 0;
// var slidesCount = slides - slidesPerPage;
// var containerWidth = container.offsetWidth;
// var prevKeyActive = false;
// var nextKeyActive = true;

// window.addEventListener("resize", checkWidth);

// function checkWidth() {
//     containerWidth = container.offsetWidth;
//     setParams(containerWidth);
// }


// function setParams(w) {
//     if (w < 551) {
//         slidesPerPage = 1;
//     } else {
//         if (w < 901) {
//             slidesPerPage = 2;
//         } else {
//             if (w < 1101) {
//                 slidesPerPage = 3;
//             } else {
//                 slidesPerPage = 4;
//             }
//         }
//     }
//     slidesCount = slides - slidesPerPage;
//     if (currentPosition > slidesCount) {
//         currentPosition -= slidesPerPage;
//     };
//     currentMargin = - currentPosition * (100 / slidesPerPage);
//     slider.style.marginLeft = currentMargin + '%';
//     if (currentPosition > 0) {
//         buttons[0].classList.remove('inactive');
//     }
//     if (currentPosition < slidesCount) {
//         buttons[1].classList.remove('inactive');
//     }
//     if (currentPosition >= slidesCount) {
//         buttons[1].classList.add('inactive');
//     }
// }

// setParams();

// function slideRight() {
//     if (currentPosition != 0) {
//         slider.style.marginLeft = currentMargin + (100 / slidesPerPage) + '%';
//         currentMargin += (100 / slidesPerPage);
//         currentPosition--;
//     };
//     if (currentPosition === 0) {
//         buttons[0].classList.add('inactive');
//     }
//     if (currentPosition < slidesCount) {
//         buttons[1].classList.remove('inactive');
//     }
// };

// function slideLeft() {
//     if (currentPosition != slidesCount) {
//         slider.style.marginLeft = currentMargin - (100 / slidesPerPage) + '%';
//         currentMargin -= (100 / slidesPerPage);
//         currentPosition++;
//     };
//     if (currentPosition == slidesCount) {
//         buttons[1].classList.add('inactive');
//     }
//     if (currentPosition > 0) {
//         buttons[0].classList.remove('inactive');
//     }
// };
//mchat

$(document).ready(function () {
   

                                   $(window).load(function() {
            $(".mcarousel .item").each(function() {
                var i = $(this).next();
                i.length || (i = $(this).siblings(":first")),
                i.children(":first-child").clone().appendTo($(this));
                
                for (var n = 0; n < 4; n++)(i = i.next()).length ||
                (i = $(this).siblings(":first")),
                i.children(":first-child").clone().appendTo($(this))
            })
        });
                                    $('#myCarousel').carousel({
              interval: 10000
            })
        });
                          //for tag product
                          $(document).ready(function () {

                                            $('body').on('click', '#tagproduct', function () {
                                               
                                               
                                                $("#headerpostsearch").toggle();
                                                // $('#tagproduct').prop('disabled', true); //for tag a product
                                               // $('#tagproduct').addClass('colorbttn'); //for tag a product

                                               

                                                

                                            });



                                        });

                          $(document).ready(function () {
                                        $('body').on('click', '#tagproduct1', function () {

                                                $("#headerpostsearch1").toggle();
                                                //$('.tagloceditpostsearch').hide();
                                               // $('.closeloc').hide();
                                                //$('.headerpost-result-sec').hide();
                                               
                                               
                                               // $("#headerpostsearch1").toggle();
                                               // $('#tagproduct1').prop('disabled', true); //for tag a product
                                               // $('#tagproduct1').addClass('colorbttn'); //for tag a product
                                                

                                            });
                                        }); 

                          $(document).ready(function () {
                                        $('body').on('click', '.closeproduct1', function () {
                                               
                                               
                                                $(".headerpost-result-sec").hide();
                                                 $("#fpid").val('');
                                                $("#headerpostsearch").val('');
                                                 $('.closeproduct1').hide();
                                                 $('.headerpostsearch').hide();


                                             
                                                

                                            });
                                        });

                          $(document).ready(function () {
                                        $('body').on('click', '.closeproduct', function () {
                                               
                                              
                                                $(".headerpost-result-sec").hide();
                                                 $("#fpid1").val('');
                                                $("#headerpostsearch1").val('');
                                                $('.closeproduct').hide();

                                             
                                                

                                            });
                                         $('body').on('click', '.closeloc', function () {
                                             //  alert('ded');
                                              
                                                $(".tagloceditpostsearch").val('');
                                                
                                               //  $("#fpid1").val('');
                                               // $("#headerpostsearch1").val('');
                                                //$('.closeproduct').hide();

                                             
                                                

                                            });

                                        $('body').on('click', '.closeloc1', function () {
                                             //  alert('ded');
                                              
                                                $(".taglocpostsearch").val('');
                                                $('.taglocpostsearch').hide();
                                                $('.closeloc1').hide();
                                                
                                                
                                               //  $("#fpid1").val('');
                                               // $("#headerpostsearch1").val('');
                                                //$('.closeproduct').hide();

                                             
                                                

                                            });
                                        
                                        $('body').on('click', '.openpost', function () {
                                          window.history.pushState('', '', 'https://sipnbourbon.com');
                                          //location.replace("https://sipnbourbon.com")
                                         

                                        });
                                        });


                           //for tag location on post
                          $(document).ready(function () {

                                            $('body').on('click', '#taglocpost', function () {
                                               
                                               
                                                $(".taglocpostsearch").toggle();
                                                $('.headerpostsearch').hide();
                                                $('.closeloc1').toggle();
                                                $('.closeproduct1').hide();
                                                $('.headerpost-result-sec').hide();
                                                // $('#taglocpost').prop('disabled', true); //for tag a product
                                                //$('#taglocpost').addClass('colorbttn'); //for tag a product

                                               

                                                

                                            });

                                             $('body').on('click', '#tageditlocpost', function () {
                                               
                                               
                                                $(".tagloceditpostsearch").toggle();
                                                $('.headerpostsearch1').hide();
                                                $('.closeproduct').hide();
                                                $('.closeloc').toggle();
                                                $('.headerpost-result-sec').hide();
                                                // $('#taglocpost').prop('disabled', true); //for tag a product
                                                //$('#taglocpost').addClass('colorbttn'); //for tag a product

                                               

                                                

                                            });

                                             $('body').on('click', '.deletepimages', function () {
                                               
                                               
                                                $("#delete_image").val(1);
                                                $('.view-gallery').hide(); 
                                                //$('#taglocpost').addClass('colorbttn'); //for tag a product

                                               

                                                

                                            });

                                              ///for add post x mark
                                             $('body').on('click', '.edicloseimage2', function () {
                                               $('input[type=file]').val('');
                                               $('#addimage li img').attr('src', '');
                                                $("#img0").val('');
                                                $("#img1").val('');
                                                $("#img2").val('');
                                                $('#addimage').hide(); 
                                                //$('#taglocpost').addClass('colorbttn'); //for tag a product

                                               

                                                

                                            });

                                             ///for edit post x mark
                                              $('body').on('click', '.edicloseimage', function () {
                                               $('input[type=file]').val('');
                                                $("#img10").val('');
                                                $("#img11").val('');
                                                $("#img12").val('');
                                                $('#addeimage').hide(); 
                                                //$('#taglocpost').addClass('colorbttn'); //for tag a product

                                               

                                                

                                            });


                                             

                                             

                                            



                                        });
                            
                            </script>
                            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-58L6H4C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

                                    
                        </div>
                    </div>
                </div>  
            </div>
                        <!--     added by sumeeth -->
                           
            
        </section>    
    </main>
 <?php    echo wp_footer(); ?>
      </div>

      <style> 

      div#popup { display: none !important; }

/* use a media query to filter small devices */
@media only screen and (max-device-width:480px) {
    /* show the popup */
    div#popup { display: block !important; }
}
      /*mchat slider css*/
          #container {
    height: 40vh;
    width: 94vw;
    margin: 0;
    padding: 0;
    /* background: teal; */
    display: grid ;
    place-items: center
  }
  
  #slider-container {
    height: 205px;
    width: 89vw;
    max-width: 1400px;
    /* background: #54d5e4; 
    box-shadow: 5px 5px 8px gray inset;*/
    position: relative;
    overflow: hidden;
    padding: 20px;
    float: left;
  }
  
  #slider-container .btn {
    /* position: absolute;
    top: calc(50% - 30px);
    height: 30px;
    width: 30px;
    border-left: 8px solid #b7a968;
    border-top: 8px solid #b7a968; */
  }
  
  #slider-container .btn:hover {
    /* transform: scale(1.2); */
  }
  
  #slider-container .btn.inactive {
    /* border-color: rgb(183 169 104); */
  }
  
  #slider-container .btn:first-of-type {
    /* transform: rotate(-45deg);
    left: 10px;
    z-index: 1;
    height:31px; */
  }
  
  #slider-container .btn:last-of-type {
    /* transform: rotate(135deg);
    right: 10px; */
  }
  
  #slider-container #slider {
    display: flex;
    width: 1000%;
    height: 100%; 
    transition: all .5s;
  }
  
  #slider-container #slider .slide {
    height: 90%;
    margin: auto 10px;
    /* background-color: #a847a4;
    box-shadow: 2px 2px 4px 2px white, -2px -2px 4px 2px white; */
    display: grid;
    place-items: center;
  }
  
  #slider-container #slider .slide span {
    color: white;
    font-size: 150px;
  }
  
  @media only screen and (min-width: 1100px) {
  
    #slider-container #slider .slide {
      width: calc(2.5% - 20px);
    }
  
  }
  
  @media only screen and (max-width: 1100px) {
  
    #slider-container #slider .slide {
      width: calc(3.3333333% - 20px);
    }
  
  }
  
  @media only screen and (max-width: 900px) {
  
    #slider-container #slider .slide { width: calc(5% - 109px); margin: 0 5px 0 0; }
    #slider-container{ padding:10px; width: 96vw; height:220px; float: left !important;}  
    #container{ height: 42vh;}
    #slider-container .btn{ /*top: calc(48% - 0px);/*}
    /* #slider-container #slider{ width:688%;} */
  }
}
  
  @media only screen and (max-width: 550px) {
    #container{ height: 31vh;}
    #slider-container .btn{ /*top:calc(40% - 2px);*/}
    #slider-container{ padding:0px; width:92vw; float: left;}
    #slider-container #slider .slide{width:calc(6% - 54px); margin:0 0 5px 0;}
      /* #slider-container #slider{ width: 485%;} */
  }

        /*mchat slider css*/
      </style>
    
<div class="modal modal-emailverification fade" id="openpopup" tabindex="-1" role="dialog">
<div class="modal-dialog modal-dialog-emailverification modal-sm">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    <i class="fa fa-info-circle fa-info-circle-custom" aria-hidden="true"></i>
    </div>
    <div class="modal-body">
   <div class="email-verification-text">Email not verified</div>
      <div class="email-verification-content">We sent an email to you please verify your email to continue</div>
      <div class="resendemail-main"><a href="#" class="resendemail" data-id="<?php echo $curemail; ?>" id="resendemail">Resend verification mail</a></div>
      <div class="resendemail-main"><a href="/profile-info" class="email-verified-div">Already verified? Go to profile</a></div>
    </div>  
  </div>
</div>
</div>
      </body>
</html>
<!--     added by sumeeth -->
     <?php //sipn_footer();?>