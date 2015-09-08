<?php
session_start();
/*

Copyright (c) 2009 Anant Garg (anantgarg.com | inscripts.com)

This script may be used for non-commercial purposes only. For any
commercial purposes, please contact the author at 
anant.garg@inscripts.com

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

*/
/*Modificado por Gianni santucci 22/12/2014*/
/*Llamando al controlador conex*/
require("../controladores/conex.php");
/*
define ('DBPATH','localhost');
define ('DBUSER','root');
define ('DBPASS','password');
define ('DBNAME','chat');
*/
/*global $dbh;
$dbh = mysql_connect(DBPATH,DBUSER,DBPASS);
mysql_selectdb(DBNAME,$dbh);
*/
//$_SESSION["username"]=$_POST['to'];
if ($_GET['action'] == "chatheartbeat") { chatHeartbeat(); } 
if ($_GET['action'] == "sendchat") { sendChat(); } 
if ($_GET['action'] == "closechat") { closeChat(); } 
if ($_GET['action'] == "startchatsession") { startChatSession(); } 

if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();	
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();	
}

function chatHeartbeat() {
	
	$bd=new Conex();
	$bd->conectar();
	$sql = "select  a.id, a.de, a.para, a.message, a.sent, a.recd, (SELECT usuario_login FROM tbl_usuarios WHERE tbl_usuarios.id_usuarios=a.de) AS nom_de,(SELECT usuario_login FROM tbl_usuarios WHERE tbl_usuarios.id_usuarios=a.para) AS nom_para from tbl_chat a where (a.para= '".$_SESSION['id_us']."' AND a.recd = 0) order by id ASC";
	//-- con mysq
	//$query = mysql_query($sql);
	//-- con postgresql
	$query=$bd->execute($sql);
	$items = '';

	$chatBoxes = array();
	//-- con mysql
	/*while ($chat = mysql_fetch_array($query)) {

		if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
			$items = $_SESSION['chatHistory'][$chat['from']];
		}*/
	//--  con postgresql
	for($i=0;$i<=count($query)-1;$i++)
	//while ($chat = mysql_fetch_array($query)) 
	{
		if (!isset($_SESSION['openChatBoxes'][$query[$i][1]]) && isset($_SESSION['chatHistory'][$query[$i][1]]))
		{
			$items = $_SESSION['chatHistory'][$query[$i][1]];
		}
		//$chat['message'] = sanitize($chat['message']);
		$query[$i][3] = sanitize($query[$i][3]);
	/*	$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },*/
	$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$query[$i][1]}",
			"m": "{$query[$i][3]}",
			"h": "{$query[$i][6]}"
	   },   
EOD;

	if (!isset($_SESSION['chatHistory'][$query[$i][1]])) 
	{
		$_SESSION['chatHistory'][$query[$i][1]] = '';
	}
	$_SESSION['chatHistory'][$query[$i][1]] .= <<<EOD
						   {
			"s": "0",
			"f": "{$query[$i][1]}",
			"m": "{$query[$i][3]}",
			"h": "{$query[$i][6]}"
	   },
	
EOD;
		
		unset($_SESSION['tsChatBoxes'][$query[$i][1]]);
		$_SESSION['openChatBoxes'][$query[$i][1]] = $query[$i][4];
	}

	if (!empty($_SESSION['openChatBoxes'])) {
	foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
		if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
			$now = time()-strtotime($time);
			$time = date('g:iA M dS', strtotime($time));
//Bloque para traerme el contenido de los nombres del usuario
	$bd=new Conex();
	$bd->conectar();
	$sql2 = "SELECT usuario_login FROM tbl_usuarios WHERE tbl_usuarios.id_usuarios='".$chatbox."';";
	$rs_to=$bd->execute($sql2);
	$to_us="xx";
//--

			$message = "Sent at $time";
			if ($now > 180) {
				$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}",
"h": "$to_us"
},
EOD;

	if (!isset($_SESSION['chatHistory'][$chatbox])) {
		$_SESSION['chatHistory'][$chatbox] = '';
	}

	$_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"m": "{$message}",
"h": "$to_us"
},
EOD;
			$_SESSION['tsChatBoxes'][$chatbox] = 1;
		}
		}
	}
}

	$bd=new Conex();
	$bd->conectar();
	$sql = "update tbl_chat set recd = 1 where tbl_chat.para = '".$_SESSION['id_us']."' and recd = 0";
	//--Para mysql
	//$query = mysql_query($sql);
	//Para Postgresql
	$query=$bd->execute($sql);
	//
	if ($items != '') {
		$items = substr($items, 0, -1);
	}
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatBoxSession($chatbox) {
	
	$items = '';
	
	if (isset($_SESSION['chatHistory'][$chatbox])) {
		$items = $_SESSION['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession() {
	$items = '';
	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": "<?php echo $_SESSION['id_us'];?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}

function sendChat() {
	$from = $_SESSION['id_us'];
	$to = $_POST['to'];
	$message = $_POST['message'];
//--
//Bloque para traerme el contenido de los nombres del usuario
	$bd=new Conex();
	$bd->conectar();
	$sql2 = "SELECT usuario_login FROM tbl_usuarios WHERE tbl_usuarios.id_usuarios='".$to."';";
	$rs_to=$bd->execute($sql2);
	$to_us=$rs_to[0][0];
//--
	$_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());
	
	$messagesan = sanitize($message);

	if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
		$_SESSION['chatHistory'][$_POST['to']] = '';
	}

	$_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}",
			"h": "{$to_us}"
	   },
EOD;


	unset($_SESSION['tsChatBoxes'][$_POST['to']]);
	$fecha=date("Y-m-d");
	$bd=new Conex();
	$bd->conectar();
	$sql = "insert into tbl_chat (de,para,message,sent) values ('".$from."', '".$to."','".$message."','".$fecha."')";
	//--Para mysql
	//$query = mysql_query($sql);
	//--Para postgresql
	$query=$bd->execute($sql);
	//
	echo "1";
	exit(0);
}

function closeChat() {

	unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
	
	echo "1";
	exit(0);
}

function sanitize($text) {
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
}