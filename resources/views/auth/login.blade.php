<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{route('login')}}" method="post">
    @csrf
        <input type="text" name="name">
        <input type="text" name="password">
        <button type="submit">Đăng nhập</button>
    </form>
</body>
</html>