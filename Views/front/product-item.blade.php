<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{asset('packages/foostart/package-product/css/product-styles.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('packages/foostart/package-product/css/bootstrap.min.css')}}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                <a href="#" class="image-popup">
                    <div class="prod-img-bg">
                        <img class="img-fluid" src="{{URL::asset( $item['product_image']) }}" alt="{{$item['product_name']}}">
                        <div class="img-sale">
                            <p>Sale: {{ $item['product_price_sale'] }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="img">
                                <img class="img-fluid" src="{{URL::asset( $item['product_image']) }}" alt="{{$item['product_name']}}">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                <h2 class="ftco-section">{{ $item['product_name'] }}</h2>
                <span class="price"> Price: {{ $item['product_price'] }} VNĐ</span>
                <span>Price-root: <del>{{ $item['product_price_root'] }}VNĐ</del></span>
                <div class="content">
                    <span class="bg-title"> @if (strlen($item['product_description']) > 200) <?php echo substr($item['product_description'], 0, 2603) ?> @endif
                    </span>
                </div>
                <div class="overview">
                    <p>Overview: <span>{{ $item['product_overview'] }}</span></p>
                </div>
                <div class="row">
                    <div class="input-group d-flex col-xl-6 col-lg-6 col-md-12 col-12">
                        <div class="number">
                            <input class="form-control" type="number" id="myNumber" value="1" min="1" max="100">
                        </div>
                    </div>
                    <div class="w-100"></div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                        <p class="price-available">100 piece available</p>
                    </div>
                </div>
                <p class="box-button">
                    <a href="#" class="btn">Add to Cart</a>
                    <a href="#" class="btn">Buy now</a>
                </p>
            </div>
        </div>
    </div>
    <div class="container">
        <form class="blox-form" action="/action_page.php">
            <label for="subject">Subject</label>
            <textarea id="subject" name="subject" placeholder="Comment something.." style="height:200px"></textarea>
            <input type="submit" value="Submit">
        </form>
    </div>

    <script src="{{asset('packages/foostart/package-product/js/jquery-3.2.1.slim.min.js')}}"></script>
    <script src="{{asset('packages/foostart/package-product/js/popper.min.js')}}"></script>
    <script src="{{asset('packages/foostart/package-product/js/bootstrap.min.js')}}"></script>
</body>

</html>