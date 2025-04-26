<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('head.php'); ?>
<?php $this->need('header.php'); ?>
<div class="container-fluid">
    <div class="row fh5co-post-entry single-entry">
        <article class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
            <figure class="animate-box">
            <?php if($this->fields->cover): ?>
                <img src="<?php $this->fields->cover(); ?>" alt="<?php $this->title() ?>" class="img-responsive">
            <?php endif; ?>
            </figure>
            <span class="fh5co-meta animate-box">
                    <div class="tag-container">
                    <?php $this->tags(', ', true, ' '); ?>
                    </div>
            </span>
            <h2 class="fh5co-article-title animate-box"><?php $this->title() ?></h2>
            <ol class="breadcrumb fh5co-meta fh5co-date animate-box" style="margin-bottom:0; background-color:#f8f9fa">
                <li><?php $this->date('Y-m-d'); ?></li>
                <li><?php get_post_view($this) ?></li>
                <li> </li>
            </ol>
            <div class="col-lg-12 col-lg-offset-0 col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0 text-left content-article">
                <div class="row">
                    <div class="col-md-12 animate-box post-content">
                        <p>     <?php $this->content(); ?> </p>
                    </div>
                </div>
                <?php $this->options->pinglun(); ?>
            </div>
        </article>
    </div>
</div>
<?php $this->need('footer.php'); ?>  