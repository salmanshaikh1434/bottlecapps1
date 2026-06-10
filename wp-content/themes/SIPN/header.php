<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="facebook-domain-verification" content="77gtxzkfpx9lhg3sf16k6s4ycckbcc" />
  <meta name="google-site-verification" content="1vTlz4CRJbALV9MG693lQcCzqmWgGAXbwcXfbhuNwX8" />
  <meta name="msvalidate.01" content="113F0CA5CC81D028907F7A02A5FE419A" />
  <?php $version_number = defined('Version_number') ? Version_number : false; ?>
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/animation.css" type="text/css"
    media="all" onload="this.media='all'" defer>
  <!-- <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/fonts/webkit.css" type="text/css"
    defer> -->
  <link rel="stylesheet"
    href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/home-new-<?php echo $version_number; ?>.css"
    type="text/css" defer>
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/existing.css" type="text/css"
    defer>
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/custom.css" type="text/css"
    defer>
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/style-new-designs.css?on=13062025"
    type="text/css" defer>
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/accessibility.css"
    type="text/css" defer>
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style-<?php echo $version_number; ?>.css"
    type="text/css" defer>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <style>
    #myModal {
      display: none;
      position: fixed;
      z-index: 1000 !important;
      /* Lower z-index for the background modal */
      padding-top: 60px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
    }

    #cropperModal {
      display: none;
      position: fixed;
      z-index: 11000;
      /* Higher z-index for the cropper modal to appear on top */
      padding-top: 60px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      /* Slightly darker background for distinction */
    }

    #mycontent {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 50%;
    }

    .canvas-container {
      width: 300px;
      height: 300px;
      margin: 20px auto;
      border: 1px solid #ddd;
    }

    #image {
      display: block;
      max-width: 100%;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }
  </style>

  <?php
  if (is_user_logged_in()) {

  } else {

    wp_logout();
  }
  global $post;
  $post_slug = $post->post_name;
  if ($post_slug == 'search-bourbon-find-bourbon' || $post_slug == 'buy-now' || isset($_GET['s'])) { ?>
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/search-slider.css"
      type="text/css">
  <?php } ?>
  <?php if ($post_slug == 'bourbon-collection') {
    echo "<meta name='description' content='Discover our exclusive bourbon collection which has something for every taste and occasion. Feel the diversity & depth of this American classic.' />";
    echo "<meta name='keywords' content='bourbon collection,bourbon collection must haves,best bourbon collection,bourbon collection for sale,my bourbon collection,bourbon collection app' />";
    echo "<meta property='og:title' content='Bourbon collection - Discover the Finest bourbon whiskey | Sipn' />";
    echo "<meta property='og:description' content='Discover our exclusive bourbon collection which has something for every taste and occasion. Feel the diversity & depth of this American classic.' />";
    echo "<meta property='og:image' content='https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/Sipnogimage.jpg' />";
    echo "<meta name='twitter:image' content='https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/Sipnogimage.jpg' />";

  }
  if ($post_slug == 'celebrity-bourbon-collection') {
    echo "<title>CELEBRITY BOURBON COLLECTION</title>";
    echo "<meta name='description' content='Each bourbon has a unique profile and our special celebrity bourbon stands out. SIPN Bourbon has created this batch, making it easier to shop bourbon' />";
    echo "<meta name='keywords' content='heavens door whiskey,heavens door bourbon,heavens door bourbon,heavens door whiskey,digits bourbon,digits bourbon price,digits bourbon cost,bradshaw bourbon,terry bradshaw bourbon,bradshaw bourbon near me,bradshaw whiskey,longbranch bourbon,wild turkey longbranch,longbranch whiskey,brothers bond bourbon,brothers bond,brothers bourbon,brother bond bourbon,brothers bond whiskey,wolf moon bourbon,wolf moon whiskey' />";
  }
  if ($post_slug == 'christmas-bourbon-collection') {
    echo "<title>CHRISTMAS BOURBON COLLECTION</title>";
    echo "<meta name='description' content='Are you searching for a Christmas bourbon collection? The holidays are about happiness, and bourbon. SIPN Bourbon can be your secret Santa!' />";
    echo "<meta name='keywords' content='smoke wagon bourbon,smoke wagon,smoke wagon small batch,makers mark whiskey,makers mark bourbon,woodford reserve,kentucky straight bourbon whiskey,woodford reserve bourbon,woodford reserve kentucky straight bourbon,basil hayden,basil hayden bourbon,basil hayden whiskey,wilderness trail bourbon,wilderness trail' />";
  }
  if ($post_slug == 'best-bourbon-under-50') {
    echo "<title>MUST HAVE BOURBONS UNDER $50</title>";
    echo "<meta name='description' content='Enjoying bourbons does not mean you need to break the bank. SIPN bourbon has provided a list of best Bourbon under $50, explore & shop right now.' />";
    echo "<meta name='keywords' content='buffalo trace bourbon,buffalo trace bourbon near me,buffalo trace near me,wild turkey whiskey,wild turkey bourbon,wild turkey bourbon whiskey,best wild turkey bourbon,brothers bond bourbon,brothers bond,brother bond bourbon,ian somerhalder bourbon,brothers bond whiskey,brothers bond bourbon,michters small batch bourbon,michters,bourbon,michters whiskey,michters small batch,michters whiskey,michters bourbon,michters whisky,1792 bourbon,1792 small batch,small batch bourbon,gentleman jack whiskey,gentleman jack whiskey,gentlemans jack,gentlemans jack,woodford reserve kentucky straight bourbon whiskey,woodford reserve whiskey,woodford bourbon,woodford whiskey,angels envy,angels envy,angels envy rye,weller special reserve,weller whiskey,weller bourbon near me,heaven hill distillery,heaven hill bourbon,heaven hill bottled in bond' />";
  }
  if ($post_slug == 'best-bourbon-under-100') {
    echo "<title>BEST BOURBONS UNDER $100</title>";
    echo "<meta name='description' content='Bourbons have become quite popular in the last few years. SIPN bourbon has provided a list of Best Bourbon under $100, explore & shop right now.' />";
    echo "<meta name='keywords' content='elijah craig toasted barrel,toasted barrel bourbon,woodford reserve double oaked,woodford double oaked,double oaked,elijah craig,elijah craig small batch,elijah craig bourbon,nulu toasted barrel,nulu toasted,' />";
  }

  if ($post_slug == 'mash-and-grape-bonded-for-life') {

    echo "<meta name='description' content='Indulge in the World of Bourbon with Mash and Grape - Discover Popular Brands That Have Caught the Eye of Wine Connoisseurs. Explore Our Collection Today!' />";
  }

  if ($post_slug == 'independence-day-collection') {

    echo "<title>4th of July Independence Day Bourbon Collection | Sipn Bourbon</title>";
    echo "<meta name='description' content='Raise your glass to freedom with Sipn Bourbons exclusive Independence Day Collection on July 4th.Indulge in patriotic spirits for a memorable celebration.' />";
    echo "<meta name='keywords' content='4th of July, independence day collection, independence day july 4th,independence day 4th of july, bourbon collection' />";
  }
  if ($post_slug == 'exquisite-collection-celebrating-black-owned-bourbon-and-rye') {

    echo "<title>Exquisite Collection: Celebrating Black-Owned Bourbon and Rye | Sipn Bourbon</title>";
    echo "<meta name='description' content='Embrace the spirit of inclusion with our Exquisite Collection: Celebrating Black-Owned Bourbon and Rye. Discover exceptional flavors.' />";

  }
  ?>
  <?php
  $canurl = home_url($wp->request);
  $url = get_current_url();

  if ($post_slug == 'celebrity-bourbon-collection' || $post_slug == 'celebrity-bourbon-collection' || $post_slug == 'christmas-bourbon-collection' || $post_slug == 'best-bourbon-under-50' || $post_slug == 'best-bourbon-under-100' || $post_slug == 'mash-and-grape-bonded-for-life' || $post_slug == 'fathers-day-gifts' || $post_slug == 'Independence-Day-Collection' || $post_slug == 'exquisite-collection-celebrating-black-owned-bourbon-and-rye' || $post_slug == 'artisan-distilleries' || $post_slug == 'best-whiskeys-for-old-fashioned' || $post_slug == 'must-have-bourbon-whiskeys-for-thanksgiving-celebration' || $post_slug == 'bourbon-whiskey-treasures-discover-top-store-pick-bottles' || $post_slug == 'christmas-bourbon-gift-guide-2023') {
    // $arr=explode('=',$url);
    $str = str_replace("-", " ", $post_slug);
    $query = "SELECT * FROM wp_collections  WHERE collection_orgname='$str'";
    $masterdata = $wpdb->get_results($query);
    ?>
    <meta property="og:image" content="<?php echo $masterdata[0]->collection_image; ?>" />
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:image" content="<?php echo $masterdata[0]->collection_image; ?>" />
    <meta property="og:description" content="<?php echo $masterdata[0]->collection_short_description; ?>" />
    <meta property="og:title" content="<?php echo $masterdata[0]->collection_name; ?>" />
    <title><?php echo $masterdata[0]->collection_name; ?></title>
    <link rel="canonical" href="<?php echo $canurl; ?>" />


  <?php } else if ($post_slug == 'https-sipnbourbon-com' || $post_slug == 'home' || $post_slug == 'sipn-bourbon-home-is-where-bourbon-is' || $post_slug == 'blogs') { ?>
      <meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Sipnogimage.jpg" />
      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:site" content="@sipnbourbon" />
      <meta name="twitter:title" content="SIPN BOURBON" />
      <meta name="twitter:description"
        content="Sipn bourbon is a social liquor ecommerce platform which allows users to post their love towards bourbon, buy bourbon and build a virtual bar" />
      <meta name="twitter:image" content="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Sipnogimage.jpg" />
  <?php } ?>
  <?php if ($post_slug == 'about-us' || $post_slug == 'contact' || $post_slug == 'terms' || $post_slug == 'sipn-bourbon-videos' || $post_slug == 'search-bourbon-find-bourbon') { ?>
    <meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Sipnogimage.jpg" />
    <meta name="twitter:image" content="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/Sipnogimage.jpg" />
    <?php
  } ?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" defer>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" defer />
  <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" defer>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <!--<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/slider.js"></script>-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/brands.min.css"
    defer />

  <script>(function (w, d, s, l, i) {
      w[l] = w[l] || []; w[l].push({
        'gtm.start':
          new Date().getTime(), event: 'gtm.js'
      }); var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
          'https://www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-58L6H4C');</script>
  <script src="https://cdn.blueconic.net/bottlecapps.js" defer></script>
  <script type="text/javascript">
    (function (c, l, a, r, i, t, y) {
      c[a] = c[a] || function () { (c[a].q = c[a].q || []).push(arguments) };
      t = l.createElement(r); t.async = 1; t.src = "https://www.clarity.ms/tag/" + i;
      y = l.getElementsByTagName(r)[0]; y.parentNode.insertBefore(t, y);
    })(window, document, "clarity", "script", "biycoilrwt");
  </script>
  <script>
    !function (f, b, e, v, n, t, s) {
      if (f.fbq) return; n = f.fbq = function () {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
      n.queue = []; t = b.createElement(e); t.async = !0;
      t.src = v; s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1508516839620415');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=1508516839620415&ev=PageView&noscript=1" /></noscript>
  <!--<link href="<?php echo get_stylesheet_directory_uri(); ?>/videojs-vimeo/lib/video-js.min.css" rel="stylesheet" />-->

  <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
  <!-- <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script> -->

  <?php wp_head(); ?>

  <?php $url = get_current_url();
  if (strpos($url, '/bar/') !== false) { ?>
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/demo.css" />
    <link rel="stylesheet" type="text/css"
      href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/drag-drop.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/style1.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/lightslider.css">
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/jquery-3.5.1.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/lightslider.js"></script> -->
    <!-- <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bar.css" type="text/css" defer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script> -->

    <script>
      $(document).ready(function () {
        $('#autoWidth1').lightSlider({
          autoWidth1: true,
          loop: true,
          onSliderLoad: function () {
            $('#autoWidth1').removeClass('cS-hidden');
          }
        });
        $('#autoWidth2').lightSlider({
          autoWidth2: true,
          loop: true,
          onSliderLoad: function () {
            $('#autoWidth2').removeClass('cS-hidden');
          }
        });
        $('#autoWidth3').lightSlider({
          autoWidth3: true,
          loop: true,
          onSliderLoad: function () {
            $('#autoWidth3').removeClass('cS-hidden');
          }
        });
      });

    </script>
  <?php } ?>
  <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Corporation",
  "name": "Sipn Bourbon",
  "url": "https://sipnbourbon.com/",
  "logo": "https://sipnbourbon.com/wp-content/themes/SIPN/assets/images/logo-sb.webp",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "",
    "contactType": "customer service",
    "contactOption": "TollFree",
    "areaServed": "US",
    "availableLanguage": "en"
  },
  "sameAs": [
    "https://www.facebook.com/Sipnbourbon/",
    "https://www.instagram.com/sipnbourbon/",
    "https://twitter.com/SipnBourbon",
    "https://www.youtube.com/@sipnbourbon",
    "https://sipnbourbon.com/"
  ]
}
</script>
  <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/buy-now-location.js"></script>
  <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/location.js"></script>

  <style>
    .delete-product {
      display: none;
    }

    .slides {
      height: 155px !important;
      display: flex !important;
    }

    .truncate {
      display: block;
      overflow: hidden;
      max-width: 14ch;
      white-space: nowrap;
      text-overflow: ellipsis;
      max-width: 100%;
    }




    @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap');
  </style>

</head>

<body>
  <?php global $current_user;
  wp_get_current_user();
  if (is_user_logged_in()) {
    $cur_user_id = get_current_user_id();
    $current_user_details = get_user_by('id', $cur_user_id);
    $curemail = $current_user_details->user_email;
    $current_user_meta = get_user_meta($cur_user_id);
    $cur_user_avatar = wp_get_attachment_image_url($current_user_meta['wp_user_avatar'][0], 'thumbnail');

    if (!$cur_user_avatar) {
      $cur_user_avatar = get_avatar_url($cur_user_id);
    }
  } ?>
  <div class="black-bg">

    <!-- Header-->
    <div class="stick-header">
      <div class="header">
        <div class="row">
          <div class="col-md-12 mp0">
            <div class="col-md-2 col-sm-4 col-xs-4">
              <div class="nav-logo">
                <button aria-label="mMenu" id="nav-icon4" type="button" class="m-menu">
                  <span class="bars"></span>
                  <span class="bars"></span>
                  <span class="bars"></span>
                </button>
                <!-- <a href="/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/brand.png" alt="logo" width="80" height="80"></a> -->
                <a href="/" class="logo-main-sipn"><img
                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-sb.webp" alt="logo"
                    width="2070px" height="792px"></a>
              </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6  p0">
              <div class="tabs tabs-custom-div">
                <div class="opdropdown-main">
                  <select class="opdropdown" id="opdropdown">
                    <option value="All">All</option>
                    <option value="product">Product</option>
                    <option value="post">Post</option>

                  </select>
                </div>
                <!-- <div class="opdropdown-main">
                  <div class="opdropdown" id="opdropdown">
                    <ul>
                    <li value="All">All</li>
                    <li value="product">Product</li>
                    <li value="post">Post</li>
                    </ul>
                  </div>
                </div> -->
                <div class="search-bar search-bar-custom">
                  <form class="form active" action="https://sipnbourbon.com/">

                    <input class="form-control active <?php if ($post_slug == 'buy-now') {
                      echo "find-buynow";
                    } else if ($post_slug == 'bourbons-to-stock-at-home') {
                      echo "find-bar";
                    } ?>" name="s" required type="search" for="search" placeholder="Search SIPN" id="header-search"
                      autocomplete="off" maxlength="60" oninput="checkInputLength(this)">
                    <button type="submit" class="search-btn-icon"><i class="fa fa-search"></i></button>
                    <div class="header-result-sec" id="header-result-sec" style="display:none;"></div>


                  </form>
                  <?php //echo do_shortcode('[apsw_search_bar_preview]'); ?>

                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-2 col-xs-2">
              <div class="settings">
                <div class="logo logo-left logout">
                  <a href="<?php if (is_user_logged_in() && $current_user->data->validate_email == '0') {
                    echo '/bar/user-' . $current_user->data->ID;
                  } else if (is_user_logged_in() && $current_user->data->validate_email == '1') {
                    echo "javascript:void(0)";
                  } else {
                    echo "/login";
                  } ?>" <?php if (is_user_logged_in() && $current_user->data->validate_email == '1') {
                     echo "data-toggle='modal' data-backdrop='static' data-target='#openpopup'";
                   } ?>>
                    <!-- <i class="fas fa-user-circle"></i> -->
                    <?php if (is_user_logged_in()) { ?> <img src="<?php echo $cur_user_avatar; ?>" alt="user-image"
                        width="50" height="50" class="img-circle"> <?php } else { ?><i class="fas fa-user-circle"></i>
                    <?php } ?>
                    <span class="profile_name"><?php if (is_user_logged_in()) {
                      if (strpos($current_user->display_name, 'user-') !== false) {
                        echo "Welcome";
                      } else {
                        echo $current_user->display_name;
                      }
                      echo '<br><span class="user-email-profile truncate">' . $current_user->user_email . '</span>';
                    } else {
                      echo "Sign In";
                    } ?>
                    </span>
                  </a>
                </div>
                <div class="social-icons">
                  <ul>
                    <li><a href="https://www.facebook.com/SipnBourbon" target="_blank"><img
                          src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/facebook.svg"
                          height="28px"></a></li>
                    <li><a href="https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=" target="_blank"><img
                          src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/instagram.svg"
                          height="28px"></a></li>
                    <li><a href="https://twitter.com/SipnBourbon" target="_blank"><img
                          src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/x-twitter.svg"
                          height="28px"></a></li>
                    <!--                                    <li><a href="https://twitter.com/SipnBourbon" target="_blank"><img src="<?php // echo get_stylesheet_directory_uri(); ?>/assets/images/icon-twitter.png"></a></li>-->
                  </ul>
                </div>
                <!-- <ul class="list-inline">
                                  <li class="list-item first">
                                      <a href="#">
                                          <i class="far fa-bell"></i>
                                          <span class="number">0</span>
                                      </a>
                                  </li>
                                  <li class="list-item">
                                      <a href="<?php if (is_user_logged_in()) {
                                        echo "/profile-info";
                                      } else {
                                        echo "javascript:void(0);";
                                      } ?>"> <i class="fas fa-user-circle"></i> </a>
                                  </li>
              
                                  <li class="list-item">
                                      <a href="#"><i class="fas fa-cog"></i></a>
                                  </li>
                              </ul> -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="full-bar">
                <div class="search-bar">
                    <form class="form mob_form" action="https://sipnbourbon.com/">
                        <label><i class="fa fa-search fa-mob"></i></label>
                        <input class="form-control <?php //if($post_slug=='buy-now') { echo "find-buynow"; } else if($post_slug=='bourbons-to-stock-at-home') { echo "find-bar"; } ?>" name="s" required type="search" for="search" placeholder="E.g. Maker's Mark" id="mob_header_search">
      <div class="result-sec1" style="display:none;"></div>
                    </form>
                </div>
            </div> -->
    </div>

    <main class="row">
      <div class="col-xs-2 col-sm-2 left-slide-bar">
        <nav class="hidden-sms sideBar" id="nav-bar">

          <div class="main-menu">
            <ul>

              <?php if (is_user_logged_in()) { ?>
                <!--<li><a><?php //if(strpos($current_user->display_name, 'user-') !== false){ echo "Welcome";}else{echo "Welcome ".$current_user->display_name;} ?></a></li>-->
              <?php } ?>
              <li class="home <?php $url = get_current_url();
              if ($url == 'https://sipnbourbon.com:443/') {
                echo 'active';
              } ?>">
                <a href="/">Home</a>
              </li>
              <!-- <li class="find"><a href="/search-bourbon-find-bourbon">Find</a></li> -->
              <!-- <li class="bar <?php $url = get_current_url();
              if (strpos($url, '/bar/') !== false) {
                echo 'active';
              } ?>"><a  href="<?php if (is_user_logged_in() && $current_user->data->validate_email == '0') {
                 echo '/bar/user-' . $current_user->data->ID;
               } else if (is_user_logged_in() && $current_user->data->validate_email == '1') {
                 echo "javascript:void(0)";
               } else {
                 echo "/login?redirect_to=bar";
               } ?>" <?php if (is_user_logged_in() && $current_user->data->validate_email == '1') {
                  echo "data-toggle='modal' data-backdrop='static' data-target='#openpopup'";
                } ?> >Bar</a></li> -->
              <li class="wishlist <?php $url = get_current_url();
              if (strpos($url, '/products-wish-list/') !== false) {
                echo 'active';
              } ?>">
                <a href="/products-wish-list">Wishlist</a>
              </li>
              
              <li class="rating <?php $url = get_current_url();
                if (strpos($url, '/the-rating-club/') !== false) {
                  echo 'active';
                } ?>">
                <a href="/the-rating-club">The Rating Club</a>
              </li>

              <li class="forums <?php $url = get_current_url();
              if (strpos($url, '/topics') !== false) {
                echo 'active';
              } ?>">
                <a href="/topics">Forums</a>
              </li>
              <li class="videos <?php $url = get_current_url();
              if (strpos($url, '/sipn-bourbon-videos') !== false) {
                echo 'active';
              } ?>">
                <a href="/sipn-bourbon-videos">Videos</a>
              </li>
              <li class="events <?php $url = get_current_url();
              if (strpos($url, '/events') !== false) {
                echo 'active';
              } ?>">
                <a href="/events">Events</a>
              </li>
              <li class="collection <?php $url = get_current_url();
              if (strpos($url, '/bourbon-collection') !== false) {
                echo 'active';
              } ?>">
                <a href="/bourbon-collection">Bourbon Collection</a>
              </li>
              <li class="invite"><span class="invite_friends">Invite</span>
               <div class="social-icons1" id="social-icons1" style="display:none;">
                  <ul style="width: 100%; float: left;">
                    <li>
                      <a href="https://www.facebook.com/sharer/sharer.php?text=One Link&amp;u=http://onelink.to/sipnbourbon"
                        target="_blank" class="invite_friends_click">
                        <i class="fa-brands fa-facebook-f"></i></a>
                    </li>
                    <li><a
                        href="https://twitter.com/messages/compose?text=Download the SIPN Bourbon app http://onelink.to/sipnbourbon"
                        target="_blank" class="invite_friends_click">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/x-twitter.svg"
                          alt="twitter"></a>
                    </li>
                    <li><a href="https://api.whatsapp.com/send?text=http://onelink.to/sipnbourbon"
                        data-action="share/whatsapp/share" target="_blank" class="invite_friends_click"> 
                        <i class="fab fa-whatsapp"></i></a>
                    </li>
                    <li><a href="mailto:subject=One Link&amp;body=http://onelink.to/sipnbourbon" target="_blank" class="invite_friends_click">
                        <i class="fa fa-envelope" aria-hidden="true"></i></a>
                    </li>
                    <li><a class="copy-cls-home invite_friends_click" id="copy-cls-home" href="javascript:void(0)" link="http://onelink.to/sipnbourbon"><i
                          class="fas fa-copy"></i></a></li>
                  </ul>
                </div>
                <!-- <span class="friends">Friends</span> -->
              </li>
              <?php if (!is_user_logged_in()) { ?>
                <li class="logout"><a href="/login">Login</a></li>
              <?php } ?>
              <?php if (is_user_logged_in()) { ?>
                <li class="logout"><a href="#" data-toggle="modal" data-target="#logoutmodel">Logout</a></li>
              <?php } ?>
              <li class="feed-buttons">
                <?php if (is_user_logged_in() && $current_user->data->validate_email == '1') { ?>

                  <a href="#." data-toggle="modal" data-backdrop="static" data-target="#openpopup">Post</a>

                <?php } else { ?>
                  <?php if (is_user_logged_in()) { ?>
                    <a href="javascript:void(0);" id="myBtn">Post</a>
                  <?php } else { ?>
                    <a href="/login/?redirect_to=msg-12345">Post</a>
                  <?php } ?>
                <?php } ?>
              </li>
            </ul>
            <div class="social-icons" id="social-icons">
              <ul>
                <li><a href="https://www.facebook.com/SipnBourbon" target="_blank"><img
                      src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/facebook.svg"></a></li>
                <li><a href="https://instagram.com/sipnbourbon?igshid=YmMyMTA2M2Y=" target="_blank"><img
                      src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/instagram.svg"></a></li>
                <li><a href="https://twitter.com/SipnBourbon" target="_blank"><img
                      src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/x-twitter.svg"></a></li>
              </ul>
            </div>
          </div>
          <div class="menu2 down-menu">
            <ul>
              <li><a href="/about-us">About Us</a></li>
              <?php // if ( is_user_logged_in() ) { ?>
              <!--
              <li>
                <a  href="<?php // if($current_user->data->validate_email=='0' ){echo bbp_get_user_profile_url($current_user->data->ID);} else if($current_user->data->validate_email=='1'){ echo "javascript:void(0)"; } else{ echo "/login"; } ?>" <?php //  if($current_user->data->validate_email=='1'){ echo "data-toggle='modal' data-backdrop='static' data-target='#openpopup'"; } ?> >Profile
                </a>
              </li>
