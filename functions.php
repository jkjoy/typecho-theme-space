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
    $qqboturl = new Typecho_Widget_Helper_Form_Element_Text('qqboturl', NULL, 'https://bot.asbid.cn', _t('QQ机器人API,保持默认则需添加 2280858259 为好友'), _t('基于cqhttp,有评论时QQ通知'));
    $form->addInput($qqboturl);
    $qqnum = new Typecho_Widget_Helper_Form_Element_Text('qqnum', NULL, '80116747', _t('QQ号码'), _t('用于接收QQ通知的号码'));
    $form->addInput($qqnum);
    $bgUrl = new Typecho_Widget_Helper_Form_Element_Text('bgUrl', NULL, NULL, _t('默认头图 地址'));
    $form->addInput($bgUrl);
    $tongji = new Typecho_Widget_Helper_Form_Element_Textarea('tongji', NULL, NULL, _t('统计代码'));
    $form->addInput($tongji);
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
//回复加上@
function getPermalinkFromCoid($coid) {
	$db = Typecho_Db::get();
	$row = $db->fetchRow($db->select('author')->from('table.comments')->where('coid = ? AND status = ?', $coid, 'approved'));
	if (empty($row)) return '';
	return '<a href="#comment-'.$coid.'" style="text-decoration: none;">@'.$row['author'].'</a>';
}
// 评论提交通知函数
function notifyQQBot($comment) {
    $options = Helper::options();
    // 检查评论是否已经审核通过
    if ($comment->status != "approved") {
        error_log('Comment is not approved.');
        return;
    } 
    // 获取配置中的QQ机器人API地址
    $cq_url = $options->qqboturl;
    // 检查API地址是否为空
    if (empty($cq_url)) {
        error_log('QQ Bot URL is empty. Using default URL.');
        $cq_url = 'https://bot.asbid.cn';
    }
    // 获取QQ号码
    $qqnum = $options->qqnum;
    // 检查QQ号码是否为空
    if (empty($qqnum)) {
        error_log('QQ number is empty.');
        return;
    }
    // 如果是管理员自己发的评论则不发送通知
    if ($comment->authorId === $comment->ownerId) {
        error_log('This comment is by the post owner.');
        return;
    }
    // 构建消息内容
    $msg = '「' . $comment->author . '」在文章《' . $comment->title . '》中发表了评论！';
    $msg .= "\n评论内容:\n{$comment->text}\n永久链接地址：{$comment->permalink}";
    // 准备发送消息的数据
    $_message_data_ = [
        'user_id' => (int) trim($qqnum),
        'message' => str_replace(["\r\n", "\r", "\n"], "\r\n", htmlspecialchars_decode(strip_tags($msg)))
    ];
    // 输出调试信息
    error_log('Sending message to QQ Bot: ' . print_r($_message_data_, true));
    // 初始化Curl请求
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "{$cq_url}/send_msg?" . http_build_query($_message_data_, '', '&'),
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0
    ]);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Curl error: ' . curl_error($ch));
    } else {
        error_log('Response: ' . $response);
    }
    curl_close($ch);
}
Typecho_Plugin::factory('Widget_Feedback')->finishComment = 'notifyQQBot';