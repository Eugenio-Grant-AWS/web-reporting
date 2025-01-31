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
                    <h6>TIP Summary x Creative Quality</h6>
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
                        <select id="customSort" class="bg-transparent border-0 form-select">
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
            <div class="mt-3 data-table-jqeury">
                <div class="table-responsive">
                    <table id="myTable" class="display nowrap dataTable dtr-inline collapsed">
                        <thead>
                            <tr>
                                <th>Media Channels</th>
                                <th>Awareness</th>
                                <th>Understanding</th>
                                <th>Trial</th>
                                <th>Top of Mind</th>
                                <th>Image</th>
                                <th>Loyalty</th>
                                {{-- <th>Mean</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commercialQualityData as $mediaType => $data)
                                @if($mediaType !== 'Column Percentages')
                                    <tr>
                                        <td>{{ $mediaType }}</td>
                                        <td>{{ round($data['1. Awareness Percentage']) ?? 'N/A' }}%</td>
                                        <td>{{ round($data['2. Understanding Percentage']) ?? 'N/A' }}%</td>
                                        <td>{{ round($data['3. Trial Percentage']) ?? 'N/A' }}%</td>
                                        <td>{{ round($data['4. Top of Mind Percentage']) ?? 'N/A' }}%</td>
                                        <td>{{ round($data['5. Image Percentage']) ?? 'N/A' }}%</td>
                                        <td>{{ round($data['6. Loyalty Percentage']) ?? 'N/A' }}%</td>
                                        {{-- <td>{{ round($data['Grand Total Row %']) ?? 'N/A' }}%</td> --}}
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        {{-- <tfoot>
                            <tr>
                                <th>Grand Total Column %</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['1. Awareness Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['2. Understanding Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['3. Trial Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['4. Top of Mind Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{round( $commercialQualityData['Column Percentages']['5. Image Column Percentage'] )?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['6. Loyalty Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['Grand Total Column %']) ?? 'N/A' }}%</th>
                            </tr>
                        </tfoot> --}}
                    </table>

                </div>
            </div>
        @endif
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#myTable', {
                "ordering": false, // Disable sorting functionality
                "info": false, // Optionally, disable the info text (number of rows, etc.)
                "paging": false, // Disable paging (if you want to show all rows at once)
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#customSort').on('change', function() {
                // Since sorting is disabled, there's no need to handle this anymore
            });
        });
    </script>

    <style>
        div.dt-container div.dt-layout-row:has(.dt-search) {
            display: none !important;
        }
    </style>
@endsection
