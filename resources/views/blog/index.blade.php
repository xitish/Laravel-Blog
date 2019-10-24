@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <p class="quote">The beautiful Laravel</p>
        </div>
    </div>
    @foreach($posts as $post)
    <div class="row posts">
        <div class="col-md-12 text-center">
            <h1 class="post-title">{{ $post->title }}</h1>
            <code style="font-weight: bold">
                @foreach($post->tags as $tag)
                    &nbsp {{ $tag->name }} &nbsp
                @endforeach
            </code>
            <p>
                {{ str_limit($post->content, 100) }}
                <a href="{{ $post->path() }}">Continue</a>
            </p>
        </div>
    </div>
    <hr>
    @endforeach
        <div class="row posts justify-content-md-center">
            <div class="col-md-12">
                {{ $posts->links() }}
            </div>
        </div>
@endsection