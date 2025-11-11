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
    $mail->Subject = $_POST["subject"];   // email subject headings
  $formattedMessage = "
<html>
<head>
    <style>
        :root {
            --primary: #ffffff;
            --secondary: #0066cc;
            --accent: #004d99;
            --dark: #f5f7fa;
            --light: #333333;
            --gray: #666666;
            --border: #e0e0e0;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--light);
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        
        .content {
            background: var(--dark);
            padding: 30px;
            border: 1px solid var(--border);
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
            border-bottom: 1px solid var(--border);
        }
        
        .detail-label {
            font-weight: bold;
            color: var(--secondary);
            width: 120px;
            flex-shrink: 0;
        }
        
        .footer {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class=\"header\">
        <h1>New Project Inquiry</h1>
        <p>StacGuru Contact Form</p>
    </div>
    
    <div class=\"content\">
        <div class=\"details\">
            <div class=\"detail-row\">
                <span class=\"detail-label\">From:</span>
                <span class=\"detail-value\">{$_POST["name"]}</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Email:</span>
                <span class=\"detail-value\">{$_POST["email"]}</span>
            </div>
            
            <div class=\"detail-row\">
                <span class=\"detail-label\">Subject:</span>
                <span class=\"detail-value\">{$_POST["subject"]}</span>
            </div>
        </div>
        
        <h3 style=\"color: var(--secondary);\">Message:</h3>
        <div style=\"background: white; padding: 20px; border-radius: 6px; border-left: 4px solid var(--secondary);\">
            <p style=\"margin: 0; white-space: pre-line;\">{$_POST["message"]}</p>
        </div>
    </div>
    
    <div class=\"footer\">
        <p style=\"margin: 0; font-size: 14px;\">
            <strong>StacGuru</strong><br>
            © " . date('Y') . " StacGuru. All rights reserved.
        </p>
    </div>
</body>
</html>
";

    $mail->Body = $formattedMessage; //email message
    // Success sent message alert
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
    $autoReplyMail->setFrom('neomoremongx@gmail.com', 'Admin StacGuru'); // Your business email
    $autoReplyMail->addAddress($_POST["email"], $_POST["name"]); // Send to the customer
    $autoReplyMail->addReplyTo('neomoremongx@gmail.com', 'Admin StacGuru'); // Reply to your business email

    //Content
    $autoReplyMail->isHTML(true);
    $autoReplyMail->Subject = "Thank You for Your Inquiry - StacGuru";
    
    $autoReplyMessage = "
    <html>
    <head>
        <style>
            :root {
            --primary: #ffffff;
            --secondary: #0066cc;
            --accent: #004d99;
            --dark: #f5f7fa;
            --light: #333333;
            --gray: #666666;
            --border: #e0e0e0;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--light);
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        
        .content {
            background: var(--dark);
            padding: 30px;
            border: 1px solid var(--border);
            border-top: none;
        }
        
        .footer {
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            margin-top: 20px;
        }
        
        .thank-you {
            font-size: 18px;
            color: var(--secondary);
            margin-bottom: 20px;
            font-weight: bold;
        }
        </style>
    </head>
    <body>
        <div class=\"header\">
            <h1>Thank You for Contacting StacGuru!</h1>
            <p>Advanced Tech Solutions</p>
        </div>
        
        <div class=\"content\">
            <div class=\"thank-you\">Dear {$_POST["name"]},</div>
            
            <p>Thank you for reaching out to StacGuru. We have received your inquiry and our team will review your message promptly.</p>
            
            <p><strong>We typically respond within 24 hours.</strong></p>
            
            <p>For urgent matters, please feel free to call us directly at 0739653460.</p>
            
            <p>Best regards,<br>
            <strong>The StacGuru Team</strong></p>
        </div>
        
        <div class=\"footer\">
            <p style=\"margin: 0; font-size: 14px;\">
                <strong>StacGuru</strong><br>
                © " . date('Y') . " StacGuru. All rights reserved.
            </p>
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

echo
" 
<script> 
 alert('Message was sent successfully!');
 document.location.href = 'index.html';
</script>
";
    echo
    " 
    <script> 
     alert('Message was sent successfully!');
     document.location.href = 'index.html';
    </script>
    ";
}


?>
