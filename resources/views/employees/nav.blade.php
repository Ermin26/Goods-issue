<nav id="employeeNav">
    <ul>
        <li>{{Auth::guard('employee')->user()->name}}</li>
        <li>Dopust</li>
        <li>Profil</li>
        <li>Odjava</li>
    </ul>
</nav>