<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Access Blocked - Country Restriction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
        }
        .icon {
            color: #e53e3e;
            font-size: 48px;
            margin-bottom: 20px;
        }
        h1 {
            color: #e53e3e;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .message {
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .alert {
            background-color: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            color: #c53030;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .reference {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⚠️</div>
        <h1>Access Blocked</h1>
        <div class="message">
            We're sorry, but access to this website is not available from your country.
            <br><br>
            Your access has been restricted due to country-based blocking policies.
        </div>
        <div class="alert">
            All access to this website, including login and registration, is blocked from your location.
        </div>
        <div class="footer">
            If you believe this is an error, please contact the website administrator.
            <div class="reference">
                Reference ID: <?php echo time(); ?>
            </div>
        </div>
    </div>

    <script>
        // Prevent back button from bypassing the block
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
    </script>
</body>
</html>
