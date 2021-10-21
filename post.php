<?php require_once('Connections/shop.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
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
  $insertSQL = sprintf("INSERT INTO product (P_ID, P_Name, P_Introduce, P_Price, P_State) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['P_ID'], "int"),
                       GetSQLValueString($_POST['P_Name'], "text"),
                       GetSQLValueString($_POST['P_Introduce'], "text"),
                       GetSQLValueString($_POST['P_Price'], "int"),
                       GetSQLValueString($_POST['P_State'], "text"));

  mysql_select_db($database_shop, $shop);
  $Result1 = mysql_query($insertSQL, $shop) or die(mysql_error());
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>無標題文件</title>
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
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">


<table width="500" border="3" align="center">
	<tr>
		<td>商品名稱
		<input type="hidden" name="P_ID" />		</td>
	  <td>
		<label>
		  <input type="text" name="P_Name" />
		</label>	  </td>
	</tr>
	<tr>
		<td>簡介</td>
		<td>
		<lable>
			<input type="text" name="P_Introduce"/>
		</lable>		
		</td>
	</tr>
	<tr>
		<td>價格</td>
		<td>
		<lable>
			<input type="text" name="P_Price"/>
		</lable>		</td>
	</tr>
	<tr>
		<td>
		狀態</td>
	  <td><label>
	    <select name="P_State">
	      <option value="正常">正常</option>
	      <option value="缺貨">缺貨</option>
        </select>
	  </label>	  </td>
	</tr>
</table>

<label>
<div align="center">
  <input type="submit" name="Submit" value="送出" />
  <br />
</div>
</label>
<input type="hidden" name="MM_insert" value="form1">
</form>

</body>
</html>
