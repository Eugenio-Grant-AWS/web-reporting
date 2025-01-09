@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsmind/style/jsmind.css">
    <style>
        .jsmind-inner canvas {
            position: absolute;
            transform: translatex(-50%);
            left: 50%;
        }

        jmnodes.theme-primary {
            transform: translateX(-50%);
            left: 50%;
        }

        .body-content {
            min-height: 100vh;
            position: relative;
            /* height: unset; */
        }

        @media screen and (max-width: 768px) {
            .body-content {
                overflow: hidden;
            }
        }
    </style>
    <div class="container-fluid">
        <div class="row align-items-baseline">

            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6>TIP Summary x Creative Quality</h6>

                </div>
            </div>
            <div class="col-xl-8 ">
                <div class="body-right">
                    <div class=" search-group bg-custom rounded-4">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="select-group bg-custom rounded-4 ">
                        <span class="flex-1">Sort by:</span>
                        <select class="form-select  bg-transparent border-0">
                            <option>Newest </option>
                            <option>Old </option>
                            <option>Alphabatical Order</option>
                        </select>
                    </div>
                    <!-- <button id="downloadChart" class="export-btn">
                                    Export Chart as Image <i class="fas fa-download"></i>
                                    </button> -->
                </div>
            </div>
            {{-- @endif --}}
        </div>
        @if ($showNoDataMessage)
            @component('components.no_data_message', ['message' => $showNoDataMessage])
            @endcomponent
        @else
            @if (!$showNoDataMessage)
                <div class="flow-chart-display">
                    <div id="jsmind_container_2"></div> <!-- Mind Map 2 -->
                    <div id="jsmind_container"></div> <!-- Mind Map 1 -->
                </div>
            @endif
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsmind/js/jsmind.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsmind/js/jsmind.draggable.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsmind/js/jsmind.screenshot.js"></script>

    <script>
        var mindMapData1 = @json($mindMapData1);
        var mindMapData2 = @json($mindMapData2);

        var options1 = {
            container: 'jsmind_container',
            theme: 'primary',
            editable: true
        };

        var options2 = {
            container: 'jsmind_container_2',
            theme: 'primary',
            editable: true
        };

        var jm1 = new jsMind(options1);
        jm1.show(mindMapData1);

        var jm2 = new jsMind(options2);
        jm2.show(mindMapData2);

        // document.addEventListener("DOMContentLoaded", () => {
        //     const flowChartDisplay = document.querySelector(".body-content");
        //     if (flowChartDisplay) {
        //         flowChartDisplay.style.height = "600px"; // Set height explicitly to 600px
        //     }
        // });
    </script>
@endsection
