<?php require_once('Connections/shop.php'); ?>
<?php
// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="reg.php";
  $loginUsername = $_POST['C_Username'];
  $LoginRS__query = "SELECT C_Username FROM customer WHERE C_Username='" . $loginUsername . "'";
  mysql_select_db($database_shop, $shop);
  $LoginRS=mysql_query($LoginRS__query, $shop) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO customer (C_Username, C_Password, C_Name, C_Addr, C_Phone, C_Email) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['C_Username'], "text"),
                       GetSQLValueString($_POST['C_Password'], "text"),
                       GetSQLValueString($_POST['C_Name'], "text"),
                       GetSQLValueString($_POST['C_Addr'], "text"),
                       GetSQLValueString($_POST['C_Phone'], "text"),
                       GetSQLValueString($_POST['C_Email'], "text"));

  mysql_select_db($database_shop, $shop);
  $Result1 = mysql_query($insertSQL, $shop) or die(mysql_error());

  $insertGoTo = "login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="CSS/style.css">
<title>註冊畫面</title>
</head>

<body>
<table width= "500" border="0" align="center">
	<tr align="center">
		<td>
		<a href="index.php">瀏覽商品 </a>
		</td>
		<td>
		<a href="showcart.php">檢視購物車 </a>
		</td>
		<td>
		<a href="cls.php">清空購物車 </a>
		</td>
		<td>
		<a href="reg.php">註冊 </a>
		</td>
		<td>
		<a href="login.php">登入 </a>
		</td>
  </tr>
</table>
<form name="form1" action="<?php echo $editFormAction; ?>" method="POST" >
<table width="500" border="3" align="center">
	<tr>
		<td colspan="2" align="center">
		<p>註冊</p>
		</td>
	</tr>
	<tr>
		<td>登入名稱</td>
		<td>
		<label>
		<input type="text" name="C_Username" />
		</label>
		</td>
	</tr>
	<tr>
		<td>密碼</td>
		<td>
		<label>
		<input type="password" name="C_Password" />
		</label>
		</td>
	</tr>
	<tr>
		<td>信箱</td>
		<td>
		<label>
		<input type="text" name="C_Email" />
		</label>
		</td>
	</tr>
	<tr>
		<td>姓名</td>
		<td>
		<label>
		<input type="text" name="C_Name" />
		</label>
		</td>
	</tr>
	<tr>
		<td>電話</td>
		<td>
		<label>
		<input type="text" name="C_Phone" />
		</label>
		</td>
	</tr>
	<tr>
		<td>住址</td>
		<td>
		<label>
		<input type="text" name="C_Addr" />
		</label>
		</td>
	</tr>
	<tr >
		<td height="31" colspan="2" align="center">
		<p>有帳號了?<a href="login.php">點我登入</a></p>
	  </td>
		
	</tr>
	<tr>
		<td colspan="2" align="right">
		  
		<input type="submit" name="submit" value="送出">
	  </td>

	</tr>
	


</table>
<input type="hidden" name="MM_insert" value="form1">
</form>


</body>
</html>
