<?php
// ClanSphere 2008 - www.clansphere.net
// $Id$

$cs_lang = cs_translate('gbook');

$start = empty($_GET['start']) ? 0 : $_GET['start'];
$where = empty($_GET['id']) ? 0 : $_GET['id'];
settype($where,'integer');

$gbook_count = cs_sql_count(__FILE__,'gbook',"gbook_users_id = '" . $where . "'");

$cs_user = cs_sql_select(__FILE__,'users','users_nick, users_active',"users_id = '" . $where . "'");
$user = cs_user($where,$cs_user['users_nick'], $cs_user['users_active']);

$data['lang']['getmsg'] = cs_getmsg();
$data['head']['mod'] = $cs_lang['mod_name'];
$data['head']['action'] = $cs_lang['head_list'];
$data['head']['addons'] = cs_addons('users','view',$where,'gbook');

$data['body']['users'] = sprintf($cs_lang['body_users'], $user);
$data['body']['pages'] = cs_pages('gbook','list',$gbook_count,$start);

$data['body']['gbook_entry'] = cs_link($cs_lang['submit'],'gbook','entry','id=' . $where);
$data['body']['all'] = $cs_lang['total'] . ': ';
$data['body']['gbook_count'] = $gbook_count;

$from = 'gbook gbk LEFT JOIN {pre}_users usr ON gbk.users_id = usr.users_id';
$select = 'gbk.gbook_id AS gbook_id, gbk.users_id AS users_id, gbk.gbook_time AS gbook_time, ';
$select .= 'gbk.gbook_nick AS gbook_nick, gbk.gbook_email AS gbook_email, gbk.gbook_icq AS gbook_icq, ';
$select .= 'gbk.gbook_msn AS gbook_msn, gbk.gbook_skype AS gbook_skype, gbk.gbook_url AS gbook_url, ';
$select .= 'gbk.gbook_town AS gbook_town, gbk.gbook_text AS gbook_text, gbk.gbook_ip AS gbook_ip, ';
$select .= 'usr.users_nick AS users_nick, usr.users_place AS users_place, usr.users_icq AS users_icq, ';
$select .= 'usr.users_msn AS users_msn, usr.users_skype AS users_skype, usr.users_email AS users_email, ';
$select .= 'usr.users_url AS users_url, usr.users_hidden AS users_hidden, usr.users_active AS users_active';
$where = "gbook_users_id = '" . $where . "'";
$order = 'gbk.gbook_id DESC';
$cs_gbook = cs_sql_select(__FILE__,$from,$select,$where,$order,$start,$account['users_limit']);
$gbook_loop = count($cs_gbook);

$c = 0;

