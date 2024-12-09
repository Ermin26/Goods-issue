<nav id="employeeNav">
    <ul>
        <li><a href="/employee">{{Auth::guard('employee')->user()->name}}</a></li>
        @if(Auth::guard('employee')->user()->working_status != "študent")
        <li><a href="/employee/vacation">Dopust</a></li>
        @endif
        <li><a href="/employee/profile">Profil</a></li>
        <li><form action="{{route('logout')}}" method="POST">
            @csrf
            <button type="submit" value="submit">Odjava</button>
            </form>
        </li>
    </ul>
</nav>