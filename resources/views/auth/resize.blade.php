@extends('auth.layout')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Resize Foto</div>
            <div class="card-body">
                <form action="{{ route('updateSizeFoto') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class=" d-flex">
                        <img id="preview-image" src={{'storage/'.$data}} alt="your image" class="h-25 w-25"/>
                        <div class=" fw-bold text-xl-center ">Ubah Menjadi -></div>
                        <img id="preview-image" src={{'storage/'.$data}} alt="your image" style="width: 100px !important; height: 100px !important;"/>
                    </div>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Resize">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection