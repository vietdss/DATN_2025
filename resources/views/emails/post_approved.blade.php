<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>✅ Bài đăng đã được chấp thuận</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            background-color: #f6f8fa;
            padding: 24px;
            margin: 0;
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
            font-size: 20px;
            color: #1a7f37;
            margin-bottom: 16px;
        }
        .status-box {
            background-color: #f0fdf4;
            border-left: 4px solid #2da44e;
            border: 1px solid #a6f4c5;
            border-radius: 6px;
            padding: 16px;
            color: #1a7f37;
            font-size: 14px;
            margin: 20px 0;
        }
        .details {
            font-size: 15px;
            color: #24292f;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background-color: #2da44e;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 16px;
        }
        .footer {
            font-size: 13px;
            color: #57606a;
            border-top: 1px solid #d0d7de;
            padding-top: 16px;
            margin-top: 32px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Bài đăng của bạn đã được chấp thuận</div>

        <div class="details">
            <p>Chào <strong>{{ $item->user->name ?? 'người dùng' }}</strong>,</p>

            <p>Bài đăng của bạn được gửi vào ngày <strong>{{ $item->created_at->format('d/m/Y') }}</strong> đã được <strong>ban quản trị duyệt và chấp thuận hiển thị</strong>.</p>

            <div class="status-box">
                📝 <strong>{{ $item->title }}</strong><br>
                Trạng thái: <strong>Đã duyệt</strong>
            </div>

            <p>Bạn có thể xem bài đăng của mình tại liên kết bên dưới:</p>

            <a href="{{ route('item.detail', $item->id) }}" class="button">🔗 Xem bài đăng</a>

            <p style="margin-top: 24px;">Cảm ơn bạn đã đóng góp nội dung lên hệ thống!</p>
        </div>

        <div class="footer">
            Đây là email tự động từ hệ thống. Vui lòng không phản hồi lại email này.
        </div>
    </div>
</body>
</html>
