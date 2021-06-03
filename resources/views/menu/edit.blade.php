@extends('phobrv::layout.app')

@section('header')
<a href="{{route('menu.index')}}"  class="btn btn-default float-left">
	<i class="fa fa-backward"></i> @lang('Back')
</a>
{!! $data['boxTranslate'] ?? '' !!}
@endsection

@section('content')

<div class="row">
	<div class="col-sm-3">
		<ul class="nav nav-pills nav-stacked">
			@include('phobrv::input.navTab',['id'=>'mainInfo','title'=>'Main Info','active'=>'active'])
			@switch($data['post']->subtype)
			@case('home')
			@include('phobrv::menu.home.nav')
			@break
			@case('category')
			@include('phobrv::input.navTab',['id'=>'category','title'=>'Cấu hình page'])
			@break
			@case('article')
			@include('phobrv::input.navTab',['id'=>'article','title'=>'Cấu hình page'])
			@break
			@case('contact')
			@include('phobrv::input.navTab',['id'=>'contact','title'=>'Cấu hình page'])
			@break
			@case('product')
			@include('phobrv::input.navTab',['id'=>'product','title'=>'Cấu hình page'])
			@break
			@endswitch

			@switch($data['post']->subtype)
			@case('category')
			@include('phobrv::input.navTab',['id'=>'sidebar','title'=>'Cấu hình box sidebar'])
			@break
			@endswitch
		</ul>
	</div>
	<div class="col-sm-9">
		<div class="tab-content">
			@include('phobrv::input.tabContent',['id'=>'mainInfo','view'=>'phobrv::menu.mainInfo','active'=>'active'])
			@switch($data['post']->subtype)
			@case('home')
			@include('phobrv::menu.home.tabpanel')
			@break
			@case('category')
			@include('phobrv::input.tabContent',['id'=>'category','view'=>'phobrv::menu.config.category'])
			@break
			@case('article')
			@include('phobrv::input.tabContent',['id'=>'article','view'=>'phobrv::menu.config.article'])
			@break
			@case('contact')
			@include('phobrv::input.tabContent',['id'=>'contact','view'=>'phobrv::menu.config.contact'])
			@break
			@case('product')
			@include('phobrv::input.tabContent',['id'=>'product','view'=>'phobrv::menu.config.product'])
			@break
			@endswitch

			@switch($data['post']->subtype)
			@case('category')
			@include('phobrv::input.tabContent',['id'=>'sidebar','view'=>'phobrv::menu.config.sidebar'])
			@break
			@endswitch
		</div>
	</div>

</div>

@endsection

@section('styles')
<style type="text/css">
	.select2{
		width: 100%!important;
	}
</style>

@endsection

@section('scripts')
<script type="text/javascript">
	window.onload = function() {
		if($('textarea[name="content"]').length > 0)
			CKEDITOR.replace('content', options);
	};
	$('.MenuForm').submit(function(e){
		e.preventDefault();

		var data = {};
		var getData = $(this).serializeArray();
		for(var i=0;i<getData.length;i++){
			if(getData[i]['name']!='_token')
				data[getData[i]['name']] = getData[i]['value'];
		}
		var editors = $(this).find('textarea');
		for(var j=0;j<editors.length;j++)
		{
			var name = editors[j].name;
			if(CKEDITOR.instances[name])
				data[name] = CKEDITOR.instances[name].getData();
		}
		$.ajax({
			headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			url: '{{URL::route("menu.updateMetaAPI")}}',
			type: 'POST',
			data: {data: data},
			success: function(output){
				// console.log(output);
				alertOutput(output['msg'],output['message'])
			}
		});
	})

</script>
@endsection