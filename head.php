<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php if($this->_currentPage>1) echo '第 '.$this->_currentPage.' 页 - '; ?><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'date'      =>  _t('在<span> %s </span>发布的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php if ($this->is('post')) $this->category(',', false);?><?php if ($this->is('post')) echo ' - ';?><?php $this->options->title(); ?><?php if ($this->is('index')) echo ' - '; ?><?php if ($this->is('index')) $this->options->description() ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="<?php $this->options->description() ?>"/>
<meta name="keywords" content="<?php $this->options->description() ?>"/>
<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
<link rel="shortcut icon" href="<?php $this->options->icoUrl() ?>">
<link rel="stylesheet" href="<?php $this->options->themeUrl('/styles/main.css'); ?>">
<!-- Modernizr JS -->
<script src="<?php $this->options->themeUrl('/media/scripts/modernizr-2.6.2.min.js'); ?>"></script>
<link href="https://cdn.bootcss.com/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
<?php if($this -> options -> cssdiy): ?>
<style><?php $this->options->cssdiy() ?></style>
<?php endif; ?>