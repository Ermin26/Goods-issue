<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/vacation.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <title>Vacation</title>
</head>

<body>
    @include('navbar') %>
        @include('flash') %>
            <div id="section" class="row row-cols-3 m-0 p-0 mt-2">
                <div id="left" class="col-3 text-center ms-auto me-auto shadow">
                    <div id="usersQtyHolidays" class="text-center ms-auto me-auto">
                        <div class="caption mt-2">
                            <h2 class="mb-3">Info about users holidays</h2>
                        </div>
                        <table id="infoHolidays" class="table table-active table-hover text-center w-100">
                            <thead>
                                <th>User</th>
                                <th>Last year</th>
                                <th>This year</th>
                                <th>Used</th>
                                <th>Remaining</th>
                                <th>Hours</th>
                            </thead>
                            <tbody>
                                <% for (status of employees) {%>
                                    <% let users = status.username + ' ' + status.lastname%>
                                    <% for(user of vacation) {%>
                                    <% if(status.status === 'active' && status.employmentStatus === 'zaposlen/a' && users === user.user  ) {%>
                                    <tr>
                                        <td>
                                            <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                                <%= status.username %>
                                                <%= status.lastname %>
                                            <% }else{ %>
                                                <p>Not available for visitors</p>
                                                <% } %>

                                        </td>
                                        <td>
                                            <%= user.lastYearHolidays %>
                                        </td>
                                        <td>
                                            <%= user.holidays %>
                                        </td>
                                        <td>
                                            <%= user.usedHolidays %>
                                        </td>
                                        <td id="preostalo">
                                        </td>
                                        <td>
                                            <%= user.overtime %>
                                        </td>
                                    </tr>
                                        <% } %>
                                    <% } %>
                                    <% } %>
                            </tbody>
                        </table>
                    </div>
                    <!-- USED HOLIDAYS, TABLES FOR EVERY USER-->
                    <div class="table-responsive">
                        <% for(let employe of employees) {%>
                            <% let userName = employe.username + ' ' + employe.lastname%>
                            <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                <caption><h2><%= userName %></h2></caption>
                            <% }else{%>
                                <caption><h2>Not available for visitors</h2></caption>
                            <% } %>
                            <table class="mb-5 table table-bordered holidayTable bg-success">
                            <thead>
                                <th scope="col" class="ps-0 pe-0 align-middle">Start date</th>
                                <th scope="col" class="ps-0 pe-0 align-middle">End date</th>
                                <th scope="col" class="ps-0 pe-0 align-middle">Status</th>
                                <th scope="col" class="ps-0 pe-0 align-middle">Days</th>
                            </thead>
                            <tbody>
                                <% for(let user of vacation) {%>
                                <% for(let usedVac of user.pendingHolidays) {%>
                                    <% if(usedVac.status == 'Approved' && user.user == userName) {%>
                                        <tr>
                                    <td scope="col" class="ps-0 pe-0 align-middle"><strong><%= usedVac.startDate %></strong></td>
                                    <td scope="col" class="ps-0 pe-0 align-middle"><strong><%= usedVac.endDate %></strong></td>
                                    <td scope="col" class="ps-0 pe-0 align-middle"><strong><%= usedVac.status %></strong></td>
                                    <td scope="col" class="ps-0 pe-0 align-middle"><strong><%= usedVac.days %></strong></td>
                                </tr>
                                <% } %>
                        <% } %>
                        <%} %>
                            </tbody>
                        </table>
                        
                        <% } %>
                    </div>
                </div>

                <div id="middle" class="col-6 shadow">
                    <div id="middleCol" class="text-center p-0 ms-auto me-auto w-100">
                        <!--REQUESTED HOLIDAYS-->
                        <div class="col mt-2 mb-3 shadow">
                            <caption>
                                <h2>Users holidays requests</h2>
                            </caption>
                            <div id="reqTable" class="row m-5 w-100 ms-auto me-auto">
                                <table id="tableReq" class="table table-info table-hover p-0 ms-auto me-auto">
                                    <thead id="reqTableHead">
                                        <th>Employee</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th>Applyed</th>
                                        <th>Approve</th>
                                        <th>Reject</th>
                                    </thead>
                                    <tbody>
                                        <% for(vac of vacation) {%>
                                            <% for(holiday of vac.pendingHolidays) {%>
                                                <% if(holiday.status=='Pending' ) {%>
                                                    <tr>
                                                        <td class="align-middle"><strong>
                                                            <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                                                <%= vac.user %>
                                                                <% }else{ %>
                                                                    <p>Not available for users</p>
                                                                    <% } %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.startDate %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.endDate %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.days %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle bg.warning"><strong>
                                                                <%= holiday.status %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.applyDate %>
                                                            </strong>
                                                        </td>
                                                            <td class="align-middle">
                                                                <form action="/vacation/approve/<%=vac.id %>"
                                                                    method="post">
                                                                    <input type="text" name="holidayId"
                                                                        value="<%= holiday.id %>" hidden>
                                                                    <input type="number" name="days"
                                                                        value="<%= holiday.days %>" hidden>
                                                                    <button class="btn btn-success">
                                                                        Approve
                                                                    </button>
                                                                </form>
                                                            </td>
                                                            <td class="align-middle">
                                                                <form action="/vacation/reject/<%=vac.id %>"
                                                                    method="post">
                                                                    <input type="text" name="holidayId"
                                                                        value="<%= holiday.id %>" hidden>
                                                                    <button class="btn btn-danger">REJECT</button>
                                                                </form>
                                                            </td>
                                                    </tr>
                                                    <% } %>

                                                        <% } %>
                                                            <% } %>
                                    </tbody>
                                </table>
                            </div>

                            <div id="hoursTable" class="row w-100 ms-auto me-auto">
                                <caption>
                                    <h2>User hours requests</h2>
                                </caption>
                                <table class="table table-info table-bordered p-0 ms-auto me-auto">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Date</th>
                                            <th>Hours</th>
                                            <th>Days</th>
                                            <th>Approve</th>
                                            <th>Reject</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                            <% for(hours of vacation) {%>
                                                <% for(hour of hours.hours) {%>
                                                    <% if(hour.status == 'Pending') {%>
                                                    <tr>
                                                        <td class="align-middle"><strong><%= hours.user %></strong></td>
                                                        <td class="align-middle"><strong><%= hour.date %></strong></td>
                                                        <td class="align-middle"><strong><%= hour.hours %></strong></td>
                                                        <td class="align-middle"><strong><%= hour.days %></strong></td>
                                                        <td class="align-middle"><form action="/hours/approve/<%=hours.id %>"
                                                            method="post">
                                                            <input type="text" name="hourId"
                                                                value="<%= hour.id %>" hidden>
                                                            <input type="number" name="hours"
                                                                value="<%= hour.hours %>" hidden>
                                                            <button class="btn btn-success">
                                                                Approve
                                                            </button>
                                                        </form></td>
                                                        <td class="align-middle"><form action="/hours/reject/<%=hours.id %>"
                                                            method="post">
                                                            <input type="text" name="hourId"
                                                                value="<%= hour.id %>" hidden>
                                                            <button class="btn btn-danger">REJECT</button>
                                                        </form></td>
                                                    </tr>
                                                <% } %>
                                                <% } %>
                                            <% } %>
                                        </tbody>

                                </table>

                            </div>
                        </div>

                        <!--APPROVED HOLIDAYS-->
                        <div class="col mt-5 mb-5 shadow">
                            <caption>
                                <h2>
                                    Approved holidays
                                </h2>
                            </caption>
                            <div id="approvedTable" class="row m-5 w-100 ms-auto me-auto">
                                <table id="tableApproved" class="table table-success table-hover mt-4 mb-5 p-0 ms-auto me-auto w-100">
                                    <thead id="approvedHead">
                                        <th>Employee</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th>Applyed</th>
                                        <th>Reject</th>
                                    </thead>
                                    <tbody>
                                        <% for(vac of vacation) {%>
                                            <% for(holiday of vac.pendingHolidays) {%>
                                                <% if(holiday.status=='Approved' ) {%>
                                                    <% let format=/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;%>
                                                    <% let currentDate=new Date() %>
                                                    <% let dateParts=[]; %>
                                                    <% let dateString=holiday.endDate.toString();%>
                                                    <% let hasDot=dateString.includes('.');%>
                                                    <% if(hasDot) {%>
                                                        <% dateParts=dateString.split('.');%>
                                                        <% secondDateFormat=new Date(dateParts[2],dateParts[1]-1,dateParts[0])%>
                                                        <% if(secondDateFormat > currentDate) {%>
                                                    <tr>
                                                        <td class="align-middle"><strong>
                                                            <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                                                <%= vac.user %>
                                                                <% }else{ %>
                                                                    <p>Not available for users</p>
                                                                    <% } %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.startDate %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.endDate %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.days %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.status %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.applyDate %>
                                                            </strong>              
                                                        <% if(currentUser.role=='admin' ) {%>
                                                            <td class="align-middle">
                                                                <form action="/vacation/rejectAfter/<%=vac.id %>"
                                                                    method="post">
                                                                    <input type="text" name="holidayId"
                                                                        value="<%= holiday.id %>" hidden>
                                                                    <button class="btn btn-danger">
                                                                        Reject 
                                                                    </button>
                                                                </form>
                                                            </td>
                                                            <% }else {%>
                                                                <td class="align-middle"><button
                                                                        class="btn btn-sm bg-danger text-light">FORBIDEN</button>
                                                                </td>
                                                                <% } %>
                                                    </tr>
                                                    <% } %>
                                                    <% } %>
                                                    <% } %>
                                                        <% } %>
                                                            <% } %>
                                    </tbody>
                                </table>
                            </div>

                            <div id="approvedHoursTable" class="row w-100 ms-auto me-auto">
                            <caption>
                                <h2>Approved hours requests</h2>
                            </caption>
                            <table class="table table-success table-bordered p-0 ms-auto me-auto">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Hours</th>
                                        <th>Days</th>
                                        <th>Reject</th>
                                    </tr>
                                    <tbody>
                                        <% for(hours of vacation) {%>
                                            <% for(hour of hours.hours) {%>
                                                <% if(hour.status == 'Approved') {%>
                                                <tr>
                                                    <td class="align-middle"><strong>
                                                        <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                                            <%= hours.user %>
                                                            <% }else{ %>
                                                                <p>Not available for users</p>
                                                                <% } %>
                                                        </strong></td>
                                                    <td class="align-middle"><strong><%= hour.date %></strong></td>
                                                    <td class="align-middle"><strong><%= hour.hours %></strong></td>
                                                    <td class="align-middle"><strong><%= hour.days %></strong></td>
                                                    <td class="align-middle"><form action="/hours/rejectAfterApprove/<%=hours.id %>"
                                                        method="post">
                                                        <input type="text" name="hourId"
                                                            value="<%= hour.id %>" hidden>
                                                        <button class="btn btn-danger">REJECT</button>
                                                    </form></td>
                                                </tr>
                                            <% } %>
                                            <% } %>
                                        <% } %>
                                    </tbody>
                                </thead>

                            </table>

                            </div>
                        </div>

                        <!--REJECTED HOLIDAYS-->
                        <div class="col mb-5 shadow">
                            <caption>
                                <h2>
                                    Rejected holidays
                                </h2>
                            </caption>
                            <div id="rejectedTable" class="row m-5 w-100 m-0 p-0 ms-auto me-auto">
                                <table id="tableRejected" class="table table-danger table-hover mt-4 mb-5 p-0 w-100 ms-auto me-auto">
                                    <thead id="rejectedHead">
                                        <th>Employee</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Days</th>
                                        <th>Status</th>
                                        <th>Applyed</th>
                                        <th>Approve</th>
                                    </thead>
                                    <tbody>
                                        <% for (status of employees) {%>
                                            <% let users = status.username + ' ' + status.lastname%>
                                        <% for(vac of vacation) {%>
                                            <% if(status.status === 'active' && status.employmentStatus === 'zaposlen/a' && users == vac.user  ) {%>
                                            <% for(holiday of vac.pendingHolidays) {%>
                                                <% if(holiday.status=='Rejected' ) {%>
                                                    <% let format=/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;%>
                                                    <% let currentDate=new Date() %>
                                                    <% let dateParts=[]; %>
                                                    <% let dateString=holiday.startDate.toString();%>
                                                    <% let hasDot=dateString.includes('.');%>
                                                    <% if(hasDot) {%>
                                                        <% dateParts=dateString.split('.');%>
                                                        <% secondDateFormat=new Date(dateParts[2],dateParts[1]-1,dateParts[0])%>
                                                        <% if(secondDateFormat > currentDate) {%>
                                                    <tr>
                                                        <td class="align-middle"><strong>
                                                            <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                                                <%= vac.user %>
                                                                <% }else{ %>
                                                                    <p>Not available for users</p>
                                                                    <% } %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.startDate %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.endDate %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.days %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.status %>
                                                            </strong>
                                                        </td>
                                                        <td class="align-middle"><strong>
                                                                <%= holiday.applyDate %>
                                                            </strong>
                                                        </td>
                                                        <% if(currentUser.role=='admin' ) {%>
                                                            <td class="align-middle">
                                                                <form action="/vacation/approve/<%=vac.id %>"
                                                                    method="post">
                                                                    <input type="text" name="holidayId"
                                                                        value="<%= holiday.id %>" hidden>
                                                                    <button class="btn btn-success">
                                                                        Approve
                                                                    </button>
                                                                </form>
                                                            </td>
                                                            <% }else {%>
                                                                <td class="align-middle"><button
                                                                        class="btn btn-danger btn-sm">FORBIDEN</button>
                                                                </td>
                                                                <% } %>
                                                    </tr>
                                                    <% } %>
                                                    <% } %>
                                                    <% } %>
                                                        <% } %>
                                                            <% } %>
                                                            <% } %>
                                                            <% } %>
                                    </tbody>
                                </table>
                            </div>
                            <div id="rejectedHoursTable" class="row w-100 ms-auto me-auto">
                            <caption>
                                <h2>Rejected hours requests</h2>
                            </caption>
                            <table class="table table-danger table-bordered p-0 ms-auto me-auto">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Hours</th>
                                        <th>Days</th>
                                        <th>Approve</th>
                                    </tr>
                                    <tbody>
                                        <% for(hours of vacation) {%>
                                            <% for(hour of hours.hours) {%>
                                                <% if(hour.status == 'Rejected') {%>
                                                <tr>
                                                    <td class="align-middle"><strong>
                                                        <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                                            <%= hours.user %>
                                                            <% }else{ %>
                                                                <p>Not available for users</p>
                                                                <% } %>
                                                        </strong></td>
                                                    <td class="align-middle"><strong><%= hour.date %></strong></td>
                                                    <td class="align-middle"><strong><%= hour.hours %></strong></td>
                                                    <td class="align-middle"><strong><%= hour.days %></strong></td>
                                                    <td class="align-middle"><form action="/hours/approve/<%=hours.id %>"
                                                        method="post">
                                                        <input type="text" name="hourId"
                                                            value="<%= hour.id %>" hidden>
                                                        <input type="number" name="hours"
                                                            value="<%= hour.hours %>" hidden>
                                                        <button class="btn btn-success">
                                                            Approve
                                                        </button>
                                                    </form></td>
                                                </tr>
                                                <% } %>
                                            <% } %>
                                        <% } %>
                                    </tbody>
                                </thead>

                            </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div id="right" class="col-3 shadow">
                    <div id="addUser" class="shadow mt-5 rounded-5 mb-5">
                        <caption>
                            <h1 class="text-center">
                                Edit users vacation
                            </h1>
                        </caption>
                        <div class="holidaysEdit mt-3 d-flex flex-column text-center">
                            <form action="/holidays" method="post">
                                <div class="mb-2 d-flex flex-column">
                                    <label for="user" class="">Employee name</label>
                                    <select name="user" id="user" class="w-50 ms-auto me-auto text-center" onchange="checkEmployee()">
                                        <option selected>Choose employee</option>
                                        <% for(employee of employees) {%>
                                            <option value="<% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%><%= employee.username %> <%= employee.lastname %><% }else {%> Not available for visitors <% }%>">
                                                <% if(currentUser.role != 'visitor' || currentUser.username == 'jan') {%>
                                                    <%= employee.username%>
                                                    <%= employee.lastname %>
                                                    <% }else {%> Not available for visitors <% }%>
                                            </option>

                                            <% } %>
                                    </select>
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="lastYearHolidays">Last year Holidays</label>
                                    <input type="number" name="lastYearHolidays"
                                        class="w-50 ms-auto me-auto text-center" id="lastYearHolidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="holidays">Holidays</label>
                                    <input type="number" name="holidays" class="w-50 ms-auto me-auto text-center"
                                        id="holidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="usedHolidays">Used holidays</label>
                                    <input type="number" name="usedHolidays" class="w-50 ms-auto me-auto text-center"
                                        id="usedHolidays" value="" min="0">
                                </div>
                                <div class="mb-2 d-flex flex-column">
                                    <label for="overtime">Overtime</label>
                                    <input class="w-50 ms-auto me-auto text-center" type="text" name="overtime"
                                        id="overtime" value="0">
                                </div>
                                    <div class="submit m-2 p-2">
                                        <button class="btn btn-success">Submit</button>
                                    </div>

                            </form>
                        </div>
                    </div>
                    <div id="div" class="mt-5 mb-5 text-center">
                        <h3>Update employee holiday data.</h3>
                        <button class="btn btn-primary" onclick="editEmployeeHolidayData()">Submit</button>
                    </div>
                </div>
            </div>



            <script>
                document.getElementById('addUser').style.display = 'none';
                const lastYearHolidays = document.getElementById('lastYearHolidays');
                const holidays = document.getElementById('holidays');
                const usedHolidays = document.getElementById('usedHolidays');
                const hours = document.getElementById('overtime');
                const holidayData = JSON.parse('<%- vacationInfo %>')
                function editEmployeeHolidayData() {
                    document.getElementById('addUser').style.display = 'block';
                    document.getElementById('div').style.display = 'none';

                }

                const myTable = document.getElementById('infoHolidays');
                const rows = myTable.rows.length - 1;

                for (let i = 1; i <= rows; i++) {
                    let userLast = myTable.rows[i].cells[1].innerHTML;
                    let userThis = myTable.rows[i].cells[2].innerHTML;
                    let userUsed = myTable.rows[i].cells[3].innerHTML;
                    myTable.rows[i].cells[4].innerHTML = parseInt(userLast) + parseInt(userThis) - parseInt(userUsed);
                }

                function checkEmployee(){
                const employee = document.getElementById('user').value;
                    for(employeeData of holidayData){
                        if(employeeData.user == employee){
                            holidays.value = employeeData.holidays;
                            lastYearHolidays.value = employeeData.lastYearHolidays;
                            usedHolidays.value = employeeData.usedHolidays;
                            hours.value = employeeData.overtime;
                        }
                    }
                }

            </script>

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
                integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
                integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
                crossorigin="anonymous"></script>
</body>

</html>
