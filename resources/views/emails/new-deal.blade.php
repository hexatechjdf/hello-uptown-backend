<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $deal->title ?? 'Deal Details' }}</title>
</head>
<body>
    @if(!empty($deal->is_featured) && $deal->is_featured)
        <p style="color: green; font-weight:bold;">⭐ Featured Deal</p>
    @endif
    <h2>{{ $deal->title ?? '' }}</h2>
    @if(!empty($deal->image))
        <img src="{{ $deal->image }}" alt="Deal Image" style="max-width:300px;">
    @endif
    <p>
        <strong>Business:</strong> {{ $deal->business->business_name ?? 'N/A' }}<br>
        <strong>Category:</strong> {{ $deal->category->name ?? 'N/A' }}
    </p>
    <h3>About this deal</h3>
    <p>{{ $deal->short_description ?? '' }}</p>

    @if(!empty($deal->long_description))
        <p>{{ $deal->long_description }}</p>
    @endif
    <h3>Pricing</h3>
    <p>
        <strong>Original Price:</strong> {{ $deal->original_price ?? '' }}<br>
        <strong>Discount:</strong> {{ $deal->discount ?? '' }}%
        @php
            $discountedPrice = null;
            if (!empty($deal->discount) && !empty($deal->original_price)) {
                $discountedPrice = $deal->original_price - ($deal->original_price * ($deal->discount / 100));
            }
        @endphp
        @if($discountedPrice)
            <br>
            <strong>Discounted Price:</strong> {{ number_format($discountedPrice, 2) }}
        @endif
    </p>
    <h3>Validity</h3>
    <p>
        <strong>Valid From:</strong>
        {{ $deal->valid_from ? \Carbon\Carbon::parse($deal->valid_from)->format('d M Y') : 'N/A' }}<br>

        <strong>Valid Until:</strong>
        {{ $deal->valid_until ? \Carbon\Carbon::parse($deal->valid_until)->format('d M Y') : 'N/A' }}
    </p>
    @if(!empty($deal->included) && is_array($deal->included))
        <h3>What’s Included</h3>
        <ul>
            @foreach($deal->included as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif
    @if(!empty($deal->terms_conditions))
        <h3>Terms & Conditions</h3>
        <p>{{ $deal->terms_conditions }}</p>
    @endif
 
    <p>
        <a href="{{ url('/deals/'.$deal->id) }}" style="font-weight:bold;">
            View Full Deal Details
        </a>
    </p>

</body>
</html>
