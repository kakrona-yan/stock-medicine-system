<style>
    .thead-light tr th{
        padding: 0.3rem 0.75rem;
    }
</style>
<div class="row mb-2">
    <div class="col-12">
        <!-- Circle Buttons -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="staff-search" action="{{ route('setting.staff_to_customer') }}" method="GET" class="form form-horizontal form-search">
                    <div class="row">
                        <div class="col-12 col-md-10">
                            <div class="row">
                                <div class="col-6 col-md-4 mb-1">
                                    <div class="form-group">
                                        <input type="text" class="form-control d-inline-flex" name="name"  
                                            value="{{ old('name', $request->name) }}" placeholder="@lang('customer.list.name')"> 
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end mb-1">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-circle btn-primary"><i class="fa fa-search"></i> @lang('button.search')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive cus-table">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-light">
                            <tr>
                                <th style="width:30px;">ឈរ.</th>
                                <th>{{ __('staff.list.name') }}</th>
                                <th>{{ __('user.list.role') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $staffs as $staff)
                            <tr>
                                <td class="text-center align-top" rowspan="{{$staff->sales->count() > 0 ? 2 : 1}}">
                                    @if ($staff->sales->count() > 0)
                                        <a href="#staff_{{$staff->id}}" data-toggle="collapse" style="text-decoration: none !important;" 
                                            aria-expanded="true"><i class="fas fa-minus-circle"></i>
                                        </a>
                                    @endif
                                </td>
                                <td><b>{{ $staff->getFullnameAttribute() }}</b></td>
                                <td><i class="fas fa-user text-pink "></i> {{$staff->user->roleType()}}</td>
                            </tr>
                            @if ($staff->sales->count() > 0)
                                <tr>
                                    <td colspan="4" id="staff_{{$staff->id}}" class="collapse p-0 show">
                                        <table class="table mb-0 tabel-row-1">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{ __('customer.list.name') }}</th>
                                                    <th>{{ __('customer.list.phone') }}</th>
                                                    <th>{{ __('customer.list.address') }}</th>
                                                    <th class="text-center">{{ __('customer.list.map_link') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($staff->sale()->exists())
                                                   <tr>
                                                        <td style="width:250px;">{{$staff->sale->customer->customerFullName() }}</td>
                                                        <td style="width:300px;">
                                                            <div class="d-flex flex-row">
                                                                <i class="fas fa-phone-square-alt text-success my-1 mr-1"></i>
                                                            <div>
                                                                {{ $staff->sale->customer->phone1 }}<br/>
                                                                {{ $staff->sale->customer->phone2 ? $staff->sale->customer->phone2: '' }}
                                                            </div>
                                                            </div>
                                                        </td>
                                                        <td style="width:500px;">{{ $staff->sale->customer->address }}</td>
                                                        <td class="text-center">
                                                            @if($staff->sale->customer->map_address)
                                                                <span class="position-relative">
                                                                    <a href="{{$staff->sale->customer->map_address}}" target="_blank"><i class="fas fa-globe-africa"></i>
                                                                    <span class="spinner-grow spinner-grow-sm  text-success position-absolute" role="status"></span></a>
                                                                </span>
                                                            @endif
                                                            
                                                        </td>
                                                   </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
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