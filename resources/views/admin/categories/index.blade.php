@extends('layouts.dashboard')

@section('content')
    <section>

        <h1>Lista delle categorie</h1>

        <ul>
            {{-- this foreach prints as many links as they are in the categories table in the database --}}
            @foreach ($categories as $category)
                <li>
                    <a href="{{ route('admin.category_info', ['slug' => $category->slug]) }}">{{ $category->name }}</a>
                </li>
            @endforeach
        </ul>

    </section>
@endsection