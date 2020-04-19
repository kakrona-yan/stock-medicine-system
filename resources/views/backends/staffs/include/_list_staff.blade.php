<div class="row mb-2">
        <div class="col-12">
            <!-- Circle Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="staff-search" action="{{ route('user.index') }}" method="GET" class="form form-horizontal form-search">
                        <div class="row">
                            <div class="col-12 col-md-10">
                                <div class="row">
                                    <div class="col-6 col-md-4 mb-1">
                                        <div class="form-group">
                                            <label class="font-weight-bold">@lang('staff.list.name')</label>
                                            <input type="text" class="form-control d-inline-flex" name="name"  value="{{ old('name', $request->name) }}"> 
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-1">
                                        <div class="form-group">
                                            <label class="font-weight-bold">@lang('staff.list.phone')</label>
                                            <input type="text" class="form-control" name="phone_number"  value="{{ old('phone_number', $request->phone_number) }}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 mb-1">
                                        <div class="form-group">
                                            <label class="font-weight-bold">@lang('staff.list.email')</label>
                                            <input type="text" class="form-control" name="email"  value="{{ old('email', $request->email) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end mb-1">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-circle btn-primary"><i class="fa fa-search"></i> @lang('button.search')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive cus-table">
                        <table class="table table-striped table-bordered">
                            <thead class="bg-primary text-light">
                                <tr>
                                    <th class="text-center w-10">{{__('staff.form.thumbnail')}}</th>
                                    <th>{{ __('staff.list.name') }}</th>
                                    <th>{{ __('staff.list.email') }}</th>
                                    <th>{{ __('staff.list.password') }}</th>
                                    <th>{{ __('staff.list.phone') }}</th>
                                    <th>{{ __('staff.list.address') }}</th>
                                    @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                    <th >{{ __('staff.list.action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $staffs as $staff)
                                <tr>
                                    <td>
                                        <div class="text-center d-flex justify-content-center align-items-center">
                                            <div class="crop-image d-flex justify-content-center align-items-center">
                                                <img class="thumbnail" src="{{$staff->thumbnail? getUploadUrl($staff->thumbnail, config('upload.staff')) : asset('images/no-avatar.jpg') }}" />
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $staff->getFullnameAttribute() }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td>{{ $staff->password }}</td>
                                    <td>
                                        <div class="d-flex flex-row">
                                            <i class="fas fa-phone-square-alt text-success my-1 mr-1"></i>
                                        <div>
                                            {{ $staff->phone1 }}<br/>
                                            {{ $staff->phone2 ? $staff->phone2: '' }}
                                        </div>
                                        </div>
                                    </td>
                                    <td>{{ str_limit($staff->address, 30) }}</td>
                                    @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                    <td>
                                        <a class="btn btn-circle btn-sm btn-info btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.show')}}"
                                            href="{{route('staff.show', $staff->id)}}"
                                        ><i class="far fa-eye"></i>
                                        </a>
                                        <a class="btn btn-circle btn-sm btn-warning btn-circle" 
                                            data-toggle="tooltip" 
                                            data-placement="top"
                                            data-original-title="{{__('button.edit')}}"
                                            href="{{route('staff.edit', $staff->id)}}"
                                        ><i class="far fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            id="btn-deleted"
                                            class="btn btn-circle btn-sm btn-danger btn-circle"
                                            onclick="deletePopup(this)"
                                            data-id="{{ $staff->id }}"
                                            data-name="{{ $staff->name}}"
                                            data-toggle="modal" data-target="#deletestaff"
                                            title="{{__('button.delete')}}"
                                            ><i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $staffs->appends(request()->query())->links() }}
                    </div>
                    @if( Session::has('flash_danger') )
                        <p class="alert text-center {{ Session::get('alert-class', 'alert-danger') }}">
                            <span class="spinner-border spinner-border-sm text-darktext-danger align-middle"></span> {{ Session::get('flash_danger') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div><!--/row-->
    <!--Modal delete staff-->
    <div class="modal fade" id="deletestaff">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">       
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('staff.confirm_delete')}}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div> 
            <!-- Modal body -->
            <div class="modal-body text-center">
                <div class="message">{{__('staff.confirm_msg') }}</div>
                <div id="modal-name"></div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer d-flex justify-content-center">
                <form id="delete_staff_form" action="{{route('staff.destroy')}}" method="POST">
                    @csrf
                    <input type="hidden" type="form-control" name="staff_id">
                    <button type="submit" class="btn btn-circle btn-primary">{{__('button.ok')}}</button>
                    <button type="button" class="btn btn-circle btn-danger" data-dismiss="modal"
                        onclick="clearData()"
                    >{{__('button.close')}}</button>
                </form>
            </div>
        </div>
        </div>
    </div>
    @push('footer-script')
    <script>
        function deletePopup(obj) {
            $('input[name="staff_id"]').val($(obj).attr("data-id"));
            $("#modal-name" ).html($(obj).attr("data-name"));
        }
    
        function clearData() {
            $('input[name="staff_id"]').val('');
            $("#modal-name" ).html('');
        }
    </script>
    @endpush