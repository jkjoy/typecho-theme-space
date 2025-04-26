<?php 
/**
 * 文章归档
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('head.php'); ?>
<?php $this->need('header.php'); ?>
<div class="container-fluid">
    <div class="row fh5co-post-entry single-entry">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0 animate-box">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-2 col-xs-12 col-xs-offset-0 text-center content-article">
                <div class="row">
                    <h2 class="text-center archives-title">
                        <i class="fa fa-archive"></i> 文章归档
                    </h2>
<?php
$stat = Typecho_Widget::widget('Widget_Stat');
Typecho_Widget::widget('Widget_Contents_Post_Recent', 'pageSize=' . $stat->publishedPostsNum)->to($archives);
$year = 0;
$output = '<div class="row post-group">'; // Start archives container
while ($archives->next()) {
$year_tmp = date('Y', $archives->created);
    // 检查是否需要新的年份标题
    if ($year != $year_tmp) {
        if ($year > 0) {
            $output .= '</div>'; // 关闭前一年的div
        }
        $year = $year_tmp;
        $output .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-offset-0 text-center post-year tag-title">'; // 开始新的年份div
        $output .= '<h3>' . $year . '年</h3>';
    }
    $output .= ' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-offset-0 text-left"> <ul class="post-list"><li class="post-item">';
    $output .= '<a href="' . $archives->permalink . '">';
    $output .= '<span class="post-title">' . $archives->title . '</span>';
    $output .= '<span class="post-day">' . date('m月d日', $archives->created) . '</span>';
    $output .= '</a></li>';

    $output .= '</ul></div>';
}
$output .= '</div>'; // 关闭最后一年的div
$output .= '</div>'; // End archives container
echo $output;
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->need('footer.php'); ?>