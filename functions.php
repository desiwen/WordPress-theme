<?php
//theme info
include( 'info.php' );
// 文章图片灯箱
	add_filter('the_content', 'cufixed_zoom');
	function cufixed_zoom ($content)
	{ global $post;
	    $pattern = "/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)/i";
	    $replacement = '<img$1src=$2$3.$4$5 data-action="zoom" $6>';
	    $content = preg_replace($pattern, $replacement, $content);
	    return $content;
	}

//特色图像
add_theme_support( 'post-thumbnails' );


//谷歌字体
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
add_action( 'init', 'remove_open_sans' );

// 后台文本编辑器增加按钮
	function download($atts, $content = null) {
		return '<a class="download" href="'.$content.'" rel="external" target="_blank" title="下载地址"><span><i class="icon-download"></i>Download</span></a>';}

		add_shortcode("download", "download"); 

		add_action('after_wp_tiny_mce', 'bolo_after_wp_tiny_mce');

		function bolo_after_wp_tiny_mce($mce_settings) {
		?>  
			<script type="text/javascript">  
				QTags.addButton( 'download', '下载按钮', "[download]下载地址[/download]" );
			    QTags.addButton('hr', '横线', "<hr />\n");//添加横线
	            QTags.addButton('h3', 'H3标签', "<h3>", "</h3>\n"); //添加标题3
	            QTags.addButton('h4', 'H4标签', "<h4>", "</h4>\n"); //添加标题3
	            QTags.addButton('sb', '上标', "<sup>", "</sup>");
	            QTags.addButton('xb', '下标', "<sub>", "</sub>");
	            QTags.addButton('shsj', '首行缩进', "&nbsp;&nbsp;");
	            QTags.addButton('hc', '回车', "<br />");
	            QTags.addButton('jz', '居中', "<center>", "</center>");
	            QTags.addButton('mark', '黄字', "<mark>", "</mark>");
	            QTags.addButton('xhx', '下划线', "<u>", "</u>");
	            QTags.addButton('embed', '文章引用', "[simplemind_insert_post ids=文章id]");
			function bolo_QTnextpage_arg1() {
			}  
			</script>
		<?php 
	}

//注册导航
register_nav_menus(
      array(
       'main' => __( '主菜单导航' )
      )
   );
   
//禁止代码标点转换
remove_filter('the_content', 'wptexturize');

//编辑器增强
 function enable_more_buttons($buttons) {
     $buttons[] = 'hr';
     $buttons[] = 'del';
     $buttons[] = 'sub';
     $buttons[] = 'sup'; 
     $buttons[] = 'fontselect';
     $buttons[] = 'fontsizeselect';
     $buttons[] = 'cleanup';   
     $buttons[] = 'styleselect';
     $buttons[] = 'wp_page';
     $buttons[] = 'anchor';
     $buttons[] = 'backcolor';
     return $buttons;
     }
add_filter("mce_buttons_3", "enable_more_buttons");

//给文章图片自动添加alt和title信息
add_filter('the_content', 'imagesalt');
function imagesalt($content) {
       global $post;
       $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
       $replacement = '<a$1href=$2$3.$4$5 alt="'.$post->post_title.'" title="'.$post->post_title.'"$6>';
       $content = preg_replace($pattern, $replacement, $content);
       return $content;
}

/*激活友情链接后台*/
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//文章字数统计
function count_words ($text) {  
global $post;  
if ( '' == $text ) {  
   $text = $post->post_content;  
   if (mb_strlen($output, 'UTF-8') < mb_strlen($text, 'UTF-8')) $output .= '共写了' . mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8') . '个字';  
   return $output;  
}  
}

//头像问题
function get_ssl_avatar($avatar) {
   $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2" class="avatar avatar-$2" height="50" width="50">',$avatar);
   return $avatar;
}
add_filter('get_avatar', 'get_ssl_avatar');

