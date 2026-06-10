<?php
/**
 * Template Name: SIPN Set New Password
 */
get_header();

// Get values from URL
$key   = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';
$login = isset($_GET['login']) ? sanitize_text_field($_GET['login']) : '';
?>

<article class="col-md-10">
  <div class="wrapper-top">
    <div class="reset-class">
      <div class="wrapper-middle">

<div class="animate" id="resetpwd">

<div class="imgcontainer">
<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-gold.png" class="logo-gold">
</div>

<div class="login-container">
<p class="resetpwd">Set New Password</p>

<input type="password" placeholder="New Password" id="new_pass" autocomplete="off" required>
<input type="password" placeholder="Confirm Password" id="confirm_pass" autocomplete="off" required>

<div class="reset">
<input type="button" id="set_pass_btn" class="signin reset" value="Update Password">
</div>

<div id="msg"></div>
</div>

</div>
</div>
</div>
</div>
</article>

<script>
const key   = "<?php echo esc_js($key); ?>";
const login = "<?php echo esc_js($login); ?>";

document.getElementById("set_pass_btn").onclick = async function () {

    let pass1 = document.getElementById("new_pass").value;
    let pass2 = document.getElementById("confirm_pass").value;

    if(pass1.length < 6){
        alert("Password must be at least 6 characters");
        return;
    }

    if(pass1 !== pass2){
        alert("Passwords do not match");
        return;
    }

    const res = await fetch(
        "<?php echo site_url('/wp-json/users/v1/set-new-password'); ?>",
        {
            method: "POST",
            headers: {"Content-Type":"application/json"},
            body: JSON.stringify({
                key: key,
                login: login,
                password: pass1
            })
        }
    );

    const data = await res.json();

    document.getElementById("msg").innerHTML = data.message;

    if(data.message.includes("success")){
        setTimeout(()=>{
            window.location.href = "https://sipnbourbon.com/login";
        },1000);
    }
};
</script>

<?php sipn_footer(); ?>
