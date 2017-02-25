<!DOCTYPE html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?>Mr.Wayne</title>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" type="text/css">
<link href="<?php bloginfo( 'template_url' ); ?>/images/favicon.ico" rel="icon">
<?php
	if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	wp_enqueue_script( 'jquery' );
	wp_head();
?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?6b2c4b81386c507c881de93765b42d97";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body>
<header class="mod-head">
	<h1 class="mod-head__title"><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
	<div class="mod-head__logo hvr-buzz">
		<a href="<?php echo get_option('home'); ?>">
			<img class="avatar" src="<?php bloginfo( 'template_url' ); ?>/images/avatar.jpg" alt="" width="26" height="26">
		</a>
		<?php if (get_posts_count_from_last_24h() != '0') { ?>
			<div class="zjgx"><?php echo get_posts_count_from_last_24h(); ?></div>
		<?php } else { } ?>
	</div>
	<nav class="mod-head__nav">
	<?php 
		$top_nav = wp_nav_menu( array( 'theme_location'=>'main', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'mod-head__ul', 'echo'=>false, 'after'=>'<span>·</span>' ) );
		$top_nav = str_replace( "<span>·</span></li>\n</ul>", "</li>\n</ul>", $top_nav );
		echo $top_nav;
	?>
	</nav>
	<a id="right-panel-link" href="#right-panel"></a>
	<div id="right-panel" class="panel">
	<h3 class="rightnavh3" style="margin-left: 21px;
">Menu</h3>
		<?php 
			$top_nav = wp_nav_menu( array( 'theme_location'=>'main', 'fallback_cb'=>'', 'container'=>'', 'menu_class'=>'ymod-head', 'echo'=>false, 'after'=>'' ) );
			$top_nav = str_replace( "</li>\n</ul>", "</li>\n</ul>", $top_nav );
			echo $top_nav;
		?>
	<button id="close-panel-bt">X Close</button>
	</div>
	<script src="<?php bloginfo( 'template_url' ); ?>/js/slider.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.9.0/highlight.min.js"></script>
	<script>$('#right-panel-link').panelslider({side: 'right', duration: 200 });$('#close-panel-bt').click(function() {$.panelslider.close();});</script>
        <script>hljs.initHighlightingOnLoad();</script>
</header>