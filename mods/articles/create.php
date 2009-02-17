<?php
// ClanSphere 2008 - www.clansphere.net
// $Id$

$cs_lang = cs_translate('articles');

require_once 'mods/categories/functions.php';
require_once 'mods/pictures/functions.php';

$data['head']['body'] = $cs_lang['body_create'];
$data['if']['head'] = 1;
$data['if']['preview'] = false;
$data['url']['form'] = cs_url('articles','create');

if(isset($_POST['submit']) OR isset($_POST['preview'])) {

    $data['art']['categories_id'] = empty($_POST['categories_id']) ? cs_categories_create('articles',$_POST['categories_name']) 
    : (int) $_POST['categories_id'];

  $data['art']['articles_com'] = isset($_POST['articles_com']) ? $_POST['articles_com'] : 0;
  $data['art']['articles_navlist'] = isset($_POST['articles_navlist']) ? $_POST['articles_navlist'] : 0;
  $data['art']['articles_fornext'] = isset($_POST['articles_fornext']) ? $_POST['articles_fornext'] : 0;
  $data['art']['articles_headline'] = $_POST['articles_headline'];
  $data['art']['articles_text'] = $_POST['articles_text'];
  $data['art']['articles_time'] = cs_time();
  $data['art']['users_id'] = $account['users_id'];

  $categories = cs_sql_select(__FILE__,'categories','categories_picture',"categories_id = '" . $data['art']['categories_id'] . "'");
  
  if(!empty($cs_main['fckeditor'])) { $data['art']['articles_text'] = '[html]' . $_POST['articles_text'] . '[/html]'; }
  
    $errormsg = '';

    if(empty($data['art']['categories_id'])) { $errormsg .= $cs_lang['no_cat'] . cs_html_br(1); }
    if(empty($data['art']['articles_headline'])) { $errormsg .= $cs_lang['no_headline'] . cs_html_br(1); }
    if(empty($data['art']['articles_text'])) { $errormsg .= $cs_lang['no_text'] . cs_html_br(1); }
    if(isset($_POST['preview'])) {
        $data['if']['preview'] = true;
        $data['if']['catimg'] = empty($categories['categories_picture']) ? false : true;
        $data['cat']['url_catimg'] = empty($data['if']['catimg']) ? '' : 'uploads/categories/'.$categories['categories_picture'];
        $data['art']['articles_text_preview'] = cs_secure($data['art']['articles_text'],1,1,1,1,1);
    }
    elseif(empty($errormsg)) {
        $articles_cells = array_keys($data['art']);
        $articles_save = array_values($data['art']);
        cs_sql_insert(__FILE__,'articles',$articles_cells,$articles_save);
        
        $articles_id = cs_sql_insertid(__FILE__);
        $files = cs_files();
        cs_pictures_upload($files['picture'], 'articles', $articles_id);
        
        cs_redirect($cs_lang['create_done'],'articles');
    }
    else {
        $data['head']['body'] = $errormsg;
    }
} else {
  $data['art']['articles_com'] = '';
  $data['art']['articles_navlist'] = '';
  $data['art']['articles_fornext'] = '';
  $data['art']['articles_headline'] = '';
  $data['art']['articles_text'] = '';
  $data['art']['articles_time'] = cs_time();
  $data['art']['users_id'] = $account['users_id'];
    $data['art']['categories_id'] = 0;
}
$categories_id = empty($data['art']['categories_id']) ? 0 : $data['art']['categories_id'];
$data['categories']['dropdown'] = cs_categories_dropdown('articles',$categories_id);
$data['pictures']['select'] = cs_pictures_select();
$data['abcode']['features'] = cs_abcode_features('articles_text',1);
$on = "onclick=\"javascript:abc_insert";
$data['abcode']['pagebreak'] = cs_html_vote('pagebreak', $cs_lang['pagebreak'],'button',0,$on . "('[pagebreak]','','articles_text')\"");
$data['abcode']['sitelink'] = cs_html_vote('sitelink',$cs_lang['sitelink'],'button',0,$on . "('[pb_url=]" .$cs_lang['sitelink']. "[/pb_url]','','articles_text')\"");
$on = "onclick=\"javascript:abc_insert";
if(empty($cs_main['fckeditor'])) {
    $data['if']['fckeditor'] = 0;
    $data['if']['nofckeditor'] = 1;
}
else {
    $data['if']['fckeditor'] = 1;
    $data['if']['nofckeditor'] = 0;
    $data['articles']['content'] = cs_fckeditor('articles_text',$data['art']['articles_text']);
}
echo cs_subtemplate(__FILE__,$data,'articles','create');
?>