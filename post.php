<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html>
<head>
<?php $this->need('head.php'); ?>
</head>
<body>
<?php $this->need('header.php'); ?>
<div class="container-fluid">
    <div class="row fh5co-post-entry single-entry">
        <article class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
            <figure class="animate-box">
            <?php
                  $firstImage = img_postthumb($this->cid);
                  $cover = $this->fields->cover;
                  $imageToDisplay = !empty($firstImage) ? $firstImage : $cover;
                  if($imageToDisplay): ?>  
                    <img src="<?php echo $imageToDisplay; ?>" alt="<?php $this->title() ?>" class="img-responsive">
                    <?php endif; ?> 
            </figure>
            <span class="fh5co-meta animate-box">
                    <div class="tag-container">
                    <?php $this->tags(', ', true, ' '); ?>
                    </div>
            </span>
            <h2 class="fh5co-article-title animate-box"><?php $this->title() ?></h2>
            <ol class="breadcrumb fh5co-meta fh5co-date animate-box" style="margin-bottom:0; background-color:#f8f9fa">
                <li>发布日期:<?php $this->date('Y-m-d'); ?></li>
                <li>所属分类:<?php $this->category(','); ?></li>
                <li>浏览 <?php get_post_view($this) ?> 次</li>
                <li><a href="<?php $this->permalink() ?>#comments"><?php $this->commentsNum('暂无评论', '1 评论', '%d 评论'); ?></a></li>
            </ol>
            <div class="col-lg-12 col-lg-offset-0 col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0 text-left content-article">
                <div class="row">
                    <div class="col-md-12 animate-box post-content">
                        <p>     <?php $this->content(); ?> </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 animate-box">
                            <div class="next-post">
                                <div class="next">下一篇</div>
                                <h3 class="post-title">
                                    <?php $this->theNext('%s','没有了'); ?> 
                                </h3>
                            </div>
                    </div>
                </div>
                <?php $this->options->pinglun(); ?>
            </div>
        </article>
    </div>
</div>
<div class="back-to-top">
        <a href="#!" id="tool-toc" class="hidden-xs hidden-sm">
            <i class="fa fa-map"></i>
        </a>
        <br> 
    <a href="#top" class="hidden-xs hidden-sm"><i class="fa fa-paper-plane"></i></a>
</div> 
    <!-- 返回顶部 -->
    <div class="post-toc animated fadeInRight hidden-xs hidden-sm" style="display: none;">
        <?php echo toc($this->content); ?>
    </div>
    <?php $this->need('footer.php'); ?>    
</body>
</html>
