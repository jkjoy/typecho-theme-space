<?php
/**
 * Space  for  Typecho
 * @package Space
 * @author Sun
 * @version 1.1.1
 * @link https://www.imsun.org
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit; 
?>
<?php $this->need('head.php'); ?>  
<?php $this->need('header.php'); ?>
    <div class="container-fluid">
    <div class="row fh5co-post-entry">
	  <?php while($this->next()): ?>
            <article class="col-lg-3 col-md-3 col-sm-3 col-xs-12 col-xxs-12 animate-box" style="height: 25em;">
            <figure class="img-box">
              <a href="<?php $this->permalink() ?>">
              <?php
                  $thumb = getPostThumbnail($this->cid);
                  if (!empty($thumb)) {
                     echo '<img data-original="' . $thumb . '" alt="<?php $this->title() ?>" class="img-responsive img-rounded lazy">';
                    } else {
                      echo '<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2MDAgMzUwIiB3aWR0aD0iNjAwIiBoZWlnaHQ9IjM1MCI+CiAgPHJlY3Qgd2lkdGg9IjYwMCIgaGVpZ2h0PSIzNTAiIGZpbGw9IiNjY2NjY2MiPjwvcmVjdD4KICA8dGV4dCB4PSI1MCUiIHk9IjUwJSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9Im1vbm9zcGFjZSIgZm9udC1zaXplPSIyNnB4IiBmaWxsPSIjMzMzMzMzIj7mmoLml6Dlm77niYc8L3RleHQ+ICAgCjwvc3ZnPg==" alt="无图片" class="img-responsive img-rounded lazy">';
                    }
                ?>
              </a>
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
