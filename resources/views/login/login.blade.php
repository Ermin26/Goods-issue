<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <title>Login</title>
</head>

<body>
    @include('navbar') %>
        <!--
    <nav id="navbar" class="navbar navbar-dark navbar-expand-md bg-dark text-light mb-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">New Bill</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-evenly" id="navbarNav">
                <ul class="navbar-nav d-flex w-100">
                    <li class="nav-item ms-2 mt-1">
                        <a href="/all"><button class="btn btn-primary" id="btn-func">All bills</button></a>
                    </li>

                    <li class="nav-item ms-2 mt-1">
                        <a href="/search"><button class="btn btn-info" id="btn-func"><strong>Search
                                    User</strong></button></a>
                    </li>
                    <li class="nav-item ms-2 mt-1">
                        <a href="/costs"><button class="btn btn-danger"
                                id="btn-func"><strong>Costs</strong></button></a>
                    </li>
                    <li class="nav-item ms-2 mt-1">
                        <a href="/add"><button class="btn btn-danger" id="btn-func"><strong>Add
                                    employee</strong></button></a>
                    </li>
                    <% if(currentUser) {%>
                        <li class="nav-item ms-auto">
                            <a class="nav-link" href="/logout"><button class="btn btn-outline-info ms-1"> Log
                                    out</button></a>
                        </li>
                        <% }else {%>
                            <li class="nav-item ms-auto">
                                <a class="nav-link" href="/login"><button class="btn btn-outline-info ms-1"> Log
                                        In</button></a>
                            </li>
                            <% } %>
                </ul>
            </div>
        </div>
    </nav>
-->
        <div class="d-flex justify-content-center mt-5">
            <!--
            <%# include('../flashError') %>
            -->
        </div>
        <div id="container" class="container mt-1 d-flex justify-content-center">
            <div class="row mt-2">
                <div class="col">
                    <form action="/login" method="post">
                        <div class="card border-1">
                            <div class="card-header" id="image">
                            </div>
                            <div id="fieldsGroup" class="card-body text-center">
                                <div id="field" class="d-inline-flex mb-1 p-2 text-center">
                                    <label id="label" for="username"
                                        class="d-inline-flex"><strong>Username:</strong></label>
                                    <input type="text" class="col d-inline-flex text-center" name="username" id="name"
                                        placeholder="User name">
                                </div>
                                <br>
                                <div id="field" class="d-inline-flex mb-2 p-2 text-center">
                                    <label id="label" for="password" class="d-inline-flex"><strong>Password:
                                        </strong></label>
                                    <input type="password" class="col d-inline-flex text-center" name="password"
                                        id="password" placeholder="Your password">
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button class="btn btn-primary d-inline p-2">Submit</button>
                            </div>
                    </form>

                    <div class="col d-inline-flex caption ms-auto me-auto text-center">
                        <p>If you can't login please <a href="mailto:mb.providio@gmail.com">Contact
                                Admin.</a></p>
                    </div>

                </div>

            </div>
        </div>


        </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
            integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
            integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
            crossorigin="anonymous"></script>
</body>

</html>