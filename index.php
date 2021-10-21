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

mysql_select_db($database_shop, $shop);
$query_Recordset1 = "SELECT * FROM products ORDER BY P_ID DESC";
$Recordset1 = mysql_query($query_Recordset1, $shop) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首頁</title>
<link rel="stylesheet" type="text/css" href="CSS/style.css">
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

<form action="" name="form1" method="post">
<table width="500" border="2" align="center">
	<tr>
		<td width="243">
		<p>商品名稱</p>
	  </td>
		<td width="239">
		<p>價格</p>
	  </td>
	</tr>
		<?php do { ?>
	  <tr>
	    <td width="257" height="25"><a href="product.php?P_ID=<?php echo $row_Recordset1['P_ID']; ?>"><?php echo $row_Recordset1['P_Name']; ?></a></td>
	    <td width="233"><?php echo $row_Recordset1['P_Price']; ?></td>
      </tr>
	  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>




</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
