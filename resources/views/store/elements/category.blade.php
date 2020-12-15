<a href="{{route('store', ['type' => $typeHandle, 'category' => $category->handle])}}">
<div class="product-teaser">
<div class="product-image">
@if(count($category->products) > 0 && count($category->products[0]->images) > 0)
<img src='{{$category->cat_img}}' alt="{{$category->name}}" />
@else
<img src='{{$category->cat_img}}' alt="{{$category->name}}" />
@endif
<div class="overlay">
<button type="button" class="barefilter-btn">Vis produkter</button>
</div>
</div>
<div class="product-description">
<h5>{{$category->name}}</h5>
<h6>Produkter totalt: {{count($category->products)}}</h6>
@if ($category->count > 0)
<h6>Underkategorier totalt: {{$category->count}}</h6>
@endif
</div>
</div>

</a>