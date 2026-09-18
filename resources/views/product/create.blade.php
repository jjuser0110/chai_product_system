@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <a class="text-muted fw-light" href="{{route('productt.index')}}">Products /</a> 
         @if (isset($product)) Edit @else Create @endif
    </h4>
    <div class="row">
        <div class="col-12">
            <div class="card">
            <h5 class="card-header">Products Details</h5>
            <div class="card-body">
                <form class="row g-3" enctype="multipart/form-data" @if (isset($product)) method="post" action="{{ route('productt.update',$product) }}" @else method="post" action="{{ route('productt.store') }}" @endif onsubmit="showLoading()">
                @csrf
                <div class="col-md-7">
                    <label class="form-label" for="product_name">Products Name</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="Mary"
                    name="product_name"
                    value="{{$product->product_name??''}}" 
                    required/>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="tag">Category</label>
                    <select name="category_id" class="form-control">
                        @foreach($category as $row)
                        <option value="{{$row->id}}" <?php echo isset($product)&&$product->category_id == $row->id?'selected':'' ?>>{{$row->category_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" class="form-control" placeholder="Description" rows="5">{{$product->description??''}}</textarea>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="arrangement">Arrangement</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="1"
                    name="arrangement" 
                    value="{{$product->arrangement??''}}"
                    required/>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="tag">Tag?</label>
                    <select name="tag" class="form-control">
                        <option value="0" <?php echo isset($product)&&$product->tag == 0?'selected':'' ?>>Nothing</option>
                        <option value="1" <?php echo isset($product)&&$product->tag == 1?'selected':'' ?>>New</option>
                        <option value="2" <?php echo isset($product)&&$product->tag == 2?'selected':'' ?>>Popular</option>
                        <option value="3" <?php echo isset($product)&&$product->tag == 3?'selected':'' ?>>Best</option>
                        <option value="4" <?php echo isset($product)&&$product->tag == 4?'selected':'' ?>>Limited</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="is_highlight">Is Highlight?</label>
                    <select name="is_highlight" class="form-control">
                        <option value="1" <?php echo isset($product)&&$product->is_highlight == 1?'selected':'' ?>>Highlight</option>
                        <option value="0" <?php echo isset($product)&&$product->is_highlight == 0?'selected':'' ?>>Not Highlight</option>
                    </select>
                </div>
                
                @if(isset($product))
                <div class="col-md-7">
                    <label class="form-label" for="is_active">Is Active?</label>
                    <select name="is_active" class="form-control">
                        <option value="1" <?php echo isset($product)&&$product->is_active == 1?'selected':'' ?>>Active</option>
                        <option value="0" <?php echo isset($product)&&$product->is_active == 0?'selected':'' ?>>Inactive</option>
                    </select>
                </div>
                @endif
                
                <div class="col-md-7">
                    <label class="form-label" for="file_attachment">File Attachment</label>
                    <input type="file"
                        class="form-control"
                        name="file_attachment[]"
                        accept="image/*"
                        multiple>
                </div>
                <hr>
                <div class="col-12">
                    <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
            <div class="row" style="padding:0 20px 20px 20px;">
                <div class="d-flex flex-wrap gap-3">
                    @foreach($product->file_attachments ?? [] as $file)
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
