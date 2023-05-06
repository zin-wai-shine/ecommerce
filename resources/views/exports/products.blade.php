<table>
    <thead>
    <tr>
        <th style="text-align:center;">Id</th>
        <th style="text-align:center;">Title</th>
        <th style="text-align:center;">Description</th>
        <th style="text-align:center;">Price</th>
        <th style="text-align:center;">Avg Rating</th>
        <th style="text-align:center;">Discount</th>
        <th style="text-align:center;">Product Status</th>
        <th style="text-align:center;">Category</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td style="text-align:center;">{{ $product->id }}</td>
            <td style="text-align:center;">{{ $product->title }}</td>
            <td style="text-align:center;">{{ $product->description }}</td>
            <td style="text-align:center;">{{ $product->price }}</td>
            <td style="text-align:center;">{{ $product->avg_rating }}</td>
            <td style="text-align:center;">{{ $product->discount }}</td>
            <td style="text-align:center;">{{ $product->product_status }}</td>
            <td style="text-align:center;">{{ $product->category->title }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
