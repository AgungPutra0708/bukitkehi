@extends('layouts.app')

@section('active_gallery', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Gallery</h3>
                <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">Create</a>
                <div class="table-responsive">
                    <table class="table table-borderless" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($galleries as $gallery)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($gallery->type == 'image')
                                            <img src="{{ asset('storage/gallery/' . $gallery->file) }}" alt=""
                                                style="object-fit: cover; max-width: 150px; height: 100px;">
                                        @else
                                            @if (filter_var($gallery->file, FILTER_VALIDATE_URL))
                                                @if (strpos($gallery->file, 'youtube.com') !== false || strpos($gallery->file, 'youtu.be') !== false)
                                                    <!-- Video dari YouTube -->
                                                    <div class="video-container">
                                                        <iframe
                                                            src="{{ str_replace('watch?v=', 'embed/', $gallery->file) }}"
                                                            class="rounded" frameborder="0"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen>
                                                        </iframe>
                                                    </div>
                                                @else
                                                    <!-- Video dari URL file video langsung -->
                                                    <video src="{{ $gallery->file }}"
                                                        style="object-fit: cover; max-width: 150px; height: 100px;"
                                                        class="w-100 rounded" controls>
                                                    </video>
                                                @endif
                                            @else
                                                <video src="{{ asset('storage/gallery/' . $gallery->file) }}" alt=""
                                                    style="object-fit: cover; max-width: 150px; height: 100px;"
                                                    controls></video>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $gallery->type }}</td>
                                    <td>
                                        <a href="{{ route('admin.gallery.destroy', $gallery->id) }}"
                                            class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
