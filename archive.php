<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<!DOCTYPE html>
<html>
  <head>
    <?php $this->need('head.php'); ?>
  </head>
  <body>
    <?php $this->need('header.php'); ?>
    <div class="container-fluid">
      <div class="row fh5co-post-entry">
	  <?php while($this->next()): ?>
            <article class="col-lg-3 col-md-3 col-sm-3 col-xs-12 col-xxs-12 animate-box" style="height: 25em;">
            <figure class="img-box">
              <a href="<?php $this->permalink() ?>">         
              <?php
                  $firstImage = img_postthumb($this->cid);
                  $cover = $this->options->bgUrl;
                  $imageToDisplay = !empty($firstImage) ? $firstImage : $cover;
                  if($imageToDisplay): ?>  
                    <img data-original="<?php echo $imageToDisplay; ?>" alt="<?php $this->title() ?>" class="img-responsive img-rounded lazy">
                    <?php endif; ?>    </a> 
            </figure>
            <span class="fh5co-meta">
            <?php $this->tags(', ', true, '无标签'); ?>
            </span>
            <h3 class="fh5co-article-title">
			<a href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
            </h3>
            <span class="fh5co-meta fh5co-date"><?php $this->date('Y-m-d'); ?></span>
          </article>
		<?php endwhile; ?>
      </div>
		  <?php
            $this->pagenav(
                '<i class="fa fa-chevron-left"></i>',
                '<i class="fa fa-chevron-right"></i>',
                 1,
                '',
                array(
                    'wrapTag' => 'div',
                    'wrapClass' => 'pagination-box animate-box pagination_page',
                    'itemTag' => 'span',
                    'textTag' => 'a',
                    'currentClass' => 'active',
                    'prevClass' => 'prev',
                    'nextClass' => 'next'
                )
            );
        ?>
    </div>
    <?php $this->need('footer.php'); ?>
  </body>
</html>
<style>
    
/* 分页 */
.pagination_page{
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: var(--margin);
    gap: 8px;
}
.pagination_page li.active a {
    background: var(--theme);
    color: #fff;
    font-weight: 500;
}
.pagination_page a{
    display: flex;
    padding: 8px;
    font-size: 16px;
    width: 20px;
    height: 20px;
    background: var(--background);
    border-radius: 50%;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    transition: 0.2s;
    -webkit-transition: 0.2s;
    justify-content: center;
    align-items: center;
    letter-spacing: 0;
}
.pagination_page span.next{
    cursor: pointer;
}
.pagination_page li.active a:hover{
    cursor: not-allowed;
}
/* 分页 */
</style>