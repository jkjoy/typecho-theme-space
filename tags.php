<?php 
/**
 * 标签
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html>
<head>
<?php $this->need('head.php'); ?>
</head>
<body>
<?php $this->need('header.php'); ?>
<div class="container-fluid" style="height: 100%;">
    <div class="row fh5co-post-entry">
    <?php $this->widget('Widget_Metas_Tag_Cloud', 'sort=mid&ignoreZeroCount=1&desc=0')->to($tags); ?>
                    <?php if($tags->have()): ?><?php while ($tags->next()): ?>
                            <article class="col-lg-3 col-md-3 col-sm-3 col-xs-12 col-xxs-12 animate-box">
                                
                                    <h2 class="fh5co-article-title">
                                    <i class="fa fa-tag" style="opacity: .6; font-size: 19px;"></i>
                                        <a role="listitem" target="<?php $this->options->sidebarLinkOpen(); ?>" data-toggle="tooltip" data-placement="top" href="<?php $tags->permalink(); ?>" rel="tag" title="<?php $tags->count(); ?> 篇文章"><?php $tags->name(); ?> </a>
                                    </h2>
                              
                            </article>  <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-center pb-2"><?php _e('没有任何标签'); ?></p>
                        <?php endif; ?>                   
   </div>
    <?php $this->need('footer.php'); ?>
</div>
</body>
</html>