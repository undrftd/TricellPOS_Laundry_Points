@foreach($products->chunk(4) as $chunk)
  @if($chunk->count() == 4) 
    <div class="row pad">
      @foreach($chunk as $product)
      @if($product->product_qty <= $lowstock->low_stock && $product->product_qty > 0 && $product->product_id > 24)
        <div class="col-lg-3 ">
          <div class="btn btn-sm btn-info full pos-button <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) { echo "product"; } ?> unhealthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
            {{str_limit($product->product_name,15)}}
            <span class="low_stock"> <br> Low Stock </span>
          </div>
        </div>
      @elseif($product->product_qty == 0)
      <div class="col-lg-3">
        <div class="btn btn-sm btn-info full pos-button disabled <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) {echo "product"; } ?> unhealthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
          {{str_limit($product->product_name,15)}}
          <span> <br> No Stock </span>
        </div>
      </div>
      @else
      <div class="col-lg-3">
        <div class="btn btn-sm btn-info full pos-button <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) {echo "product"; } ?> healthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
          {{str_limit($product->product_name,15)}}
        </div>
      </div>
      @endif
      @endforeach
    </div>
  @elseif($chunk->count() == 3) 
    <div class="row pad">
      @foreach($chunk as $product)
      @if($product->product_qty <= $lowstock->low_stock && $product->product_qty > 0 && $product->product_id > 24)
        <div class="col-lg-4 ">
          <div class="btn btn-sm btn-info full pos-button <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) { echo "product"; } ?> unhealthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
            {{str_limit($product->product_name,15)}}
            <span class="low_stock"> <br> Low Stock </span>
          </div>
        </div>
      @elseif($product->product_qty == 0)
      <div class="col-lg-4">
        <div class="btn btn-sm btn-info full pos-button disabled <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) {echo "product"; } ?> unhealthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
          {{str_limit($product->product_name,15)}}
          <span> <br> No Stock </span>
        </div>
      </div>
      @else
      <div class="col-lg-4">
        <div class="btn btn-sm btn-info full pos-button <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) {echo "product"; } ?> healthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
          {{str_limit($product->product_name,15)}}
        </div>
      </div>
      @endif
      @endforeach
    </div>
  @elseif($chunk->count() == 2) 
    <div class="row pad">
      @foreach($chunk as $product)
      @if($product->product_qty <= $lowstock->low_stock && $product->product_qty > 0 && $product->product_id > 24)
        <div class="col-lg-6 ">
          <div class="btn btn-sm btn-info full pos-button <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) { echo "product"; } ?> unhealthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
            {{str_limit($product->product_name,15)}}
            <span class="low_stock"> <br> Low Stock </span>
          </div>
        </div>
      @elseif($product->product_qty == 0)
      <div class="col-lg-6">
        <div class="btn btn-sm btn-info full pos-button disabled <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) {echo "product"; } ?> unhealthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
          {{str_limit($product->product_name,15)}}
          <span> <br> No Stock </span>
        </div>
      </div>
      @else
      <div class="col-lg-6">
        <div class="btn btn-sm btn-info full pos-button <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) {echo "product"; } ?> healthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
          {{str_limit($product->product_name,15)}}
        </div>
      </div>
      @endif
      @endforeach
    </div>
  @elseif($chunk->count() == 1) 
    <div class="row pad">
      @foreach($chunk as $product)
      @if($product->product_qty <= $lowstock->low_stock && $product->product_qty > 0 && $product->product_id > 24)
        <div class="col-lg-12 ">
          <div class="btn btn-sm btn-info full pos-button <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) { echo "product"; } ?> unhealthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
            {{str_limit($product->product_name,15)}}
            <span class="low_stock"> <br> Low Stock </span>
          </div>
        </div>
      @elseif($product->product_qty == 0)
      <div class="col-lg-12">
        <div class="btn btn-sm btn-info full pos-button disabled <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) {echo "product"; } ?> unhealthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
          {{str_limit($product->product_name,15)}}
          <span> <br> No Stock </span>
        </div>
      </div>
      @else
      <div class="col-lg-12">
        <div class="btn btn-sm btn-info full pos-button <?php if($product->product_id <=24 && $product->product_id >=13) { echo "dryer"; }else if($product->product_id > 24) {echo "product"; } ?> healthy-button" data-id="{{$product->product_id}}" data-description="{{$product->product_name}}" data-price="{{$product->price}}" data-memprice="{{$product->member_price}}" data-qty="{{$product->product_qty}}">
          {{str_limit($product->product_name,15)}}
        </div>
      </div>
      @endif
      @endforeach
    </div>
  @endif
@endforeach 

<br>
{{$products->links()}}