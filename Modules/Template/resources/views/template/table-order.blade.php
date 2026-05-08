<div>
    @php
$invoices = [
    [
        'id' => 1,
        'name' => 'Mickie Melmoth',
        'email' => 'mmsht23@gmail.com',
        'avatar' => 'avatar5.png',
        'created_at' => '2025-07-05',
        'due_date' => '2025-07-11',
        'qty' => 3000,
        'status' => 'Paid',
        'badge' => 'bg-light-success',
    ],
    [
        'id' => 2,
        'name' => 'Shelba Thews',
        'email' => 'shelba@gmail.com',
        'avatar' => 'avatar4.png',
        'created_at' => '2025-07-06',
        'due_date' => '2025-07-08',
        'qty' => 3000,
        'status' => 'Cancelled',
        'badge' => 'bg-light-danger',
    ],
    [
        'id' => 3,
        'name' => 'tass23@gmail.com',
        'email' => 'shelba@gmail.com',
        'avatar' => 'avatar2.png',
        'created_at' => '2025-07-01',
        'due_date' => '2025-07-02',
        'qty' => 1000,
        'status' => 'Unpaid',
        'badge' => 'bg-light-info',
    ],
];
@endphp
<div class="row">

    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title">Bordered Table</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-hover table-bordered" role="table">
                    <thead>
                        <tr class="text-center" >
                            <th style="width: 120px">INVOICE ID</th>
                            <th>USER NAME</th>
                            <th style="width: 150px">CREATE DATE</th>
                            <th style="width: 150px">DUE DATE</th>
                            <th style="width: 100px">QUANTITY</th>
                            <th style="width: 100px">STATUS</th>
                            <th style="width: 150px">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($invoices as $item)
                            <tr>
                                <td class="text-center" >{{ $item['id'] }}</td>

                                <td>
                                    <div class="row align-items-center">
                                        <div class="col-auto pe-0">
                                            <img src="/assets/img/{{ $item['avatar'] }}"
                                                 class="rounded-circle" width="40" height="40">
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">{{ $item['name'] }}</h6>
                                            <p class="text-muted small mb-0">{{ $item['email'] }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-end" >{{ $item['created_at'] }}</td>
                                <td class="text-end" >{{ $item['due_date'] }}</td>
                                <td class="text-end" >{{ number_format($item['qty']) }}</td>

                                <td class="text-center">
                                        <span class="badge text-bg-primary {{ $item['badge'] }}">{{ $item['status'] }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-success me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <ul class="pagination pagination-sm m-0 float-end">
                <li class="page-item">
                  <a class="page-link" href="#">«</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                  <a class="page-link" href="#">»</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>

</div>
