<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .link-message {
            text-align: center;
            margin: 50px auto;
            font-size: 30px;
        }

        .certification-link {
            border-radius: 1px solid black;
            background-color: black;
            color: white;
            size: 50px;
            padding: 20px;
            margin: 0px auto;
            text-decoration: none;
        }

    </style>
</head>
<body>

    <p class="link-message">以下のリンクをクリックして認証を完了してください。</p>
    <a class="certification-link" href="{{ route('auth.auth', ['email' => $email, 'onetime_token' => $onetime_token]) }}">
        認証リンク
    </a>

</body>
</html>






