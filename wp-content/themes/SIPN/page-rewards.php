<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 ?>
<?php
/**
 * Template Name: SIPN Rewards
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
global $wpdb;
if (!is_user_logged_in()) {
    wp_redirect("/");
    exit;
}
$cur_user = wp_get_current_user();
$user_details = get_user_meta($cur_user->data->ID);
$curloginname = $cur_user->data->display_name;
$curemail = $cur_user->data->user_email;
$unsubscribe = $cur_user->data->unsubscribe;
$avatar = wp_get_attachment_image_url($user_details['wp_user_avatar'][0], 'thumbnail');

    $user_id = get_current_user_id();
    // Get total points from users_rewards
    $total_points_earned = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT SUM(points_earned) AS total_points
             FROM user_reward_history 
             WHERE user_id = %d",
            $user_id
        )
    );

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE users_rewards SET total_points = %d WHERE user_id = %d",
            $total_points_earned,
            $user_id
        )
    );

    
    // Get the next min_points from levels table based on total_points_earned
    $next_level_points = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT min_points 
             FROM levels 
             WHERE min_points > %d 
             ORDER BY min_points ASC 
             LIMIT 1",
            $total_points_earned
        )
    );

  $level_name = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT level_name 
             FROM levels 
             WHERE min_points < %d 
             ORDER BY min_points DESC 
             LIMIT 1",
            $total_points_earned
        )
    );

    // If user's points are below the lowest level, show earned points instead
    $total_points = $next_level_points > 0 ? $next_level_points : $total_points_earned;

    // Fetch challenge categories
    $categories = $wpdb->get_results(
        "SELECT id AS category_id, category_name FROM challenge_categories order by category_id ASC",
        ARRAY_A
    );

    // Query to get challenge-wise breakdown from user_reward_history
    $query = $wpdb->prepare(
        "SELECT  
            cc.id AS category_id,
            cc.category_name,
            rc.id AS challenge_id,
            rc.challenge_name,
            COALESCE(SUM(urh.points_earned), 0) AS total_points_earned
        FROM reward_challenges rc
        JOIN challenge_categories cc ON rc.category_id = cc.id
        LEFT JOIN user_reward_history urh  
            ON rc.id = urh.challenge_id  
            AND urh.user_id = %d
        GROUP BY cc.id, rc.id
        ORDER BY cc.id, rc.id",
        $user_id
    );

    $challenges = $wpdb->get_results($query, ARRAY_A);

    // Result structure
    $result = [
        'total_points' => $total_points,  
    'level' => $level_name,
        'total_points_earned' => $total_points_earned,  
        'categories' => []
    ];

    // Initialize categories
    foreach ($categories as $category) {
        $category_id = $category['category_id'];
        $result['categories'][$category_id] = [
            'category_name' => $category['category_name'],
            'total_points_earned' => 0,
            'challenges' => []
        ];
    }

    // Process challenges
    foreach ($challenges as $row) {
        $category_id = $row['category_id'];
        $points_earned = (int) $row['total_points_earned'];

        $result['categories'][$category_id]['challenges'][] = [
            'challenge_name' => $row['challenge_name'],
            'points_earned' => $points_earned
        ];

        // Sum total points earned per category and overall
        $result['categories'][$category_id]['total_points_earned'] += $points_earned;
    }
    birthday_rewards_web();
    $levels = get_levels();
?>
 <style>
    /*
 CSS for the main interaction
*/
.tabset > input[type="radio"] {
  position: absolute;
  left: -200vw;
}

.tabset .tab-panel {
  display: none;
}

.tabset > input:first-child:checked ~ .tab-panels > .tab-panel:first-child,
.tabset > input:nth-child(3):checked ~ .tab-panels > .tab-panel:nth-child(2),
.tabset > input:nth-child(5):checked ~ .tab-panels > .tab-panel:nth-child(3),
.tabset > input:nth-child(7):checked ~ .tab-panels > .tab-panel:nth-child(4),
.tabset > input:nth-child(9):checked ~ .tab-panels > .tab-panel:nth-child(5),
.tabset > input:nth-child(11):checked ~ .tab-panels > .tab-panel:nth-child(6) {
  display: block;
}

