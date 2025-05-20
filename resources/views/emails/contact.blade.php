<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📨 New Contact Message</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            background-color: #f6f8fa;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 680px;
            margin: auto;
            background-color: #ffffff;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 24px;
        }
        .header {
            border-bottom: 1px solid #d0d7de;
            margin-bottom: 16px;
            padding-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            color: #24292f;
        }
        .content p {
            margin: 4px 0 12px 0;
            color: #24292f;
            font-size: 15px;
        }
        .label {
            font-weight: 600;
            color: #57606a;
        }
        .message-box {
            background-color: #f6f8fa;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            padding: 16px;
            white-space: pre-wrap;
            word-wrap: break-word;
            color: #1f2328;
            font-size: 14px;
            margin-top: 12px;
        }
        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #d0d7de;
            font-size: 13px;
            color: #57606a;
            text-align: center;
        }
        .footer a {
            color: #0969da;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📨 New Contact Message Received</h2>
        </div>

        <div class="content">
            <p><span class="label">👤 Name:</span> {{ $contactData['name'] }}</p>
            <p><span class="label">📧 Email:</span> {{ $contactData['email'] }}</p>
            <p><span class="label">🏷️ Subject:</span> {{ $contactData['subject'] }}</p>

            <p class="label">📝 Message:</p>
            <div class="message-box">
                {{ $contactData['message'] }}
            </div>
        </div>

        <div class="footer">
            <p>This is an automated message from your contact system. Please do not reply directly.</p>
            <p><a href="{{ url('/') }}">Go to Website</a></p>
        </div>
    </div>
</body>
</html>
