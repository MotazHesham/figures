<div class="modal-body p-4">
    <div class="row">
        <div class="col-md-9"> 
            @foreach($listing->listing_images as $raw)
                <img src="{{asset($raw->image)}}" width="200" height="200"  alt="">
            @endforeach
        </div>
        <div class="col-md-3">
            @if($listing->dataset1 != 'null')
                @foreach(json_decode($listing->dataset1) as $dataset)
                    @if($dataset->type == 'image') 
                        <img src="{{asset($dataset->src)}}" width="200" height="200"  alt="" style="margin-bottom:10px">
                    @endif
                @endforeach
            @endif

            @if($listing->dataset2 != 'null')
                @foreach(json_decode($listing->dataset2) as $dataset)
                    @if($dataset->type == 'image') 
                        <img src="{{asset($dataset->src)}}" width="200" height="200"  alt="" style="margin-bottom:10px">
                    @endif
                @endforeach
            @endif

            @if($listing->dataset3 != 'null')
                @foreach(json_decode($listing->dataset3) as $dataset) 
                    @if($dataset->type == 'image') 
                        <img src="{{asset($dataset->src)}}" width="200" height="200"  alt="" style="margin-bottom:10px">
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div> 
