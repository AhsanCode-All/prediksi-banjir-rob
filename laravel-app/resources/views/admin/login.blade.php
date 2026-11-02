<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
</head>
<body>

    <h1>Login Administrator</h1>
    <p>Silakan masuk untuk mengelola data prediksi banjir rob.</p>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.login.process') }}" method="POST">
        @csrf

        <div>
            <label for="email">Email</label><br>
            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="password">Password</label><br>
            <input
                type="password"
                name="password"
                id="password"
                required
            >
        </div>

        <br>

        <button type="submit">Masuk</button>
    </form>

</body>
</html>