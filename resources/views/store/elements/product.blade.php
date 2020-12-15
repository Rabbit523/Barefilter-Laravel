<a href="{{route('product', ['handle' => $product->handle])}}">
    <div class="product-teaser" id="product-{{$product->id}}">
        <div class="product-image">
            <img src='{{$product->images->get(0)["url"]}}' alt="{{$product->name}}" />
            <div class="overlay">
                <!-- <button type="button" class="barefilter-btn product-preview-button" data-handle="{{$product->handle}}">Rask Visning</button> -->
            </div>
        </div>
        <div class="product-description">
            <h5>{{$product->name}}</h5>
            <h6>Varenr: {{$product->sku}}</h6>
            <h6>Rammemål: {{$product->width}}x{{$product->height}}x{{$product->length}}</h6>
            <p>kr {{$product->price}},-</p>
            <div class="shopping-cart-bag quick-add-to-cart-button" data-id="{{$product->id}}" data-name="{{$product->name}}" data-category="{{$product->sku}}" data-image="{{$product->images->get(0)['url']}}" data-price="{{$product->price}}"></div>
            <div class="remove-from-shopping-cart-bag"></div>
            <div class="barefilter-loader"></div>
        </div>
    </div>
</a>
