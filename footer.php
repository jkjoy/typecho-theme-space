<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!-- jQuery -->
<script src="<?php $this->options->themeUrl('/media/scripts/jquery.min.js');?>"></script>
<!-- img lazy load -->
<script src="<?php $this->options->themeUrl('/media/scripts/jquery.lazyload.min.js');?>"></script>
<!-- jQuery Easing -->
<script src="<?php $this->options->themeUrl('/media/scripts/jquery.easing.1.3.js');?>"></script>
<!-- Bootstrap -->
<script src="<?php $this->options->themeUrl('/media/scripts/bootstrap.min.js');?>"></script>
<!-- Waypoints -->
<script src="<?php $this->options->themeUrl('/media/scripts/jquery.waypoints.min.js');?>"></script>
<!-- Main JS -->
<script src="<?php $this->options->themeUrl('/media/scripts/main.js');?>"></script>
<!-- Md5 Min JS -->
<script src="<?php $this->options->themeUrl('/media/scripts/md5.min.js');?>"></script>
<!-- highlight -->
<script src="https://cdn.bootcss.com/highlight.js/9.15.8/highlight.min.js"></script>
<script type="application/javascript">
    // 代码高亮
    hljs.initHighlightingOnLoad();
    // img 懒加载
    $(function () {
        $("img.lazy").lazyload({
            effect: "fadeIn",  // 懒加载动画
            threshold: 180  // 在图片距离屏幕180px时提前载入
        });
        // tooltip
        $('[data-toggle="tooltip"]').tooltip();
        // 目录
        $('#tool-toc').click(function () {
            $('.post-toc').toggle();
        });
    });  
</script>
<footer id="fh5co-footer">
&copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>. 主题：<a target="_blank" href="https://imsun.org">Space</a>
<?php $this->options->tongji(); ?>
</footer>