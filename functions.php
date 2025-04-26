<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
function themeConfig($form) {
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点 LOGO 地址'));
    $form->addInput($logoUrl);
    $icoUrl = new Typecho_Widget_Helper_Form_Element_Text('icoUrl', NULL, NULL, _t('站点 Favicon 地址'));
    $form->addInput($icoUrl);
    $tagUrl = new Typecho_Widget_Helper_Form_Element_Text('tagUrl', NULL, NULL, _t('标签页面 地址'));
    $form->addInput($tagUrl);
    $infoUrl = new Typecho_Widget_Helper_Form_Element_Text('infoUrl', NULL, NULL, _t('关于页面 地址'));
    $form->addInput($infoUrl);
    $githubUrl = new Typecho_Widget_Helper_Form_Element_Text('githubUrl', NULL, 'https://github.com', _t('Github 地址'));
    $form->addInput($githubUrl);
    $weiboUrl = new Typecho_Widget_Helper_Form_Element_Text('weiboUrl', NULL, 'https://weibo.com', _t('微博 地址'));
    $form->addInput($weiboUrl);
    $zhihuUrl = new Typecho_Widget_Helper_Form_Element_Text('zhihuUrl', NULL, 'https://zhihu.com', _t('知乎 地址'));
    $form->addInput($zhihuUrl);
    $twitterUrl = new Typecho_Widget_Helper_Form_Element_Text('twitterUrl', NULL, 'https://x.com', _t('推特 地址'));
    $form->addInput($twitterUrl);
    $bgUrl = new Typecho_Widget_Helper_Form_Element_Text('bgUrl', NULL, NULL, _t('默认头图 地址'));
    $form->addInput($bgUrl);
    $tongji = new Typecho_Widget_Helper_Form_Element_Textarea('tongji', NULL, NULL, _t('统计代码'));
    $form->addInput($tongji);
    $pinglun = new Typecho_Widget_Helper_Form_Element_Textarea('pinglun', NULL, NULL, _t('评论代码'));
    $form->addInput($pinglun);
    $cssdiy = new Typecho_Widget_Helper_Form_Element_Textarea('cssdiy', NULL, NULL, _t('自定义CSS'));
    $form->addInput($cssdiy);
}
function get_post_view($archive) {
    $cid = $archive->cid;
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
        echo 0;
        return;
    }
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($archive->is('single')) {
        $views = Typecho_Cookie::get('extend_contents_views');
        if (empty($views)) {
            $views = array();
        } else {
            $views = explode(',', $views);
        }
        if (!in_array($cid, $views)) {
            $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
            array_push($views, $cid);
            $views = implode(',', $views);
            Typecho_Cookie::set('extend_contents_views', $views); //记录查看cookie 
        }
    }
    echo $row['views'];
}
/**
 * 获取文章缩略图（优先自定义字段 cover，其次文章图片，最后默认图片）
 * @param int $cid 文章ID
 * @return string 图片URL
 */
function getPostThumbnail($cid) {
    $db = Typecho_Db::get();
    // 1. 优先检查自定义字段 cover
    $cover = $db->fetchRow($db->select('table.fields.str_value')
        ->from('table.fields')
        ->where('table.fields.cid = ?', $cid)
        ->where('table.fields.name = ?', 'cover'));
    if ($cover && !empty($cover['str_value'])) {
        return $cover['str_value']; // 直接返回 cover 字段的图片URL
    }
    // 2. 如果没有 cover，尝试获取文章内容中的第一张图片
    $thumbFromContent = img_postthumb($cid);
    if (!empty($thumbFromContent)) {
        return $thumbFromContent;
    }
    // 3. 如果前两者都没有，返回后台设置的默认图片（bgUrl）
    $options = Typecho_Widget::widget('Widget_Options');
    return $options->bgUrl ?? ''; // 如果 bgUrl 未设置，返回空字符串
}

/**
 * 从文章内容中提取第一张图片（原函数）
 */
function img_postthumb($cid) {
    $db = Typecho_Db::get();
    $rs = $db->fetchRow($db->select('table.contents.text')
        ->from('table.contents')
        ->where('table.contents.cid = ?', $cid)
        ->limit(1)); 
    if (!$rs || empty($rs['text'])) {
        return "";
    }
    // 正则匹配图片URL
    preg_match_all("/https?:\/\/[^\s]*\.(png|jpeg|jpg|gif|bmp|webp)/i", $rs['text'], $matches);
    return $matches[0][0] ?? ""; // 返回第一张图片或空字符串
}

// 单独生成目录项
function handleToc($obj, $n, &$html) {
    $html .= '<li><a href="#menu_index_' . $n . '">' . htmlentities($obj->textContent) . '</a></li>';
}
// 更新后的 toc 函数将返回一个只包含 <li> 的列表
function toc($content) {
    $html = '<ul class="markdownIt-TOC">'; // 开始一个新的无序列表
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_use_internal_errors(false);
    $xpath = new DOMXPath($dom);
    $objs = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');
    if ($objs->length) {
        foreach ($objs as $n => $obj) {
            $obj->setAttribute('id', 'TOC' . ($n + 1));
            handleToc($obj, $n + 1, $html);
        }
    }
    $html .= '</ul>'; // 结束无序列表
    return $html;
}