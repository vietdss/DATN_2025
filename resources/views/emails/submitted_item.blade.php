<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>📦 New Listing Submitted</title>
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
        .title {
            margin-top: 0;
            color: #1f2328;
            font-size: 20px;
        }
        .meta {
            font-size: 14px;
            color: #57606a;
            margin-bottom: 16px;
        }
        .highlight-box {
            background-color: #f6f8fa;
            border-left: 4px solid #0969da;
            padding: 16px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .description {
            background-color: #ffffff;
            border: 1px solid #d0d7de;
            padding: 12px;
            white-space: pre-wrap;
            word-wrap: break-word;
            border-radius: 6px;
            margin-top: 8px;
            font-size: 14px;
        }
        .action-link {
            display: inline-block;
            margin-top: 16px;
            background-color: #0969da;
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        .footer {
            font-size: 14px;
            color: #57606a;
            margin-top: 32px;
            border-top: 1px solid #d0d7de;
            padding-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title">📦 New Listing Submitted</h2>
        <p class="meta"><strong>User:</strong> {{ $item->user->name ?? 'Người dùng không xác định' }}</p>

        <p>A new listing has been submitted and is pending your review:</p>

        <div class="highlight-box">
            <p style="margin: 0 0 8px;"><strong>📝 Title:</strong> {{ $item->title }}</p>
            <p style="margin: 0;"><strong>📄 Description:</strong></p>
            <div class="description">{{ $item->description }}</div>
        </div>

        <a href="{{ route('admin.items.show', $item->id) }}" class="action-link">🔍 Review Listing</a>

        <div class="footer">
            Thanks for keeping the platform clean and organized.<br>
            — {{ config('app.name') }} Team
        </div>
    </div>
</body>
</html>
