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
                    <h6>Touchpoint Influence</h6>
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
            <div class="filter-section mb-4">
                <form method="GET" id="filter-form">
                    <div class="row">
                        @if(!empty($uniqueGender))
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="uniqueGender">Gender</label>
                                    <select name="uniqueGender[]" id="uniqueGender" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueGender as $gender)
                                            <option value="{{ $gender }}">{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($uniqueAge))
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="uniqueAge">Age</label>
                                    <select name="uniqueAge[]" id="uniqueAge" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueAge as $age)
                                            <option value="{{ $age }}">{{ $age }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($uniqueValue))
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="uniqueValue">Value</label>
                                    <select name="uniqueValue[]" id="uniqueValue" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueValue as $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($uniqueMediaType))
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="uniqueMediaType">Media Type</label>
                                    <select name="uniqueMediaType[]" id="uniqueMediaType" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueMediaType as $media)
                                            <option value="{{ $media }}">{{ $media }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($uniqueRespoSer))
                            <div class="col-md-4 mt-3">
                                <div class="filter-group">
                                    <label for="uniqueRespoSer">Response Service</label>
                                    <select name="uniqueRespoSer[]" id="uniqueRespoSer" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueRespoSer as $respoSer)
                                            <option value="{{ $respoSer }}">{{ $respoSer }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($uniqueQuoSegur))
                            <div class="col-md-4 mt-3">
                                <div class="filter-group">
                                    <label for="uniqueQuoSegur">Security Quote</label>
                                    <select name="uniqueQuoSegur[]" id="uniqueQuoSegur" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueQuoSegur as $quoSegur)
                                            <option value="{{ $quoSegur }}">{{ $quoSegur }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($uniqueReach))
                            <div class="col-md-4 mt-3">
                                <div class="filter-group">
                                    <label for="uniqueReach">Reach</label>
                                    <select name="uniqueReach[]" id="uniqueReach" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueReach as $reach)
                                            <option value="{{ $reach }}">{{ $reach }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($uniqueattentive_exposure))
                            <div class="col-md-4 mt-3">
                                <div class="filter-group">
                                    <label for="uniqueattentive_exposure">Attentive Exposure</label>
                                    <select name="uniqueattentive_exposure[]" id="uniqueattentive_exposure" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueattentive_exposure as $attentive_exposure)
                                            <option value="{{ $attentive_exposure }}">{{ $attentive_exposure }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-4 mt-3 text-end">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                    </div>
                </form>
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
                                <th>Mean</th>
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
                                        <td>{{ round($data['Grand Total Row %']) ?? 'N/A' }}%</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Mean</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['1. Awareness Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['2. Understanding Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['3. Trial Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['4. Top of Mind Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{round( $commercialQualityData['Column Percentages']['5. Image Column Percentage'] )?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['6. Loyalty Column Percentage']) ?? 'N/A' }}%</th>
                                <th>{{ round($commercialQualityData['Column Percentages']['Grand Total Column %']) ?? 'N/A' }}%</th>
                            </tr>
                        </tfoot>
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
        "ordering": false,
        "info": false,
        "paging": false,
    });

    // Initialize Select2 for multi-select filters
    $('.js-example-basic-multiple').select2({
        placeholder: "Select an option",
        width: '50%'
    });

    // Handle form submission with AJAX
    $('#filter-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission (which reloads the page)
        applyFilters(); // Call the function to apply filters
    });

    // Function to apply filters via AJAX
    function applyFilters() {
        var formData = $('#filter-form').serialize(); // Get all form data

        $.ajax({
            url: '{{ route('touchpoint-influence') }}', // The route to your controller action
            method: 'GET',
            data: formData,
            success: function(response) {
                // Update table with filtered data
                updateTable(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

    // Function to update the table with the new data
    function updateTable(response) {
    var tableBody = $('#myTable tbody');
    tableBody.empty(); // Clear existing table data

    console.log(response);  // Log the response to the console for debugging

    if (response.commercialQualityData) {
        // Loop through the new data and populate the table
        Object.keys(response.commercialQualityData).forEach(function(mediaType) {
            const mediaTypeData = response.commercialQualityData[mediaType];
            tableBody.append(`
                <tr>
                    <td>${mediaType}</td>
                    <td>${mediaTypeData['1. Awareness Percentage'] || 'N/A'}%</td>
                    <td>${mediaTypeData['2. Understanding Percentage'] || 'N/A'}%</td>
                    <td>${mediaTypeData['3. Trial Percentage'] || 'N/A'}%</td>
                    <td>${mediaTypeData['4. Top of Mind Percentage'] || 'N/A'}%</td>
                    <td>${mediaTypeData['5. Image Percentage'] || 'N/A'}%</td>
                    <td>${mediaTypeData['6. Loyalty Percentage'] || 'N/A'}%</td>
                    <td>${mediaTypeData['Grand Total Row %'] || 'N/A'}%</td>
                </tr>
            `);
        });

        // Redraw the DataTable to reflect new data
        $('#myTable').DataTable().clear().draw();
        $('#myTable').DataTable().rows.add(tableBody.children()).draw(); // Refresh DataTable rows
    } else {
        // Handle the case where there is no data to display (optional)
        tableBody.append('<tr><td colspan="8">No data available</td></tr>');
    }
}



});


        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Select an option", // Placeholder text
                width: '50%'
            });
        });
    </script>

    <style>
        div.dt-container div.dt-layout-row:has(.dt-search) {
            display: none !important;
        }
    </style>
@endsection
