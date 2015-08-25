<?php 
/***************************
	receive the data from client side
	==> forward it to remote server
	http://www.paulund.co.uk/make-cross-domain-ajax-calls-with-jquery-and-php
***************************/

header("Content-type: text/plain"); // http://stackoverflow.com/questions/1414325/is-headercontent-typetext-plain-necessary-at-all

// retrieve "forward url" from AJAX call (stored in "data")
$forward_url = $_POST['forward_url'];
unset($_POST['forward_url']);

$strToSend = $_REQUEST['strToSend'];
// the file is received and stored. Let's put it in a variable.
$localFile = $_FILES['audioFile']['tmp_name']; 
$filename = basename($_FILES['audioFile']['name']);

error_log($strToSend);
error_log($localFile);
error_log($filename);
error_log($forward_url);


$data = array(
	// ALTERNATIVE (better): 'uploaded_file' => curl_file_create($localFile, $_FILES['audioFile']['type'], $filename
    // http://stackoverflow.com/questions/4223977/send-file-via-curl-from-form-post-in-php
    'audioFile' => '@'.$localFile.';filename='.$filename,
    'strToSend'=> $strToSend // without @ (since it's not a file)
);

//open connection
$curl = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // to use cUrl http <--> https
curl_setopt($curl,CURLOPT_URL,$forward_url); // where to send to
curl_setopt($curl,CURLOPT_POSTFIELDS,$data); // data to send
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // useless From PHP 5.1.3 >> http://php.net/manual/en/function.curl-setopt.php
curl_setopt($curl, CURLOPT_TIMEOUT, '3'); // maximum number of seconds to allow cURL functions to execute.
// curl_setopt($curl, CURLOPT_POST, 1); // Set request method to POST

//execute post (FormData)
$result = curl_exec($curl);

if(!curl_exec($curl)){ // if error ==> print actual error, otherwise print result
    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
} else {
	print_r($result);
}

//close connection
curl_close($curl);
?>