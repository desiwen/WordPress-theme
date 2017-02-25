<?php
/**
 * 404页面
 *
 */
get_header(); ?>

	<center><h1 class="not-found-title hvr-wobble-horizontal"><?php esc_html_e( '404', 'simple-mind' ); ?></h1>
	<br><br>
        <h2 class="not-found-message hvr-grow"><?php esc_html_e( '哎呀！你找的东西好像不存在了哎。' ); ?></h2>
        <br><br>
	<a class="not-found-back" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '返回主页', 'simple-mind' ); ?></a></center>

<?php
get_footer();
