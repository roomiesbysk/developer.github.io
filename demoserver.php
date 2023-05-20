
<?php 
include 'partials/configure.php';
?><?php

  // $connect=mysqli_connect("localhost","root","","project") or die("can not connect to data base");
   if(isset($_POST['payid']) && isset($_POST['amount']) && isset($_POST['availablebed']) && isset($_POST['bprice']) && isset($_POST['address']) && isset($_POST['pin']) && isset($_POST['mno']) ){
    $pay_id=$_POST['payid'];
    $amount=$_POST['amount'];  
    $available_bed=$_POST['availablebed'];
    $bprice=$_POST['bprice'];
    $address=$_POST['address'];
    $pin=$_POST['pin'];
    $mno=$_POST['mno'];
    $img=$_POST['img'];

    session_start();
    if(isset($_SESSION['id'])){
        $sellid=$_SESSION['id'];
    }
    $sql="INSERT INTO `sell` (`bed_available`, `bed_price`, `Address`, `pin`, `mno`, `payid`, `amount`, `sellid`) VALUES ('$available_bed', '$bprice', '$address', '$pin', '$mno', '$pay_id', '$amount', '$sellid')";
    $result=mysqli_query($connect,$sql);
     

    // GETTING LAST DATA INSERTED IN SELL TABLE
 $sqlcmd = "SELECT id FROM sell ORDER BY id DESC LIMIT 1";
 $res=mysqli_query($connect,$sqlcmd);
 if(mysqli_num_rows($res)>0){
    $row=mysqli_fetch_assoc($res);
    $sellid=$row['id'];
 }


    
    // here i am inserting img into img_upload table
    $filename = $_FILES['img']['name'];
$file_tmp = $_FILES['img']['tmp_name'];    
$filetype = $_FILES['img']['type'];
$filesize = $_FILES['img']['size'];
for($i=0; $i<=count($file_tmp); $i++){
  echo 'hello';
if(!empty($file_tmp[$i])){
$name = addslashes($filename[$i]);
$temp = addslashes(file_get_contents($file_tmp[$i]));

$sql="INSERT INTO `img_upload`(`img_name`,`id`) values('$temp',$sellid)"; 
$result=mysqli_query($connect,$sql);
if($result){
}
else{
echo "failed";
echo "<br />";
}
}
}
    


}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
<!-- this is for razorpay -->
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
    rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
    crossorigin="anonymous">
<!-- end razorpay links -->
</head>
<body>
    
<div class="container py-5">
<form action="demoserver.php" enctype="multipart/form-data" method="post">
<div class="form-row">
<div class="form-group col-md-6">
  <label for="inputEmail4">Available bed</label>
  <input type="number" name="abed" class="form-control" id="abed" placeholder="Quantity" required>
</div>
<div class="form-group col-md-6">
  <label for="inputPassword4">Per Bed price</label>
  <input type="number" name="bprice" class="form-control" id="bprice" placeholder="One Bed Price" required>
</div>
</div>
<div class="form-group">
<label for="inputAddress">Address</label>
<input type="text" class="form-control" id="address" placeholder="Local Address" required>
</div>
<div class="form-group">
<label for="inputAddress2">City pin code</label>
<input type="number" class="form-control" id="pin" placeholder="City pin" required>
</div>
<div class="form-row">
<div class="form-group col-md-6">
  <label for="inputCity">Contact No</label>
  <input type="number" class="form-control" id="mno" placeholder="Contact Number" required>
</div>
<input multiple="" accept="image/png, image/jpeg" class="form-control form-control-lg" id="formFileLg" name="img[]" type="file" />

<!-- <div class="form-group col-md-4">
  <label for="inputState">State</label>
  <select id="inputState" class="form-control">
    <option selected>Choose...</option>
    <option>...</option>
  </select>
</div> -->
<!-- <div class="form-group col-md-2">
  <label for="inputZip">Select image</label>
  <input type="file" name="img[]" multipart="" id="img" class="form-control" id="inputZip">
</div> -->
</div>
<!-- <div class="form-group">
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="gridCheck">
  <label class="form-check-label" for="gridCheck">
    Check me out
  </label>
</div>
</div> -->
<!-- <button type="submit" class="btn btn-primary">Sign in</button> -->
<input type="button" class="btn btn-danger mb-2" value="Sell Now" name="button" onclick="MakePayment100()">
</form> 
</div>





<script>
	function MakePayment100(){
		//var name= $("#paynow").val();
		//var name= 
		//var amount= $("#amount").val();
    // var email =document.getElementById('email').value;
    // alert(email);
    var Available_bed=document.getElementById('abed').value;
    var bprice=document.getElementById('bprice').value;
    var add=document.getElementById('address').value;
    var pin=document.getElementById('pin').value;
    var mno=document.getElementById('mno').value;
    var img=document.getElementById('formFileLg').value;

		var amount=100;

		var options = {
    "key": "rzp_test_XkyAfIibqTU8Oz", // Enter the Key ID generated from the Dashboard 
    "amount": amount*100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "INR",
    "name": "name",
    "description": "Test Transaction",
    "image": "image/myimage.jpg",
    //"order_id": "order_IluGWxBm9U8zJ8", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
    	$.ajax({
    		type:"POST",
    		url:"demoserver.php",
    		data:"payid="+response.razorpay_payment_id+"&amount="+amount+"&availablebed="+Available_bed+"&bprice="+bprice+"&address="+add+"&pin="+pin+"&mno="+mno+"&img="+img,
    		success:function(result){
    			alert("Payment successfull Done"); //window.location.href="payment.php"; 
                //window.location.href="upload_img.php";
    		}
    	})
    }
};
var rzp1 = new Razorpay(options);
//document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
//}
			
	} 
</script>

<!-- 

  if (isset($_POST['submit'])) {
    $filename = $_FILES['img']['name'];
$file_tmp = $_FILES['img']['tmp_name'];    
$filetype = $_FILES['img']['type'];
$filesize = $_FILES['img']['size'];
for($i=0; $i<=count($file_tmp); $i++){
if(!empty($file_tmp[$i])){
$name = addslashes($filename[$i]);
$temp = addslashes(file_get_contents($file_tmp[$i]));

$sql="INSERT INTO `img_upload`(`img_name`,`id`) values('$temp','$sellid')"; 
$result=mysqli_query($connect,$sql);
if($result){
}
else{
echo "failed";
echo "<br />";
}
}
}

  }
 -->



</body>
</html>
