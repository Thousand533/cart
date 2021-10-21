<?php 
	session_start();
	
	if(isset($_POST['Modify']))
	{
		foreach($_SESSION['Quantity'] as $i => $val)
		{
			$_SESSION['Quantity'][$i]=$_POST['Modify'][$i];
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
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

<form method="post" name="form1" action="showcart.php">
	<table width="800" align="center" border="2">
		<tr>
			<td>
			<p>
			商品編號			</p>			</td>
			<td>
			<p>
			商品名稱			</p>			</td>
			<td>
			<p>
			單價			</p>			</td>
			<td>
			<p>
			數量			</p>			</td>
			<td>
			<p>
			小計			</p>			</td>
		</tr>
		
		<?php 
		if(isset($_SESSION['Cart'])){
		$_SESSION['Total'] = 0;
		foreach($_SESSION['Cart'] as $i => $val)
		{
		?>
		<tr>
		  <td>
			<?php echo $_SESSION['Cart'][$i]; ?>		  </td>
			<td>
			<?php echo $_SESSION['Name'][$i]; ?>		  </td>
			<td>
			<?php echo $_SESSION['Price'][$i]; ?>		  </td>
			<td>
			<input name="Modify[]" type="text" value="<?php echo $_SESSION['Quantity'][$i]; ?>" size="5"/>		  </td>
			<td>
			<?php //小計與總價格
				echo $_SESSION['itemTotal'][$i] = $_SESSION['Price'][$i] * $_SESSION['Quantity'][$i] ;
				$_SESSION['Total'] += $_SESSION['itemTotal'][$i];
			?>			</td>
		</tr>
		<?php } }?>
		<tr>
			<td colspan="5" align="right">
			
			total:
			<?php  
			if(isset($_SESSION['Cart'])){
				echo $_SESSION['Total'];
				}
			?>			</td>
		</tr>
		<tr>
			<td colspan="5" align="right">
			<input name="" type="image" src="update.png" />
			<a href="checkout.php"><img src="pay.png" /></a>	</td>
		</tr>
	</table>


</form>

</body>
</html>
