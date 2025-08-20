<h2>Offers</h2>
<a href="{{ route('offers.create') }}">Create New Offer</a>
<table>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Parameters</th>
    </tr>
    @foreach($offers as $offer)
    <tr>
        <td>{{ $offer->name }}</td>
        <td>{{ $offer->type }}</td>
        <td>{{ json_encode($offer->parameters) }}</td>
    </tr>
    @endforeach
</table>
