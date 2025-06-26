<?php

$responseMessage = '';

//  Config
$ocrApiKey = 'YOUR_OCR_API_KEY'; // Replace with your OCR.Space key
$insertApiUrl = 'MY_API_URL'; // Input_Operation.php

// Retrieve messages from Twilio
$mediaUrl = $_POST['MediaUrl0'] ?? '';
$mediaType = $_POST['MediaContentType0'] ?? '';
$from = $_POST['From'] ?? 'Unknown';


// Check if the message contains an image
if (strpos($mediaType, 'image') !== false && !empty($mediaUrl)) {
   //  OCR Process 
    $ocrResponse = ocrSpaceImage($mediaUrl, $ocrApiKey);
    file_put_contents("ocr_response.log", print_r($ocrResponse, true), FILE_APPEND);

    $ocrText = $ocrResponse['ParsedResults'][0]['ParsedText'] ?? '';

    if (!empty($ocrText)) {
			 // 1. Default values
			$header = "WhatsApp OCR receipt";
			$item = "";
		    $address = "";
			$quantity = 0;
			$price = 0.0;
			$tax = 0.0;

			// 2. Parse the value based on the pattern
		    if (preg_match('/Address:\s*(.+)/i', $ocrText, $match)) {
				$address = trim($match[1]);
			}
		
			if (preg_match('/Item:\s*(.+)/i', $ocrText, $match)) {
				$item = trim($match[1]);
			}

			if (preg_match('/Quantity:\s*(\d+)/i', $ocrText, $match)) {
				$quantity = (int) $match[1];
			}

			if (preg_match('/Price:\s*([\d\.]+)/i', $ocrText, $match)) {
				$price = (float) $match[1];
			}

			if (preg_match('/Tax:\s*([\d\.]+)/i', $ocrText, $match)) {
				$tax = (float) $match[1];
			}

			// Send to API insert (if needed)
			$data = [
				'Header' => $header .', '. $from,
				'Body' => substr($ocrText, 0, 100),
				'Create_datetime' => date('Y-m-d'),
				'Address' => $address,
				'Item' => $item,
				'Quatity' => $quantity,
				'Price' => $price,
				'Tax' => $tax
			];

			//  Reply message format to WhatsApp
			$responseMessage = "Thank you Hanif! Your receipt has been processed:\n"
				. "- Header: $header\n"
				. "- Item: $item\n"
				. "- Quantity: $quantity\n"
				. "- Price: RM" . number_format($price, 2) . "\n"
				. "- Tax: RM" . number_format($tax, 2) . "\n"
				. "- Visit APP_Receipt : URL_YOUR_APP" ;
		} else {
        	$responseMessage = "Sorry Hanif, your image cannot be read by the OCR system. Please make sure the image is clear and try again.";
        }
}else {
    $responseMessage = "Please send a picture of the receipt only.";
}

// === Response message to whatsap
header("Content-Type: application/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>\n";
echo "  <Message>" . htmlspecialchars($responseMessage) . "</Message>\n";
echo "</Response>";


function ocrSpaceImage($twilioImageUrl, $apiKey) {
  
    $twilio_sid = 'ACxxxxxxxxxxxxxxxxxxxxx'; 
    $twilio_token = 'your_auth_token';       

    $tmpFile = tempnam(sys_get_temp_dir(), 'ocr_') . '.jpg';
    $fp = fopen($tmpFile, 'w+');

   
    $ch = curl_init($twilioImageUrl);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$twilio_sid:$twilio_token"); 
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    // Send to OCR.Space
    $post = [
        'file' => new CURLFile($tmpFile, 'image/jpeg', 'upload.jpg'),
        'language' => 'eng',
        'isOverlayRequired' => 'false'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.ocr.space/parse/image');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $apiKey
    ]);
    $result = curl_exec($ch);
    curl_close($ch);

    unlink($tmpFile); 

    return json_decode($result, true);
}
?>