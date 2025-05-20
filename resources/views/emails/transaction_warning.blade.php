<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>⚠️ Unconfirmed Request Alert</title>
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
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h2 {
            margin: 0;
            color: #cf222e;
            font-size: 20px;
        }
        .content p {
            margin: 8px 0 16px 0;
            color: #24292f;
            font-size: 15px;
        }
        .highlight {
            color: #d1242f;
            font-weight: bold;
        }
        .footer {
            border-top: 1px solid #d0d7de;
            margin-top: 32px;
            padding-top: 16px;
            font-size: 13px;
            color: #57606a;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>⚠️ Yêu cầu chưa được xác nhận</h2>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $transaction->user->name }}</strong>,</p>

            <p>Bạn đã tạo một yêu cầu vào ngày <strong>{{ $transaction->created_at->format('d/m/Y') }}</strong> nhưng hiện tại vẫn chưa xác nhận.</p>

            <p>⏰ Hệ thống sẽ <span class="highlight">tự động xóa</span> yêu cầu này nếu không được xác nhận trước ngày <span class="highlight">{{ $transaction->created_at->addDays(7)->format('d/m/Y') }}</span>.</p>

            <p>Nếu bạn vẫn muốn tiếp tục xử lý, vui lòng truy cập hệ thống và xác nhận trước thời hạn.</p>

            <p style="margin-top: 24px;">— Hệ thống quản lý giao dịch</p>
        </div>
        <div class="footer">
            Email này được gửi tự động. Vui lòng không trả lời.
        </div>
    </div>
</body>
</html>
