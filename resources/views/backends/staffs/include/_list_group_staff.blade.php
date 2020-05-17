<div class="row mb-2">
    <div class="col-12 col-md-7">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <a href="#createGroupStaff" 
                    class="btn btn-circle btn-primary mb-2"
                    data-toggle="modal" data-target="#createGroupStaff"
                    data-original-title="{{__('button.add_new')}}"
                ><i class="fas fa-plus-circle"></i> {{__('button.add_new')}}</a>
                <div class="table-responsive cus-table">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>  
                                <th>{{ __('staff.group_staff') }}</th>
                                @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                <th class="text-center" style="width: 130px;">{{ __('staff.list.action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $groupStaffs as $groupStaff)
                            <tr>
                                <td>{{ $groupStaff->name }}</td>
                                @if(Auth::user()->isRoleAdmin() || Auth::user()->isRoleEditor())
                                <td class="text-center" style="width: 130px;">
                                    <a class="btn btn-circle btn-sm btn-info btn-circle"
                                        href="#showGroupStaff"
                                        onclick="showGroupStaff(this)"
                                        data-id="{{ $groupStaff->id }}"
                                        data-name="{{ $groupStaff->name}}"
                                        data-toggle="modal" data-target="#showGroupStaff"
                                    ><i class="far fa-eye"></i>
                                    </a>
                                    <a class="btn btn-circle btn-sm btn-warning btn-circle"
                                        href="#updateGroupStaff"
                                        onclick="updateGroupStaff(this)"
                                        data-id="{{ $groupStaff->id }}"
                                        data-name="{{ $groupStaff->name}}"
                                        data-toggle="modal" data-target="#updateGroupStaff"
                                    ><i class="far fa-edit"></i>
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $groupStaffs->appends(request()->query())->links() }}
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
<!--Modal create staff-->
<div class="modal fade" id="createGroupStaff">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">       
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('staff.confirm_create')}}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div> 
            <!-- Modal body -->
            <form id="create_group_staff_form" action="{{route('staff.group.store')}}" method="POST">
                @csrf
                <div class="modal-body text-center form-inline">
                    <div class="form-group w-100">
                        <label for="name" class="mr-2">{{__('staff.group_staff')}}:</label>
                        <input type="text" class="form-control" 
                            placeholder="{{__('staff.group_staff')}}"
                            name="name"
                            value="{{ old('name', $request->name) }}"
                        >
                        <span class="text-danger">
                            <p id="name_error">{{ $errors->first('name') }}</p>
                        </span>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-circle btn-primary">{{__('button.ok')}}</button>
                        <button type="button" class="btn btn-circle btn-danger" data-dismiss="modal"
                            onclick="clearData()"
                        >{{__('button.close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div><!--/createGroupStaff-->
<!--Modal show staff-->
<div class="modal fade" id="showGroupStaff">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">       
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('staff.show_confirm')}}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div> 
            <!-- Modal body -->
            <div class="modal-body text-center form-inline">
                <div class="form-group w-100">
                    <label for="name" class="mr-2">{{__('staff.group_staff')}}:</label>
                    <input type="text" class="form-control" name="name" readonly>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-circle btn-danger pl-5 pr-5" data-dismiss="modal"
                        onclick="clearData()"
                    >{{__('button.close')}}</button>
            </div>
        </div>
    </div>
</div><!--/showGroupStaff-->
<div class="modal fade" id="updateGroupStaff">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">       
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('staff.confirm_update')}}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div> 
            <!-- Modal body -->
            <form id="update_group_staff_form" action="{{route('staff.group.update')}}" method="POST">
                @csrf
                <div class="modal-body text-center form-inline">
                    <div class="form-group w-100">
                        <label for="id" class="mr-2">{{__('staff.group_staff')}}:</label>
                        <input type="hidden" class="form-control" name="group_staff_id">
                        <input type="text" class="form-control" 
                            placeholder="{{__('staff.group_staff')}}"
                            name="name"
                            value="{{ old('name', $request->name) }}"
                        >
                        <span class="text-danger">
                            <p id="name_error">{{ $errors->first('name') }}</p>
                        </span>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-circle btn-primary">{{__('button.ok')}}</button>
                        <button type="button" class="btn btn-circle btn-danger" data-dismiss="modal"
                            onclick="clearData()"
                        >{{__('button.close')}}</button>
                </div>
            </form>
        </div>
    </div>
</div><!--/updateGroupStaff-->
@push('footer-script')
<script>
    $(function(){
        const formGroupStaff = $('#create_group_staff_form');
        console.log(formUpdateGroupStaff);
        formGroupStaff.submit(function(e) {
            e.preventDefault();
            $.ajax({
                url     : formGroupStaff.attr('action'),
                type    : formGroupStaff.attr('method'),
                data    : formGroupStaff.serialize(),
                dataType: 'json',
                success : function (json) {
                    location.href = '{{ route("staff.index") }}';
                },
                error: function(json){
                    $.each(json.responseJSON.errors, function (key, value) {
                        $(`#${key}_error`).text(value);
                    });
                    $("html, body").animate({ scrollTop: 0 }, 500);
                }
            });
        });
    });

    $(function(){
        const formUpdateGroupStaff = $('#update_group_staff_form');
        formUpdateGroupStaff.submit(function(e) {
            e.preventDefault();
            $.ajax({
                url     : formUpdateGroupStaff.attr('action'),
                type    : formUpdateGroupStaff.attr('method'),
                data    : formUpdateGroupStaff.serialize(),
                dataType: 'json',
                success : function (json) {
                    location.href = '{{ route("staff.index") }}';
                },
                error: function(json){
                    $.each(json.responseJSON.errors, function (key, value) {
                        $(`#${key}_error`).text(value);
                    });
                    $("html, body").animate({ scrollTop: 0 }, 500);
                }
            });
        });
    });
    function showGroupStaff(obj) {
        $('.modal input[name="id"]').val($(obj).attr("data-id"));
        $('.modal input[name="name"]').val($(obj).attr("data-name"));
    }

    function updateGroupStaff(obj) {
        $('.modal #update_group_staff_form input[name="group_staff_id"]').val($(obj).attr("data-id"));
        $('.modal #update_group_staff_form input[name="name"]').val($(obj).attr("data-name"));
    }

    function clearData() {
        $('.modal input[name="group_staff_id"]').val('');
        $('.modal input[name="name"]').val('');
    }
</script>
@endpush