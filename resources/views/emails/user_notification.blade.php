<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.08);
            padding: 25px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            color: #4CAF50;
            font-size: 22px;
        }
        .content {
            margin: 20px 0;
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #888888;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #4CAF50;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #43a047;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📩 You Have a New Notification</h2>
        </div>
        <div class="content">
            <p>{{ $messageText }}</p>
            <a href="{{ url('/') }}" class="btn">Go to Website</a>
        </div>
        <div class="footer">
            <p>— Sent by <strong>SagelyApp</strong></p>
        </div>
    </div>
</body>
</html>