/*
 Styling
*/
.tabset > label {
  position: relative;
  display: inline-block;
  padding: 3% 7% 3%;
  border: 1px solid transparent;
  border-bottom: 0;
  cursor: pointer;
  font-weight: 600;
  margin-bottom: 0 !important;
}

.tabset > label::after {
  /* content: "";
  position: absolute;
  left: 15px;
  bottom: 10px;
  width: 22px;
  height: 4px;
  background: #8d8d8d; */
}

input:focus-visible + label {
  outline: 2px solid rgba(0,102,204,1);
  border-radius: 3px;
}

.tabset > label:hover,
.tabset > input:focus + label,
.tabset > input:checked + label {
  color: #fff;
}

.tabset > label:hover::after,
.tabset > input:focus + label::after,
.tabset > input:checked + label::after {
  background: #fff;
}

.tabset > input:checked + label {
  /* border-color: #ccc; */
  border-bottom: 1px solid #fff;
  /* margin-bottom: -1px; */
}

.tab-panel {
  padding: 30px 0;
  border-top: 1px solid #545454;
}

/*
 Demo purposes only
*/
*,
*:before,
*:after {
  box-sizing: border-box;
}

body {
  padding: 30px;
}

.tabset {
  max-width: 65em;
}
article.rewards{ overflow-x: hidden;}
/* Accordion */
.accordion {
	margin: 0;
	width: 100%;
    position: relative !important;
    left: -25px;
    background: transparent;
    top: 0;
    height: auto;
    z-index: auto !important;
}
.accordion input {
	display: none;
}
.box, .rewards-box, .points-box{
	position: relative;
	background: #252525;
    height: 64px;
    transition: all .15s ease-in-out;
    margin-bottom: 20px;
    border-radius: 4px;
    padding-left: 15px;
}
.levels-box{background: #252525;transition: all .15s ease-in-out;margin-bottom: 20px;border-radius: 4px;padding: 10px; margin-bottom: 10px;}
.leaderboard{ background: #fff;transition: all .15s ease-in-out;margin-bottom: 20px;border-radius: 4px;padding: 10px; margin-bottom: 10px; color: #000;}
.rewards-box .box-title:before{ display: none !important;}
.points-box{ display: flex; justify-content: space-between; height: auto; padding: 9px 15px 5px 15px; font-size: 16px; line-height: 30px;}
.left-points, .right-points{display: flex;}
.left-points span{padding-right: 5px; padding-top: 3px;}
.right-points .gold{ font-size: 32px; margin-right: 10px;}
.box svg, .rewards-box svg{ float: left; margin-top: 18px;}
.box label span, .rewards-box label span{ float: right; font-size: 16px; font-weight: normal;}
.box::before {
    content: '';
    position: absolute;
    display: block;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    pointer-events: none;
    /* box-shadow: 0 -1px 0 #e5e5e5,0 0 2px rgba(0,0,0,.12),0 2px 4px rgba(0,0,0,.24); */
}
header.box {
	background: #00BCD4;
	z-index: 100;
	cursor: initial;
	/* box-shadow: 0 -1px 0 #e5e5e5,0 0 2px -2px rgba(0,0,0,.12),0 2px 4px -4px rgba(0,0,0,.24); */
}
header .box-title {
	margin: 0;
	font-weight: normal;
	font-size: 16pt;
	color: white;
	cursor: initial;
}
.box-title {
	width: calc(100% - 40px);
	height: 64px;
	line-height: 64px;
	padding: 0 10px;
	display: inline-block;
	cursor: pointer;
	-webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;
}
.box-content {
	width: calc(100% - 5px);
	padding: 10px 0px 12px;
	/* font-size: 11pt; */
	color: #fff;
	display: none;
  border-top: solid 1px #515151;
}
.box-close {
	position: absolute;
	height: 64px;
	width: 100%;
	top: 0;
	left: 0;
	cursor: pointer;
	display: none;
}
input:checked + .box {
	height: auto;
	margin: 0 0 20px 0;
    /* box-shadow: 0 0 6px rgba(0,0,0,.16),0 6px 12px rgba(0,0,0,.32); */
}
input:checked + .box .box-title {
	border-bottom: 1px solid rgba(0,0,0,.18);
}
input:checked + .box .box-content,
input:checked + .box .box-close {
	display: inline-block;
}
.arrows section .box-title {
	padding-left: 6px;
	width: calc(100% - 64px);
    font-family: 'Nunito', sans-serif;
    font-size: 20px;
    font-weight: 600;
}
.arrows section .box-title:before {
	position: absolute;
	display: block;
	content: '\203a';
	font-size: 18pt;
	right: 20px;
	top: -2px;
	transition: transform .15s ease-in-out;
	color: #fff;
    font-weight: normal;
}
input:checked + section.box .box-title:before {
	transform: rotate(90deg);
}
.line{ border-left: 1px solid #fff; height: auto; margin-left: 16px; width: 100%;}
.mb-0{ margin-bottom: 0;}
.gold{color: #BDA766;}
.text-center{ text-align: center;}
.rewards-prof-image{ margin-top: 35px;}
.rewards-prof-image img{ border-radius: 100%; max-width: 43%; display: block; margin: 0 auto; border: solid 3px #D6A742;}
.rewards-prof-image .badge{ background: transparent; margin: -2px 0 0 0; padding: 0;}
.profile-info h2{ font-size: 32px;}
.profile-info .points{ font-size: 40px;}
.profile-info{ font-size: 24px;}
.level{ display: flex; justify-content: center; align-items: center; margin-top: 25px;}
.level span{ margin-left: 35px;}
.rewards-profile{ border: solid 1px #fff; border-radius: 4px; padding: 30px 0;}
.ranking-month{ font-size: 20px; font-weight: bold;}
.box-content table{ width: 100%; line-height: 24px;}
.box-content .btn{ padding: 0; margin-top: 10px;}
.box-content .btn button{ background-color: #B7A968; border: none; border-radius: 4px; color: #fff; font-size: 14px; font-weight: bold;}
.points-header{ display: flex; padding: 0 0 0px 0;border-bottom: solid 0px #515151; margin-bottom: 0px; justify-content: space-between;}
.points-header h3, .leaderboard h3{ margin: 0; font-size: 16px; font-weight: 600;}
.right-level{ font-size: 14px;}
.leaderboard h3{ margin: 0 0 0 10px; line-height: normal;}
.leaderboard { display: flex; justify-content: space-between;}
.leaderboard svg path{ fill: #000;}
.leaderboard .left-leader{ display: flex;}
.red{ color: red;}
/* Accordion New */
details { margin: 0 0 20px 0; font-size: 18px;}
details > * { padding: .75rem;}
details > div { background: #252525; border-radius: 0 0 5px 5px; border-top: solid 1px #515151; padding: 15px 0px 15px 15px;}
details table{ font-size: 14px; width: 100%; line-height: 24px;}
summary { border-radius: 5px; background: #252525; cursor: pointer; position: relative; transition: .3s; text-indent: 0px; height: 65px; padding: 19px 48px 0 0;}
summary h4{ font-size: 20px; font-family: 'Nunito', sans-serif; font-weight: 600; color: #fff; margin: 3px 0 0 0; float: left;}
summary::marker { content: "";}
summary::before{ content: ''; position: absolute; display: block; top: 0; bottom: 0; left: 0; right: 0; pointer-events: none;}
summary svg{ float: left; margin: 1px 5px 0 12px;}
summary span{ font-size: 16px; float: right;}
summary::after {content:'\203a'; position:absolute; /* inset: 1.75rem; */ left: auto; aspect-ratio: 1; /* background: conic-gradient(from 90deg at 26% 26%, #0000 90deg, #fff 0)  100% 100%/58% 58%; */ /* clip-path: inset(1px); */ transition: .3s; font-size: 22px; font-weight: normal; right: 20px; top: 17px;}
details[open] summary::after { transform: rotate(90deg);}
details[open] summary { border-radius: 5px 5px 0 0; background: #252525; /* text-indent: 1rem; */}
/* Badge Styles */
.badge-level-block{ display: block; background-color: #252525; border-radius: 4px; padding: 15px; margin-bottom: 20px;}
.badge-level-head, .badge-level-content{ display: flex; justify-content: space-between;}
.badge-level-head{ border-bottom: solid 1px #515151; margin-bottom: 10px;}
.badge-level-head span, .badge-level-head h3, .badge-level-content span{ margin-top: 5px;}
.badge-level-block img.badge-rewards-level{ max-width: 44px;}
.badge-level-head h3{ font-size: 16px; font-weight: 600;}
.badge-level-content .rewards-level { position: relative;}
.badge-level-content .rewards-level i img{ width: 17px; position: absolute; bottom: -1px; right: 0px;}


@media screen and (max-width: 1440px) and (min-width: 1200px) {
  summary h4{ font-size: 17px;}
  summary span{ font-size: 14px; margin-top: 2px;}
}
@media screen and (max-width: 1199px) and (min-width: 992px) {
  summary h4{ font-size: 17px;}
  summary span{ font-size: 14px; margin-top: 2px;}
}
@media screen and (max-width: 991px) and (min-width: 768px) {
  summary h4{ font-size: 17px;}
  summary span{ font-size: 14px; margin-top: 2px;}
}
@media screen and (max-width: 767px) {
  summary h4{ font-size: 17px; width: auto}
  summary span{ font-size: 13px; margin-top: 2px;}
  summary svg{ width: 20px;}
  summary::after{ top: 12px;}
}
@media screen and (max-width: 480px){
  summary h4{ width: 128px; font-size: 15px;}
}

</style>
<article class="col-md-10 rewards">
    <div class="wrapper-top">
        <div class="wrapper-bottom">
            <div class="container">
                
                <div class="col-md-12 p-0">
                  
                    <div class="col-md-6 p-0">
                      <div class="col-md-12">
                        <h1 class="heading-main-events">Rewards</h1>
                      </div>
                      <div class="col-md-12">
                        <p>Complete challenges and earn rewards</p>
                      </div>
                        <div class="tabset">
                        <!-- Tab 1 -->
                        <input type="radio" name="tabset" id="tab1" aria-controls="marzen" checked>
                        <label for="tab1">Challenges</label>
                        <!-- Tab 2 -->
                        <input type="radio" name="tabset" id="tab2" aria-controls="rauchbier">
                        <label for="tab2">Badges</label>
                        <!-- Tab 3 -->
                        <!-- <input type="radio" name="tabset" id="tab3" aria-controls="dunkles">
                        <label for="tab3">Leaderboard</label> -->

                        <div class="tab-panels">
                            <section id="marzen" class="tab-panel">
                            <!-- <h2>Challenges</h2> -->
                            
                              <div class="points-box p-0">
                                <div class="left-points">
                                  <span>
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.0006 15.2165L4.12271 18.5067L5.43548 11.8998L0.48999 7.32634L7.17919 6.53322L10.0006 0.416504L12.8219 6.53322L19.5111 7.32634L14.5657 11.8998L15.8784 18.5067L10.0006 15.2165Z" fill="white"/>
                                    </svg>
                                  </span> Points
                                </div>
                                <div class="right-points">
                                    <span class="gold"><?php echo $result['total_points_earned']; ?></span> of <?php echo $result['total_points']; ?>
                                </div>
                              </div>
                              <div class="profile-info">
                                <div class="level">
                                <svg width="71" height="93" viewBox="0 0 71 93" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M35.5 22.6294C54.8301 22.6294 70.5 38.2992 70.5 57.6293C70.5 76.9594 54.8301 92.6293 35.5 92.6293C16.17 92.6293 0.5 76.9594 0.5 57.6293C0.5 38.2992 16.17 22.6294 35.5 22.6294ZM35.5 31.3794C21.0025 31.3794 9.25 43.1319 9.25 57.6293C9.25 72.1267 21.0025 83.8793 35.5 83.8793C49.9974 83.8793 61.75 72.1267 61.75 57.6293C61.75 43.1319 49.9974 31.3794 35.5 31.3794ZM35.5 37.9418L41.2859 49.6655L54.2241 51.5454L44.8621 60.6712L47.0719 73.5569L35.5 67.473L23.928 73.5569L26.138 60.6712L16.7761 51.5454L29.7141 49.6655L35.5 37.9418ZM61.75 0.754375V13.8794L55.7851 18.8562C50.9459 16.3194 45.5734 14.6618 39.8798 14.0959L39.875 0.75L61.75 0.754375ZM31.125 0.75L31.1237 14.0955C25.4305 14.6609 20.0583 16.3178 15.2193 18.8539L9.25 13.8794V0.754375L31.125 0.75Z" fill="#D6A742"/>
                                </svg>
                                <span><?php echo $result['level']; ?></span>
                              </div> 
                            </div>
                          
                              <div class="line">
                                <!-- <nav class="accordion arrows">
                                    <?php $res_i = 0;
                                    $pointsEarned = 0;
                                    foreach ($result['categories'] as $category) {
                                        foreach ($category['challenges'] as $challenge) {
                                            if ($challenge['challenge_name'] === "Complete Account Profile") {
                                                $pointsEarned = $challenge['points_earned'];
                                                break 2; // Exit both loops
                                            }
                                        }
                                    }
                                    foreach($result['categories'] as $res){ ?>
                                    <input type="radio" name="accordion" id="cb<?=$res_i?>" />
                                    <section class="box">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z" fill="white"/>
                                        </svg>
                                        <label class="box-title" for="cb<?=$res_i?>"><?php echo $res['category_name']; ?>
                                            <span><?php echo $res['total_points_earned']; ?> pts</span>
                                        </label>
                                        <label class="box-close" for="acc-close"></label>
                                        <div class="box-content">
                                          <table>
                                            <?php foreach($res['challenges'] as $challenge){ ?>
                                            <tr>
                                              <td><?php echo $challenge['challenge_name']; ?></td>
                                              <td><?php echo $challenge['points_earned']; ?> pts</td>
                                            </tr>
                                            <?php } ?>
                                          </table>
                                          <?php if($pointsEarned == 0 && $challenge['challenge_name'] === "Complete Account Profile"){ ?>
                                          <div class="btn">
                                            <a href="/profile-edit/" type="submit">Complete Profile</a>
                                          </div>
                                          <?php } ?>
                                        </div>
                                    </section>
                                    <?php $res_i++; } ?>
                                </nav> -->
                            
                                <div class="accordion">
                                    <?php $res_i = 0;
                                    $pointsEarned = 0;
                                    foreach ($result['categories'] as $category) {
                                        foreach ($category['challenges'] as $challenge) {
                                            if ($challenge['challenge_name'] === "Complete Account Profile") {
                                                $pointsEarned = $challenge['points_earned'];
                                                break 2; // Exit both loops
                                            }
                                        }
                                    }
                                    foreach($result['categories'] as $res){ ?>
                                  <details>
                                    <summary>
                                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M20 22H4C3.44772 22 3 21.5523 3 21V3C3 2.44772 3.44772 2 4 2H20C20.5523 2 21 2.44772 21 3V21C21 21.5523 20.5523 22 20 22ZM19 20V4H5V20H19ZM7 6H11V10H7V6ZM7 12H17V14H7V12ZM7 16H17V18H7V16ZM13 7H17V9H13V7Z" fill="white"/>
                                          </svg>  
                                      <h4 class="title"><?php echo $res['category_name']; ?></h4> <span><?php echo $res['total_points_earned']; ?> pts</span>
                                    </summary>
                                    <div>
                                      <table>
                                        <tbody>
                                            <?php foreach($res['challenges'] as $challenge){ ?>
                                            <tr>
                                              <td><?php echo $challenge['challenge_name']; ?></td>
                                              <td><?php echo $challenge['points_earned']; ?> pts</td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </details>
                                    <?php $res_i++; } ?>
                                </div>
                            
                              </div>
                        </section>
                        <section id="rauchbier" class="tab-panel">
                            <h2>Badges</h2>
                            <?php
                            foreach($levels as $level){ ?>
                                <div class="badge-level-block">
                                  <div class="badge-level-head">
                                    <h3><?php echo $level['level_name']; ?></h3>
                                    <span><?php echo $level['min_points']; ?> pts</span>
                                  </div>
                                  <div class="badge-level-content">
                                    <?php if($level['points_to_reach'] > 0){ ?>
                                        <span><span class="gold"><?php echo $level['points_to_reach']; ?></span> points to next badge</span>
                                        <!-- <img class="badge-rewards-level" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/img-badge-level.png"> -->
                                    <?php }else{ ?>
                                        <span>Badge Completed</span>
                                        <!-- <div class="rewards-level">
                                          <img class="badge-rewards-level" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/img-badge-level.png">
                                          <i><img class="badge-rewards-level" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/icon-rewards-badge.png"></i>
                                        </div> -->
                                    <?php } ?>
                                  </div>
                                </div>
                            <?php } ?>
                        </section>
                        <!-- <section id="dunkles" class="tab-panel">
                            <h2>User Streaks</h2>
                            <div class="leaderboard">
                                <div class="left-leader">
                                <span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M10.0006 15.2165L4.12271 18.5067L5.43548 11.8998L0.48999 7.32634L7.17919 6.53322L10.0006 0.416504L12.8219 6.53322L19.5111 7.32634L14.5657 11.8998L15.8784 18.5067L10.0006 15.2165Z" fill="white"></path>
                                </svg>
                                </span>  
                                <h3>Created a post</h3></div>
                                <div class="right-leader">+125 pts</div>
                            </div>
                            <div class="leaderboard">
                                <div class="left-leader">
                                <span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M10.0006 15.2165L4.12271 18.5067L5.43548 11.8998L0.48999 7.32634L7.17919 6.53322L10.0006 0.416504L12.8219 6.53322L19.5111 7.32634L14.5657 11.8998L15.8784 18.5067L10.0006 15.2165Z" fill="white"></path>
                                </svg>
                                </span>  
                                <h3>Uploaded a Profile Image</h3></div>
                                <div class="right-leader">+10 pts</div>
                            </div>
                            <div class="leaderboard">
                                <div class="left-leader">
                                <span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M10.0006 15.2165L4.12271 18.5067L5.43548 11.8998L0.48999 7.32634L7.17919 6.53322L10.0006 0.416504L12.8219 6.53322L19.5111 7.32634L14.5657 11.8998L15.8784 18.5067L10.0006 15.2165Z" fill="white"></path>
                                </svg>
                                </span>  
                                <h3>Points Uitlized</h3></div>
                                <div class="right-leader red">-125 pts</div>
                            </div>
                            <div class="leaderboard">
                                <div class="left-leader">
                                <span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M10.0006 15.2165L4.12271 18.5067L5.43548 11.8998L0.48999 7.32634L7.17919 6.53322L10.0006 0.416504L12.8219 6.53322L19.5111 7.32634L14.5657 11.8998L15.8784 18.5067L10.0006 15.2165Z" fill="white"></path>
                                </svg>
                                </span>  
                                <h3>Liked a Collection</h3></div>
                                <div class="right-leader">+100 pts</div>
                            </div>
                         </section> -->
                        </div>

                        </div>
                    </div>
                    <div class="col-md-6 p-0" style="display: none;">
                        <div class="points-box">
                          <div class="left-points">
                            <span>
                              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M10.0006 15.2165L4.12271 18.5067L5.43548 11.8998L0.48999 7.32634L7.17919 6.53322L10.0006 0.416504L12.8219 6.53322L19.5111 7.32634L14.5657 11.8998L15.8784 18.5067L10.0006 15.2165Z" fill="white"/>
                              </svg>
                            </span> Points
                          </div>
                          <div class="right-points">
                              <span class="gold"><?php echo $result['total_points_earned']; ?></span> of 15,000
                          </div>
                        </div>
                        <div class="rewards-profile text-center">
                          
                          <div class="rewards-prof-image">
                            <!-- <img src="https://staging.sipnbourbon.com/wp-content/themes/SIPN/assets/images/img-rewards.jpg"> -->
                            <!-- <div class="badge">
                              <svg width="35" height="40" viewBox="0 0 35 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M10.9286 40L12.5714 32.5L6 27.5L14.2143 25.8333L17.5 20L20.7857 25.8333L29 27.6316L22.4286 32.5L24.0714 40L17.5 35L10.9286 40Z" fill="#D6A742"/>
                              <path d="M0 10V0H35V10L18.3333 20L0 10Z" fill="#D6A742"/>
                              </svg>
                            </div> -->
                            <div class="profile-info">
                              <!-- <h2>Amelia Jackson</h2> -->
                              <!-- <div class="gold points">
                              2045 pts
                              </div>
                              <p>of 15,000 points</p> -->
                              <div class="level">
                                <svg width="71" height="93" viewBox="0 0 71 93" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M35.5 22.6294C54.8301 22.6294 70.5 38.2992 70.5 57.6293C70.5 76.9594 54.8301 92.6293 35.5 92.6293C16.17 92.6293 0.5 76.9594 0.5 57.6293C0.5 38.2992 16.17 22.6294 35.5 22.6294ZM35.5 31.3794C21.0025 31.3794 9.25 43.1319 9.25 57.6293C9.25 72.1267 21.0025 83.8793 35.5 83.8793C49.9974 83.8793 61.75 72.1267 61.75 57.6293C61.75 43.1319 49.9974 31.3794 35.5 31.3794ZM35.5 37.9418L41.2859 49.6655L54.2241 51.5454L44.8621 60.6712L47.0719 73.5569L35.5 67.473L23.928 73.5569L26.138 60.6712L16.7761 51.5454L29.7141 49.6655L35.5 37.9418ZM61.75 0.754375V13.8794L55.7851 18.8562C50.9459 16.3194 45.5734 14.6618 39.8798 14.0959L39.875 0.75L61.75 0.754375ZM31.125 0.75L31.1237 14.0955C25.4305 14.6609 20.0583 16.3178 15.2193 18.8539L9.25 13.8794V0.754375L31.125 0.75Z" fill="#D6A742"/>
                                </svg>
                                <span><?php echo $result['level']; ?></span>
                              </div> 
                            </div>
                          </div>
                          <br>
                          <p class="ranking-month">User Streaks</p>
                          <div>
                            
                          </div>
                        </div>
                    </div>
                </div>

            </div>
            <script>
              let details = document.querySelectorAll('.accordion details')

              details.forEach(function (d, index) {
              d.onclick = () => {
                  details.forEach(function(c, i) {
                  index === i ?'':c.removeAttribute('open')
                  });
              };
              });
            </script>
<?php sipn_footer();?>