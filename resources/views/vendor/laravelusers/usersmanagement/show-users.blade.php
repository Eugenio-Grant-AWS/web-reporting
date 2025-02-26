@extends(config('laravelusers.laravelUsersBladeExtended'))
{{-- @extends('layouts.admin') --}}
@section('template_title')
    {!! trans('laravelusers::laravelusers.showing-all-users') !!}
@endsection

@section('template_linked_css')
    @if(config('laravelusers.enabledDatatablesJs'))
        <link rel="stylesheet" type="text/css" href="{{ config('laravelusers.datatablesCssCDN') }}">
    @endif
    @if(config('laravelusers.fontAwesomeEnabled'))
        <link rel="stylesheet" type="text/css" href="{{ config('laravelusers.fontAwesomeCdn') }}">
    @endif
    @include('laravelusers::partials.styles')
    @include('laravelusers::partials.bs-visibility-css')
@endsection

@section('content')
    <div class="container">
        @if(config('laravelusers.enablePackageBootstapAlerts'))
            <div class="row">
                <div class="col-sm-12">
                    @include('laravelusers::partials.form-status')
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {!! trans('laravelusers::laravelusers.showing-all-users') !!}
                            </span>

                            <div class="btn-group pull-right btn-group-xs">
                                @if(config('laravelusers.softDeletedEnabled'))
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v fa-fw" aria-hidden="true"></i>
                                        <span class="sr-only">
                                            {!! trans('laravelusers::laravelusers.users-menu-alt') !!}
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('users') }}">
                                                @if(config('laravelusers.fontAwesomeEnabled'))
                                                    <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                                @endif
                                                {!! trans('laravelusers::laravelusers.buttons.create-new') !!}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/users/deleted">
                                                @if(config('laravelusers.fontAwesomeEnabled'))
                                                    <i class="fa fa-fw fa-group" aria-hidden="true"></i>
                                                @endif
                                                {!! trans('laravelusers::laravelusers.show-deleted-users') !!}
                                            </a>
                                        </li>
                                    </ul>
                                @else
                                    <a href="{{ route('users.create') }}" class="btn btn-default btn-sm pull-right" data-toggle="tooltip" data-placement="left" title="{!! trans('laravelusers::laravelusers.tooltips.create-new') !!}">
                                        @if(config('laravelusers.fontAwesomeEnabled'))
                                            <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                        @endif
                                        {!! trans('laravelusers::laravelusers.buttons.create-new') !!}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- @if(config('laravelusers.enableSearchUsers'))
                            @include('laravelusers::partials.search-users-form')
                        @endif --}}

                        <div class="table-responsive users-table">
                            <table class="table table-striped table-sm data-table">
                                <caption id="user_count">
                                    {!! trans_choice('laravelusers::laravelusers.users-table.caption', 1, ['userscount' => $users->count()]) !!}
                                </caption>
                                <thead class="thead">
                                    <tr>
                                        <th>{!! trans('laravelusers::laravelusers.users-table.id') !!}</th>
                                        <th>{!! trans('laravelusers::laravelusers.users-table.name') !!}</th>
                                        <th class="hidden-xs">{!! trans('laravelusers::laravelusers.users-table.email') !!}</th>
                                        @if(config('laravelusers.rolesEnabled'))
                                            <th class="hidden-sm hidden-xs">{!! trans('laravelusers::laravelusers.users-table.role') !!}</th>
                                        @endif
                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('laravelusers::laravelusers.users-table.created') !!}</th>
                                        <th class="hidden-sm hidden-xs hidden-md">{!! trans('laravelusers::laravelusers.users-table.updated') !!}</th>
                                        <th class="no-search no-sort">{!! trans('laravelusers::laravelusers.users-table.actions') !!}</th>
                                        <th class="no-search no-sort"></th>
                                        <th class="no-search no-sort"></th>
                                    </tr>
                                </thead>
                                <tbody id="users_table">
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->firstname . ' ' . $user->lastname}}</td>
                                            <td class="hidden-xs">{{$user->email}}</td>
                                            @if(config('laravelusers.rolesEnabled'))
                                                <td class="hidden-sm hidden-xs">
                                                    @foreach ($user->roles as $user_role)
                                                        @if ($user_role->name == 'User')
                                                            @php $badgeClass = 'primary' @endphp
                                                        @elseif ($user_role->name == 'Admin')
                                                            @php $badgeClass = 'warning' @endphp
                                                        @elseif ($user_role->name == 'Unverified')
                                                            @php $badgeClass = 'danger' @endphp
                                                        @else
                                                            @php $badgeClass = 'dark' @endphp
                                                        @endif
                                                        <span class="badge badge-{{$badgeClass}}">{{ $user_role->name }}</span>
                                                    @endforeach
                                                </td>
                                            @endif
                                            <td class="hidden-sm hidden-xs hidden-md">{{$user->created_at}}</td>
                                            <td class="hidden-sm hidden-xs hidden-md">{{$user->updated_at}}</td>
                                            <td>
                                                {!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'DELETE', 'class' => '', 'data-toggle' => 'tooltip', 'title' => trans('laravelusers::laravelusers.tooltips.delete')]) !!}
                                                    <!-- Delete Button with SVG Icon -->
                                                    {!! Form::button(
                                                        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16" fill="currentColor"><path d="M32 464a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128H32zm272-256a16 16 0 0 1 32 0v224a16 16 0 0 1 -32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1 -32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1 -32 0zM432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.7 23.7 0 0 0 -21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0 -16-16z"/></svg>',
                                                        ['class' => 'btn btn-danger btn-sm', 'type' => 'button', 'style' => 'width: 100%;', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => trans('laravelusers::modals.delete_user_title'), 'data-message' => trans('laravelusers::modals.delete_user_message', ['user' => $user->name])]
                                                    ) !!}
                                                {!! Form::close() !!}
                                            </td>


                                            <td>
                                                <a class="btn btn-sm btn-success btn-block" href="{{ route('users.show', $user->id) }}" data-toggle="tooltip" title="{!! trans('laravelusers::laravelusers.tooltips.show') !!}">
                                                    <!-- Use the provided SVG icon for the Show button -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16" fill="currentColor">
                                                        <!-- Font Awesome Free 6.7.2 -->
                                                        <path d="M288 144a110.9 110.9 0 0 0 -31.2 5 55.4 55.4 0 0 1 7.2 27 56 56 0 0 1 -56 56 55.4 55.4 0 0 1 -27-7.2A111.7 111.7 0 1 0 288 144zm284.5 97.4C518.3 135.6 410.9 64 288 64S57.7 135.6 3.5 241.4a32.4 32.4 0 0 0 0 29.2C57.7 376.4 165.1 448 288 448s230.3-71.6 284.5-177.4a32.4 32.4 0 0 0 0-29.2zM288 400c-98.7 0-189.1-55-237.9-144C98.9 167 189.3 112 288 112s189.1 55 237.9 144C477.1 345 386.7 400 288 400z"/>
                                                    </svg>
                                                    <!-- Optionally, you can add a small text next to the icon, or leave it out -->
                                                </a>
                                            </td>

                                            <td>
                                                <a class="btn btn-sm btn-info btn-block" href="{{ route('users.edit', $user->id ) }}" data-toggle="tooltip" title="{!! trans('laravelusers::laravelusers.tooltips.edit') !!}">
                                                    <!-- Use the provided SVG icon for the Edit button -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16" fill="currentColor">
                                                        <!-- Font Awesome Free 6.7.2 -->
                                                        <path d="M402.3 344.9l32-32c5-5 13.7-1.5 13.7 5.7V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h273.5c7.1 0 10.7 8.6 5.7 13.7l-32 32c-1.5 1.5-3.5 2.3-5.7 2.3H48v352h352V350.5c0-2.1 .8-4.1 2.3-5.6zm156.6-201.8L296.3 405.7l-90.4 10c-26.2 2.9-48.5-19.2-45.6-45.6l10-90.4L432.9 17.1c22.9-22.9 59.9-22.9 82.7 0l43.2 43.2c22.9 22.9 22.9 60 .1 82.8zM460.1 174L402 115.9 216.2 301.8l-7.3 65.3 65.3-7.3L460.1 174zm64.8-79.7l-43.2-43.2c-4.1-4.1-10.8-4.1-14.8 0L436 82l58.1 58.1 30.9-30.9c4-4.2 4-10.8-.1-14.9z"/>
                                                    </svg>
                                                    <!-- Optionally, you can add a small text next to the icon, or leave it out -->
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if(config('laravelusers.enableSearchUsers'))
                                    <tbody id="search_results"></tbody>
                                @endif
                            </table>

                            <div class="c-pagination">
                                <ul class="justify-center gap-2 d-flex align-items-center">
                                    {{-- Previous Button --}}
                                    @if ($users->onFirstPage())
                                        <li class="disabled"><a href="#"><i class="fas fa-chevron-left"></i></a></li>
                                    @else
                                        <li><a href="{{ $users->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                        <li class="{{ $page == $users->currentPage() ? 'active' : '' }}">
                                            <a href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    {{-- Next Button --}}
                                    @if ($users->hasMorePages())
                                        <li><a href="{{ $users->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
                                    @else
                                        <li class="disabled"><a href="#"><i class="fas fa-chevron-right"></i></a></li>
                                    @endif
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('laravelusers::modals.modal-delete')

@endsection

@section('template_scripts')
    @if ((count($users) > config('laravelusers.datatablesJsStartCount')) && config('laravelusers.enabledDatatablesJs'))
        @include('laravelusers::scripts.datatables')
    @endif
    @include('laravelusers::scripts.delete-modal-script')
    @include('laravelusers::scripts.save-modal-script')
    @if(config('laravelusers.tooltipsEnabled'))
        @include('laravelusers::scripts.tooltips')
    @endif
    @if(config('laravelusers.enableSearchUsers'))
        @include('laravelusers::scripts.search-users')
    @endif

@endsection
