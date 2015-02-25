<?php
/**
 * Quick Simple Editor
 * https://github.com/martec
 *
 * Copyright (C) 2015-2015, Martec
 *
 * Quick Simple Editor is licensed under the GPL Version 2 license:
 *	http://opensource.org/licenses/gpl-2.0.php
 *
 * @fileoverview Quick Simple Editor - Lightweight editor based of phpBB and PunBB editor for Mybb
 * @author Martec
 * @requires jQuery and Mybb
 * @credits phpBB (https://www.phpbb.com/) and PunBB (http://punbb.informer.com/)
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

define('QSE', '1.0.0');

// Plugin info
function quicksmpeditor_info ()
{

	global $db, $lang;

	$lang->load('config_quicksmpeditor');

	$QAE_description = <<<EOF
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
{$lang->quicksmpeditor_plug_desc}
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
</form>
EOF;

	return array(
		"name"			  => "Quick Simple Editor",
		"description"	 => $QAE_description,
		"website"		 => "https://github.com/martec/quicksmpeditor",
		"author"		=> "martec",
		"authorsite"	=> "http://community.mybb.com/user-49058.html",
		"version"		 => QSE,
		"compatibility" => "18*"
	);
}

function quicksmpeditor_install()
{
	global $db, $lang, $mybb;

	$lang->load('config_quicksmpeditor');

	$groupid = $db->insert_query('settinggroups', array(
		'name'		=> 'quicksmpeditor',
		'title'		=> 'Quick Simple Editor',
		'description'	=> $lang->quicksmpeditor_sett_desc,
		'disporder'	=> $dorder,
		'isdefault'	=> '0'
	));

	$new_setting[] = array(
		'name'		=> 'quicksmpeditor_smile',
		'title'		=> $lang->quicksmpeditor_smile_title,
		'description'	=> $lang->quicksmpeditor_smile_desc,
		'optionscode'	=> 'onoff',
		'value'		=> '0',
		'disporder'	=> '1',
		'gid'		=> $groupid
	);

	$db->insert_query_multiple("settings", $new_setting);
	rebuild_settings();
}

function quicksmpeditor_is_installed()
{
	global $db;

	$query = $db->simple_select("settinggroups", "COUNT(*) as rows", "name = 'quicksmpeditor'");
	$rows  = $db->fetch_field($query, 'rows');

	return ($rows > 0);
}

function quicksmpeditor_uninstall()
{
	global $db;

	$db->write_query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN('quicksmpeditor_smile')");
	$db->delete_query("settinggroups", "name = 'quicksmpeditor'");
}

function quicksmpeditor_activate()
{
	global $db;
	include_once MYBB_ROOT.'inc/adminfunctions_templates.php';

	$new_template_global['scodebutquick'] = "<script type=\"text/javascript\">
// <![CDATA[
	function change_palette()
	{
		toggleDisplay('colour_palette');
		e = document.getElementById('colour_palette');

		if (e.style.display == 'block')
		{
			document.getElementById('bbpalette').value = '{\$lang->quicksmpeditor_hide_fontcolor}';
		}
		else
		{
			document.getElementById('bbpalette').value = '{\$lang->editor_fontcolor}';
		}
	}
// ]]>
\$(document).ready(function() {
	if (\$('#quick_reply_form, #quickreply_e').length) {
		\$('#format-buttons').show();
	}
});
</script>
<script type=\"text/javascript\" src=\"{\$mybb->asset_url}/jscripts/editor.min.js?ver=".QSE."\"></script>
<div id=\"colour_palette\" style=\"display: none;\">
	<dl style=\"clear: left;\">
		<dt><label>{\$lang->editor_fontcolor}:</label></dt>
		<dd id=\"color_palette_placeholder\" data-orientation=\"h\" data-height=\"12\" data-width=\"15\" data-bbcode=\"true\"></dd>
	</dl>
</div>
<div id=\"format-buttons\" style=\"display: none;\">
	<input type=\"button\" accesskey=\"b\" value=\" B \" style=\"font-weight:bold; width: 30px\" onclick=\"editor.insert_text('[b]','[/b]')\" title=\"{\$lang->editor_bold}\" />
	<input type=\"button\" accesskey=\"i\" value=\" i \" style=\"font-style:italic; width: 30px\" onclick=\"editor.insert_text('[i]','[/i]')\" title=\"{\$lang->editor_italic}\" />
	<input type=\"button\" accesskey=\"u\" value=\" U \" style=\"text-decoration: underline; width: 30px\" onclick=\"editor.insert_text('[u]','[/u]')\" title=\"{\$lang->editor_underline}\" />
	<input type=\"button\" accesskey=\"s\" value=\" S \" style=\"text-decoration: line-through; width: 30px\" onclick=\"editor.insert_text('[s]','[/s]')\" title=\"{\$lang->editor_strikethrough}\" />
	<select class=\"bbcode-size\" name=\"fontsize\" onchange=\"editor.insert_text('[size=' + this.form.fontsize.options[this.form.fontsize.selectedIndex].value + ']', '[/size]');this.form.fontsize.selectedIndex = 2;\" title=\"{\$lang->editor_fontsize}\">
		<option value=\"1\">1</option>
		<option value=\"2\">2</option>
		<option value=\"\" selected=\"selected\">{\$lang->quicksmpeditor_default}</option>
		<option value=\"3\">3</option>
		<option value=\"4\">4</option>
		<option value=\"5\">5</option>
		<option value=\"6\">6</option>
		<option value=\"7\">7</option>
	</select>
	<input type=\"button\" name=\"bbpalette\" id=\"bbpalette\" value=\"Font colour\" onclick=\"change_palette();\" title=\"{\$lang->editor_fontcolor}\" />
	<input type=\"button\" accesskey=\"q\" value=\"Quote\" style=\"width: 50px\" onclick=\"editor.insert_text('[quote]','[/quote]')\" title=\"{\$lang->editor_insertquote}\" />
	<input type=\"button\" accesskey=\"c\" value=\"Code\" style=\"width: 50px\" onclick=\"editor.insert_text('[code]','[/code]')\" title=\"{\$lang->editor_code}\" />
	<input type=\"button\" accesskey=\"l\" value=\"List\" style=\"width: 40px\" onclick=\"editor.insert_text('[list]','[/list]')\" title=\"{\$lang->editor_bullist}\" />
	<input type=\"button\" accesskey=\"o\" value=\"List=\" style=\"width: 40px\" onclick=\"editor.insert_text('[list=1]','[/list]')\" title=\"{\$lang->editor_numlist}\" />
	<input type=\"button\" accesskey=\"y\"	value=\"[*]\" style=\"width: 40px\" onclick=\"editor.insert_text('[*]','')\" title=\"{\$lang->quicksmpeditor_list_item}\" />
	<input type=\"button\" accesskey=\"p\" value=\"Img\" style=\"width: 40px\" onclick=\"editor.insert_text('[img]','[/img]')\" title=\"{\$lang->editor_insertimg}\" />
	<input type=\"button\" accesskey=\"h\"	value=\"hr\" style=\"width: 30px\" onclick=\"editor.insert_text('[hr]','')\" title=\"{\$lang->editor_inserthr}\" />
	<input type=\"button\" accesskey=\"w\" value=\"URL\" style=\"text-decoration: underline; width: 40px\" onclick=\"editor.insert_text('[url]','[/url]')\" title=\"{\$lang->editor_url}\" />
	<input type=\"button\" accesskey=\"v\" value=\"Video\" style=\"width: 50px\" onclick=\"editor.insert_text('[video=]','[/video]')\" title=\"{\$lang->editor_insertvideo}\" />
	<input type=\"button\" accesskey=\"e\" value=\"Email\" style=\"width: 50px\" onclick=\"editor.insert_text('[email=]','[/email]')\" title=\"{\$lang->editor_email}\" />
</div>";

	foreach($new_template_global as $title => $template)
	{
		$new_template_global = array('title' => $db->escape_string($title), 'template' => $db->escape_string($template), 'sid' => '-1', 'version' => '1801', 'dateline' => TIME_NOW);
		$db->insert_query('templates', $new_template_global);
	}

	find_replace_templatesets(
		'showthread_quickreply',
		'#' . preg_quote('<textarea') . '#i',
		'{$scodebutquick}<textarea'
	);

	find_replace_templatesets(
		'private_quickreply',
		'#' . preg_quote('<textarea') . '#i',
		'{$scodebutquick}<textarea'
	);

	find_replace_templatesets(
		'showthread_quickreply',
		'#' . preg_quote('<span class="smalltext">{$lang->message_note}<br />') . '#i',
		'<span class="smalltext">{$lang->message_note}<br />{$smilieinserter}'
	);

	find_replace_templatesets(
		'private_quickreply',
		'#' . preg_quote('<span class="smalltext">{$lang->message_note}<br />') . '#i',
		'<span class="smalltext">{$lang->message_note}<br />{$smilieinserter}'
	);
}

function quicksmpeditor_deactivate()
{
	global $db;
	include_once MYBB_ROOT."inc/adminfunctions_templates.php";

	$db->delete_query("templates", "title IN('scodebutquick')");

	find_replace_templatesets(
		'showthread_quickreply',
		'#' . preg_quote('{$scodebutquick}<textarea') . '#i',
		'<textarea'
	);

	find_replace_templatesets(
		'private_quickreply',
		'#' . preg_quote('{$scodebutquick}<textarea') . '#i',
		'<textarea'
	);

	find_replace_templatesets(
		'showthread_quickreply',
		'#' . preg_quote('<span class="smalltext">{$lang->message_note}<br />{$smilieinserter}') . '#i',
		'<span class="smalltext">{$lang->message_note}<br />'
	);

	find_replace_templatesets(
		'private_quickreply',
		'#' . preg_quote('<span class="smalltext">{$lang->message_note}<br />{$smilieinserter}') . '#i',
		'<span class="smalltext">{$lang->message_note}<br />'
	);
}

$plugins->add_hook('global_start', 'smpedt_cache_scodebutquick');
function smpedt_cache_scodebutquick()
{
	global $templatelist, $mybb;

	if (isset($templatelist)) {
		$templatelist .= ',';
	}

	if (THIS_SCRIPT == 'showthread.php' || THIS_SCRIPT == 'private.php') {
		if($mybb->settings['quicksmpeditor_smile'] != 0) {
			$templatelist .= 'codebutquick,smilieinsert,smilieinsert_smilie,smilieinsert_getmore';
		}
		else {
			$templatelist .= 'codebutquick';
		}
	}
}

$plugins->add_hook("showthread_start", "scodebuttonsquick");
$plugins->add_hook("private_start", "scodebuttonsquick");
function scodebuttonsquick () {

	global $smilieinserter, $scodebutquick, $mybb, $templates, $lang;

	if (!$lang->quicksmpeditor) {
		$lang->load('quicksmpeditor');
	}

	eval("\$scodebutquick = \"".$templates->get("scodebutquick")."\";");
	$smilieinserter = '';
	if($mybb->settings['quicksmpeditor_smile'] != 0) {
		$smilieinserter = build_clickable_smilies();
	}
}
?>