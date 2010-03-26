<?php
// ClanSphere 2009 - www.clansphere.net
// $Id$

$cs_lang = cs_translate('board');

require_once 'mods/board/functions.php';

$data = array();

$data['stats']['topics'] = cs_sql_count(__FILE__,'threads');
$data['stats']['posts'] = cs_sql_count(__FILE__,'comments','comments_mod = \'board\'');
$data['stats']['users'] = cs_sql_count(__FILE__,'users', 'users_active = 1 AND users_delete = 0');
$data['stats']['categories'] = cs_sql_count(__FILE__,'categories','categories_mod = \'board\'');
$data['stats']['boards'] = cs_sql_count(__FILE__,'board');
$data['stats']['users_active'] = users_comments_toplist(0, 0, 1);


$user = users_comments_toplist(3,0);
$data['stats']['toplist'] = '';
if(!empty($user)) {
  foreach ($user AS $users_id => $users_data)
		$data['stats']['toplist'] .= empty($users_data['users_nick']) ? '- (' . $users_data['comments'] . ' ' . $cs_lang['posts'] . '), ' : cs_user($users_id, $users_data['users_nick'], $users_data['users_active'], $users_data['users_delete']) . ' (' . $users_data['comments'] . ' ' . $cs_lang['posts'] . '), ';
  $data['stats']['toplist'] = substr($data['stats']['toplist'],0,-2);
}


$tables  = 'threads t INNER JOIN {pre}_board b ON t.board_id = b.board_id ';
$tables .= 'AND b.board_access <= \''.$account['access_board'].'\' AND b.board_pwd = \'\' ';
$tables .= 'INNER JOIN {pre}_comments cms ON cms.comments_mod = \'board\' ';
$tables .= 'AND cms.comments_fid = t.threads_id GROUP BY t.threads_id';
$cells  = 't.threads_id AS threads_id, t.threads_comments AS threads_comments, ';
$cells .= 't.threads_headline AS threads_headline, COUNT(cms.comments_id) AS comments';
$select = cs_sql_select(__FILE__,$tables,$cells,0,'comments DESC');
$data['stats']['longest_thread'] = $select['threads_headline'];
$data['stats']['longest_thread_posts'] = $select['comments'];
$data['url']['longest_thread'] = cs_url('board','thread','where=' . $select['threads_id']);
$data['stats']['average_posts'] = !empty($data['stats']['topics']) ? round($data['stats']['posts'] / $data['stats']['topics'],2) : 0;
/*
$tables = 'comments cms LEFT JOIN {pre}_users usr ON cms.users_id = usr.users_id GROUP BY usr.users_id';
$cells = 'usr.users_nick AS users_nick, usr.users_id AS users_id, COUNT(cms.comments_id) AS smileys';
$cond = 'cms.comments_text LIKE \'%:)%\'';
$select = cs_sql_select(__FILE__,$tables,$cells,$cond,'smileys DESC');*/

echo cs_subtemplate(__FILE__,$data,'board','stats');