<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{asset('packages/foostart/package-product/css/product-styles.css')}}" rel="stylesheet">
    <link href="{{asset('packages/foostart/package-product/css/bootstrap.min.css')}}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Search Widget -->
                <div class="card my-4">
                    <h5 class="card-header">Search</h5>
                    <form class="card-body" action="/search" method="GET" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for..." name="q">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary" type="submit">Go!</button>
                            </span>
                        </div>
                    </form>
                </div>
                <!-- <div class="block-search">
                    <input class="form-control" type="text" id="myInput" placeholder="Search for names.."><i class="fa fa-search"></i>
                </div> -->
            </div>
            <div class="col-md-12">
                <div class="row">
                    @foreach ($items as $item)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="card mb-3">
                            <img class="card-img" src="{{URL::asset($item->product_image)}}" alt="{{$item->product_name}}">
                            <div class="card-img-overlay d-flex justify-content-end" style="display: none !important;">
                                <h4 class="text-secondary">-{{$item->product_price_sale}}</h4>
                            </div>
                            <div class="card-body">
                                <a class="detail-a" href="{!! URL::route('products.detailt', [   'id' => $item->id,
                                    '_token' => csrf_token()
                                    ])
                                !!}">
                                    <h4 class="card-title"> @if (strlen($item->product_name) > 10) <?php echo substr($item->product_name, 0, 15) . '...'  ?> @endif </h4>
                                </a>
                                <p class="card-text">
                                    @if (strlen($item->product_description) > 77) <?php echo substr($item->product_description, 0, 80) . '...'  ?> @endif
                                </p>
                                <div class="buy d-flex justify-content-between align-items-center">
                                    <div class="mt-4 text-success">{{number_format($item->product_price, 3, '.', ' ') }} VNĐ</div>
                                    <div class="mt-4 text-secondary"><del>{{number_format($item->product_price_root, 3, '.', ' ') }} VNĐ</del></div>
                                    <a href="{!! URL::route('products.detailt', [   'id' => $item->id,
                                        '_token' => csrf_token()
                                        ])
                                    !!}" class="btn btn-danger mt-3">Detailt</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>