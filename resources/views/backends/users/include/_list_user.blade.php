<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="user-search" action="{{ route('user.index') }}" method="GET" class="form form-horizontal form-search form-inline mb-2">
                    <div class="form-group mb-2 mr-2">
                        <label for="name" class="mr-sm-2">{{ __('user.list.filter') }}:</label>
                        <input type="text" class="form-control mr-1" id="name" 
                            name="name" value="{{ old('name', $request->name)}}"
                            placeholder="@lang('user.list.name')"
                        >
                    </div>
                    {{-- <div class="form-group mb-2 mr-2">
                        <input type="email" class="form-control mr-1" id="email" 
                            name="email" value="{{ old('email', $request->email)}}"
                                placeholder="email"
                        >
                    </div> --}}
                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-circle btn-primary"><i class="fa fa-search"></i> @lang('button.search')</button>
                    </div>
                    <div class="form-group d-block w-100">
                        <div class="input-group mw-btn-125" style="width: 120px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-hand-pointer"></i></span>
                            </div>
                            <select class="form-control" name="limit" id="limit">
                                @for( $i=10; $i<=50; $i +=10 )
                                    <option value="{{$i}}" 
                                    {{ $request->limit == $i || config('pagination.limit') == $i ? 'selected' : ''}}
                                    >{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </form>
                <div class="table-responsive cus-table">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th class="text-center">{{__('user.list.thumbnail')}}</th>
                                <th>{{ __('user.list.name') }}</th>
                                {{-- <th>{{ __('user.list.email') }}</th> --}}
                                <th>{{ __('user.list.role') }}</th>
                                <th>Password</th>
                                <th class="text-center">{{ __('user.list.active') }}</th>
                                <th class="w-action text-center">{{__('user.list.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <div class="thumbnail-cicel">
                                            <img class="thumbnail" src="{{$user->thumbnail? getUploadUrl($user->thumbnail, config('upload.user')) : asset('images/no-avatar.jpg') }}" alt="{{$user->name}}" width="45"/>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$user->name}}</td>
                                {{-- <td>{{$user->email}}</td> --}}
                                <td><i class="fas fa-user text-pink "></i> {{$user->roleType()}}</td>
                                <td>{{$user->staff ? $user->staff->password : '123****'}}</td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input type="checkbox" data-toggle="toggle" data-onstyle="success" name="active"
                                            {{ $user->is_active == 1 ? 'checked' : '' }}
                                        > 
                                        <span class="slider"><span class="on">ON</span><span class="off">OFF</span>
                                        </span>
                                    </label>
                                </td>                           
                                <td>
                                    <div class="w-action">
                                    <a class="btn btn-sm btn-info btn-circle" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        data-original-title="{{__('button.show')}}"
                                        href="{{route('user.show', $user->id)}}"
                                    ><i class="far fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-warning btn-circle" 
                                        data-toggle="tooltip" 
                                        data-placement="top"
                                        data-original-title="{{__('button.edit')}}"
                                        href="{{route('user.edit', $user->id)}}"
                                    ><i class="far fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        id="btn-deleted"
                                        class="btn btn-sm btn-danger btn-circle"
                                        onclick="deletePopup(this)"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name}}"
                                        data-toggle="modal" data-target="#deleteuser"
                                        title="{{__('button.delete')}}"
                                        ><i class="fa fa-trash"></i>
                                    </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach 
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $users->appends(request()->query())->links() }}
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
<!--Modal delete user-->
<div class="modal fade" id="deleteuser">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">       
        <!-- Modal Header -->
        <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-trash"></i> {{__('user.confirm_delete')}}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div> 
        <!-- Modal body -->
        <div class="modal-body text-center">
            <div class="message">{{__('user.confirm_msg') }}</div>
            <div id="modal-name"></div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer d-flex justify-content-center">
            <form id="delete_user_form" action="{{route('user.destroy')}}" method="POST">
                @csrf
                <input type="hidden" type="form-control" name="user_id">
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
        $('input[name="user_id"]').val($(obj).attr("data-id"));
        $("#modal-name" ).html($(obj).attr("data-name"));
    }

    function clearData() {
        $('input[name="user_id"]').val('');
        $("#modal-name" ).html('');
    }
    
    (function( $ ){
        $("[name='limit']").select2({
            allowClear: false
        }).on('select2:select', function (e) {
            $('#user-search').submit();
        });
    })( jQuery );
    
</script>
@endpush