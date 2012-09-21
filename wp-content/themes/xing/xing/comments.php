<?php
/* Comments Template */ ?>

<div id="comments">
<?php if ( post_password_required() ) : ?>
<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'xing' ); ?></p>
</div><!-- #comments -->
<?php return;
endif;
if ( have_comments() ) : ?>
    <h4 id="comments-title"><?php printf( _n( 'One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'xing' ), number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' ); ?></h4>
    <ol class="commentlist"><?php wp_list_comments( array( 'callback' => 'xing_comments' ) ); ?></ol>
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
    <div class="navigation">
        <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'xing' ) ); ?></div>
        <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'xing' ) ); ?></div>
    </div><!-- .pagination -->
    <?php endif; // comment navigation
endif; // have_comments()

if ( comments_open() ) :
	$custom_args = array(
		'must_log_in' => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'xing' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
		'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'xing' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
		'title_reply' => __( 'Leave a reply', 'xing' ),
		'comment_notes_after'  => '',
		'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" rows="8" aria-required="true"></textarea><label for="comment">' . __( 'Comment*', 'xing' ) . '</label></p>',
		'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published. ', 'xing' ) . ( $req ? __('Required fields are marked *', 'xing') : '' ) . '</p>',
		'label_submit' => __( 'Post Comment', 'xing' )
	);
	comment_form($custom_args);
endif; ?>
</div><!-- #comments -->