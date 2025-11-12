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

    // Check if form fields exist before accessing them
    $name = isset($_POST["contact-name"]) ? $_POST["contact-name"] : '';
    $email = isset($_POST["contact-email"]) ? $_POST["contact-email"] : '';
    $phone = isset($_POST["contact-phone"]) ? $_POST["contact-phone"] : '';
    $subject = isset($_POST["subject"]) ? $_POST["subject"] : '';
    $message = isset($_POST["contact-message"]) ? $_POST["contact-message"] : '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "
        <script>
         alert('Error: Please fill in all required fields.');
         document.location.href = 'index.html';
        </script>
        ";
        exit;
    }

    try {
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
        $mail->setFrom($email, $name); // Sender Email and name
        $mail->addAddress('neomoremongx@gmail.com');     //Add a recipient email  
        $mail->addReplyTo($email, $name); // reply to sender email

        //Content
        $mail->isHTML(true);               //Set email format to HTML
        $mail->Subject = "Contact Form: " . $subject;   // email subject headings

        $mail->Body = createContactEmailContent($name, $email, $phone, $subject, $message);

        // Success sent message alert
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
        $autoReplyMail->addAddress($email, $name); // Send to the customer
        $autoReplyMail->addReplyTo('neomoremongx@gmail.com', 'A & E Decor');

        //Content
        $autoReplyMail->isHTML(true);
        $autoReplyMail->Subject = "Thank You for Your Inquiry - A & E Decor";
        
        $autoReplyMail->Body = createContactAutoReplyContent($name);
        $autoReplyMail->send();
        
        // Success message
        echo "
        <script> 
         alert('Thank you! Your message has been sent successfully. We will contact you shortly.');
         document.location.href = 'index.html';
        </script>
        ";
        
    } catch (Exception $e) {
        // Error message
        echo "
        <script> 
         alert('Sorry, there was an error sending your message. Please try again or contact us directly at 082 801 2827.');
         document.location.href = 'index.html';
        </script>
        ";
    }
} else {
    // If not a POST request, redirect to home
    header("Location: index.html");
    exit;
}

// Function to create contact form email content
function createContactEmailContent($name, $email, $phone, $subject, $message) {
    return "
    <html>
    <head>
        <style>
            :root {
                --primary: #FF69B4;
                --primary-dark: #E75480;
                --accent: #000000;
                --light: #fff5f9;
                --dark: #222222;
                --text: #2c2c2c;
                --border: #e0e0e0;
            }
            
            body {
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
                line-height: 1.6;
                color: var(--text);
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            
            .header {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: #FF69B4;
                padding: 30px;
                text-align: center;
                border-radius: 8px 8px 0 0;
            }
            
            .content {
                background: var(--light);
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
                color: var(--primary);
                width: 120px;
                flex-shrink: 0;
            }
            
            .footer {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: #FF69B4;
                padding: 25px;
                text-align: center;
                border-radius: 0 0 8px 8px;
                margin-top: 20px;
            }
            
            .message-box {
                background: white;
                padding: 20px;
                border-radius: 6px;
                border-left: 4px solid var(--primary);
                margin-top: 15px;
            }
        </style>
    </head>
    <body>
        <div class=\"header\">
            <h1>New Contact Inquiry</h1>
            <p>A & E Wedding and Function Decor</p>
        </div>
        
        <div class=\"content\">
            <div class=\"details\">
                <div class=\"detail-row\">
                    <span class=\"detail-label\">From:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($name) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Email:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($email) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Phone:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($phone) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Subject:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($subject) . "</span>
                </div>
            </div>
            
            <h3 style=\"color: var(--primary); margin-bottom: 10px;\">Message:</h3>
            <div class=\"message-box\">
                <p style=\"margin: 0; white-space: pre-line;\">" . htmlspecialchars($message) . "</p>
            </div>
        </div>
        
        <div class=\"footer\">
            <p style=\"margin: 0; font-size: 14px;\">
                <strong>A & E Wedding and Function Decor</strong><br>
                41 Paryslaan, Potchefstroom, South Africa<br>
                Phone: 082 801 2827 | Email: aedecor@aedecor.org.za<br>
                © " . date('Y') . " A & E Decor. All rights reserved.
            </p>
        </div>
    </body>
    </html>
    ";
}

// Function to create contact auto-reply content
function createContactAutoReplyContent($name) {
    return "
    <html>
    <head>
        <style>
            :root {
                --primary: #FF69B4;
                --primary-dark: #E75480;
                --accent: #000000;
                --light: #fff5f9;
                --dark: #222222;
                --text: #2c2c2c;
                --border: #e0e0e0;
            }
            
            body {
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
                line-height: 1.6;
                color: var(--text);
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            
            .header {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: white;
                padding: 30px;
                text-align: center;
                border-radius: 8px 8px 0 0;
            }
            
            .content {
                background: var(--light);
                padding: 30px;
                border: 1px solid var(--border);
                border-top: none;
            }
            
            .footer {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: white;
                padding: 25px;
                text-align: center;
                border-radius: 0 0 8px 8px;
                margin-top: 20px;
            }
            
            .thank-you {
                font-size: 18px;
                color: var(--primary);
                margin-bottom: 20px;
                font-weight: bold;
            }
            
            .contact-info {
                background: white;
                padding: 20px;
                border-radius: 8px;
                margin: 20px 0;
                border-left: 4px solid var(--primary);
            }
        </style>
    </head>
    <body>
        <div class=\"header\">
            <h1>Thank You for Contacting A & E Decor!</h1>
            <p>Wedding and Function Decor Specialists</p>
        </div>
        
        <div class=\"content\">
            <div class=\"thank-you\">Dear " . htmlspecialchars($name) . ",</div>
            
            <p>Thank you for reaching out to A & E Wedding and Function Decor. We have received your inquiry and our team will review your message promptly.</p>
            
            <p><strong>We typically respond within 24 hours.</strong></p>
            
            <div class=\"contact-info\">
                <h3 style=\"color: var(--primary); margin-top: 0;\">Our Contact Information</h3>
                <p style=\"margin: 5px 0;\"><strong>Phone:</strong> 082 801 2827</p>
                <p style=\"margin: 5px 0;\"><strong>Email:</strong> aedecor@aedecor.org.za</p>
                <p style=\"margin: 5px 0;\"><strong>Address:</strong> 41 Paryslaan, Potchefstroom, South Africa</p>
                <p style=\"margin: 5px 0;\"><strong>Business Hours:</strong> Mon-Fri: 8:00 AM - 5:00 PM, Sat: 9:00 AM - 2:00 PM</p>
            </div>
            
            <p>For urgent matters, please feel free to call us directly at 082 801 2827.</p>
            
            <p>Best regards,<br>
            <strong>The A & E Decor Team</strong></p>
        </div>
        
        <div class=\"footer\">
            <p style=\"margin: 0; font-size: 14px;\">
                <strong>A & E Wedding and Function Decor</strong><br>
                Creating Dream Events Since 2023<br>
                © " . date('Y') . " A & E Decor. All rights reserved.
            </p>
        </div>
    </body>
    </html>
    ";
}

?>