@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
   <style>
        /* Table Styling */
        #myTable {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        /* Green header with white text */
        #myTable th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
        }

        /* Adjusting column widths */
        #myTable td, #myTable th {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        /* Fixed column widths */
        #myTable th:nth-child(1), #myTable td:nth-child(1) {
            width: 20%; /* Adjust according to your needs */
        }

        #myTable th:nth-child(2), #myTable td:nth-child(2) {
            width: 12%;
        }

        #myTable th:nth-child(3), #myTable td:nth-child(3) {
            width: 12%;
        }

        #myTable th:nth-child(4), #myTable td:nth-child(4) {
            width: 12%;
        }

        #myTable th:nth-child(5), #myTable td:nth-child(5) {
            width: 12%;
        }

        #myTable th:nth-child(6), #myTable td:nth-child(6) {
            width: 12%;
        }

        #myTable th:nth-child(7), #myTable td:nth-child(7) {
            width: 12%;
        }

        /* Zebra striping for rows (optional) */
        #myTable tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Hover effect for rows */
        #myTable tr:hover {
            background-color: #ddd;
        }

        /* Table Footer (optional) */
        #myTable tfoot th {
            /* background-color: #55f140; */
            background-color: #4CAF50;
        }


    </style>
    <div class="container-fluid">
        <div class="row align-items-baseline ">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6>Indexed Review Of Stronger Drivers</h6>
                </div>
            </div>
            <div class="col-xl-8 ">
                <div class="body-right">
                    {{-- <div class=" search-group bg-custom rounded-4">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" id="customSearch" class="bg-transparent border-0">
                    </div> --}}
                    <div class="select-group bg-custom rounded-4">
                        <select id="mediaFilter" class="bg-transparent border-0 form-select">
                            <option value="">Select Media</option>
                            @foreach ($commercialQualityData as $mediaType => $data)
                                @if($mediaType !== 'Column Percentages')
                                    <option value="{{ $mediaType }}">{{ $mediaType }}</option>
                                @endif
                            @endforeach
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commercialQualityData as $mediaType => $data)
                                @if($mediaType !== 'Column Percentages')
                                    <tr>
                                        <td>{{ $mediaType }}</td>
                                        @foreach ([
                                            '1. Awareness Adjusted Percentage',
                                            '2. Understanding Adjusted Percentage',
                                            '3. Trial Adjusted Percentage',
                                            '4. Top of Mind Adjusted Percentage',
                                            '5. Image Adjusted Percentage',
                                            '6. Loyalty Adjusted Percentage'
                                        ] as $key)
                                            @php
                                                $value = $data[$key] ?? 'N/A';
                                                $style = '';
                                                if (is_numeric($value)) {
                                                    $value = round($value); // Round off value to nearest integer
                                                    if ($value <= 80) {
                                                        $style = 'background-color: #ffc7ce;'; // Red
                                                    } elseif ($value > 130) {
                                                        $style = 'background-color: #c6efce;'; // Green
                                                    }
                                                }
                                            @endphp
                                            <td style="{{ $style }}">{{ is_numeric($value) ? $value : 'N/A' }}</td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        @endif
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    {{-- <script>
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
    </script> --}}
    <script>
        $(document).ready(function() {
            let table = new DataTable('#myTable', {
                "ordering": false, // Disable sorting functionality
                "info": false, // Optionally, disable the info text (number of rows, etc.)
                "paging": false, // Disable paging (if you want to show all rows at once)
            });

            // Custom Search functionality
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Media Channel filter functionality
            $('#mediaFilter').on('change', function() {
                var selectedMedia = this.value;
                table.columns(0).search(selectedMedia ? '^' + selectedMedia + '$' : '', true, false).draw();
            });
        });
    </script>

    <style>
        div.dt-container div.dt-layout-row:has(.dt-search) {
            display: none !important;
        }
    </style>
@endsection
