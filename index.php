<?php
$merchantId = 'PGTESTPAYUAT';
$apiKey="099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
$redirectUrl = 'https://api-preprod.phonepe.com/apis/pg-sandbox';
$order_id = uniqid(); 
$name="Gowri Shankar";
$email="info@tutorialswebsite.com";
$mobile=8019562956;
$amount = 10;
$description = 'Payment for Product/Service';

$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => "MT7850590068188104",
    "merchantUserId"=>"MUID123",
    'amount' => $amount*100,
    'redirectUrl'=>$redirectUrl,
    'redirectMode'=>"POST",
    'callbackUrl'=>$redirectUrl,
    "merchantOrderId"=>$order_id,
   "mobileNumber"=>$mobile,
   "message"=>$description,
   "email"=>$email,
   "shortName"=>$name,
   "paymentInstrument"=> array(    
    "type"=> "PAY_PAGE",
  )
);


 $jsonencode = json_encode($paymentData);
 $payloadMain = base64_encode($jsonencode);
 $salt_index = 1;
 $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
 $sha256 = hash("sha256", $payload);
 $final_x_header = $sha256 . '###' . $salt_index;
 $request = json_encode(array('request'=>$payloadMain));
                
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => $request,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
     "X-VERIFY: " . $final_x_header,
     "accept: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $res = json_decode($response);
 
if(isset($res->success) && $res->success=='1'){
$paymentCode=$res->code;
$paymentMsg=$res->message;
$payUrl=$res->data->instrumentResponse->redirectInfo->url;

header('Location:'.$payUrl) ;
}
}
?>