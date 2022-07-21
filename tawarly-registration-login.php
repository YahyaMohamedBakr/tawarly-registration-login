<?php
/*
Plugin Name: Tawarly Custom Login & Registration Form
Plugin URI: https://github.com/sivviii/tawarly-registration-login
Description: A shortcode based Lightweight WordPress plugin that creates custom login and registration forms that can be implemented using a shortcode. [register_tutor_form] , [register_student_form]
Version: 1.0
Author: Tawarly
Author URI: https://tawarly.com/
*/

/* ------------------------------------------------------------------------- */
// user registration login form
/* ------------------------------------------------------------------------- */
function tawarly_tutor_registration_form() {
 
	// only show the registration form to non-logged-in members
	if(!is_user_logged_in()) {
 
		global $tawarly_load_css;
 
		// set this to true so the CSS is loaded
		$tawarly_load_css = true;
 
		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');
 
		// only show the registration form if allowed
		if($registration_enabled) {
			$output = tawarly_tutor_registration_form_fields();
		} else {
			$output = __('User registration is not enabled');
		}
		return $output;
	}
}
add_shortcode('register_tutor_form', 'tawarly_tutor_registration_form');

function tawarly_student_registration_form() {
 
	// only show the registration form to non-logged-in members
	if(!is_user_logged_in()) {
 
		global $tawarly_load_css;
 
		// set this to true so the CSS is loaded
		$tawarly_load_css = true;
 
		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');
 
		// only show the registration form if allowed
		if($registration_enabled) {
			$output = tawarly_student_registration_form_fields();
		} else {
			$output = __('User registration is not enabled');
		}
		return $output;
	}
}
add_shortcode('register_student_form', 'tawarly_student_registration_form');



/* ------------------------------------------------------------------------- */
// registration form fields
/* ------------------------------------------------------------------------- */
function tawarly_tutor_registration_form_fields() {
 
	ob_start(); ?>	
		<h3 class="tawarly_header"><?php _e('Register New Account'); ?></h3>
 
		<?php 
		// show any error messages after form submission
		tawarly_show_error_messages(); ?>
 
		<form id="tawarly_registration_form" class="tawarly_form" action="" method="POST">
			<fieldset>
				<input type="hidden" name="tawarly_tutor_registration" value="1"/>
				<p>
					<label for="tawarly_user_Login"><?php _e('Username'); ?></label>
					<input name="tawarly_user_login" id="tawarly_user_login" class="required" type="text"/>
				</p>
				<p>
					<label for="tawarly_user_email"><?php _e('Email'); ?></label>
					<input name="tawarly_user_email" id="tawarly_user_email" class="required" type="email"/>
				</p>
				<p>
					<label for="tawarly_user_first"><?php _e('First Name'); ?></label>
					<input name="tawarly_user_first" id="tawarly_user_first" class="required" type="text"/>
				</p>
				<p>
					<label for="tawarly_user_last"><?php _e('Last Name'); ?></label>
					<input name="tawarly_user_last" id="tawarly_user_last" class="required" type="text"/>
				</p>
				<p>
					<label for="password"><?php _e('Password'); ?></label>
					<input name="tawarly_user_pass" id="password" class="required" type="password"/>
				</p>
				<p>
					<label for="password_again"><?php _e('Password Again'); ?></label>
					<input name="tawarly_user_pass_confirm" id="password_again" class="required" type="password"/>
				</p>
				<p>
					<input type="hidden" name="tawarly_register_nonce" value="<?php echo wp_create_nonce('tawarly-register-nonce'); ?>"/>
					<input type="submit" value="<?php _e('Register Your Account'); ?>"/>
				</p>
			</fieldset>
		</form>
	<?php
	return ob_get_clean();
}

