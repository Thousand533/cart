<?php require_once('Connections/shop.php'); ?>
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

if (isset($_POST['D_PName']))
{
/**
*    Credit信用卡付款產生訂單範例
*/   
    //載入SDK(路徑可依系統規劃自行調整)
    include('ECPay.Payment.Integration.php');
    try {      
    	$obj = new ECPay_AllInOne();
        //服務參數
        $obj->ServiceURL  = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";   //服務位置
        $obj->HashKey     = '5294y06JbISpM5x9' ;                                           //測試用Hashkey，請自行帶入ECPay提供的HashKey
        $obj->HashIV      = 'v77hoKGq4kWxNNIS' ;                                           //測試用HashIV，請自行帶入ECPay提供的HashIV
        $obj->MerchantID  = '2000214';                                                     //測試用MerchantID，請自行帶入ECPay提供的MerchantID
        $obj->EncryptType = '1';                                                           //CheckMacValue加密類型，請固定填入1，使用SHA256加密


        //基本參數(請依系統規劃自行調整)
        $MerchantTradeNo = "Test".time() ;
        $obj->Send['ReturnURL']         = "http://localhost/shop/index.php" ;    //付款完成通知回傳的網址
        $obj->Send['OrderResultURL']         = "http://localhost/shop/orders.php" ; 
        $obj->Send['MerchantTradeNo']   = $MerchantTradeNo;                          //訂單編號
        $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');                       //交易時間
        $obj->Send['TotalAmount']       = $_POST['Total'];                                      //交易金額
        $obj->Send['TradeDesc']         = "Thank you" ;                          //交易描述
        $obj->Send['ChoosePayment']     = ECPay_PaymentMethod::Credit ;              //付款方式:Credit
        $obj->Send['IgnorePayment']     = ECPay_PaymentMethod::GooglePay ;           //不使用付款方式:GooglePay

        //訂單的商品資料	
		foreach($_SESSION['Cart'] as $i => $val)
		{
		array_push($obj->Send['Items'], array('Name' => $_POST['D_PName'][$i], 'Price' => (int)$_POST['D_PPrice'][$i] ,
                   'Currency' => "元", 'Quantity' => (int) $_POST['D_PQuantity'][$i], 'URL' => "dedwed"));
		}

		
        //Credit信用卡分期付款延伸參數(可依系統需求選擇是否代入)
        //以下參數不可以跟信用卡定期定額參數一起設定
        $obj->SendExtend['CreditInstallment'] = $_POST['CreditInstallment'] ;    //分期期數，預設0(不分期)，信用卡分期可用參數為:3,6,12,18,24
        $obj->SendExtend['InstallmentAmount'] = 0 ;    //使用刷卡分期的付款金額，預設0(不分期)
        $obj->SendExtend['Redeem'] = false ;           //是否使用紅利折抵，預設false
        $obj->SendExtend['UnionPay'] = false;          //是否為聯營卡，預設false;

        //Credit信用卡定期定額付款延伸參數(可依系統需求選擇是否代入)
        //以下參數不可以跟信用卡分期付款參數一起設定
        // $obj->SendExtend['PeriodAmount'] = '' ;    //每次授權金額，預設空字串
        // $obj->SendExtend['PeriodType']   = '' ;    //週期種類，預設空字串
        // $obj->SendExtend['Frequency']    = '' ;    //執行頻率，預設空字串
        // $obj->SendExtend['ExecTimes']    = '' ;    //執行次數，預設空字串
        
        # 電子發票參數
        /*
        $obj->Send['InvoiceMark'] = ECPay_InvoiceState::Yes;
        $obj->SendExtend['RelateNumber'] = "Test".time();
        $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
        $obj->SendExtend['CustomerPhone'] = '0911222333';
        $obj->SendExtend['TaxType'] = ECPay_TaxType::Dutiable;
        $obj->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
        $obj->SendExtend['InvoiceItems'] = array();
        // 將商品加入電子發票商品列表陣列
        foreach ($obj->Send['Items'] as $info)
        {
            array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => ECPay_TaxType::Dutiable));
        }
        $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
        $obj->SendExtend['DelayDay'] = '0';
        $obj->SendExtend['InvType'] = ECPay_InvType::General;
        */


        //產生訂單(auto submit至ECPay)
        $obj->CheckOut();

    
    } catch (Exception $e) {
    	echo $e->getMessage();
    } 
}

/*if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{


  $insertGoTo = "test.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}*/
 

 if(!isset($_SESSION['O_ID']))
 	$_SESSION['O_ID'] = date("YmdHis").substr(md5(uniqid(rand())),0,6);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>
<link rel="stylesheet" type="text/css" href="CSS/style.css">
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

<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">

<table width="800" align="center" border="2">
	<tr>
	  <td colspan="5">
		訂單編號:
		<input name="D_OID" type="hidden" id="D_OID" value="<?php echo $_SESSION['O_ID']; ?>" />		<?php echo $_SESSION['O_ID']; ?></td> 
	</tr>
</table>
<table width="800" align="center" border="2">
	<tr align="center">
		<td>
		商品編號		</td>
		<td>
		商品名稱		</td>
		<td>
		商品單價		</td>
		<td>
		訂購數量		</td>
		<td>
		小計</td>
		<?php foreach($_SESSION['Cart'] as $i => $val)
			  {
		?>
	<tr align="center">
	
		<td>
		<p><?php echo $_SESSION['Cart'][$i]; ?>
		  <input type="hidden" name="Cart[]"  value="<?php echo $_SESSION['Cart'][$i]; ?>" />
</p>		</td>
		<td>
		<p>
		  <?php echo $_SESSION['Name'][$i]; ?>
		  <input name="D_PName[]" type="hidden" value="<?php echo $_SESSION['Name'][$i]; ?>" />
</p>		</td>
		<td>
		<p>
		  <?php echo $_SESSION['Price'][$i]; ?>
		  <input name="D_PPrice[]" type="hidden" value="<?php echo $_SESSION['Price'][$i]; ?>" />
</p>		</td>
		<td>
		<p>
		  <?php echo $_SESSION['Quantity'][$i]; ?>
		  <input name="D_PQuantity[]" type="hidden" value="<?php echo $_SESSION['Quantity'][$i]; ?>" />
</p>		</td>
		<td>
		<p>
		  <?php echo $_SESSION['itemTotal'][$i]; ?>
		  <input name="D_ItemTotal[]" type="hidden" value="<?php echo $_SESSION['itemTotal'][$i]; ?>" />
</p>		</td>
	</tr>
	<?php } ?>
	<tr align="right">
		<td colspan="5">
		總金額:<?php echo $_SESSION['Total']; ?>		</td>
	</tr>
	<tr>
		<td colspan="3"> 一次或分期 
		  <label>
		  <select name="CreditInstallment" id="CreditInstallment">
		    <option value="0">一次付清</option>
		    <option value="3">分三期</option>
		    <option value="6">分六期</option>
	      </select>
	    </label></td>
		<td colspan="2" align="right">
		  <input name="Total" type="hidden" id="Total" value="<?php echo $_SESSION['Total']; ?>" />
	  <input name="" type="submit" value="前往付款"/>		</td>	
	</tr>
</table>



</form>
</body>
</html>