//去除分类标志代码
add_action( 'load-themes.php',  'no_category_base_refresh_rules');
add_action('created_category', 'no_category_base_refresh_rules');
add_action('edited_category', 'no_category_base_refresh_rules');
add_action('delete_category', 'no_category_base_refresh_rules');
function no_category_base_refresh_rules() {
    global $wp_rewrite;
    $wp_rewrite -> flush_rules();
}
// register_deactivation_hook(__FILE__, 'no_category_base_deactivate');
// function no_category_base_deactivate() {
//  remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
//  // We don't want to insert our custom rules again
//  no_category_base_refresh_rules();
// }
// Remove category base
add_action('init', 'no_category_base_permastruct');
function no_category_base_permastruct() {
    global $wp_rewrite, $wp_version;
    if (version_compare($wp_version, '3.4', '<')) {
        // For pre-3.4 support
        $wp_rewrite -> extra_permastructs['category'][0] = '%category%';
    } else {
        $wp_rewrite -> extra_permastructs['category']['struct'] = '%category%';
    }
}
// Add our custom category rewrite rules
add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
function no_category_base_rewrite_rules($category_rewrite) {
    //var_dump($category_rewrite); // For Debugging
    $category_rewrite = array();
    $categories = get_categories(array('hide_empty' => false));
    foreach ($categories as $category) {
        $category_nicename = $category -> slug;
        if ($category -> parent == $category -> cat_ID)// recursive recursion
            $category -> parent = 0;
        elseif ($category -> parent != 0)
            $category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
        $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
        $category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
        $category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
    }
    // Redirect support from Old Category Base
    global $wp_rewrite;
    $old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
    $old_category_base = trim($old_category_base, '/');
    $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';
    //var_dump($category_rewrite); // For Debugging
    return $category_rewrite;
}
// Add 'category_redirect' query variable
add_filter('query_vars', 'no_category_base_query_vars');
function no_category_base_query_vars($public_query_vars) {
    $public_query_vars[] = 'category_redirect';
    return $public_query_vars;
}
// Redirect if 'category_redirect' is set
add_filter('request', 'no_category_base_request');
function no_category_base_request($query_vars) {
    //print_r($query_vars); // For Debugging
    if (isset($query_vars['category_redirect'])) {
        $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
        status_header(301);
        header("Location: $catlink");
        exit();
    }
    return $query_vars;
}

//禁json
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );


//禁emoj
function disable_embeds_init() {
    /* @var WP $wp */
    global $wp;
    // Remove the embed query var.
    $wp->public_query_vars = array_diff( $wp->public_query_vars, array(
        'embed',
    ) );
    // Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    // Turn off
    add_filter( 'embed_oembed_discover', '__return_false' );
    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
    // Remove all embeds rewrite rules.
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
add_action( 'init', 'disable_embeds_init', 9999 );
/**
 * Removes the 'wpembed' TinyMCE plugin.
 *
 * @since 1.0.0
 *
 * @param array $plugins List of TinyMCE plugins.
 * @return array The modified list.
 */
function disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}
/**
 * Remove all rewrite rules related to embeds.
 *
 * @since 1.2.0
 *
 * @param array $rules WordPress rewrite rules.
 * @return array Rewrite rules without embeds rules.
 */
function disable_embeds_rewrites( $rules ) {
    foreach ( $rules as $rule => $rewrite ) {
        if ( false !== strpos( $rewrite, 'embed=true' ) ) {
            unset( $rules[ $rule ] );
        }
    }
    return $rules;
}

/**
 * Remove embeds rewrite rules on plugin activation.
 *
 * @since 1.2.0
 */
function disable_embeds_remove_rewrite_rules() {
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );
/**
 * Flush rewrite rules on plugin deactivation.
 *
 * @since 1.2.0
 */
function disable_embeds_flush_rewrite_rules() {
    remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );

/**最新文章数*/
function get_posts_count_from_last_24h($post_type ='post') {
    global $wpdb;
    $numposts = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(ID) ".
            "FROM {$wpdb->posts} ".
            "WHERE ".
                "post_status='publish' ".
                "AND post_type= %s ".
                "AND post_date> %s",
            $post_type, date('Y-m-d H:i:s', strtotime('-24 hours'))
        )
    );
    return $numposts;
}
//注册状态形式
add_theme_support( 'post-formats', array( 'status' ) );
//友情链接页面
function get_the_link_items($id = null){
    $bookmarks = get_bookmarks('orderby=date&category=' . $id);
    $output    = '';
    if (!empty($bookmarks)) {
        $output .= '<div class="catalog-share">' . count($bookmarks) . ' items in collection</div><div class="userItems">';
        foreach ($bookmarks as $bookmark) {
            $output .= '<div class="userItem"><div class="userItem--inner"><div class="userItem-content">'. get_avatar($bookmark->link_notes,64) . '
            <div class="userItem-name"><a class="link link--primary" href="' . $bookmark->link_url . '" target="_blank" >' . $bookmark->link_name . '</a></div></div></div></div>';
        }
        $output .= '</div>';
    }
    return $output;
}

