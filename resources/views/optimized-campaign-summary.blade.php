@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')
    <h1></h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="body-left">

                    <h6>Optimized Campaign Summary</h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="gap-3 d-flex align-items-center justify-content-end">
                    <div class="p-2 search-group bg-custom">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="select-group bg-custom py-3 px-3 rounded-4 mt-lg-0 mt-3 ">
                        <span>Sort by:</span>
                        <select class="border-0 bg-transparent">
                            <option>Newest </option>
                            <option>Old </option>
                            <option>Alphabatical Order</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