function tawarly_student_registration_form_fields() {
 
	ob_start(); ?>	
		<h3 class="tawarly_header"><?php _e('Register New Account'); ?></h3>
 
		<?php 
		// show any error messages after form submission
		tawarly_show_error_messages(); ?>
 
		<form id="tawarly_registration_form" class="tawarly_form" action="" method="POST">
			<fieldset>
			<input type="hidden" name="tawarly_student_registration" value="1"/>
				<p>
					<label for="tawarly_user_Login"><?php _e('Username'); ?></label>
					<input name="tawarly_user_login" id="tawarly_user_login" class="required" type="text"/>
				</p>
				<p>
					<label for="tawarly_user_email"><?php _e('Email'); ?></label>
					<input name="tawarly_user_email" id="tawarly_user_email" class="required" type="email"/>
				</p>
				<p>
					<label for="tawarly_user_phone"><?php _e('Phone'); ?></label>
					<input name="tawarly_user_phone" id="tawarly_user_phone" class="required" type="tel"/>
					<span id="span-phone-vrified" style="color: green;"></span>
				</p>

				<button type="button" onclick="verifyOtp();">Verify</button>

				<div name="recaptcha-container" id="recaptcha-container"></div>

				<p>
					<label for="tawarly_user_first"><?php _e('First Name'); ?></label>
					<input name="tawarly_user_first" id="tawarly_user_first" class="required" type="text"/>
				</p>
				<p>
					<label for="tawarly_user_last"><?php _e('Last Name'); ?></label>
					<input name="tawarly_user_last" id="tawarly_user_last" class="required" type="text"/>
				</p>
				<p>
					<label for="password"><?php _e('Password'); ?></label>
					<input name="tawarly_user_pass" id="password" class="required" type="password"/>
				</p>
				<p>
					<label for="password_again"><?php _e('Password Again'); ?></label>
					<input name="tawarly_user_pass_confirm" id="password_again" class="required" type="password"/>
				</p>
				<p>
					<input type="hidden" name="tawarly_register_nonce" value="<?php echo wp_create_nonce('tawarly-register-nonce'); ?>"/>
					<button type="button" onclick="submitForm();" id="tawarly_user_submit"><?php _e('Register Your Account'); ?></button>
				</p>
			</fieldset>
		</form>
		<!-- <script src="https://www.gstatic.com/firebasejs/4.8.1/firebase.js"></script> -->
	<?php
	return ob_get_clean();
}

/* ------------------------------------------------------------------------- */
// Register a new user
/* ------------------------------------------------------------------------- */

function tawarly_add_new_tutor() {
  	if (isset( $_POST["tawarly_user_login"] ) && wp_verify_nonce($_POST['tawarly_register_nonce'], 'tawarly-register-nonce')) {
		$user_login		= $_POST["tawarly_user_login"];	
		$user_email		= $_POST["tawarly_user_email"];
		$user_first 	= $_POST["tawarly_user_first"];
		$user_last	 	= $_POST["tawarly_user_last"];
		$user_pass		= $_POST["tawarly_user_pass"];
		$pass_confirm 	= $_POST["tawarly_user_pass_confirm"];
 
		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');
 
		if(username_exists($user_login)) {
			// Username already registered
			tawarly_errors()->add('username_unavailable', __('Username already taken'));
		}
		if(!validate_username($user_login)) {
			// invalid username
			tawarly_errors()->add('username_invalid', __('Invalid username'));
		}
		if($user_login == '') {
			// empty username
			tawarly_errors()->add('username_empty', __('Please enter a username'));
		}

		
		if(!is_email($user_email)) {
			//invalid email
			tawarly_errors()->add('email_invalid', __('Invalid email'));
		}
		if(email_exists($user_email)) {
			//Email address already registered
			tawarly_errors()->add('email_used', __('Email already registered'));
		}
		if($user_pass == '') {
			// passwords do not match
			tawarly_errors()->add('password_empty', __('Please enter a password'));
		}
		if($user_pass != $pass_confirm) {
			// passwords do not match
			tawarly_errors()->add('password_mismatch', __('Passwords do not match'));
		}

 
		$errors = tawarly_errors()->get_error_messages();
 
		// only create the user in if there are no errors
		if(empty($errors)) {
 
			$new_user_id = wp_insert_user(array(
					'user_login'		=> $user_login,
					'user_pass'	 		=> $user_pass,
					'user_email'		=> $user_email,
					'first_name'		=> $user_first,
					'last_name'			=> $user_last,
					'user_registered'	=> date('Y-m-d H:i:s'),
					'role'				=> 'wpamelia-provider'
				)
			);
			if($new_user_id) {
				// send an email to the admin alerting them of the registration
				wp_new_user_notification($new_user_id);
 
				// log the new user in
				wp_setcookie($user_login, $user_pass, true);
				wp_set_current_user($new_user_id, $user_login);	
				do_action('wp_login', $user_login);
 
				// send the newly created user to the home page after logging them in
				wp_redirect(home_url("/")); exit;
			}
 
		}
 
	}
}

