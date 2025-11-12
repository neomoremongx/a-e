<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
if (isset($_POST["send"])) {
   
   $name= $_POST["name"];
   $subject = "Reservations";
   $email= $_POST["email"];
   $phone = $_POST["phone"];
   $special_requests = $_POST["requests"];
   $guests = filter_input(INPUT_POST,"guests",FILTER_VALIDATE_INT);
   $time = $_POST["time"];
   $date = $_POST["date"];
   $terms = filter_input(INPUT_POST,"terms",FILTER_VALIDATE_BOOL);

  $mail = new PHPMailer(true);

    //Server settings
    $mail->isSMTP();                              //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;             //Enable SMTP authentication
    $mail->Username   = 'neomoremongx@gmail.com';   //SMTP write your email
    $mail->Password   = 'pxcosqmpbjlodmyw';      //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
    $mail->Port       = 465;                                    

    //Recipients
    $mail->setFrom( $_POST["email"], $_POST["name"]); // Sender Email and name
    $mail->addAddress('neomoremongx@gmail.com');     //Add a recipient email  
    $mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email

    //Content
    $mail->isHTML(true);               //Set email format to HTML
    $mail->Subject = $subject;   // email subject headings
    
    $reservation_message = "
<html>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/pimenta.jpg') no-repeat center center/cover;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .details {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-label {
            font-weight: bold;
            color: #1e3f2d;
            width: 180px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #555;
        }
        .footer {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('images/pimenta.jpg') no-repeat center center/cover;
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            margin-top: 20px;
        }
        .special-requests {
            background: #fff8e1;
            padding: 20px;
            border-left: 4px solid #c8a97e;
            margin: 15px 0;
            border-radius: 4px;
        }
        .thank-you {
            font-size: 18px;
            color: #1e3f2d;
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
        }
        .restaurant-info {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class=\"header\">
        <h1> Reservation Request</h1>
        <p>Pimenta is Hand Crafted Portuguese Cuisine at its best</p>
    </div>
    
    <div class=\"content\">
        
       
        
        <p>Dear </p>
        
        <p>You have received a reservation request from <strong>{$name}</strong>, </p>
        
        <div class=\"details\">
            <h3 style=\"color: #1e3f2d; margin-top: 0; text-align: center;\"> Reservation Details</h3>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Guest Name:</span>
                <span class=\"detail-value\">{$name}</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Reservation Date:</span>
                <span class=\"detail-value\">" . date('F j, Y', strtotime($date)) . "</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Reservation Time:</span>
                <span class=\"detail-value\">{$time}</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Number of Guests:</span>
                <span class=\"detail-value\">{$guests} " . ($guests == 1 ? 'person' : 'people') . "</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Contact Email:</span>
                <span class=\"detail-value\">{$email}</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Contact Phone:</span>
                <span class=\"detail-value\">{$phone}</span>
            </div>
        </div>
        
        " . (!empty($special_requests) ? "
        <div class=\"special-requests\">
            <h4 style=\"color: #1e3f2d; margin-top: 0;\">Special Requests</h4>
            <p style=\"margin: 0; font-style: italic;\">{$special_requests}</p>
        </div>
        " : "") . "


    <p>Contact <strong>{$name}</strong> to confirm the reservation</p>
        
         
    
    <div class=\"footer\">
        <p style=\"margin: 0; font-size: 14px;\">
            <strong>Pimenta Restaurant</strong>
            <br>
            © " . date('Y') . " Pimenta Restaurant. All rights reserved.
        </p>
    </div>
</body>
</html>
";
   $today = date('Y-m-d');
    if ($date < $today) {
    echo "
    <script>
     alert('Error: Please select a date that is today or in the future. You cannot make reservations for past dates.');
     document.location.href = 'index.html';
    </script>
    ";
    exit;
    }
    // Success sent message alert
    $mail->Body    = $reservation_message; //email message;
    $mail->send();

   

// Auto-reply to customer
$autoReplyMail = new PHPMailer(true);

try {
    //Server settings - same as your main email
    $autoReplyMail->isSMTP();
    $autoReplyMail->Host       = 'smtp.gmail.com';
    $autoReplyMail->SMTPAuth   = true;
    $autoReplyMail->Username   = 'neomoremongx@gmail.com';
    $autoReplyMail->Password   = 'pxcosqmpbjlodmyw';
    $autoReplyMail->SMTPSecure = 'ssl';
    $autoReplyMail->Port       = 465;

    //Recipients
    $autoReplyMail->setFrom('neomoremongx@gmail.com', 'Pimenta Restaurant');
    $autoReplyMail->addAddress($_POST["email"], $_POST["name"]); // Send to the customer
    $autoReplyMail->addReplyTo('neomoremongx@gmail.com', 'Pimenta Restaurant');

    //Content
    $autoReplyMail->isHTML(true);
    $autoReplyMail->Subject = "Reservation Request Received - Pimenta Restaurant";
    
    $autoReplyMessage = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: #1e3f2d; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .footer { background: #1e3f2d; color: white; padding: 15px; text-align: center; }
        </style>
    </head>
    <body>
        <div class=\"header\">
            <h2>Thank You for Your Reservation Request!</h2>
        </div>
        <div class=\"content\">
            <p>Dear {$name},</p>
            <p>We have received your reservation request for <strong>" . date('F j, Y', strtotime($date)) . "</strong> at <strong>{$time}</strong> for <strong>{$guests}</strong> " . ($guests == 1 ? 'person' : 'people') . ".</p>
            <p>We will review your request and contact you shortly to confirm your reservation.</p>
            <p><strong>Reservation Details:</strong><br>
            Date: " . date('F j, Y', strtotime($date)) . "<br>
            Time: {$time}<br>
            Guests: {$guests}<br>
            Phone: {$phone}</p>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <p>Best regards,<br><strong>The Pimenta Restaurant Team</strong></p>
        </div>
        <div class=\"footer\">
            <p>© " . date('Y') . " Pimenta Restaurant. All rights reserved.</p>
        </div>
    </body>
    </html>
    ";
    
    $autoReplyMail->Body = $autoReplyMessage;
    $autoReplyMail->send();
    
} catch (Exception $e) {
    // Optional: Log error but don't show to user to avoid confusion
    error_log("Auto-reply failed: " . $autoReplyMail->ErrorInfo);
}

// ... continue with your success message ...
    echo
    " 
    <script> 
     alert('Message was sent successfully!');
     document.location.href = 'index.html';
    </script>
    ";
}

?>





