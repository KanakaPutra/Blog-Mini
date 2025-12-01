<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            background-color: #111827;
            padding: 32px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px 32px;
        }
        .icon-wrapper {
            text-align: center;
            margin-bottom: 24px;
        }
        .lock-icon {
            background-color: #eff6ff;
            color: #2563eb;
            width: 64px;
            height: 64px;
            line-height: 64px;
            border-radius: 50%;
            font-size: 32px;
            display: inline-block;
        }
        h2 {
            color: #111827;
            font-size: 20px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 16px;
            text-align: center;
        }
        p {
            margin-bottom: 24px;
            color: #4b5563;
        }
        .btn-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 8px;
            display: inline-block;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #1d4ed8;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px 32px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
        .link-break {
            word-break: break-all;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Blog Mini</h1>
        </div>
        
        <div class="content">
            <div class="icon-wrapper">
                <span class="lock-icon">🔒</span>
            </div>
            
            <h2>Halo!</h2>
            
            <p>Seseorang (mungkin kamu) telah meminta untuk merubah password akunmu. Demi keamanan, kami ingin memastikan bahwa ini benar-benar kamu.</p>
            
            <p>Jika kamu merasa melakukan permintaan ini, silakan klik tombol di bawah untuk melanjutkan proses reset password:</p>
            
            <div class="btn-wrapper">
                <a href="{{ $url }}" class="btn">Reset Password Saya</a>
            </div>
            
            <p style="font-size: 14px; color: #6b7280; text-align: center;">
                Link ini hanya berlaku selama {{ $count }} menit.<br>
                Jika kamu tidak merasa meminta reset password, abaikan saja email ini. Akunmu tetap aman.
            </p>
        </div>

        <div class="footer">
            <p style="margin-bottom: 12px;">Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser kamu:</p>
            <a href="{{ $url }}" class="link-break">{{ $url }}</a>
            <p style="margin-top: 24px;">&copy; {{ date('Y') }} Blog Mini. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
