@extends('layouts.admin')
@section('title', 'Pages')
@section('content')


    <div class="container-fluid">
        <div class="d-lg-flex align-items-center justify-content-between">
            <div class="col-lg-6">
                <div class="content-heading">

                    <h6>Reach Exposure / Probability with mean</h6>
                </div>
            </div>



            <div class="gap-3 d-lg-flex align-items-center justify-content-end">
                <div class="px-3 py-3 search-group bg-custom rounded-4">
                    <i class="fas fa-search me-2 "></i>
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

@endsection
