@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <a class="text-muted fw-light" href="{{route('banner.index')}}">Banner /</a> 
         @if (isset($banner)) Edit @else Create @endif
    </h4>
    <div class="row">
        <div class="col-12">
            <div class="card">
            <h5 class="card-header">Banner Details</h5>
            <div class="card-body">
                <form class="row g-3" enctype="multipart/form-data" @if (isset($banner)) method="post" action="{{ route('banner.update',$banner) }}" @else method="post" action="{{ route('banner.store') }}" @endif onsubmit="showLoading()">
                @csrf
                <div class="col-md-7">
                    <label class="form-label" for="title">Title</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="Best in World"
                    name="title"
                    value="{{$banner->title??''}}" 
                    required/>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" class="form-control" placeholder="Description" rows="5">{{$banner->description??''}}</textarea>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="arrangement">Arrangement</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="1"
                    name="arrangement" 
                    value="{{$banner->arrangement??''}}"
                    required/>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="box_wording">Box Wording</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="Best in World"
                    name="box_wording"
                    value="{{$banner->box_wording??''}}" 
                    required/>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="button_text">Button Text</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="Best in World"
                    name="button_text"
                    value="{{$banner->button_text??''}}" 
                    required/>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="button_link">Button Link</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="https://example.com"
                    name="button_link"
                    value="{{$banner->button_link??''}}" 
                    required/>
                </div>
                @if(isset($banner))
                <div class="col-md-7">
                    <label class="form-label" for="is_active">Is Active?</label>
                    <select name="is_active" class="form-control">
                        <option value="1" <?php echo isset($banner)&&$banner->is_active == 1?'selected':'' ?>>Active</option>
                        <option value="0" <?php echo isset($banner)&&$banner->is_active == 0?'selected':'' ?>>Inactive</option>
                    </select>
                </div>
                @endif
                
                <div class="col-md-7">
                    <label class="form-label" for="file_attachment">File Attachment</label>
                    <input type="file"
                        class="form-control"
                        name="file_attachment"
                        accept="image/*">
                </div>
                <hr>
                <div class="col-12">
                    <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
            <div class="row" style="padding:0 20px 20px 20px;">
                <div class="d-flex flex-wrap gap-3">
                    @foreach($banner->file_attachments ?? [] as $file)
                        <div class="position-relative">
                            <img src="{{ asset('storage/'.$file->file_path) }}"
                                alt="{{ $file->file_name }}"
                                class="img-thumbnail"
                                style="width:120px;height:120px;object-fit:cover;">

                            <a href="javascript:void(0)"
                            onclick="if(confirm('Are you sure you want to delete?')){window.location.href='{{ route('removeimage',$file->id) }}'}"
                            class="position-absolute top-0 end-0"
                            style="color:red;background:white;padding:2px 5px;border-radius:50%;">
                                <i class="bx bx-trash"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->
@endsection

@section('scripts')
@endsection
