@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsmind/style/jsmind.css">
    <style>
        #jsmind_container,
        #jsmind_container_2 {
            width: 100%;
            height: 100%;

        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="body-left">

                    <h6>TIP Summary x Creative Quality</h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="gap-3 d-flex align-items-center justify-content-end">
                    <div class="p-2 search-group bg-custom">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="px-3 py-3 mt-3 select-group bg-custom rounded-4 mt-lg-0 ">
                        <span>Sort by:</span>
                        <select class="bg-transparent border-0">
                            <option>Newest </option>
                            <option>Old </option>
                            <option>Alphabatical Order</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="flow-chart-display d-flex align-items-center vh-100 ">
            <div id="jsmind_container_2"></div>
            <div id="jsmind_container"></div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/jsmind/js/jsmind.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsmind/js/jsmind.draggable.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsmind/js/jsmind.screenshot.js"></script>

    <script>
        var mindMapData2 = {
            "meta": {
                "name": "architecture",
                "author": "user",
                "version": "1.0"
            },
            "format": "node_tree",
            "data": {
                "id": "root_2",
                "topic": "Arc...",
                "children": [{
                        "id": "layers",
                        "topic": "Illustrate a simple example of how system components relate in the Pipeline Architecture",
                        "direction": "left",

                    },
                    {
                        "id": "layerss",
                        "topic": "Show the flow of data or operations between these components/stages",
                        "direction": "left",

                    },

                    {
                        "id": "deployment",
                        "topic": "Break down the stages or components within the pipeline",
                        "direction": "left",

                    }
                ]
            }
        };

        var mindMapData1 = {
            "meta": {
                "name": "flowchart",
                "author": "user",
                "version": "1.0"
            },
            "format": "node_tree",
            "data": {
                "id": "root",
                "topic": "Arc...",
                "children": [{
                        "id": "characteristics",
                        "topic": "Characteristics",
                        "direction": "right",
                        "children": [{
                                "id": "parallel",
                                "topic": "Parallel stages handling data..."
                            },
                            {
                                "id": "segmented",
                                "topic": "Segmented flow of work"
                            },
                            {
                                "id": "efficiency",
                                "topic": "Efficiency in parallel processing"
                            }
                        ]
                    },
                    {
                        "id": "usage",
                        "topic": "Usage",
                        "direction": "right",
                        "children": [{
                                "id": "dataprocessing",
                                "topic": "Data processing systems"
                            },
                            {
                                "id": "streaming",
                                "topic": "Streaming applications"
                            },
                            {
                                "id": "continuous",
                                "topic": "Continuous integration/continuous delivery"
                            }
                        ]
                    },
                    {
                        "id": "benefits",
                        "topic": "Benefits",
                        "direction": "right",
                        "children": [{
                                "id": "performance",
                                "topic": "Enhanced performance via parallelism"
                            },
                            {
                                "id": "optimization",
                                "topic": "Segmentation allows for optimization"
                            }
                        ]
                    },
                    {
                        "id": "tradeoffs",
                        "topic": "Trade-offs",
                        "direction": "right",
                        "children": [{
                                "id": "complexity",
                                "topic": "Complexity in coordinating stages"
                            },
                            {
                                "id": "bottlenecks",
                                "topic": "Potential for bottlenecks if stages aren't balanced"
                            }
                        ]
                    },
                    {
                        "id": "antipatterns",
                        "topic": "Antipatterns",
                        "direction": "right",
                        "children": [{
                                "id": "sync_issues",
                                "topic": "Inefficient synchronization mechanisms"
                            },
                            {
                                "id": "oversegmentation",
                                "topic": "Over-segmentation leading to overhead"
                            }
                        ]
                    },
                    {
                        "id": "challenges",
                        "topic": "Challenges",
                        "direction": "right",
                        "children": [{
                                "id": "sync",
                                "topic": "Maintaining synchronization"
                            },
                            {
                                "id": "workload",
                                "topic": "Balancing the workload across stages"
                            }
                        ]
                    }
                ]
            }
        };




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
    </script>

@endsection
