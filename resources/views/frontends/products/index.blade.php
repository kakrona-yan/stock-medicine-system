<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>RRPS-PHAMA | MEDECIN LIST</title>
    @include('backends.partials.head')
    <style>
        .display-5.font-weight-bold {
            width: 100px;
            height: 100px;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50px;
            color: #0dc6d3;
            position: absolute;
            right: 0px;
            z-index: 99;
            top: -10px;
        }
    </style>
</head>

<body class="list-product">
    <div class="container py-5">
        <!-- For demo purpose -->
        <div class="row text-center text-white mb-5">
          <div class="col-lg-8 mx-auto">
            <h1 class="display-4 font-weight-bold"><i class="fas fa-user-md"></i> MEDECIN LIST <i class="fas fa-user-md"></i></h1>
          </div>
        </div>
        <!-- End -->
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <p class="text-right mb-3"><a class="display-5 font-weight-bold" href="{{route('dashboard')}}"><i class="fas fa-home mr-1"></i> Home</a></p>
            <!-- List group-->
            <ul class="list-group shadow">
                <li class="list-group-item">
                    <form id="product-search" action="{{ route('product_rrps') }}" method="GET" class="form form-horizontal form-search form-inline mb-2">
                        <div class="d-md-flex align-items-center justify-content-between mt-1">
                        <div class="form-group mb-2">
                            <input type="text" class="form-control mr-1" id="title" 
                                name="title" value="{{ old('title', $request->title)}}"
                                placeholder="title"
                            >
                        </div>
                        <div class="form-group mb-2 select-group pr-md-1 pl-md-1 d-inline-flex" style="max-width: 280px;width: 100%;">
                            <select class="form-control w-100" id="category_id" name="category_id" >
                                <option value="">Please select</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ $id == $request->category_id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2 d-inline-flex">
                            <button type="submit" class="btn btn-circle btn-primary"><i class="fa fa-search mr-2"></i> @lang('button.search')</button>
                        </div>
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
                </li>
                @foreach ($products as $product)
                <li class="list-group-item">
                    <!-- Custom content-->
                    <div class="media align-items-lg-center flex-column flex-lg-row p-1">
                        <div class="media-body order-2 order-lg-1">
                        <h5 class="mt-0 font-weight-bold mb-2">
                            <i class="fas fa-capsules text-success"></i> {{$product->id}}. {{$product->title}}
                        </h5>
                        <p style="font-size: 12px;">
                            <i class="fas fa-bullhorn mr-1 text-blue-100"></i>
                            <span>{{$product->category ? $product->category->name : ''}}</span>
                        </p> 
                        <p class="font-italic text-muted mb-0 small">
                            {!! Str::limit(nl2br($product->description), 145) !!}
                        </p>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            
                            <div class="prince text-danger font-weight-bold">USA {{$product->price}}</div>
                            <ul class="list-inline small">
                                <li class="list-inline-item m-0"><i class="fa fa-star text-warning"></i></li>
                                <li class="list-inline-item m-0"><i class="fa fa-star text-warning"></i></li>
                                <li class="list-inline-item m-0"><i class="fa fa-star text-warning"></i></li>
                                <li class="list-inline-item m-0"><i class="fa fa-star text-warning"></i></li>
                                <li class="list-inline-item m-0"><i class="fa fa-star-o text-gray"></i></li>
                            </ul>
                        </div>
                      </div>
                      <img src="{{$product->thumbnail? asset(getUploadUrl($product->thumbnail, config('upload.product'))) : asset('images/no-thumbnail.jpg') }}" width="200" class="ml-lg-5 order-1 order-lg-2 mb-2" alt="{{$product->title}}">
                    </div>
                    <!-- End -->
                  </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </li>
            </ul>
            <!-- End -->
          </div>
        </div>
      </div>    
</body>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    (function( $ ){
        $("[name='category_id']").select2({
            allowClear: false
        });
        $("[name='limit']").select2({
            allowClear: false
        }).on('select2:select', function (e) {
            $('#product-search').submit();
        });
    })( jQuery );
</script>
</html>
