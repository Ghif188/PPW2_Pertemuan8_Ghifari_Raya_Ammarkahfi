@extends('auth.layout')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">Edit Data</div>
            <div class="card-body">
                <form action="{{ route('gallery.update', $data->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 row">
                        <label for="title" class="col-md-4 col-form-label text-md-end text-start">Title</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ $data->title }}">
                            @if ($errors->has('title'))
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">Description</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{$data->description}}</textarea>
                            @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="picture" class="col-md-4 col-form-label text-md-end text-start">Picture</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control @error('picture') is-invalid @enderror" name="picture" onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])"">
                                @if ($errors->has('picture'))
                                <span class="tet-danger">{{ $errors->first('picture')}}</span>
                                @endif
                                <img id="preview-image" src="{{asset('storage/posts_image/'.$data->picture)}}" alt="your image" class="mt-3 w-25"/>
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Simpan">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection