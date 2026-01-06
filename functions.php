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

/**
 * 文章浏览次数统计函数
 * @param Typecho_Widget $archive 文章对象
 */
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

function space_toc_slugify($text) {
    $text = html_entity_decode(strip_tags((string)$text), ENT_QUOTES, 'UTF-8');
    $text = trim(preg_replace('/\s+/u', ' ', $text));
    if (function_exists('mb_strtolower')) {
        $text = mb_strtolower($text, 'UTF-8');
    } else {
        $text = strtolower($text);
    }
    $text = preg_replace('/[^\p{L}\p{N}\s\-_]+/u', '', $text);
    $text = trim(preg_replace('/[\s\_]+/u', '-', $text), '-');
    return $text !== '' ? $text : 'section';
}

function space_toc_dom_inner_html($node) {
    if (!$node || !isset($node->childNodes)) {
        return '';
    }
    $html = '';
    foreach ($node->childNodes as $child) {
        $html .= $node->ownerDocument->saveHTML($child);
    }
    return $html;
}

function space_build_toc($html, $options = array()) {
    $options = array_merge(array(
        'title' => '目录',
        'minHeadings' => 2,
        'minLevel' => 2,
        'maxLevel' => 6,
        'idPrefix' => 'toc-',
    ), is_array($options) ? $options : array());

    $result = array('content' => (string)$html, 'toc' => '');
    if (trim(strip_tags((string)$html)) === '') {
        return $result;
    }
    if (!class_exists('DOMDocument')) {
        return $result;
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $previousUseErrors = libxml_use_internal_errors(true);

    $wrapId = '__space_toc_root__';
    $wrappedHtml = '<!doctype html><html><head><meta charset="utf-8"></head><body><div id="' . $wrapId . '">' . $html . '</div></body></html>';
    $loaded = $dom->loadHTML($wrappedHtml, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
    libxml_clear_errors();
    libxml_use_internal_errors($previousUseErrors);
    if (!$loaded) {
        return $result;
    }

    $root = $dom->getElementById($wrapId);
    if (!$root) {
        return $result;
    }

    $xpath = new DOMXPath($dom);
    $headingNodes = $xpath->query('.//h1|.//h2|.//h3|.//h4|.//h5|.//h6', $root);
    if (!$headingNodes || $headingNodes->length === 0) {
        $result['content'] = space_toc_dom_inner_html($root);
        return $result;
    }

    $allHeadings = array();
    $minFoundLevel = 6;
    foreach ($headingNodes as $node) {
        $level = (int)substr($node->nodeName, 1);
        if ($level < 1 || $level > 6) {
            continue;
        }
        $minFoundLevel = min($minFoundLevel, $level);
        $allHeadings[] = array('node' => $node, 'level' => $level);
    }

    if (!$allHeadings) {
        $result['content'] = space_toc_dom_inner_html($root);
        return $result;
    }

    $minLevel = (int)$options['minLevel'];
    $maxLevel = (int)$options['maxLevel'];
    $hasAtOrAboveMin = false;
    foreach ($allHeadings as $h) {
        if ($h['level'] >= $minLevel && $h['level'] <= $maxLevel) {
            $hasAtOrAboveMin = true;
            break;
        }
    }
    if (!$hasAtOrAboveMin) {
        $minLevel = $minFoundLevel;
    }

    $idCounts = array();
    $existingIdNodes = $xpath->query('.//*[@id]', $root);
    if ($existingIdNodes) {
        foreach ($existingIdNodes as $n) {
            $id = trim($n->getAttribute('id'));
            if ($id !== '') {
                if (!isset($idCounts[$id])) {
                    $idCounts[$id] = 0;
                }
                $idCounts[$id]++;
            }
        }
    }

    $items = array();
    $usedHeadingIds = array();
    foreach ($allHeadings as $h) {
        $level = $h['level'];
        if ($level < $minLevel || $level > $maxLevel) {
            continue;
        }
        $node = $h['node'];
        $text = trim(preg_replace('/\s+/u', ' ', $node->textContent));
        if ($text === '') {
            continue;
        }

        $id = trim($node->getAttribute('id'));
        if ($id !== '' && isset($idCounts[$id]) && $idCounts[$id] === 1 && !isset($usedHeadingIds[$id])) {
            // keep existing unique id
        } else {
            $base = $options['idPrefix'] . space_toc_slugify($text);
            $candidate = $base;
            $suffix = 2;
            while (isset($idCounts[$candidate]) || isset($usedHeadingIds[$candidate])) {
                $candidate = $base . '-' . $suffix;
                $suffix++;
            }
            $id = $candidate;
        }

        $node->setAttribute('id', $id);
        $node->setAttribute('tabindex', '-1');
        $usedHeadingIds[$id] = true;

        $items[] = array('id' => $id, 'level' => $level, 'text' => $text);
    }

    $result['content'] = space_toc_dom_inner_html($root);

    if (count($items) < (int)$options['minHeadings']) {
        return $result;
    }

    $baseLevel = 6;
    foreach ($items as $it) {
        $baseLevel = min($baseLevel, (int)$it['level']);
    }

    $toc = '<nav class="toc" aria-label="' . htmlspecialchars($options['title'], ENT_QUOTES, 'UTF-8') . '">';
    $toc .= '<div class="toc-title">' . htmlspecialchars($options['title'], ENT_QUOTES, 'UTF-8') . '</div>';
    $toc .= '<ul class="toc-list">';

    $prevLevel = $baseLevel;
    $first = true;
    foreach ($items as $it) {
        $level = (int)$it['level'];
        if ($first) {
            $first = false;
        } else {
            if ($level === $prevLevel) {
                $toc .= '</li>';
            } elseif ($level > $prevLevel) {
                while ($level > $prevLevel) {
                    $toc .= '<ul class="toc-list">';
                    $prevLevel++;
                }
            } else {
                $toc .= '</li>';
                while ($level < $prevLevel) {
                    $toc .= '</ul></li>';
                    $prevLevel--;
                }
            }
        }

        $toc .= '<li class="toc-item toc-level-' . $level . '">';
        $toc .= '<a class="toc-link" href="#' . htmlspecialchars($it['id'], ENT_QUOTES, 'UTF-8') . '">';
        $toc .= htmlspecialchars($it['text'], ENT_QUOTES, 'UTF-8');
        $toc .= '</a>';
    }

    $toc .= '</li>';
    while ($prevLevel > $baseLevel) {
        $toc .= '</ul></li>';
        $prevLevel--;
    }
    $toc .= '</ul></nav>';

    $result['toc'] = $toc;
    return $result;
}

if (!function_exists('toc')) {
    function toc($content) {
        $built = space_build_toc($content);
        return $built['toc'];
    }
}
