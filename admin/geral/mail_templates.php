<?php 

 /*                        Copyright 2005 Fl?vio Ribeiro

         This file is part of OCOMON.

         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.

         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.

         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

    	print "<BR><B>".TRANS('TTL_CONFIG_MAIL_TEMPLATES').":</b><BR><br>";
        print "<TD align='left'>".
        		"<input type='button' class='button' id='idBtIncluir' value='".TRANS('ACT_NEW')."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=new&cellStyle=true');\">".
        	"</TD><br><BR>";

		print "<form name='form1' action='".$_SERVER['PHP_SELF']."' method='post' onSubmit='return valida()'>"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='70%'>";



		$query = "SELECT * FROM mail_templates ";
		if (isset($_GET['cod'])) {
			$query .= "WHERE tpl_cod=".$_GET['cod']."";
		}
		$resultado = mysql_query($query) or die (TRANS('ERR_QUERY'));

	if ((empty($_GET['action'])) and empty($_POST['submit'])){


		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('MSG_NO_RECORDS'));
		}
		else
		{
				$cor=TD_COLOR;
				$cor1=TD_COLOR;
				$linhas = mysql_numrows($resultado);

				print "<TABLE border='0' cellpadding='1' cellspacing='0' width='100%'>";
				print "<tr class='header'>";
				print "<td class='line'>".TRANS('TPL_SIGLA','SIGLA')."</td><td class='line'>".TRANS('TPL_SUBJECT','Assunto')."</td>".
					"<td class='line'>".TRANS('OPT_MSG')."</td>".//<td class='line'>".TRANS('OPT_ALTERNATE_MSG')."</td>
					"</td><td class='line'>".TRANS('ACT_EDIT','Editar')."</td>";
				print "<td class='line'>".TRANS('COL_DEL','')."</TD>";
				print "</tr>";

				$j = 2;
				while ($row = mysql_fetch_array($resultado)) {
					if ($j % 2) {
							$trClass = "lin_par";
					}
					else {
							$trClass = "lin_impar";
					}
					$j++;
					print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
					print "<td class='line'>".$row['tpl_sigla']."</td><td class='line'>".$row['tpl_subject']."</td>".
						"<td class='line'>".nl2br($row['tpl_msg_text'])."</td>";//<td class='line'>".$row['tpl_msg_html']."</td>
					print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['tpl_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
					print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['tpl_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
					print "</tr>";
				}

				print "</table>";
		}

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td>".TRANS('TPL_SIGLA')."</td><td><input type='text' class='text' name='tpl_sigla' id='idSigla' value='".$row['tpl_sigla']."'></td></tr>";
		print "<tr><td>".TRANS('TPL_SUBJECT')."</td><td><input type='text' class='text' name='tpl_subject' id='idTplDesc' value='".$row['tpl_subject']."'></td></tr>";


		//print "<tr><td>".TRANS('OPT_HTML_MSG')."</td><td>";
		?>
<!--		<script type="text/javascript">
  			var oFCKeditor = new FCKeditor( 'msg_html' ) ;
  			oFCKeditor.BasePath = '../../includes/fckeditor/';
			oFCKeditor.Value = '<?php //print $row['tpl_msg_html'];?>';
			oFCKeditor.ToolbarSet = 'ocomon';
			oFCKeditor.Width = '400px';
			oFCKeditor.Height = '100px';
			oFCKeditor.Create() ;
		</script>-->
		<?php 
		//print "</td></tr>";
		//print "<tr><td>".TRANS('OPT_ALTERNATE_MSG')."</td><td><textarea name='msg_text' class='textarea2'>".$row['tpl_msg_text']."</textarea></td></tr>";
		print "<tr><td>".TRANS('OPT_MSG')."</td><td><textarea name='msg_text' class='textarea2' id='idText'>".$row['tpl_msg_text']."</textarea></td></tr>";


		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_EDIT')."'></td>";
		print "<input type='hidden' value='".$_GET['cod']."' name='cod'>";
		print "<td><input type='reset' name='reset' class='button'  value='".TRANS('BT_CANCEL')."' onclick=\"redirect('".$_SERVER['PHP_SELF']."')\"></td></tr>";

	} else
	if (isset($_GET['action']) && $_GET['action']=="new"){

		//$row = mysql_fetch_array($resultado);

		print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td>".TRANS('TPL_SIGLA').":</td><td><input type='text' name='tpl_sigla' id='idSigla' class='text'></td></tr>";
		print "<tr><td>".TRANS('TPL_SUBJECT').":</td><td><input type='text' name='tpl_subject' id='idTplDesc' class='text'></td></tr>";
		//print "<tr><td>".TRANS('OPT_HTML_MSG','Msg HTML')."</td><td>";
		?>
		<script type="text/javascript">
/*  			var oFCKeditor = new FCKeditor( 'msg_html' ) ;
  			oFCKeditor.BasePath = '../../includes/fckeditor/';
			oFCKeditor.ToolbarSet = 'ocomon';
			oFCKeditor.Width = '400px';
			oFCKeditor.Height = '100px';
			oFCKeditor.Create() ;*/
		</script>
		<?php 
		//print "</td></tr>";
		//print "<tr><td>".TRANS('OPT_ALTERNATE_MSG','Msg Alternativa')."</td><td><textarea name='msg_text' class='textarea2'></textarea></td></tr>";
		print "<tr><td>".TRANS('OPT_MSG','Msg Alternativa')."</td><td><textarea name='msg_text' class='textarea2' id='idText'></textarea></td></tr>";


		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_CAD')."'></td>";
		print "<td><input type='reset' name='reset' class='button'  value='".TRANS('BT_CANCEL')."' onclick=\"redirect('".$_SERVER['PHP_SELF']."')\"></td></tr>";


	} else
	if (isset($_POST['submit']) && $_POST['submit'] == TRANS('BT_CAD')){

		$qry = "INSERT INTO mail_templates (tpl_sigla, tpl_subject, tpl_msg_html, tpl_msg_text) values ".
				//"('".noHtml($_POST['tpl_sigla'])."', '".noHtml($_POST['tpl_subject'])."', '".$_POST['msg_html']."', '".noHtml($_POST['msg_text'])."');";
				"('".noHtml($_POST['tpl_sigla'])."', '".noHtml($_POST['tpl_subject'])."', '".$_POST['msg_text']."', '".noHtml($_POST['msg_text'])."');";
		$execQry = mysql_query($qry) or die(mysql_error());

		print "<script>mensagem('".TRANS('OK_CAD','',0)."!'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else
	if (isset($_POST['submit']) && $_POST['submit'] == TRANS('BT_EDIT')){


		$qry = "UPDATE mail_templates SET ".
				"tpl_sigla = '".noHtml($_POST['tpl_sigla'])."', tpl_subject = '".noHtml($_POST['tpl_subject'])."', ".
				"tpl_msg_html = '".noHtml($_POST['msg_text'])."', tpl_msg_text = '".noHtml($_POST['msg_text'])."' ".
			" WHERE tpl_cod = ".$_POST['cod']."";

		$exec= mysql_query($qry) or die(TRANS('ERR_EDIT'));

		print "<script>mensagem('".TRANS('OK_EDIT')."!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$query2 = "DELETE FROM mail_templates WHERE tpl_cod='".$_GET['cod']."'";
		$resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
				$aviso = TRANS('ERR_DEL');
		}
		else
		{
				$aviso = TRANS('OK_DEL');
		}
		print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}


	print "</table>";
	print "</form>";

	?>
	<script language="JavaScript">

		function valida(){
			var ok = false;

			var ok = validaForm('idSigla','','<?php print TRANS('TPL_SIGLA')?>',1);
			if (ok) var ok = validaForm('idTplDesc','','<?php print TRANS('TPL_SUBJECT')?>',1);
			if (ok) var ok = validaForm('idText','','<?php print TRANS('OPT_MSG')?>',1);

			return ok;
		}
	//-->
	</script>
	<?php 




print "</body>";
print "</html>";

?>