function get_link_items(){
    $linkcats = get_terms('link_category');
    if (!empty($linkcats)) {
        foreach ($linkcats as $linkcat) {
            $result .= '
            <h3 class="catalog-title" style="
    margin-bottom: 2px">' . $linkcat->name . '</h3><div class="catalog-description">' . $linkcat->description . '</div>
            ';
            $result .= get_the_link_items($linkcat->term_id);
        }
    } else {
        $result = get_the_link_items();
    }
    return $result;
}

function shortcode_link(){
    return get_link_items();
}
add_shortcode('bigfalink', 'shortcode_link');
//pageview
function custom_the_views($post_id, $echo=true, $views=' views') {
 $count_key = 'views';
 $count = get_post_meta($post_id, $count_key, true);
 if ($count == '') {
 delete_post_meta($post_id, $count_key);
 add_post_meta($post_id, $count_key, '0');
 $count = '0';
 }
 if ($echo)
 echo number_format_i18n($count) . $views;
 else
 return number_format_i18n($count) . $views;
}
function set_post_views() {
 global $post;
 $post_id = $post->ID;
 $count_key = 'views';
 $count = get_post_meta($post_id, $count_key, true);
 if (is_single() || is_page()) {
 if ($count == '') {
 delete_post_meta($post_id, $count_key);
 add_post_meta($post_id, $count_key, '0');
 } else {
 update_post_meta($post_id, $count_key, $count + 1);
 }
 }
}
add_action('get_header', 'set_post_views');

// 文章引用
function simplemind_insert_posts( $atts, $content = null ){
    extract( shortcode_atts( array(
        'ids' => ''
    ),
        $atts ) );
    global $post;
    $content = '';
    $postids =  explode(',', $ids);
    $inset_posts = get_posts(array('post__in'=>$postids));
    foreach ($inset_posts as $key => $post) {
        setup_postdata( $post );
        $content .=  '
			<div class="wp-embed-post hvr-glow">
			    <a href="#" target="_blank" class="wp-embed-post-img" style="background-image:url('. trailingslashit( get_stylesheet_directory_uri() ) . 'images/embed-img.png' .')"></a>
			        <a href="'. get_permalink() .'" target="_blank"><span class="wp-embed-post-title">' . get_the_title() . '</span></a><em class="wp-embed-post-excerpt">'.wp_trim_words( get_the_content(), 60, '...' ).'</em>
			        <div class="wp-embed-post-meta">
			        	<span>' . get_the_time('Y年n月j日') . '</span>
			            <a target="_blank" href="' . get_permalink() . '#comments"> 评论 ' . get_comments_number(). '</a>
			        </div>
			</div>
        ';
    }
    wp_reset_postdata();
    return $content;
}
add_shortcode('simplemind_insert_post', 'simplemind_insert_posts');


//添加状态文章模板
//add_action('template_include', 'load_single_template');
//function load_single_template($template) {
//  $new_template = '';
//  if( is_single() ) {
//    global $post;
//    if ( has_post_format( 'status' )) {
//      $new_template = locate_template(array('single-status.php' ));
//    }
//  }
//  return ('' != $new_template) ? $new_template : $template;
//}

//标签去a
function tages(){
	global $post;
	$a = wp_get_post_tags($post->ID);
	if( $a ){
	foreach($a as $b ){
		$c .= $b->name.', ';
	}
	echo ''.rtrim($c, ' , ').'';
	}
}

//对分类目录描述改写
function ithink_del_tags($str){
return trim(strip_tags($str));
}
add_filter('category_description', 'ithink_del_tags');