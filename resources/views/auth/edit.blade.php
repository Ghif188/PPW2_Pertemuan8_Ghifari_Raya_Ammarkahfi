@extends('auth.layout')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Edit Profile</div>
            <div class="card-body">
                <form action="{{ route('updateFoto') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 row">
                        <label for="photo" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                        <div class="col-md-6">
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])"">
                            @if ($errors->has('photo'))
                            <span class="tet-danger">{{ $errors->first('photo')}}</span>
                            @endif
                        </div>
                    </div>
                    <img id="preview-image" src={{'storage/'.$data}} alt="your image" class="mt-3 w-25 h-25 "/>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Edit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection