<?php require_once('Connections/shop.php'); ?>
<?php /*?><?php require_once('Connections/shop.php'); ?><?php */?>
<?php
session_start();

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
  $insertSQL = sprintf("INSERT INTO orders (O_OID, O_CName, O_CAddr, O_CPhone, O_CEmail, O_Date, O_Total, O_State) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['O_OID'], "text"),
                       GetSQLValueString($_POST['O_CName'], "text"),
                       GetSQLValueString($_POST['O_CAddr'], "text"),
                       GetSQLValueString($_POST['O_CPhone'], "text"),
                       GetSQLValueString($_POST['O_CEmail'], "text"),
                       GetSQLValueString($_POST['O_Date'], "date"),
                       GetSQLValueString($_POST['O_Total'], "int"),
                       GetSQLValueString($_POST['O_State'], "text"));

  mysql_select_db($database_shop, $shop);
  $Result1 = mysql_query($insertSQL, $shop) or die(mysql_error());

  $insertGoTo = "pay.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}



$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_shop, $shop);
$query_Recordset1 = sprintf("SELECT * FROM customer WHERE C_Username = '%s'", $colname_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $shop) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="CSS/style.css">
<title>資料</title>
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

  </tr>
</table>

<form	name="form1" action="<?php echo $editFormAction; ?>" method="POST">

<table width="700" align="center" border="2">

	<tr>
		<td width="135">
		訂單編號		</td>
		<td width="547">		
		<p><?php echo $_SESSION['O_ID']; ?></p>	  </td>
	</tr>
	<tr>
		<td>
		姓名		</td>
		<td>
		<input	name="O_CName" type="text" id="O_CName"  value="<?php echo $row_Recordset1['C_Name']; ?>"/>		</td>
	</tr>
	<tr>
		<td>
		電話		</td>
		<td>
		<input	name="O_CPhone" type="text" id="O_CPhone" value="<?php echo $row_Recordset1['C_Phone']; ?>"/>		</td>
	</tr>
	<tr>
		<td>
		Email		</td>
		<td>
		<input	name="O_CEmail" type="text" id="O_CEmail" value="<?php echo $row_Recordset1['C_Email']; ?>"/>		</td>
	</tr>
	<tr>
		<td>
		住址		</td>
		<td>
		<input	name="O_CAddr" type="text" id="O_CAddr" value="<?php echo $row_Recordset1['C_Addr']; ?>"/>		</td>
	</tr>
	<tr >
	  <td >
		<p></p>	  </td>
	  <td>
	  <input name="O_OID" type="hidden" id="O_OID" value="<?php echo $_SESSION['O_ID']?>" />
		<input type="hidden" name="O_Date" id="O_Date" value="<?php echo date("Y:m:d:H:i:s")?>"/>
	  <input type="hidden" name="O_Total" id="O_Total" value="<?php echo $_SESSION['Total']?>"/>
	  <input type="hidden" name="O_State" id="O_State" value="處理中"/></td>
	</tr>
	<tr >
		<td colspan="2" align="right">
		<input type="submit" name="button" value="送出" />		</td>
	</tr>
</table>


<input type="hidden" name="MM_insert" value="form1">
</form>


</body>
</html>
<?php
mysql_free_result($Recordset1);

?>
