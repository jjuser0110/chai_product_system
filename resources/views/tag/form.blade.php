@extends('layouts.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="py-3 breadcrumb-wrapper mb-4">

        <a class="text-muted fw-light"
           href="{{ route('tag.index') }}">
            Tag /
        </a>

        @if(isset($tag))
            Edit
        @else
            Create
        @endif

    </h4>

    <div class="row">

        <div class="col-12">

            <div class="card">

                <h5 class="card-header">
                    Tag Details
                </h5>

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ isset($tag)
                            ? route('tag.update', $tag)
                            : route('tag.store') }}"
                        class="row g-3"
                        onsubmit="showLoading()"
                    >

                        @csrf

                        <div class="col-md-7">

                            <label class="form-label">
                                Tag Name
                            </label>

                            <input
                                type="text"
                                name="tag_name"
                                class="form-control"
                                placeholder="New"
                                value="{{ old('tag_name', $tag->tag_name ?? '') }}"
                                required
                            >

                            @error('tag_name')
                                <div class="text-danger mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="col-md-7">

                            <label class="form-label">
                                Arrangement
                            </label>

                            <input
                                type="number"
                                name="arrangement"
                                class="form-control"
                                placeholder="1"
                                value="{{ old('arrangement', $tag->arrangement ?? 0) }}"
                                required
                            >

                        </div>


                        <div class="col-md-7">

                            <label class="form-label">
                                Is Active?
                            </label>

                            <select
                                name="is_active"
                                class="form-control"
                            >

                                <option value="1"
                                    {{ old('is_active', $tag->is_active ?? 1) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0"
                                    {{ old('is_active', $tag->is_active ?? 1) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>


                        <hr>


                        <div class="col-12">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Submit
                            </button>

                            <a
                                href="{{ route('tag.index') }}"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection