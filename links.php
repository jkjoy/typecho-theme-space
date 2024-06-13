<?php 
/**
 * 链接
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html>
<head>
<style>
  a {
    text-decoration: none;
  }

  #card_one {
    margin-left: 25%;
  }

  h3{
    zoom: 80%;
  }

  .iconjinghao {
    zoom: 150%;
    color: cyan!important;
}

  .notes_card {
    font-weight: 300;
    /* box-sizing: border-box; */
    zoom: 70%;
    margin-top: 5px;
    margin-left: 5px;
    width: 20vw;
    background: rgba(255, 255, 255, 0.25);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    backdrop-filter: blur(8.0px);
    -webkit-backdrop-filter: blur(8.0px);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.18);
    display: inline-block;
    height: 235px;
    transition: 0.3s;
  }

  .notes_card:hover {
    box-shadow: 0 8px 39px 0 rgba(0, 0, 0, 0.37);
    transform: translateY(-10px);
  }

  .card-img {
    height: 160px;
    overflow: hidden;
    border-radius: 10px;
    margin: 2.5px;
    background-position: center;
    background-size: cover;
  }

  .card-u {
    padding-top: 10px;
    height: 45px;
    position: relative;
  }

  .card-u-a {
    width: 50px;
    position: absolute;
    left: 0;
    top: 10px;
  }

  .card-u-avatar {
    width: 50px;
    /* height: 35px; */
    border-radius: 10px;
    margin: 5px;
  }

  .card-u-t {
    box-sizing: border-box;
    width: 100%;
    padding-left: 50px;
  }

  .card-u-t-title {
    font-size: 20px;
    color: black;
    line-height: 25px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 300;
  }

  .card-u-t-text {
    font-weight: 300;
    font-size: 13px;
    color: var(--post-text-color);
    line-height: 20px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-left: 10px;
  }

  @media (max-width: 1000px) {
    .card {
      width: 45vw !important;
    }
    .notes_card{
        width: 40vw;
    }
  }
  @media (max-width: 500px) {
    .card {
      width: 90vw !important;
    }
    .notes_card{
        width: 100vw;
    }
  }     
</style>   
<?php $this->need('head.php'); ?>
</head>
<body>
<?php $this->need('header.php'); ?>
<div class="container-fluid">
    <div class="row fh5co-post-entry single-entry">
        <article class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
            <figure class="animate-box">
            <?php if($this->fields->cover): ?>
                <img src="<?php $this->fields->cover(); ?>" alt="<?php $this->title() ?>" class="img-responsive">
            <?php endif; ?>     
            </figure>
            <h2 class="fh5co-article-title animate-box"><?php $this->title() ?></h2>
            <div class="col-lg-12 col-lg-offset-0 col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0 text-left content-article">
                <div class="row">
                    <div class="col-md-12 animate-box post-content">
                        <p>     <?php $this->content(); ?> </p>
                          <?php
Links_Plugin::output('
    <div class="notes_card">
        <a target="_blank" href="{url}">
            <div class="card-img" style="background-image: url(https://image.thum.io/get/width/1024/crop/768/{url});"></div>
            <div class="card-u">
                <div class="card-u-a">
                    <img class="card-u-avatar" src="{image}" onerror="this.onerror=null; this.src=\'https://cdn.jsdelivr.net/gh/cdn-x/placeholder@1.0.1/link/8f277b4ee0ecd.svg\'">
                </div>
                <div class="card-u-t">
                    <p class="card-u-t-text">
                        <b class="card-u-t-title">{name}</b> 
                        <b class="card-u-t-text">{title}</b>
                    </p>
                </div>
            </div>
        </a>
    </div>');?>     
                    </div>
                </div>    
            </div>
        </article>
    </div>
</div>
<div class="back-to-top">
    <a href="#top" class="hidden-xs hidden-sm"><i class="fa fa-paper-plane"></i></a>
</div> 
    <?php $this->need('footer.php'); ?>  
</body>
</html>