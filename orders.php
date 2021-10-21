<?php require_once('Connections/shop.php'); ?>
<?php
$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysql_select_db($database_shop, $shop);
$query_Recordset1 = "SELECT * FROM orders ORDER BY O_ID DESC";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $shop) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
<link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>

<body>
<table width= "500" border="0" align="center">
	<tr align="center" >
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


<form name="form1" method="post" action="">
<table align="center" width="700" border="3">
	<tr>
		<td>
		訂單編號
		</td>
		<td>
		訂購者
		</td>
		<td>
		日期
		</td>
		<td>
		金額
		</td>
		<td>
		狀態
		</td>
	</tr>
	<?php do { ?>
	  <tr>
	      <td>
            <a href="orderdetail.php?O_ID=<?php echo $row_Recordset1['O_OID']; ?>"><?php echo $row_Recordset1['O_OID']; ?></a>		</td>
	    <td>
	      <?php echo $row_Recordset1['O_CName']; ?>            </td>
	    <td>
	      <?php echo $row_Recordset1['O_Date']; ?>	        </td>
	    <td>
	      <?php echo $row_Recordset1['O_Total']; ?>	        </td>
	    <td>
	      <?php echo $row_Recordset1['O_State']; ?>	        </td>
      </tr>
	  <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
</form>

</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
