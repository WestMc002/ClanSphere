<table class="forum" cellpadding="0" cellspacing="{page:cellspacing}" style="width:{page:width}">
 <tr>
  <td class="headb">FCKEditor - {lang:options}</td>
 </tr>
 <tr>
  <td class="leftb">{lang:options_info}</td>
 </tr>
</table>
<br />
<form method="post" action="{url:fckeditor_options}">
<table class="forum" cellpadding="0" cellspacing="{page:cellspacing}" style="width:{page:width}">
 <tr>
  <td class="leftc">{icon:kllckety} {lang:skin}</td>
  <td class="leftb">{op:skin}</td>
 </tr>
 <tr>
  <td class="leftc">{icon:resizecol} {lang:height}</td>
  <td class="leftb"><input type="text" name="height" value="{op:height}" maxlength="8" size="8" /> {lang:pixel}</td>
 </tr>
 <tr>
  <td class="leftc">{icon:ksysguard} {lang:options}</td>
  <td class="leftb">
    <input type="submit" name="submit" value="{lang:edit}" />
    <input type="reset" name="reset" value="{lang:reset}" />
  </td>
 </tr>
</table>
</form>