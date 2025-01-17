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
function img_postthumb($cid) {
    $db = Typecho_Db::get();
    $rs = $db->fetchRow($db->select('table.contents.text')
        ->from('table.contents')
        ->where('table.contents.cid=?', $cid)
        ->order('table.contents.cid', Typecho_Db::SORT_ASC)
        ->limit(1));
    // 检查是否获取到结果
    if (!$rs) {
        return "";
    }
    preg_match_all("/https?:\/\/[^\s]*.(png|jpeg|jpg|gif|bmp|webp)/", $rs['text'], $thumbUrl);  //通过正则式获取图片地址
    // 检查是否匹配到图片URL
    if (count($thumbUrl[0]) > 0) {
        return $thumbUrl[0][0];  // 返回第一张图片的URL
    } else {
        return "";  // 没有匹配到图片URL，返回空字符串
    }
}
// 单独生成目录项
function handleToc($obj, $n, &$html) {
    // 使用 htmlentities 处理内容
    $html .= '<li><a href="#menu_index_' . $n . '">' . htmlentities($obj->textContent, ENT_QUOTES, 'UTF-8') . '</a></li>';
}

// 更新后的 toc 函数将返回一个只包含 <li> 的列表
function toc($content) {
    $html = '<ul class="markdownIt-TOC">'; // 开始一个新的无序列表
    $dom = new DOMDocument();
    
    // 设置错误处理
    libxml_use_internal_errors(true);

    // 将内容包装在一个完整的 HTML 文档中，并指定 UTF-8 编码
    $content = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>' . $content . '</body></html>';
    $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    // 恢复错误处理
    libxml_use_internal_errors(false);

    // 使用 XPath 查询所有标题元素
    $xpath = new DOMXPath($dom);
    $objs = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6');
    
    if ($objs->length) {
        foreach ($objs as $n => $obj) {
            // 设置每个标题元素的 id 属性
            $obj->setAttribute('id', 'menu_index_' . ($n + 1));
            handleToc($obj, $n + 1, $html);
        }
    }
    $html .= '</ul>'; // 结束无序列表
    return $html;
}
