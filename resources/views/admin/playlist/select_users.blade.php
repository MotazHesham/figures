@php
    $designer_end_select = 0;
    $manifacturer_end_select = 0;
    $preparer_end_select = 0;
@endphp
<div class="row"> 
    <div class="col-md-8"> 
        <span>&nbsp;</span>
        <div class="" style="min-width: 160px;margin-bottom: 10px">
            <select class="form-control demo-select2" name="designer_id" id="designer_id" required>
                <option value="">أختر الديزاينر</option> 
                @foreach($staffs as $staff)
                    <option value="{{$staff->id}}"
                            @if($raw->designer_id == $staff->id) 
                                selected @php $designer_end_select = 1; @endphp
                            @elseif($generalsetting->designer_id == $staff->id && $designer_end_select != 1)
                                selected
                            @endif>
                            {{$staff->email}}
                    </option>
                @endforeach 
            </select>
        </div>  
    </div>
    <div class="col-md-8"> 
        <span>&nbsp;</span>
        <div class="" style="min-width: 160px;margin-bottom: 10px">
            <select class="form-control demo-select2" name="manifacturer_id" id="manifacturer_id" required>
                <option value="">اختر المصنع</option> 
                @foreach($staffs as $staff)
                <option value="{{$staff->id}}"
                        @if($raw->manifacturer_id == $staff->id) 
                            selected @php $manifacturer_end_select = 1; @endphp
                        @elseif($generalsetting->manifacturer_id == $staff->id && $manifacturer_end_select != 1)
                            selected
                        @endif>
                            {{$staff->email}}
                    </option>
                @endforeach 
            </select>
        </div>  
    </div>
    <div class="col-md-8"> 
        <span>&nbsp;</span>
        <div class="" style="min-width: 160px;margin-bottom: 10px">
            <select class="form-control demo-select2" name="preparer_id" id="preparer_id" required>
                <option value="">اختر المجهز</option> 
                @foreach($staffs as $staff)
                <option value="{{$staff->id}}"
                        @if($raw->preparer_id == $staff->id) 
                            selected @php $preparer_end_select = 1; @endphp
                        @elseif($generalsetting->preparer_id == $staff->id && $preparer_end_select != 1)
                            selected
                        @endif>
                            {{$staff->email}}
                    </option>
                @endforeach 
            </select>
        </div>  
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>