function tawarly_add_new_student() {
	if (isset( $_POST["tawarly_user_login"] ) && wp_verify_nonce($_POST['tawarly_register_nonce'], 'tawarly-register-nonce')) {
	  $user_login		= $_POST["tawarly_user_login"];	
	  $user_email		= $_POST["tawarly_user_email"];
	  $user_phone		= $_POST["tawarly_user_phone"];
	  $user_first 	= $_POST["tawarly_user_first"];
	  $user_last	 	= $_POST["tawarly_user_last"];
	  $user_pass		= $_POST["tawarly_user_pass"];
	  $pass_confirm 	= $_POST["tawarly_user_pass_confirm"];

	  // this is required for username checks
	  require_once(ABSPATH . WPINC . '/registration.php');

	  if(username_exists($user_login)) {
		  // Username already registered
		  tawarly_errors()->add('username_unavailable', __('Username already taken'));
	  }
	  if(!validate_username($user_login)) {
		  // invalid username
		  tawarly_errors()->add('username_invalid', __('Invalid username'));
	  }
	  if($user_login == '') {
		  // empty username
		  tawarly_errors()->add('username_empty', __('Please enter a username'));
	  }

	  
	  if(!is_email($user_email)) {
		  //invalid email
		  tawarly_errors()->add('email_invalid', __('Invalid email'));
	  }
	  if(email_exists($user_email)) {
		  //Email address already registered
		  tawarly_errors()->add('email_used', __('Email already registered'));
	  }
	  if($user_pass == '') {
		  // passwords do not match
		  tawarly_errors()->add('password_empty', __('Please enter a password'));
	  }
	  if($user_pass != $pass_confirm) {
		  // passwords do not match
		  tawarly_errors()->add('password_mismatch', __('Passwords do not match'));
	  }


	  $errors = tawarly_errors()->get_error_messages();

	  // only create the user in if there are no errors
	  if(empty($errors)) {

		  $new_user_id = wp_insert_user(array(
				  'user_login'		=> $user_login,
				  'user_pass'	 		=> $user_pass,
				  'user_email'		=> $user_email,
				  'first_name'		=> $user_first,
				  'last_name'			=> $user_last,
				  'user_registered'	=> date('Y-m-d H:i:s'),
				  'role'				=> 'wpamelia-customer' //wpamelia-provider
			  )
		  );
		  if($new_user_id) {
			  // send an email to the admin alerting them of the registration
			  wp_new_user_notification($new_user_id);

			 // add amilia user
			  global $wpdb;
			  $sql = "INSERT INTO `".$wpdb->prefix."amelia_users` (`status`, `type`, `externalId`, `firstName`, `lastName`, `email`, `phone`) 
			  VALUES ('visible','customer','$new_user_id','$user_first','$user_last','$user_email','$user_phone')";
			  
			  $sql = $wpdb->prepare($sql);
			  $results = $wpdb->query($sql);

			  // log the new user in//
			  try{
				wp_setcookie($user_login, $user_pass, true);
			  	wp_set_current_user($new_user_id, $user_login);	
			  	do_action('wp_login', $user_login,'');
			  }
			  catch(Exception $e){
			  }

			  // send the newly created user to the home page after logging them in
			  wp_redirect(home_url("/")); exit;
		  }

	  }

  }
}

function tawarly_add_new_member(){
	if (isset( $_POST["tawarly_tutor_registration"] ))
	tawarly_add_new_tutor();
	else if (isset( $_POST["tawarly_student_registration"] ))
	tawarly_add_new_student();
}
add_action('init', 'tawarly_add_new_member');


/* ------------------------------------------------------------------------- */
// used for tracking error messages
/* ------------------------------------------------------------------------- */
function tawarly_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

/* ------------------------------------------------------------------------- */
// Displays error messages from form submissions
/* ------------------------------------------------------------------------- */
function tawarly_show_error_messages() {
	if($codes = tawarly_errors()->get_error_codes()) {
		echo '<div class="tawarly_errors">';
		    // Loop error codes and display errors
		   foreach($codes as $code){
		        $message = tawarly_errors()->get_error_message($code);
		        echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		    }
		echo '</div>';
	}	
}

