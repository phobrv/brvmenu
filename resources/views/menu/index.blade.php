@extends('phobrv::layout.app')

@section('header')
<ul>
	<li>
		<a href="{{route('menugroup.index')}}"  class="btn btn-default float-left">
			<i class="fa fa-backward"></i> @lang('Back')
		</a>
	</li>
	<li>
		{{ Form::open(array('route'=>'menu.updateUserSelect','method'=>'post')) }}
		<table class="form" width="100%" border="0" cellspacing="1" cellpadding="1">
			<tbody>
				<tr>
					<td style="text-align:center; padding-right: 10px;">
						<div class="form-group">
							{{ Form::select('select',$arrayMenu,(isset($data['select']) ? $data['select'] : '0'),array('id'=>'choose','class'=>'form-control')) }}
						</div>
					</td>
					<td>
						<div class="form-group">
							<button id="btnSubmitFilter" type="submit" class="btn btn-primary ">@lang('Filter')</button>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		{{Form::close()}}
	</li>
</ul>
@endsection

@section('content')
<div class="row">
	<div class="col-md-5">
		@isset($data['term'])
		<form id="formSubmit" method="post" action="{{isset($data['post']) ? route('menu.update',['menu'=>$data['post']->id]) : route('menu.store')}}">
			<div class="box box-primary">
				<div class="box-header font16">
					Create/Edit quick
				</div>
				<div class="box-body">
					<input type="hidden" name="type" value="menu">
					<input type="hidden" name="lang" value="{{ $data['lang'] ?? 'vi' }}">
					<input type="hidden" name="term_id" value="{{$data['term']->id}}">
					@csrf
					<div class="form-group">
						{{Form::text('title', old('title',isset($data['post']) ? $data['post']->title :'' ),['class'=>'form-control','placeholder'=>'Name menu','required'=>'required'])}}
						@if ($errors->has('title'))
						<span class="invalid-feed" role="alert">
							<strong>{{ $errors->first('title') }}</strong>
						</span>
						@endif
					</div>
					@if(isset($data['post']['childs']) && count($data['post']['childs']) == 0 || !isset($data['post']['childs']) )
					<label>Menu parent</label>
					<div class="form-group">
						{{Form::select('parent', $data['arrayMenuParent'] ,(isset($data['post']->parent) ? $data['post']->parent : '0'),array('class'=>'form-control'))}}
						@if ($errors->has('parent'))
						<span class="invalid-feed" role="alert">
							<strong>{{ $errors->first('parent') }}</strong>
						</span>
						@endif
					</div>
					@endif
					<label>Choose template menu</label>
					<div class="form-group">
						{{Form::select('subtype', config('option.templateMenu'),(isset($data['post']->subtype) ? $data['post']->subtype : '0'),array('class'=>'form-control'))}}
						@if ($errors->has('subtype'))
						<span class="invalid-feed" role="alert">
							<strong>{{ $errors->first('subtype') }}</strong>
						</span>
						@endif
					</div>
				</div>
				<div class="box-footer">
					<button class="btn btn-primary pull-right">{{$data['submit_label']}}</button>
				</div>
			</div>
		</form>
		@endif
	</div>
	<div class="col-md-7">
		<div class="box box-primary">
			<div class="box-body">
				<form action="{{ route('menu.updateOrder') }}" method="post">
					@csrf
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>{{__('Name')}}</th>
								<th class="text-center">{{__('Type')}}</th>
								<th class="text-center">{{__('Status')}}</th>
								<th class="text-center">{{__('Lang')}}</th>
								<th class="text-center"> <button type="subit" class="btn btn-primary">Submit Order</button> </th>
								<th class="text-center">{{__('Action')}}</th>
							</tr>
						</thead>
						<tbody>
							@isset($data['menus'])
							@foreach($data['menus'] as  $r)
							<tr>
								<td>{{$loop->index + 1}}</td>
								<td>
									<a href="{{ route('level1',['slug'=>$r->slug]) }}">
										{{$r->title}}
									</a>
								</td>
								<td align="center">
									@isset(config('option.templateMenu')[$r->subtype])
									{{ config('option.templateMenu')[$r->subtype] }}
									@else
									{{$r->subtype}}
									@endif
								</td>
								<td align="center">
									@if(isset($r->status) && $r->status == 1 )
									<i style="color: green;" class="fa fa-check-circle-o" aria-hidden="true"></i>
									@else
									<i style="color: red;" class="fa fa-times-circle-o" aria-hidden="true"></i>
									@endif
								</td>
								<td align="center">
									{!! $r->langButtons ?? '' !!}
								</td>
								<td >
									<input type="hidden" name="id[]" value="{{ $r->id }}">
									<input type="number" class="order_input" name="order[]" value="{{ $r->order }}">
								</td>
								<td align="center">

									<a href="{{route('menu.edit',array('menu'=>$r->id))}}"><i class="fa fa-edit" title="Sửa"></i></a>
									@if(count($r->childs) == 0)
									&nbsp;&nbsp;&nbsp;
									<a style="color: red" href="#" onclick="destroy('{{ route('menu.destroy',['menu'=>$r->id]) }}')"><i class="fa fa-times" title="Sửa"></i></a>
									@endif
								</td>
							</tr>
							@if($r->childs)
							@foreach($r->childs as $c)
							<tr>
								<td align="center">{{$loop->index + 1}}</td>
								<td style="padding-left: 30px;">
									<a href="{{ route('level1',['slug'=>$c->slug]) }}">
										{{$c->title}}
									</a>
								</td>
								<td align="center">
									@isset(config('option.templateMenu')[$c->subtype])
									{{ config('option.templateMenu')[$c->subtype] }}
									@else
									{{$c->subtype}}
									@endif
								</td>
								<td align="center">
									@if(isset($c->status) && $c->status == 1 )
									<i style="color: green;" class="fa fa-check-circle-o" aria-hidden="true"></i>
									@else
									<i style="color: red;" class="fa fa-times-circle-o" aria-hidden="true"></i>
									@endif
								</td>
								<td align="center">
									{!! $c->langButtons ?? '' !!}
								</td>
								<td style="padding-left: 40px;">
									<input type="hidden" name="id[]" value="{{ $c->id }}">
									<input type="number" class="order_input" name="order[]" value="{{ $c->order }}">
								</td>
								<td align="center">
									<a href="{{route('menu.edit',array('menu'=>$c->id))}}"><i class="fa fa-edit" title="Sửa"></i></a>
									&nbsp;&nbsp;&nbsp;
									<a style="color: red" href="#" onclick="destroy('{{ route('menu.destroy',array('menu'=>$c->id)) }}')"><i class="fa fa-times" title="Sửa"></i></a>
								</td>
							</tr>
							@endforeach
							@endif
							@endforeach
							@endif
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>


@endsection

@section('styles')
<style type="text/css">
.order_input{
	width: 60px;
	padding:  2px;
	text-align: center;
}
</style>
@endsection

@section('scripts')
<script type="text/javascript">
	function destroy(url){
		console.log(url)
		var anwser =  confirm("Bạn muốn menu item này?");
		if(anwser){
			event.preventDefault();
			$.ajax({
				headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				method: "delete",
				url: url,
			}).done(function(output) {
				location.reload()
			}).fail(function(jqXHR, textStatus, errorThrown) {
				location.reload()
			});
		}
	}
</script>
@endsection