-->
              <?php // } ?>
              <!--              <li><a href="/wishlist">Wishlist</a></li>-->
              <li><a href="/blogs">Blog</a></li>
              <li><a href="/contact">Contact Us</a></li>
              <li><a href="/blocked-users">Blocked users</a></li>
              <li><a href="/terms">Terms</a></li>
              <li><a href="/privacy-policy">Privacy Policy</a></li>
            </ul>
          </div>
        </nav>
      </div>

      <div id="logoutmodel" class="modal">
        <div class="modal-content">
          <div class="report">
            <p class="content_delete">Are you sure, you want to Logout?</p>
            <div class="row">
              <div class="btns-cancel-proceed">
                <button class="btn btn-profile-cancel" data-dismiss="modal">Cancel</button>
                <button class="btn btn-profile-save"
                  onclick="window.location.href='<?php echo wp_logout_url('/'); ?>'">Ok</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="common-confirm" class="modal" style="display:none;z-index:10010;">
        <div class="modal-content">
          <div class="report">
            <p class="content_delete"></p>
            <div class="row">
              <div class="btns-cancel-proceed">
                <button class="btn btn-profile-cancel" data-dismiss="modal">ok</button>
              </div>
            </div>
          </div>
        </div>
      </div>



      <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
          <header>
            <h2>Create New Post</h2>
            <span class="close openpost">&times;</span>
          </header>
          <div class="">
            <div class="user-chat">
              <div class="msg-opt-in">
                <div class="more-user-info">

                  <div class="write-block">

                    <div class="headerpost-result-sec" style="display:none;"></div>
                    <input type="hidden" id="fpid" />

                    <textarea placeholder="Write a Post..." class="text-area add" id="comment_0"></textarea>

                  </div>
                  <div class="createimageholder emojis post_paperclip">
                    <div class="inputWrapper1">
                      <div class="image_upload">
                        <input accept="image/jpg,image/jpeg,image/png,image/webp" onchange="readURL(this.files);"
                          class="fileInput commentInput" rid="0" id="profile-pic" name="pImage[]" type="file"
                          multiple="">

                        <ul id="addimage" class="viewonly add_image_post" style="display:none;"> </ul>


                      </div>



                      <label for="profile-pic">
                        <img src="/wp-content/themes/SIPN/assets/images/icon-pin.png">
                      </label>
                      <div id="mulimg"></div>
                      <input type="hidden" id="comment_img_0" value="">
                    </div>
                  </div>
                  <div class="tag_product_location">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                      <!--                                                                <input type="button" class="colorbttn" value="Tag Product" id="tagproduct" disabled="true">-->
                      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/post-tag-product.png"
                        class="tag_product_input" alt="" id="tagproduct" disabled="true">
                      <input class="form-control headerpostsearch" name="s" required type="text" for="search"
                        placeholder="Search Bourbons" id="headerpostsearch" autocomplete="off"
                        style="color: #000 !important; display:none;" /><span class="closeproduct1"
                        style="display:none;">×</span>
                    </div>

                    <div class="col-md-6 col-sm-12 col-xs-12">
                      <!--                                                                <input type="button" class="taglocpost colorbttn" value="Tag Location" id="taglocpost" disabled="true">-->
                      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/post-location.png"
                        class="tag_location_input" alt="" id="taglocpost" disabled="true">
                      <span class="closeloc1" style="display: none;">×</span>
                      <input id="search_input_head" class="form-control pac-target-input bn_address taglocpostsearch"
                        placeholder="Enter Address" type="text" autocomplete="off" style="display:none;">
                    </div>

                  </div>
                </div>
              </div>
              <div class="btn-postnow">
                <button type="button" class="post colorbttn submitWrapper post_spinner" id="btnPost" rid="0">POST </button>
              </div>
            </div>
          </div>
        </div>

      </div> <!-- end of modal -->

      <div id="cropperModal" class="modal">
        <div class="modal-content" id="mycontent">
          <span class="closecropper">&times;</span>
          <h2>Crop Image</h2>
          <div class="canvas-container">
            <img id="image" style="max-width: 100%;">
          </div>
          <button id="cropButton"></button>
        </div>
      </div>

      <!-- Age Confirmation Modal -->
     <?php if (!isset($_COOKIE['age_verified'])): ?>
      <div class="age-overlay">
          <div id="ageModal" class="age-verify">
              <div class="age-bg">
                  <div class="age-logo">
                      <a href="/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-goldsmall.png" /></a>
                  </div>
                  <div class="age-body">
                      <h2>Are you at least 21 years old?</h2>
                      <p>By entering this site you are agreeing to the 
                          <a target="_blank" href="/terms">Terms of Use</a> and 
                          <a target="_blank" href="/privacy-policy">Privacy Policy</a>
                      </p>
                      
                      <button id="under21" class="age-over">No, Exit</button>
                      <button id="over21" class="age-under">I am Over 21</button>
                  </div>
              </div>
          </div> 
      </div>
  <?php endif; ?>


