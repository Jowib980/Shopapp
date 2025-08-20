<h2>Assign Offer to Product ID: {{ $productId }}</h2>
<form action="{{ url('admin/products/'.$productId.'/offers') }}" method="POST">
    @csrf
    <select name="offer_id">
        @foreach($offers as $offer)
            <option value="{{ $offer->id }}" {{ isset($productOffer) && $productOffer->offer_id==$offer->id ? 'selected' : '' }}>
                {{ $offer->name }}
            </option>
        @endforeach
    </select>
    <button type="submit">Assign Offer</button>
</form>
