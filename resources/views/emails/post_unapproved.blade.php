<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>❌ Bài đăng không được chấp thuận</title>
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
            color: #d73a49;
            margin-bottom: 16px;
        }
        .status-box {
            background-color: #fff5f5;
            border-left: 4px solid #d73a49;
            border: 1px solid #fbb7b9;
            border-radius: 6px;
            padding: 16px;
            color: #d73a49;
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
            background-color: #d73a49;
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
        <div class="header">Bài đăng của bạn không được chấp thuận</div>

        <div class="details">
            <p>Chào <strong>{{ $item->user->name ?? 'người dùng' }}</strong>,</p>

            <p>Bài đăng của bạn được gửi vào ngày <strong>{{ $item->created_at->format('d/m/Y') }}</strong> đã bị <strong>ban quản trị từ chối duyệt</strong> và không được hiển thị.</p>

             <div class="status-box">
                📝 <strong>{{ $item->title }}</strong><br>
                Trạng thái: <strong>Không duyệt</strong>
            </div>

            @if(!empty($reason))
                <div class="details" style="margin-bottom: 16px;">
                    <strong>Lý do không duyệt:</strong>
                    <div style="white-space: pre-line; color: #d73a49;">{{ $reason }}</div>
                </div>
            @endif

            <p>Vui lòng kiểm tra lại bài đăng và chỉnh sửa nếu cần thiết.</p>

            <p>Bạn có thể chỉnh sửa bài đăng của mình tại liên kết bên dưới:</p>

            <a href="{{ route('item.edit', $item->id) }}" class="button">✏️ Chỉnh sửa bài đăng</a>

            <p style="margin-top: 24px;">Cảm ơn bạn đã đóng góp nội dung lên hệ thống!</p>
        </div>

        <div class="footer">
            Đây là email tự động từ hệ thống. Vui lòng không phản hồi lại email này.
        </div>
    </div>
</body>
</html>
