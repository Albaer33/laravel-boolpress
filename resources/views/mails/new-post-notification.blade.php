<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>E' stato creato un nuovo posto nel blog</h2>

    <div>Titolo: {{ $new_post->title }}</div>

    <div>Per vedere il nuovo post <a href="{{ route('admin.posts.show', ['post' => $new_post->id]) }}">clicca qui</a></div>
</body>
</html>