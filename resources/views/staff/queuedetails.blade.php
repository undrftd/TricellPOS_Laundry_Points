<div class="container-fluid">
	<div class="row">
		@if(count($washers) != 0)
			@foreach($washers as $washer)
				<div class="col mx-auto service">
					<h6><b>{{substr($washer->product->product_name, strpos($washer->product->product_name, " ") + 1)}}
						 </b></h6>
					<h6 class="qty_switch">{{ $washer->quantity }}</h6>
					<center><i class="material-icons laundry_icon_button2">local_laundry_service</i></center>
					<center><label class="switch switch_type1" role="switch">
						<input type="checkbox" class="switch__toggle" id="switch{{$washer->id}}" data-id="{{$washer->id}}" data-product-id="{{$washer->product_id}}" data-sales-id = "{{ $washer->sales_id }}" data-switch = "{{ $washer->product->switch }}" data-used="{{$washer->used}}" data-isused ="{{$washer->isUsed}}"
						<?php
						if($washer->switch != 0) 
						{ 
							echo 'checked'; 
						}  
						
						if($washer->product->used_by != 0)
						{
							if($washer->sales_id != $washer->product->used_by) 
							{ 
								echo 'disabled'; 
							} 	
						}
						else
						{
							if(($washer->quantity == $washer->used) && $washer->switch == 0)
							{
								echo 'disabled'; 
							}
						}
						?>>
						<span class="switch__label"></span>
					</label></center>
				</div>
			@endforeach
		@endif
	</div>
	@if(count($dryers) != 0 && count($washers) != 0 )
		<hr class="divider">
	@endif
	<div class="row">
		@if(count($dryers) != 0)
			@foreach($dryers as $dryer)
				<div class="col mx-auto service">
					<h6><b>{{substr($dryer->product->product_name, strpos($dryer->product->product_name, " ") + 1)}} </b></h6>
					<h6 class="qty_switch">{{ $dryer->quantity }}</h6>
					<center><i class="material-icons laundry_icon_button2">toys</i></center>
					<center><label class="switch switch_type1" role="switch">
						<input type="checkbox" class="switch__toggle" id="switch{{$dryer->id}}" data-id="{{$dryer->id}}" data-product-id="{{$dryer->product_id}}" data-sales-id = "{{ $dryer->sales_id }}" data-switch = "{{ $dryer->product->switch }}" data-used="{{$dryer->used}}" data-isused ="{{$dryer->isUsed}}"
						<?php
						if($dryer->switch != 0) 
						{ 
							echo 'checked'; 
						}  
						
						if($dryer->product->used_by != 0)
						{
							if($dryer->sales_id != $dryer->product->used_by) 
							{ 
								echo 'disabled'; 
							} 	
						}
						else
						{
							if(($dryer->quantity == $dryer->used) && $dryer->switch == 0)
							{
								echo 'disabled'; 
							}
						}
						?>>
						<span class="switch__label"></span>
					</label></center>
				</div>
			@endforeach
		@endif
	</div>
</div>