<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome Page</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>

<style>
    .main-content {
        text-align: center;
        position: relative;
        padding: 30px;
    }

    .router-center {
        background-color: black;
        width: 500px;
        position: absolute;
        top: 0px;
        left: 40%;
    }

    .router-link:hover {
        text-decoration: none;
        margin: 5px;
        color: orangered;
        font-size: 26px;
    }

    .router-link {
        color: #fff;
        text-decoration: none;
        margin-right: 10px;

    }
</style>

<body>
    <div id="app">
        <div class="main-content">
            <div class="router-center">
                <div>
                    <router-link class="router-link" :to="{ path: '/PrivateChat' }">PrivateChat</router-link>
                </div>

                <div>
                    <router-link class="router-link" :to="{ path: '/groupChat' }">My Groups</router-link>
                </div>
            </div>
        </div>


        <router-view></router-view>
    </div>
</body>
<script>
    window.authUser = @json(auth()->user());
</script>
<script src="{{ asset('/js/app.js') }}"></script>

</html>
