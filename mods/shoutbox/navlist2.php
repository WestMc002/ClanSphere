<?php 
// ClanSphere 2008 - www.clansphere.net  
// $Id$
  
$cs_lang = cs_translate('shoutbox'); 

$shoutbox_count = cs_sql_count(__FILE__,'shoutbox');
$shoutbox_options = cs_sql_option(__FILE__,'shoutbox');

$data = array();
$data['shoutbox'] = '';

$min = 0;

if ($shoutbox_options['order'] == 'ASC') {
  $order = 'shoutbox_date ASC';
  
  if ($shoutbox_count > $shoutbox_options['limit']) {
    $min = $shoutbox_count - $shoutbox_options['limit'];
  }
}
else
  $order = 'shoutbox_date DESC';
  
$cells = 'shoutbox_name, shoutbox_text, shoutbox_date';
$data['shoutbox'] = cs_sql_select(__FILE__,'shoutbox',$cells,0,$order,$min,$shoutbox_options['limit']);

$pattern = "=([^\s*?]{".$shoutbox_options['linebreak']."})(?![^<]+>|[^&]*;)=";
$count_shoutbox = count($data['shoutbox']);

for($i = 0; $i < $count_shoutbox; $i++) {
  $temp = preg_replace($pattern,"\\0 ",$data['shoutbox'][$i]['shoutbox_text']);
  $data['shoutbox'][$i]['shoutbox_text'] = cs_secure($temp,0,1,0);
  $data['shoutbox'][$i]['shoutbox_name'] = cs_secure($data['shoutbox'][$i]['shoutbox_name'],0,0,0);
  $data['shoutbox'][$i]['shoutbox_date'] = cs_date('unix',$data['shoutbox'][$i]['shoutbox_date'],1);
}

echo cs_subtemplate(__FILE__,$data,'shoutbox','navlist2');

?>