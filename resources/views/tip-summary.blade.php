@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">

    <style>
        .status {
            text-align: center;
            border-radius: 5px;
            padding: 5px 27px;
            border: 1px solid;
        }

        .status.true {
            background: #a6e7d8;
            border-color: #008767;
            color: #008767;
        }

        .status.false {
            background: #FFC5C5;
            border-color: #DF0404;
            color: #DF0404;
        }

        tr {
            border-bottom: 1px solid #00000014;
        }

        td,
        th {
            font-size: 14px;
            text-align: center;
            padding: 20px 0;
        }

        th {
            color: #000000;
        }

        td {
            color: #292D32;
            font-weight: 500;
        }
    </style>


    <div class="container-fluid">
        <div class="row align-items-baseline ">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6>TIP Summary</h6>
                </div>
            </div>
            <div class="col-xl-8 ">
                <div class="body-right">
                    <div class=" search-group bg-custom rounded-4">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" id="customSearch" class="bg-transparent border-0">
                    </div>
                    <div class="select-group bg-custom rounded-4">
                        <span class="flex-1">Sort by:</span>
                        <select id="customSort" class="form-select bg-transparent border-0">
                            <option value="">Select</option>
                            <option value="0">Customer Name</option>
                            <option value="1">Company</option>
                            <option value="2">Phone Number</option>
                            <option value="3">Email</option>
                            <option value="4">Country</option>
                            <option value="5">Status</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
            <div class="data-table-jqeury mt-3">
                <div class="table-responsive">
                    <table id="myTable" class="display nowrap dataTable dtr-inline collapsed">
                        <thead>
                            <tr>
                                <th data-dt-column="0">Customer Name</th>
                                <th data-dt-column="1">Company</th>
                                <th data-dt-column="2">Phone Number</th>
                                <th data-dt-column="3">Email</th>
                                <th data-dt-column="4">Country</th>
                                <th data-dt-column="5">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commercialQualityData as $customer)
                                <tr>
                                    <td>{{ $customer['name'] }}</td>
                                    <td>{{ $customer['company'] }}</td>
                                    <td>{{ $customer['phone'] }}</td>
                                    <td>{{ $customer['email'] }}</td>
                                    <td>{{ $customer['country'] }}</td>
                                    <td>
                                        <span
                                            class="status {{ $customer['status'] }}">{{ ucfirst($customer['status']) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#myTable');

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#customSort').on('change', function() {
                let columnIndex = $(this).val();
                if (columnIndex) {
                    let order = table.order();
                    let orderColumn = parseInt(columnIndex);
                    let newOrder = order[0][0] === orderColumn ? 'desc' : 'asc';
                    table.order([orderColumn, newOrder]).draw();
                } else {
                    table.order([]).draw();
                }
            });
        });
    </script>
    <style>
        div.dt-container div.dt-layout-row:has(.dt-search) {
            display: none !important;
        }
    </style>
@endsection
