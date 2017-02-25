<?php
/*
Template Name: Archive
*/
?>
<?php get_header(); ?>
<style type="text/css">
.fr{float: right;font-size: 13px;line-height: 25px;color: rgba(0,0,0,.6);}
#primary h3 ,.catalog-title{color: rgba(0,0,0,.7);font-family: "PingFang SC", "Microsoft JHengHei";font-size: 18px;}
#primary h3::before,.catalog-title::before {content: "[";margin-right: 3px;color: #42b983;font-size: 16px;}
#primary h3::after,.catalog-title::after {content: "]";margin-left: 3px;color: #42b983;font-size: 16px;}
.archive-title {padding-bottom: 25px;}
.archives a {font-family: "PingFang SC", "Microsoft JHengHei";display: block;padding: 8px 0;letter-spacing: 0.5px;font-size: 15px;text-decoration:none;transition:all 0.15s}
.archives i {color: #939393;float: right;font-size: 13px;font-style: normal;}
.time {color: #939393;padding-right: 10px;}
.mimelove_tags {border-bottom: 1px solid #eee;padding-bottom: 20px;}
.mimelove_tags a {font-family: "PingFang SC", "Microsoft JHengHei";padding: 5px 10px;display: inline-block;font-size: 15px !important;text-decoration:none;transition:all 0.15s}
.archives a:hover,.mimelove_tags a:hover{color: rgba(119, 119, 119, 1);}
</style>
<article class="mod-post mod-post__type-page">
	<header>
		<h1 class="mod-post__title"><?php the_title(); ?></h1>
	</header>
<div id="primary">
    <div class="post-content">
        <?php
	    $the_query = new WP_Query('posts_per_page=-1&ignore_sticky_posts=1'); //update: 加上忽略置顶文章
    $year = 0;
    $mon = 0;
    $i = 0;
    $j = 0;
    $all = array();
    $output = '<div id="archives">';
    while ($the_query->have_posts()) : $the_query->the_post();
    $year_tmp = get_the_time('Y');
    $mon_tmp = get_the_time('n');
    //var_dump($year_tmp);
     $y = $year;
    $m = $mon;
    if ($mon != $mon_tmp && $mon > 0)
        $output .= '</div></div>';
    if ($year != $year_tmp) {
        $year = $year_tmp;
        $all[$year] = array();
    }
    if ($mon != $mon_tmp) {
        $mon = $mon_tmp;
        array_push($all[$year], $mon);
        $output .= "<div class='archive-title' id='arti-$year-$mon'><h3>$year-$mon<a class='fr' href='/date/$year/$mon'></a></h3><div class='archives archives-$mon' data-date='$year-$mon'>"; //输出月份
    }
    $output .= '<a class="hvr-grow" href="' . get_permalink() . '"><span class="time">' . get_the_time('n-d') . '</span>' . get_the_title() . '<i>'  . get_comments_number() .' 则留言</i></a>'; //输出文章日期和标题
    endwhile;
    wp_reset_postdata();
    $output .= '</div></div></div>';
    echo $output;
    ?>
    </div>
    <div class="mimelove_tags">
        <h3>标签云</h3>
            <p class="hvr-grow"><?php wp_tag_cloud(); ?></p>
    </div>
</div>
<?php get_footer(); ?>