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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
   $name = $_POST["name"];
   $subject = "Decor Booking Request - A&E Decor";
   $email = $_POST["email"];
   $phone = $_POST["phone"];
   $event_type = $_POST["event-type"];
   $event_date = $_POST["event-date"];
   $guest_count = filter_input(INPUT_POST, "guest-count", FILTER_VALIDATE_INT);
   $budget = $_POST["budget"];
   $message = $_POST["message"];

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
    $mail->setFrom($_POST["email"], $_POST["name"]); // Sender Email and name
    $mail->addAddress('neomoremongx@gmail.com');     //Add a recipient email  
    $mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email

    //Content
    $mail->isHTML(true);               //Set email format to HTML
    $mail->Subject = $subject;   // email subject headings
    
    // Map budget values to readable text
    $budget_ranges = [
        'under-5000' => 'Under R5,000',
        '5000-10000' => 'R5,000 - R10,000',
        '10000-20000' => 'R10,000 - R20,000',
        '20000-50000' => 'R20,000 - R50,000',
        'over-50000' => 'Over R50,000'
    ];
    
    $budget_text = isset($budget_ranges[$budget]) ? $budget_ranges[$budget] : $budget;

    $booking_message = "
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
            background: linear-gradient(rgba(255, 105, 180, 0.8), rgba(255, 105, 180, 0.9));
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
            color: #FF69B4;
            width: 180px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #555;
        }
        .footer {
            background: linear-gradient(rgba(255, 105, 180, 0.8), rgba(255, 105, 180, 0.9));
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            margin-top: 20px;
        }
        .special-requests {
            background: #fff0f7;
            padding: 20px;
            border-left: 4px solid #FF69B4;
            margin: 15px 0;
            border-radius: 4px;
        }
        .thank-you {
            font-size: 18px;
            color: #FF69B4;
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class=\"header\">
        <h1>Decor Booking Request</h1>
        <p>A & E Wedding and Function Decor - Creating Dream Events</p>
    </div>
    
    <div class=\"content\">
        <div class=\"thank-you\">
            New Booking Request Received!
        </div>
        
        <p>You have received a new decor booking request from <strong>{$name}</strong>.</p>
        
        <div class=\"details\">
            <h3 style=\"color: #FF69B4; margin-top: 0; text-align: center;\">Event Details</h3>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Client Name:</span>
                <span class=\"detail-value\">{$name}</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Event Type:</span>
                <span class=\"detail-value\">" . ucfirst(str_replace('-', ' ', $event_type)) . "</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Event Date:</span>
                <span class=\"detail-value\">" . date('F j, Y', strtotime($event_date)) . "</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Number of Guests:</span>
                <span class=\"detail-value\">{$guest_count}</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Budget Range:</span>
                <span class=\"detail-value\">{$budget_text}</span>
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
        
        " . (!empty($message) ? "
        <div class=\"special-requests\">
            <h4 style=\"color: #FF69B4; margin-top: 0;\">Event Details & Special Requests</h4>
            <p style=\"margin: 0; font-style: italic;\">{$message}</p>
        </div>
        " : "") . "

        <p>Please contact <strong>{$name}</strong> at {$email} or {$phone} to discuss this booking request.</p>
    </div>
    
    <div class=\"footer\">
        <p style=\"margin: 0; font-size: 14px;\">
            <strong>A & E Wedding and Function Decor</strong>
            <br>
            41 Paryslaan, Potchefstroom, South Africa
            <br>
            Phone: 082 801 2827 | Email: aedecor@aedecor.org.za
            <br>
            © " . date('Y') . " A & E Decor. All rights reserved.
        </p>
    </div>
</body>
</html>
";

   // Date validation
   $today = date('Y-m-d');
   if ($event_date < $today) {
    echo "
    <script>
     alert('Error: Please select a date that is today or in the future. You cannot make reservations for past dates.');
     document.location.href = 'index.html';
    </script>
    ";
    exit;
   }

    try {
        // Success sent message alert
        $mail->Body = $booking_message;
        $mail->send();

        // Auto-reply to customer
        $autoReplyMail = new PHPMailer(true);

        //Server settings - same as your main email
        $autoReplyMail->isSMTP();
        $autoReplyMail->Host       = 'smtp.gmail.com';
        $autoReplyMail->SMTPAuth   = true;
        $autoReplyMail->Username   = 'neomoremongx@gmail.com';
        $autoReplyMail->Password   = 'pxcosqmpbjlodmyw';
        $autoReplyMail->SMTPSecure = 'ssl';
        $autoReplyMail->Port       = 465;

        //Recipients
        $autoReplyMail->setFrom('neomoremongx@gmail.com', 'A & E Wedding and Function Decor');
        $autoReplyMail->addAddress($_POST["email"], $_POST["name"]); // Send to the customer
        $autoReplyMail->addReplyTo('neomoremongx@gmail.com', 'A & E Decor');

        //Content
        $autoReplyMail->isHTML(true);
        $autoReplyMail->Subject = "Booking Request Received - A & E Decor";
        
        $autoReplyMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background: #FF69B4; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { background: #FF69B4; color: white; padding: 15px; text-align: center; }
                .details { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class=\"header\">
                <h2>Thank You for Your Booking Request!</h2>
            </div>
            <div class=\"content\">
                <p>Dear {$name},</p>
                <p>We have received your decor booking request for your <strong>" . ucfirst(str_replace('-', ' ', $event_type)) . "</strong> on <strong>" . date('F j, Y', strtotime($event_date)) . "</strong> for <strong>{$guest_count}</strong> guests.</p>
                
                <div class=\"details\">
                    <p><strong>Booking Summary:</strong><br>
                    Event Type: " . ucfirst(str_replace('-', ' ', $event_type)) . "<br>
                    Event Date: " . date('F j, Y', strtotime($event_date)) . "<br>
                    Guest Count: {$guest_count}<br>
                    Budget Range: {$budget_text}<br>
                    Contact: {$phone}</p>
                </div>
                
                <p>Our team will review your request and contact you within 24-48 hours to discuss your event in detail and provide a customized quote.</p>
                <p>If you have any urgent questions, please don't hesitate to contact us at 082 801 2827.</p>
                <p>Best regards,<br><strong>The A & E Decor Team</strong></p>
            </div>
            <div class=\"footer\">
                <p>A & E Wedding and Function Decor<br>
                41 Paryslaan, Potchefstroom | 082 801 2827 | aedecor@aedecor.org.za<br>
                © " . date('Y') . " A & E Decor. All rights reserved.</p>
            </div>
        </body>
        </html>
        ";
        
        $autoReplyMail->Body = $autoReplyMessage;
        $autoReplyMail->send();
        
        // Success message
        echo "
        <script> 
         alert('Thank you! Your booking request has been sent successfully. We will contact you shortly.');
         document.location.href = 'index.html';
        </script>
        ";
        
    } catch (Exception $e) {
        // Error message
        echo "
        <script> 
         alert('Sorry, there was an error sending your booking request. Please try again or contact us directly at 082 801 2827.');
         document.location.href = 'index.html';
        </script>
        ";
    }
} else {
    // If not a POST request, redirect to home
    header("Location: index.html");
    exit;
}

?>