<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Deal</title>
</head>
<body>
    <h2>{{ $deal->title ?? '' }}</h2>

    <p>{{ $deal->short_description ?? '' }}</p>

    <p>
        <strong>Discount:</strong> {{ $deal->discount ?? '' }}<br>
        <strong>Original Price:</strong> {{ $deal->original_price ?? '' }}
    </p>

    <p>
        Valid till: {{ \Carbon\Carbon::parse($deal->valid_until)->format('d M Y') }}
    </p>

    <a href="{{ url('/deals/'.$deal->id) }}">
        View Deal
    </a>
</body>
</html>
