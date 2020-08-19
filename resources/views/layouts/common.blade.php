<div class="form-group">
    <h5>部门	</h5>
    <select  class="form-control" name="group" id="gid" style="width:140px;">
        @if($groups->count() > 1)
            <option value="0" >全部</option>
        @endif
        @foreach($groups as $item)
            <option value="{{$item->id}}" @if(request()->input('group') == $item->id) selected @endif>{{$item->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <h5>人员	</h5>
    <select  class="form-control" name="pdr" id="pdr" style="width:140px;">
        <option value="0" >全部</option>
    </select>
</div>

@push('scripts')
    @include('layouts.auth.js')
@endpush