/* ------------------------------------------------------------------------- */
// register our form css and js
/* ------------------------------------------------------------------------- */
function tawarly_register_css() {
	wp_register_style('tawarly-form-css', plugin_dir_url( __FILE__ ) . '/css/forms.css');
	wp_register_style('tawarly-phoneinput-css', plugin_dir_url( __FILE__ ) . '/phoneinput/css/intlTelInput.css');
}
add_action('init', 'tawarly_register_css');

function tawarly_register_js() {
	wp_enqueue_script( 'tawarly-phoneinput-js', plugins_url( '/phoneinput/js/intlTelInput.js', __FILE__ ));
	wp_register_script('tawarly-firebase-js', 'https://www.gstatic.com/firebasejs/7.20.0/firebase.js', false, false, false );
	wp_enqueue_script('tawarly-firebase-js');

}
add_action('wp_enqueue_scripts', 'tawarly_register_js');

function call_script_in_footer(){
    ?>
    <script>

		var intltelinput = window.intlTelInput(document.querySelector("#tawarly_user_phone"), {
			// allowDropdown: false,
			// autoHideDialCode: false,
			// autoPlaceholder: "off",
			// dropdownContainer: document.body,
			// excludeCountries: ["us"],
			// formatOnDisplay: false,
			// geoIpLookup: function(callback) {
			//   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			//     var countryCode = (resp && resp.country) ? resp.country : "";
			//     callback(countryCode);
			//   });
			// },
			// hiddenInput: "full_number",
			// initialCountry: "auto",
			// localizedCountries: { 'de': 'Deutschland' },
			// nationalMode: false,
			// onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
			// placeholderNumberType: "MOBILE",
			// preferredCountries: ['cn', 'jp'],
			// separateDialCode: true,
			utilsScript: "<?= plugins_url( '/phoneinput/js/utils.js', __FILE__ ) ?>",
		});

		// Initialize Firebase
		var config = {
			apiKey: "AIzaSyDhKFN5r9bp1V-o6yrXahhJDAK-Bhdmlyc",
			authDomain: "go-edx.firebaseapp.com",
			databaseURL: "https://go-edx-default-rtdb.firebaseio.com",
			projectId: "go-edx",
			storageBucket: "go-edx.appspot.com",
			messagingSenderId: "869424516732",
			appId: "1:869424516732:web:84c791847e65bfd3dd83f9",
			measurementId: "G-2DPBQ16642"
		};
		firebase.initializeApp(config);

		// window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');s

		function verifyOtp(){
			window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
				'size': 'invisible',
			});
	
			firebase.auth().signInWithPhoneNumber(intltelinput.getNumber(), window.recaptchaVerifier) 
			.then(function(confirmationResult) {
				// At this point SMS is sent. Ask user for code. 
				var code = window.prompt('A confirmation message was just sent. Please enter the 6 digit code'); 
				return confirmationResult.confirm(code).then((result) => {
				// // User signed in successfully.
				// const user = result.user;
				document.querySelector('#span-phone-vrified').innerText = 'successfaully verified';
				}).catch((error) => {
					// window.recaptchaVerifier.reset();
					alert('Something went wrong. Please try again.');
					location.reload();
				});
			});
		}

		function submitForm(){
			if(!document.querySelector('#span-phone-vrified').innerText){
				alert('Phonenumber is not verified, Please verify');
				return;
			}
			document.querySelector('#tawarly_user_phone').value = intltelinput.getNumber();
			document.querySelector('#tawarly_registration_form').submit();
		}
    </script>
    <?php
    }
add_action( 'wp_footer', 'call_script_in_footer' );

/* ------------------------------------------------------------------------- */
// load our form css
/* ------------------------------------------------------------------------- */
function tawarly_print_css() {
	global $pippin_load_css;
 
	wp_print_styles('tawarly-phoneinput-css');

	// this variable is set to TRUE if the short code is used on a page/post
	if ( ! $tawarly_load_css )
		return; // this means that neither short code is present, so we get out of here
 
	wp_print_styles('tawarly-form-css');
}
add_action('wp_footer', 'tawarly_print_css');

/* ------------------------------------------------------------------------- */
// Disable Admin Bar for All Users Except for Administrators
/* ------------------------------------------------------------------------- */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}