for($run=0; $run<$gbook_loop; $run++)
{
  $entry_count = $gbook_count - $start - $c;
  $c++;
  $gbook[$run]['entry_count'] = $entry_count;
  if($cs_gbook[$run]['users_id'] == 0) {
    $gbook[$run]['users_nick'] = cs_secure($cs_gbook[$run]['gbook_nick']);
    $gbook[$run]['town'] = '';
    $gbook[$run]['icon_town'] = '';
    if (!empty($cs_gbook[$run]['gbook_town'])) {
      $gbook[$run]['icon_town'] = cs_icon('gohome');
      $gbook[$run]['town'] = cs_secure($cs_gbook[$run]['gbook_town']);
    }
    $mail = cs_secure($cs_gbook[$run]['gbook_email']);
    $gbook[$run]['icon_mail'] = empty($mail) ? '' : cs_html_link("mailto:$mail",cs_icon('mail_generic'));
    $icq = cs_secure($cs_gbook[$run]['gbook_icq']);
    $gbook[$run]['icon_icq'] = empty($icq) ? '' : cs_html_link("http://www.icq.com/$icq",cs_icon('licq'));
    $msn = cs_secure($cs_gbook[$run]['gbook_msn']);
    $gbook[$run]['icon_msn'] = empty($msn) ? '' : cs_html_link("http://members.msn.com/$msn",cs_icon('msn_protocol'));
    $skype = cs_secure($cs_gbook[$run]['gbook_skype']);
    $url = 'http://mystatus.skype.com/smallicon/' . $skype;
    $gbook[$run]['icon_skype'] = empty($skype) ? '' : cs_html_link("skype:$skype?userinfo",cs_html_img($url,'16','16','0','Skype'),'0');
    $url = cs_secure($cs_gbook[$run]['gbook_url']);
    $gbook[$run]['icon_url'] = empty($url) ? '' : cs_html_link("http://$url",cs_icon('gohome'));
  }
  else
  {
    $hidden = explode(',',$cs_gbook[$run]['users_hidden']);
    $allow = $cs_gbook[$run]['users_id'] == $account['users_id'] OR $account['access_users'] > 4 ? 1 : 0;
    $gbook[$run]['users_nick'] = cs_user($cs_gbook[$run]['users_id'],$cs_gbook[$run]['users_nick'], $cs_gbook[$run]['users_active']);
        $gbook[$run]['town'] = '';
    $gbook[$run]['icon_town'] = '';
    if (!empty($cs_gbook[$run]['users_place'])) {
      $gbook[$run]['icon_town'] = cs_icon('gohome');
      $gbook[$run]['town'] = cs_secure($cs_gbook[$run]['users_place']);
    }
    $mail = cs_secure($cs_gbook[$run]['users_email']);
    if(in_array('users_email',$hidden)) {
      $mail = empty($allow) ? '' : $mail;
    }
    $gbook[$run]['icon_mail'] = empty($mail) ? '' : cs_html_link("mailto:$mail",cs_icon('mail_generic'));
    $icq = cs_secure($cs_gbook[$run]['users_icq']);
    if(in_array('users_icq',$hidden)) {
      $icq = empty($allow) ? '' : $icq;
    }
    $gbook[$run]['icon_icq'] = empty($icq) ? '' : cs_html_link("http://www.icq.com/$icq",cs_icon('licq'));
    $msn = cs_secure($cs_gbook[$run]['users_msn']);
    if(in_array('users_msn',$hidden)) {
      $msn = empty($allow) ? '' : $msn;
    }
    $gbook[$run]['icon_msn'] = empty($msn) ? '' : cs_html_link("http://members.msn.com/$msn",cs_icon('msn_protocol'));
    $skype = cs_secure($cs_gbook[$run]['users_skype']);
    $url = 'http://mystatus.skype.com/smallicon/' . $skype;
    $skype = cs_html_link('skype:' . $cs_gbook[$run]['users_skype'] . '?userinfo',cs_html_img($url,'16','16','0','Skype'),'0');
    if(in_array('users_skype',$hidden)) {
      $skype = empty($allow) ? '' : $skype;
    }
    $gbook[$run]['icon_skype'] = empty($cs_gbook[$run]['users_skype']) ? '' : $skype;
    $url = cs_secure($cs_gbook[$run]['users_url']);
    if(in_array('users_url',$hidden)) {
      $url = empty($allow) ? '' : $url;
    }
    $gbook[$run]['icon_url'] = empty($url) ? '' : cs_html_link("http://$url",cs_icon('gohome'));
  }
  $gbook[$run]['text'] = cs_secure($cs_gbook[$run]['gbook_text'],1,1);
  $gbook[$run]['time'] = cs_date('unix',$cs_gbook[$run]['gbook_time'],1);
  if($account['access_gbook'] >= 4)
  {
    $img_edit = cs_icon('edit',16,$cs_lang['edit']);
    $gbook[$run]['icon_edit'] = cs_link($img_edit,'gbook','edit','id=' . $cs_gbook[$run]['gbook_id'],0,$cs_lang['edit']);
    $img_del = cs_icon('editdelete',16,$cs_lang['remove']);
       $gbook[$run]['icon_remove'] = cs_link($img_del,'gbook','remove','id=' . $cs_gbook[$run]['gbook_id'],0,$cs_lang['remove']);
    $img_ip = cs_icon('important',16,$cs_lang['ip']);
    $gbook[$run]['icon_ip'] = cs_link($img_ip,'gbook','ip','id=' . $cs_gbook[$run]['gbook_id']);
  }
}
$data['gbook'] = !empty($gbook) ? $gbook : '';
echo cs_subtemplate(__FILE__,$data,'gbook','users');
?>