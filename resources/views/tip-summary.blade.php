@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')
    <!-- Include DataTables and Select2 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <style>
        #myTable {
            width: 100%;
            border-collapse: collapse;
        }
        #myTable th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
        }
        #myTable td, #myTable th {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        #myTable tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        #myTable tr:hover {
            background-color: #ddd;
        }
        .filter-section {
            margin-bottom: 20px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    cursor: default;
    padding-left: 17px;
    padding-right: 7px;
}
    </style>

    <div class="container-fluid">
        <h6>TIP Summary</h6>
        @if (empty($commercialQualityData['Column Percentages']) || count($commercialQualityData['Column Percentages']) === 0)

            <div class="alert alert-warning">{{ $dataMessage }}</div>
        @else
        <!-- Filter Section -->
            <div class="mt-3 row">
                <div class="col-12">
                    <div class="p-3 rounded shadow-sm select-group bg-custom">
                        <h5 class="mb-3">Apply Filters</h5>
                <form id="filter-form">
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
                                            <option value="{{ $cleanValue }}">
                                                {{ $mediaTypeMapping[$cleanValue] ?? $cleanValue }}
                                            </option>
                                        @else
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                        <div class="col-md-3 align-self-end">
                            <button type="button" id="applyFilters" class="btn btn-primary">Aplicar</button>
                        </div>
                    </div>
                </form>
            </div>
                </div>
            </div>



            <!-- Data Table -->
            <div id="data-container">
                <div class="table-responsive">
                    <table id="myTable" class="display nowrap">
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
                                @if ($mediaType !== 'Column Percentages')
                                    <tr>
                                        <td>{{ $mediaType }}</td>
                                        <td>{{ round($data['1. Awareness Percentage'] ?? 0) }}%</td>
                                        <td>{{ round($data['2. Understanding Percentage'] ?? 0) }}%</td>
                                        <td>{{ round($data['3. Trial Percentage'] ?? 0) }}%</td>
                                        <td>{{ round($data['4. Top of Mind Percentage'] ?? 0) }}%</td>
                                        <td>{{ round($data['5. Image Percentage'] ?? 0) }}%</td>
                                        <td>{{ round($data['6. Loyalty Percentage'] ?? 0) }}%</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @endif
    </div>

    <!-- Include jQuery, DataTables, and Select2 JS libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            var table = new DataTable('#myTable', {
                ordering: false,
                info: false,
                paging: false
            });

            // Initialize Select2 for each filter select
            $('.js-multiple-filter').select2({
                placeholder: "Seleccionar",
                allowClear: true,
                width: "100%"
            });

            // Aplicar via AJAX when the button is clicked
            $('#applyFilters').on('click', function () {
                var formData = $('#filter-form').serialize();
                $.ajax({
                    url: "{{ route('tip-summary') }}",
                    method: "GET",
                    data: formData,
                    success: function (response) {
                        if (response.data && response.data.length > 0) {
                            // Rebuild the table with filtered data
                            $('#data-container').html(renderTable(response.data));
                            table.destroy();
                            table = new DataTable('#myTable', {
                                ordering: false,
                                info: false,
                                paging: false
                            });
                        } else {
                            $('#data-container').html('<div class="alert alert-warning">No data available in the table</div>');
                        }
                    },
                    error: function () {
                        alert("Failed to Aplicar. Please try again.");
                    }
                });
            });

            // Function to build table HTML from the AJAX response data
            function renderTable(data) {
                var html = '<div class="table-responsive"><table id="myTable" class="display nowrap"><thead><tr>' +
                           '<th>Media Channels</th>' +
                           '<th>Awareness</th>' +
                           '<th>Understanding</th>' +
                           '<th>Trial</th>' +
                           '<th>Top of Mind</th>' +
                           '<th>Image</th>' +
                           '<th>Loyalty</th>' +
                           '</tr></thead><tbody>';
                $.each(data, function (index, row) {
                    html += '<tr>' +
                            '<td>' + row.MediaChannels + '</td>' +
                            '<td>' + row.Awareness + '</td>' +
                            '<td>' + row.Understanding + '</td>' +
                            '<td>' + row.Trial + '</td>' +
                            '<td>' + row.TopOfMind + '</td>' +
                            '<td>' + row.Image + '</td>' +
                            '<td>' + row.Loyalty + '</td>' +
                            '</tr>';
                });
                html += '</tbody></table></div>';
                return html;
            }
        });
    </script>
@endsection
