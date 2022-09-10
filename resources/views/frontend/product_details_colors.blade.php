
<div class="product-gal-img"> 
    <img src="{{ asset('frontend/images/placeholder.jpg') }}" class="xzoom img-fluid lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($listing_image[0] ? $listing_image[0]->image : '') }}" xoriginal="{{ asset($listing_image[0] ? $listing_image[0]->image : '') }}" /> 
</div>
<div class="product-gal-thumb">
    <div class="xzoom-thumbs">
        @foreach ($listing_image as $key => $raw)
            <a href="{{ asset($raw->image) }}">
                <img src="{{ asset('frontend/images/placeholder.jpg') }}" class="xzoom-gallery lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" width="80" data-src="{{ asset($raw->image) }}"  @if($key == 0) xpreview="{{ asset($raw->image) }}" @endif>
            </a>
        @endforeach
        
    </div> 
</div>