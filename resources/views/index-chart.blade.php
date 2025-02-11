@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <style>
        /* Table Styling */
        #myTable {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }
        /* Header styling */
        #myTable th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
        }
        /* Column styling */
        #myTable td, #myTable th {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        /* Fixed column widths */
        #myTable th:nth-child(1), #myTable td:nth-child(1) { width: 20%; }
        #myTable th:nth-child(2), #myTable td:nth-child(2) { width: 12%; }
        #myTable th:nth-child(3), #myTable td:nth-child(3) { width: 12%; }
        #myTable th:nth-child(4), #myTable td:nth-child(4) { width: 12%; }
        #myTable th:nth-child(5), #myTable td:nth-child(5) { width: 12%; }
        #myTable th:nth-child(6), #myTable td:nth-child(6) { width: 12%; }
        #myTable th:nth-child(7), #myTable td:nth-child(7) { width: 12%; }
        /* Zebra striping and hover effects */
        #myTable tr:nth-child(even) { background-color: #f2f2f2; }
        #myTable tr:hover { background-color: #ddd; }
        /* Table Footer */
        #myTable tfoot th { background-color: #4CAF50; }
    </style>
    <div class="container-fluid">
        <h6>Indexed Review Of Stronger Drivers</h6>

        <!-- Filter Section (server‑side filters) -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="p-3 rounded shadow-sm select-group bg-custom">
                    <h5 class="mb-3">Apply Filters</h5>
            <form id="filter-form" method="GET">
                <div class="row">
                    @foreach ($distinctValues as $key => $values)
                        <div class="col-md-3">
                        <label for="{{ $key }}">
                            <strong>{{ $filterLabels[$key] ?? ucwords(str_replace('unique', '', $key)) }}</strong>
                        </label>
                            <select name="{{ $key }}[]" id="{{ $key }}" class="form-select js-multiple-filter" multiple>
                                @foreach ($values as $value)
                                    @if($key === 'uniqueMediaType')
                                        @php $cleanValue = trim($value); @endphp
                                        <option value="{{ $cleanValue }}"
                                            {{ (request($key) && in_array($cleanValue, request($key))) ? 'selected' : '' }}>
                                            {{ $mediaTypeMapping[$cleanValue] ?? $cleanValue }}
                                        </option>
                                    @else
                                        <option value="{{ $value }}"
                                            {{ (request($key) && in_array($value, request($key))) ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                    <div class="col-md-3 align-self-end mb-3">
                        <button type="submit" class="btn btn-primary">Aplicar</button>
                    </div>
                </div>
            </form>
        </div>
            </div>
        </div>

        <!-- Data Table Container -->
        <div id="data-container">
            @section('table')
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
                                            // Round off the value to the nearest integer
                                            $value = round($value);
                                            if ($value <= 80) {
                                                $style = 'background-color: #ffc7ce;';
                                            } elseif ($value > 130) {
                                                $style = 'background-color: #c6efce;';
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
            @show
        </div>
    </div>

    <!-- Include jQuery, DataTables, and Select2 JS libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = new DataTable('#myTable', {
                ordering: false,
                info: false,
                paging: false,
            });

            // Initialize Select2 on all filter dropdowns
            $('.js-multiple-filter').select2({
                placeholder: "Seleccionar",
                allowClear: true,
                width: "100%"
            });

            // AJAX submission for the filter form (prevents page reload)
            $('#filter-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('indexed-review-of-stronger-drivers') }}",
                    type: "GET",
                    data: formData,
                    success: function(response) {
                        if(response.html) {
                            $('#data-container').html(response.html);
                            // Reinitialize DataTable after updating the table HTML
                            table.destroy();
                            table = new DataTable('#myTable', {
                                ordering: false,
                                info: false,
                                paging: false,
                            });
                        }
                    },
                    error: function() {
                        alert("Failed to Aplicar. Please try again.");
                    }
                });
            });
        });
    </script>
    <style>
        /* Optionally hide DataTables default search box */
        div.dt-container div.dt-layout-row:has(.dt-search) {
            display: none !important;
        }
    </style>
@endsection
