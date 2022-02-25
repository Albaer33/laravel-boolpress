@extends('layouts.dashboard')

@section('content')
    <section>
        <h1>{{ $post->title }}</h1>

        <div class="mb-2"><strong>Slug:</strong> {{ $post->slug }}</div>

        {{-- show the category of a post[id], if that post has no category it set 'nessuna' as default --}}
        <div class="mb-2"><strong>Categoria:</strong> {{ $post->category ? $post->category->name : 'nessuna' }}</div>

        <div class="mb-2"><strong>Tags:</strong>
            {{-- se ci sono mostra i tag appartenenti altrimenti nessuno --}}
            @forelse ($post->tags as $tag)
                {{-- stampa il tag name con una virgola, se è l'ultimo elemento non la stampa --}}
                {{ $tag->name }}{{ $loop->last ? '' : ', ' }}
            @empty
                nessuno
            @endforelse
        </div>

        <p>{{ $post->content }}</p>

        <div>
            <a href="{{ route('admin.posts.edit', ['post' => $post->id]) }}">Modifica post</a>
        </div>

        <div>
            <form action="{{ route('admin.posts.destroy', ['post' => $post->id]) }}" method="post">
                @csrf
                @method('DELETE')

                <button class="btn btn-danger" onclick="return confirm('Sei sicuro di voler cancellare?')">Cancella</button>
            </form>
        </div>
    </section>
@endsection