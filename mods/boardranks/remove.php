<?php
// ClanSphere 2008 - www.clansphere.net
// Id: remove.php (Tue Nov 25 13:20:50 CET 2008) fAY-pA!N

$cs_lang = cs_translate('boardranks');
$cs_get = cs_get('id,agree,cancel');
$data = array();

$boardranks_id = empty($cs_get['id']) ? 0 : $cs_get['id'];
$agree = empty($cs_get['agree']) ? 0 : $cs_get['agree'];
$cancel = empty($cs_get['cancel']) ? 0 : $cs_get['cancel'];

if(!empty($agree)) {
  cs_sql_delete(__FILE__,'boardranks',$boardranks_id);
  cs_redirect($cs_lang['del_true'], 'boardranks');
}

if(!empty($cancel))   
  cs_redirect($cs_lang['del_false'], 'boardranks');

if(empty($agree) AND empty($cancel)) {
  $data['head']['body'] = sprintf($cs_lang['del_rly'],$boardranks_id);
  $data['url']['agree'] = cs_url('boardranks','remove','id=' . $boardranks_id . '&amp;agree');
  $data['url']['cancel'] = cs_url('boardranks','remove','id=' . $boardranks_id . '&amp;cancel');
  
  echo cs_subtemplate(__FILE__,$data,'boardranks','remove');
  
}

?>
