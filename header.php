<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="fh5co-offcanvas">
    <a href="#!" class="fh5co-close-offcanvas js-fh5co-close-offcanvas">
        <span><i class="icon-cross3"></i> <span>Close</span></span>
    </a>
    <div class="fh5co-bio">
        <figure>
            <img src="<?php $this->options->logoUrl() ?>" alt="<?php $this->options->title() ?>"
                 class="img-responsive">
        </figure>
        <a href="<?php $this->options->infoUrl(); ?>"><h3 class="heading">ABOUT ME</h3></a>
        <h2><?php $this->options->title() ?></h2>
        <p><?php $this->options->description() ?></p>
        <?php if($this -> user -> hasLogin()): ?>
            <p><a href="<?php $this -> options -> adminUrl(); ?>" target="_blank">进入后台</a></p>
        <?php endif; ?>
        <ul class="fh5co-social">
        <li>
                        <a href="<?php $this->options->weiboUrl() ?>" target="_blank">
                            <i class="fab fa-weibo"></i>
                        </a>
        </li>
        <li>
                        <a href="<?php $this->options->githubUrl() ?>" target="_blank">
                            <i class="fab fa-github"></i>
                        </a>
        </li>
        <li>
                        <a href="<?php $this->options->zhihuUrl() ?>" target="_blank">
                            <i class="fab fa-zhihu"></i>
                        </a>
        </li>
        <li>
                        <a href="<?php $this->options->twitterUrl() ?>" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
        </li>
        </ul>
    </div>
    <div class="fh5co-menu">
        <!-- 标签 -->
        <div class="fh5co-box">
            <a href="<?php $this->options->tagUrl(); ?>"><h3 class="heading">👉标签</h3></a>
            <?php $this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 20))->to($tags); ?> 
            <?php while($tags->next()): ?> 
            <a rel="tag" href="<?php $tags->permalink(); ?>"><?php $tags->name(); ?></a> 
            <?php endwhile; ?>                
        </div>
    </div>
</div>
<header id="fh5co-header">
    <div class="container-fluid">
        <div class="row">
            <a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle"><i></i></a>
            <ul class="fh5co-social">
                    <li class="whome"><a <?php if($this->is('index')): ?> class="current"<?php endif; ?> href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a></li>
                    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                    <?php while($pages->next()): ?>
                    <li><a <?php if($this->is('page', $pages->slug)): ?> class="current"<?php endif; ?> href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a></li>
                    <?php endwhile; ?>
            </ul>
            <div class="col-lg-12 col-md-12 text-center">
                <h1 id="fh5co-logo">
                    <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?> </a>
                </h1>
            </div>
        </div>
    </div>
</header>