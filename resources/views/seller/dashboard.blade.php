<!DOCTYPE html>
<html>
<head>
    <title>Seller Dashboard</title>
</head>
<body>
    <h1>Seller Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}</p>
    <p>Shop: {{ auth()->user()->shop_name }}</p>
    <p>Role: {{ auth()->user()->role }}</p>
    
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>