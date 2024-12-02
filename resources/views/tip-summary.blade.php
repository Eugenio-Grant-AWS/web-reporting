@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
<style>
    .status {

    text-align: center;
    border-radius: 5px;
    padding: 4px 15px;
    border: 1px solid;
    &.active{
        background: #a6e7d8;
        border-color:  #008767;
        color: #008767;
    }
    &.inactive{
        background:#FFC5C5;
        border-color:#DF0404;
        color: #DF0404;
    }
}



tr {
    border-bottom: 1px solid #00000038;
}

td,
th {
    font-size: 14px;
    text-align: center;
    padding: 20px 0;
}

th {
    color: #b5b7c0;
}

td {
    color: #292D32;
}
</style>

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
    <div class="data-table-jqeury">
    <table id="example" class="row-border" style="width:100%">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Company</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Country</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tiger Nixon</td>
                <td>Microsoft</td>
                <td>(225) 555-0118</td>
                <td>jane@microsoft.com</td>
                <td>United States</td>
                <td ><span class="status active">Active</span> </td>
            </tr>
            <tr>
                <td>Tiger Nixon</td>
                <td>Microsoft</td>
                <td>(225) 555-0118</td>
                <td>jane@microsoft.com</td>
                <td>United States</td>
                <td ><span class="status inactive">Active</span> </td>
            </tr>
            <tr>
                <td>Tiger Nixon</td>
                <td>Microsoft</td>
                <td>(225) 555-0118</td>
                <td>jane@microsoft.com</td>
                <td>United States</td>
                <td ><span class="status active">Active</span> </td>
            </tr>
            <tr>
                <td>Tiger Nixon</td>
                <td>Microsoft</td>
                <td>(225) 555-0118</td>
                <td>jane@microsoft.com</td>
                <td>United States</td>
                <td ><span class="status inactive">Active</span> </td>
            </tr>
            <tr>
                <td>Tiger Nixon</td>
                <td>Microsoft</td>
                <td>(225) 555-0118</td>
                <td>jane@microsoft.com</td>
                <td>United States</td>
                <td ><span class="status inactive">Active</span> </td>
            </tr>
            <tr>
                <td>Tiger Nixon</td>
                <td>Microsoft</td>
                <td>(225) 555-0118</td>
                <td>jane@microsoft.com</td>
                <td>United States</td>
                <td ><span class="status inactive">Active</span> </td>
            </tr>
            <tr>
                <td>Tiger Nixon</td>
                <td>Microsoft</td>
                <td>(225) 555-0118</td>
                <td>jane@microsoft.com</td>
                <td>United States</td>
                <td ><span class="status inactive">Active</span> </td>
            </tr>



    </table>
    </div>
  
</div>




    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
       new DataTable('#example');
    </script>


@endsection
