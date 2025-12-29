<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unsubscribe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .success {
            color: #16a34a;
        }

        .error {
            color: #dc2626;
        }

        p {
            color: #475569;
            margin-top: 12px;
        }
    </style>
</head>
<body>

<div class="card">
    @if($status === 'success')
        <h2 class="success">✅ Unsubscribed</h2>
    @else
        <h2 class="error">⚠️ Unsubscribe Failed</h2>
    @endif

    <p>{{ $message }}</p>
</div>

</body>
</html>
