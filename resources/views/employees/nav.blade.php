<nav id="employeeNav">
    <ul>
        <li>{{Auth::guard('employee')->user()->name}}</li>
        <li><a href="/employee/vacation">Dopust</a></li>
        <li><a href="/employee/profile">Profil</a></li>
        <li><a href="/employee/logout">Odjava</a></li>
    </ul>
</nav>