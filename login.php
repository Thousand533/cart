<?php require_once('Connections/shop.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_shop, $shop);
  
  $LoginRS__query=sprintf("SELECT C_Username, C_Password FROM customer WHERE C_Username=%s AND C_Password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $shop) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="CSS/style.css">
<title>登入畫面</title>
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

<form action="<?php echo $loginFormAction; ?>" name="form1" method="POST" >

<table width="500" border="2" align="center">
	<tr>
		<td colspan="2" align="center">
		<p> 登入 </p>
		</td>
	</tr>
	<tr>
		<td>
		<p>登入名稱</p>
		</td>
		<td>
		<p>
		<label>
		<input type="text" name="username" />
		</label>
		</p>
		</td>
	</tr>
	<tr>
		<td>
		<p>密碼</p>
		</td>
		<td>
		<p>
		<label>
		<input type="password" name="password" />
		</label>
		</p>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
		<p>
		<input type="submit" name="submit" value="送出"/>
		</p>
		</td>
	</tr>
	

</table>

</form>
</body>
</html>
