
@extends('layouts.admin')
@section('title','Pages')
@section('content')


<div class="container-fluid">
    <div class="d-lg-flex  align-items-center justify-content-between">
        <div class="col-lg-6">
            <div class="content-heading">

                <h6>Reach Exposure / Probability with mean</h6>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="d-lg-flex  align-items-center justify-content-end gap-3">
                <div class="search-group py-3 px-3 bg-custom rounded-4">
                    <i class="fas fa-search me-2 "></i>
                    <input type="text" placeholder="Search" class="border-0 bg-transparent">
                </div>
                <div class="select-group bg-custom py-3 px-3 rounded-4 mt-lg-0 mt-3 ">
                    <span>Sort by:</span>
                    <select class="border-0 bg-transparent">
                        <option >Newest </option>
                        <option >Old </option>
                        <option >Alphabatical Order</option>
                    </select>
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection