<div id="payed" class="text-center">
    <table id="payedBills" class="table table-striped-columns table-hover mb-0 ">
      <thead id="table-data" class="table-dark text-center align-middle border-bottom">
          <tr class="text-light">
              <th class="col">Kupec</th>
              <th class="col">Produkt</th>
              <th class="col-1">Količina</th>
              <th class="col-1">Cena</th>
              <th class="col-1">DDV</th>
              <th class="col">Free</th>
              <th class="col">Teden</th>
              <th class="col">Neto</th>
              <th class="col-1">Št / mesec</th>
              <th class="col-1">Mesec</th>
              <th class="col-1">Redna št</th>
              <th class="col">Leto</th>
              <th class="col">Izdano</th>
              <th class="col">Izdal</th>
          </tr>
      </thead>
          <tbody>
            @foreach($payed as $bill)
            @foreach ($products as $product)
            @if($bill->id == $product->bills_id && $bill->payed == 1)
            <tr class="m-1 align-middle">
                @if(Auth::user()->role !== 'visitor')
                    <td class="bg-success"><a href="all/view/{{$bill->id}}">{{$bill->buyer}}</a></td>
                @else
                <td class="bg-warning"><a href="all/view/{{$bill->id}}">Not for visitors</a></td>
                @endif
                <td>{{$product->name}}</td>
                <td>{{$product->qty}}</td>
                <td>{{$product->price}}</td>
                <td>1.50</td>
                @if ($product->firstOfWeek == 1)
                        <td><img src="{{asset('img/payed.jpg')}}" alt="Payed"></td>
                    @else
                        <td><img src="{{asset('img/notPay.jpg')}}" alt="Not Payed"></td>
                    @endif
                <td>{{$bill->kt}}</td>
                <td>{{$product->total}}</td>
                <td>{{$bill->num_per_month}}</td>
                <td>{{$bill->month}}</td>
                <td>{{$bill->num_per_year}}</td>
                <td>{{$bill->year}}</td>
                <td>{{\Carbon\Carbon::parse($bill->sold_date)->format('d.m.Y')}}</td>
                @if(Auth::user()->role !== 'visitor')
                <td>{{$bill->published}}</td>
                @else
                <td class="bg-warning">Not for visitors</td>
                @endif
            </tr>
            @endif
            @endforeach
            @endforeach
          </tbody>
    </table>
    {{ $payed->links('custom') }}
</div>