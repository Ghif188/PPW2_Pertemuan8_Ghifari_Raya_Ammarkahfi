@extends('auth.layout')
@section('content')
<div class=" d-flex justify-content-center">
    <div class="w-50">
        <a class=" btn btn-info w-100 mt-3 mb-3" href="{{route('gallery.create')}}">+ Tambah Data</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Images</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $post)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$post->title}}</td>
                    <td>{{$post->description}}</td>
                    <td><img src="{{asset('storage/posts_image/'.$post->picture)}}" width="100px" alt=""></td>
                    <td>
                        <div class="d-flex">
                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('gallery.destroy', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                        <a class="btn btn-secondary btn-sm ms-2 " href="{{route('gallery.edit', $post->id)}}">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection