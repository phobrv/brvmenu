<div class="box box-primary">
	<div class="box-header font16">
		<a href="#" onclick="save()"  class="btn btn-primary float-left">
			<i class="fa fa-floppy-o"></i> @lang('Save & Close')
		</a>
		<a href="#" onclick="update()"  class="btn btn-warning float-left">
			<i class="fa fa-wrench"></i> @lang('Update')
		</a>
	</div>
	<form class="form-horizontal" id="formSubmit" method="post" action="{{route('menu.update',['menu'=>$data['post']->id])}}">
		@csrf
		@isset($data['post']) @method('put') @endif
		<input type="hidden" name="typeSubmit" id="typeSubmit" value="">
		<div class="box-body">
			@isset($data['post'])
			@include('phobrv::input.inputSelect',['label'=>'Show','key'=>'status','default'=>'1','array'=>['0'=>'No','1'=>'Yes']])
			@endif
			@if( isset($data['post']->slug ))
			@if($data['post']->subtype == 'link')
			<input type="hidden" name="slug" value="{{$data['post']->slug}}">
			@else
			@include('phobrv::input.inputText',['label'=>'Url','key'=>'slug'])
			@endif
			@endif
			@include('phobrv::input.inputText',['label'=>'Name','key'=>'title','required'=>true])

			@if(isset($data['post']['childs']) && count($data['post']['childs']) == 0 || !isset($data['post']['childs']) )
			@include('phobrv::input.inputSelect',['label'=>'Parent','key'=>'parent','array'=>$data['arrayMenuParent']])
			@endif

			@if($data['post']->lang == $langMain)
			@include('phobrv::input.inputSelect',['label'=>'Template','key'=>'subtype','array'=>config('option.templateMenu')])
			@else
			<i>Menu không phải ngôn ngữ chính sẽ kế thừa các thuộc tính này từ menu gốc</i>
			<input type="hidden" name="subtype" value="{{ $data['post']->subtype }}">
			@include('phobrv::input.text',['label'=>'Template','value'=>config('option.templateMenu')[$data['post']->subtype]])
			@endif
			@if($data['post']->subtype != 'link')
			@include('phobrv::input.label',['label'=>'Seo Meta'])
			@include('phobrv::input.inputImage',['label'=>'Thumb Meta','key'=>'thumb','width'=>'200px'])
			@include('phobrv::input.inputText',['label'=>'Meta Title','key'=>'meta_title','type'=>'meta'])
			@include('phobrv::input.inputText',['label'=>'Meta Description','key'=>'meta_description','type'=>'meta'])
			@include('phobrv::input.inputText',['label'=>'Meta Keywords','key'=>'meta_keywords','type'=>'meta'])
			@else
			@include('phobrv::input.inputText',['label'=>'Link','key'=>'excerpt','required'=>true])
			@endif
		</div>
		<button type="submit" id="btnSubmit" style="display: none;">Submit</button>
	</form>
</div>
