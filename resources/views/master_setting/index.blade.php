@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <a class="text-muted fw-light">Master Setting</a> 
    </h4>
    <div class="row">
        <div class="col-12">
            <div class="card">
            <h5 class="card-header">Master Setting Details</h5>
            <div class="card-body">
                <form class="row g-3" enctype="multipart/form-data" method="post" action="{{ route('master_setting.store') }}" onsubmit="showLoading()">
                @csrf
                <div class="col-md-7">
                    <label class="form-label" for="whatsapp">Whatsapp</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="https://wa.me/+60145569999"
                    name="whatsapp"
                    value="{{ $whatsapp ? $whatsapp->value : '' }}" 
                    />
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="telegram">Telegram</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="https://t.me/+601153429996"
                    name="telegram"
                    value="{{ $telegram ? $telegram->value : '' }}" 
                    />
                </div>
                <div class="col-md-7">
                    <label class="form-label" for="phone">Phone</label>
                    <input
                    type="text"
                    class="form-control"
                    placeholder="0123456789"
                    name="phone"
                    value="{{ $phone ? $phone->value : '' }}" 
                    />
                </div>
                <hr>
                <div class="col-12">
                    <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->
@endsection

@section('scripts')
@endsection
