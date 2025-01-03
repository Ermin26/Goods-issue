<div id="not_payed" class="text-center">
    @php
    \Log::info('Neto Not Payed (Blade): ' . $netoNotPayed);
@endphp
    <table id="all" class="table table-striped-columns hoverable mb-0 ">
      <thead id="table-data" class="table-dark text-center align-middle border-bottom">
          <tr class="text-light">
              <th class="col">Kupec</th>
              <th class="col">Produkt</th>
              <th class="col-1">Količina</th>
              <th class="col-1">Cena</th>
              <th class="col-1">DDV</th>
              <th class="col">Teden</th>
              <th class="col">Neto</th>
              <th class="col">Total</th>
              <th class="col-1">Št / mesec</th>
              <th class="col-1">Mesec</th>
              <th class="col-1">Redna št</th>
              <th class="col">Leto</th>
              <th class="col">Izdano</th>
              <th class="col">Izdal</th>
          </tr>
      </thead>
          <tbody>
            @foreach($notPayed as $bill)
            <tr class="m-1 align-middle">
                @if(Auth::user()->role !== 'visitor')
                    <td class="bg-danger"><a href="all/view/{{$bill->id}}">{{$bill->buyer}}</a></td>
                @else
                <td class="bg-warning"><a href="all/view/{{$bill->id}}">Not for visitors</a></td>
                @endif
                <td>
                    @foreach ($products as $product)
                        @if($bill->id == $product->bills_id)
                            {{$product->name}}<br>
                        @endif
                    @endforeach
                </td>
                <td>@foreach ($products as $product)
                    @if($bill->id == $product->bills_id)
                        {{$product->qty}}<br>
                    @endif
                @endforeach</td>
                <td style="text-wrap:nowrap">@foreach ($products as $product)
                    @if($bill->id == $product->bills_id)
                        {{$product->price}} €<br>
                    @endif
                @endforeach</td>
                <td style="text-wrap:nowrap">1.50 €</td>
                <td>{{$bill->kt}}</td>
                <td style="text-wrap:nowrap">@foreach ($products as $product)
                    @if($bill->id == $product->bills_id)
                        {{$product->total}} €<br>
                    @endif
                @endforeach</td>
                <td style="text-wrap:nowrap">{{$bill->total}} €</td>
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
            @endforeach
          </tbody>
    </table>
    {{ $notPayed->links('custom') }}
</div>