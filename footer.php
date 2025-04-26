<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!-- 返回顶部 -->
<div class="back-to-top">
         <a href="#!" id="tool-toc" class="hidden-xs hidden-sm">
         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="32" height="32">
            <path d="M384 476.1L192 421.2l0-385.3L384 90.8l0 385.3zm32-1.2l0-386.5L543.1 37.5c15.8-6.3 32.9 5.3 32.9 22.3l0 334.8c0 9.8-6 18.6-15.1 22.3L416 474.8zM15.1 95.1L160 37.2l0 386.5L32.9 474.5C17.1 480.8 0 469.2 0 452.2L0 117.4c0-9.8 6-18.6 15.1-22.3z"/>
        </svg>
         </a>
         <br> 
     <a href="#top" class="hidden-xs hidden-sm">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="32" height="32">
            <path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2 160 448c0 17.7 14.3 32 32 32s32-14.3 32-32l0-306.7L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/>
        </svg>
     </a>
 </div> 
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
<script src="<?php $this->options->themeUrl('/media/scripts/highlight.min.js');?>"></script>
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
</body>
</html>