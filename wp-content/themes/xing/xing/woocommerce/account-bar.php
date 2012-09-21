<?php
/* WooCommerce Account Bar in header */

if (class_exists( 'woocommerce' )):
	global $woocommerce; ?>
	<nav id="account-bar">
        <ul class="account-nav">
            <li class="welcome">
            <?php if ( is_user_logged_in() ) {
            global $current_user;
            get_currentuserinfo();
            if($current_user->user_firstname)
				echo 'Welcome, ' . $current_user->user_firstname;
            elseif($current_user->display_name)
				echo 'Welcome, ' . $current_user->display_name;
            else
				echo 'Welcome, ' . $current_user->user_login;
            }
            else {
            _e( 'Welcome, Guest', 'xing' );
            } ?>
            </li>
            <?php if ( is_user_logged_in() ) { ?>
            <li><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e( 'View your account', 'xing' ); ?>"><?php _e('Account','xing'); ?></a></li>
            <li><a href="<?php echo $woocommerce->cart->get_checkout_url();?>" title="<?php _e( 'Proceed to checkout', 'xing' ) ?>"><?php _e( 'Checkout', 'xing' ) ?></a></li>
            <li><a class="log_out" href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e( 'Log out of your account', 'xing' ); ?>"><?php _e( 'Logout', 'xing' ); ?></a></li>
            <?php }
            else { ?>
            <li><a class="log_in" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e( 'Login or register for a new account', 'xing' ); ?>"><?php _e( 'Login / Register', 'xing' ); ?></a></li>
            <?php } ?>
            <li class="cart_status"><a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart', 'xing' ); ?>"><span class="cart-label"><?php echo sprintf( _n( '%d item<br/>in cart', '%d items<br/>in cart', $woocommerce->cart->cart_contents_count, 'xing' ), $woocommerce->cart->cart_contents_count); ?></span><?php echo $woocommerce->cart->get_cart_total(); ?></a></li>
        </ul><!-- .account-nav -->
	</nav><!-- #account-bar -->
<?php endif; ?>