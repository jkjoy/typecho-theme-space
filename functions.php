<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
require_once("single.php");
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
    $cssdiy = new Typecho_Widget_Helper_Form_Element_Textarea('cssdiy', NULL, NULL, _t('自定义CSS'));
    $form->addInput($cssdiy);
}
function themeInit($comment){
$comment = spam_protection_pre($comment, $post, $result);
}
function spam_protection_math(){
    $num1=rand(1,9);
    $num2=rand(1,9);
    echo '<input type="text" id="code" required name="sum" value="" placeholder="'.$num1.' + '.$num2. ' = ? *" />';
    echo '<input type="hidden" name="num1" value="'.$num1.'" />';
    echo '<input type="hidden" name="num2" value="'.$num2.'" />';
}
function spam_protection_pre($comment, $post, $result){
    $sum=$_POST['sum'];
    switch($sum){
        case $_POST['num1']+$_POST['num2']:
        break;
        case null:
        throw new Typecho_Widget_Exception(_t('对不起: 请输入验证码。<a href="javascript:history.back(-1)">返回上一页</a>','评论失败'));
        break;
        default:
        throw new Typecho_Widget_Exception(_t('对不起: 验证码错误，请<a href="javascript:history.back(-1)">返回重试</a>。','评论失败'));
    }
    return $comment;
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
//回复加上@
function getPermalinkFromCoid($coid) {
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select('author')->from('table.comments')->where('coid = ? AND status = ?', $coid, 'approved'));
	if (empty($row)) return '';
	return '<a href="#comment-'.$coid.'" style="text-decoration: none;">@'.$row['author'].'</a>';
}