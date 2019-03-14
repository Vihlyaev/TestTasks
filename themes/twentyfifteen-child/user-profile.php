<?php
/*
Template Name: User Profile
*/
get_header();
$user_id = $_GET[ 'id' ];
$user_info = get_userdata( $user_id );
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<header class="entry-header">
				<?php
				if ( is_single() ):
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( sprintf( '<h2 class="entry-title">', esc_url( get_permalink() ) ), '</h2>' );
				endif;
				?>
			</header>

			<div class="entry-content">
				<?php 					
					$url = get_avatar_url( $user_info->$user_id);
					$img = '<img style="float: right;" alt="" src="'. $url .'">';
					?>
				<h3>
					<?php echo $user_info->first_name, ' ', $user_info->last_name, $img;?>
				</h3>
				<p><strong>Email:</strong>
					<?php echo $user_info->user_email;?>
				</p>
				<p><strong>Логин:</strong>
					<?php echo $user_info->user_login;?>
				</p>
				<p><strong>Дата регистрации:</strong>
					<?php echo $user_info->user_registered;?>
				</p>
				<h3>Дополнительные метаполя</h3>
				<hr>
				<p><strong>Адрес:</strong>
					<?php echo $obj->decrypt(get_user_meta($user_id, 'address', true));?>
				</p>
				<p><strong>Телефон:</strong>
					<?php echo $obj->decrypt(get_user_meta($user_id, 'phone', true));?>
				</p>
				<p><strong>Пол:</strong>
					<?php echo $obj->decrypt(get_user_meta($user_id, 'gender', true));?>
				</p>
				<p><strong>Семейное положение:</strong>
					<?php echo $obj->decrypt(get_user_meta($user_id, 'family_status', true));?>
				</p>
			</div>
			<!-- .entry-content -->

			<footer class="entry-footer">
				<?php twentyfifteen_entry_meta(); ?>
				<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
			</footer>
			<!-- .entry-footer -->

		</article>
		<!-- #post-## -->

	</main>
	<!-- .site-main -->
</div> <!-- .content-area -->

<?php get_footer(); ?>