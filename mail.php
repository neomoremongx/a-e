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
    $mail->setFrom($_POST["email"], $_POST["name"]); // Sender Email and name
    $mail->addAddress('neomoremongx@gmail.com');     //Add a recipient email  
    $mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email

    //Content
    $mail->isHTML(true);               //Set email format to HTML
    
    // Determine which form was submitted and set appropriate subject
    if (isset($_POST["event-type"])) {
        // Booking form was submitted
        $mail->Subject = "New Booking Request: " . $_POST["event-type"];
        $formattedMessage = createBookingEmailContent();
    } else {
        // Contact form was submitted
        $mail->Subject = $_POST["subject"];
        $formattedMessage = createContactEmailContent();
    }

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
        $autoReplyMail->setFrom('neomoremongx@gmail.com', 'A & E Decor'); // Your business email
        $autoReplyMail->addAddress($_POST["email"], $_POST["name"]); // Send to the customer
        $autoReplyMail->addReplyTo('neomoremongx@gmail.com', 'A & E Decor'); // Reply to your business email

        //Content
        $autoReplyMail->isHTML(true);
        
        if (isset($_POST["event-type"])) {
            $autoReplyMail->Subject = "Thank You for Your Booking Inquiry - A & E Decor";
            $autoReplyMessage = createBookingAutoReplyContent();
        } else {
            $autoReplyMail->Subject = "Thank You for Your Inquiry - A & E Decor";
            $autoReplyMessage = createContactAutoReplyContent();
        }
        
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
}

// Function to create booking form email content
function createBookingEmailContent() {
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
                width: 150px;
                flex-shrink: 0;
            }
            
            .footer {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
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
            <h1>New Booking Request</h1>
            <p>A & E Wedding and Function Decor</p>
        </div>
        
        <div class=\"content\">
            <div class=\"details\">
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Client Name:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["name"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Email:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["email"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Phone:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["phone"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Event Type:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["event-type"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Event Date:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["event-date"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Guest Count:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["guest-count"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Budget Range:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["budget"]) . "</span>
                </div>
            </div>
            
            <h3 style=\"color: var(--primary);\">Event Details & Special Requests:</h3>
            <div style=\"background: white; padding: 20px; border-radius: 6px; border-left: 4px solid var(--primary);\">
                <p style=\"margin: 0; white-space: pre-line;\">" . htmlspecialchars($_POST["message"]) . "</p>
            </div>
        </div>
        
        <div class=\"footer\">
            <p style=\"margin: 0; font-size: 14px;\">
                <strong>A & E Wedding and Function Decor</strong><br>
                © " . date('Y') . " A & E Decor. All rights reserved.
            </p>
        </div>
    </body>
    </html>
    ";
}

// Function to create contact form email content
function createContactEmailContent() {
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
            <h1>New Contact Inquiry</h1>
            <p>A & E Wedding and Function Decor</p>
        </div>
        
        <div class=\"content\">
            <div class=\"details\">
                <div class=\"detail-row\">
                    <span class=\"detail-label\">From:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["name"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Email:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["email"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Phone:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["phone"]) . "</span>
                </div>
                
                <div class=\"detail-row\">
                    <span class=\"detail-label\">Subject:</span>
                    <span class=\"detail-value\">" . htmlspecialchars($_POST["subject"]) . "</span>
                </div>
            </div>
            
            <h3 style=\"color: var(--primary);\">Message:</h3>
            <div style=\"background: white; padding: 20px; border-radius: 6px; border-left: 4px solid var(--primary);\">
                <p style=\"margin: 0; white-space: pre-line;\">" . htmlspecialchars($_POST["message"]) . "</p>
            </div>
        </div>
        
        <div class=\"footer\">
            <p style=\"margin: 0; font-size: 14px;\">
                <strong>A & E Wedding and Function Decor</strong><br>
                © " . date('Y') . " A & E Decor. All rights reserved.
            </p>
        </div>
    </body>
    </html>
    ";
}

// Function to create booking auto-reply content
function createBookingAutoReplyContent() {
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
        </style>
    </head>
    <body>
        <div class=\"header\">
            <h1>Thank You for Your Booking Request!</h1>
            <p>A & E Wedding and Function Decor</p>
        </div>
        
        <div class=\"content\">
            <div class=\"thank-you\">Dear " . htmlspecialchars($_POST["name"]) . ",</div>
            
            <p>Thank you for your booking request with A & E Wedding and Function Decor. We have received your event details and our team will review your request promptly.</p>
            
            <p><strong>We typically respond within 24 hours.</strong></p>
            
            <p>For urgent matters, please feel free to call us directly at 082 801 2827.</p>
            
            <p>Best regards,<br>
            <strong>The A & E Decor Team</strong></p>
        </div>
        
        <div class=\"footer\">
            <p style=\"margin: 0; font-size: 14px;\">
                <strong>A & E Wedding and Function Decor</strong><br>
                © " . date('Y') . " A & E Decor. All rights reserved.
            </p>
        </div>
    </body>
    </html>
    ";
}

// Function to create contact auto-reply content
function createContactAutoReplyContent() {
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
        </style>
    </head>
    <body>
        <div class=\"header\">
            <h1>Thank You for Contacting A & E Decor!</h1>
            <p>Wedding and Function Decor Specialists</p>
        </div>
        
        <div class=\"content\">
            <div class=\"thank-you\">Dear " . htmlspecialchars($_POST["name"]) . ",</div>
            
            <p>Thank you for reaching out to A & E Wedding and Function Decor. We have received your inquiry and our team will review your message promptly.</p>
            
            <p><strong>We typically respond within 24 hours.</strong></p>
            
            <p>For urgent matters, please feel free to call us directly at 082 801 2827.</p>
            
            <p>Best regards,<br>
            <strong>The A & E Decor Team</strong></p>
        </div>
        
        <div class=\"footer\">
            <p style=\"margin: 0; font-size: 14px;\">
                <strong>A & E Wedding and Function Decor</strong><br>
                © " . date('Y') . " A & E Decor. All rights reserved.
            </p>
        </div>
    </body>
    </html>
    ";
}

?>
