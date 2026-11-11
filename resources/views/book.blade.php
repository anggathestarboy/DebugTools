
@foreach ($books['data'] as $book)

    <h1>{{ $loop->iteration }} {{ $book['name'] }}</h1>
    <p>{{ $book['desc'] }}</p>
@endforeach

{{ dump("Hello World") }}



    <form action="/" method="POST">
        @csrf

        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name">
            @error('name')
                <p>{{ $message }}</p>
               
            @enderror
        </div>

        <div>
            <label for="desc">Description</label>
            <textarea name="desc" id="desc"></textarea>
            @error('desc')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="publised_at">Published At</label>
            <input type="date" name="publised_at" id="publised_at">
            @error('publised_at')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Create Book</button>
    </form>