@extends('admin.master_layout')
@section('title')
<title>Private Child Category</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Edit Private Child Category</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.private_category.index') }}">Private Category</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.private_sub_category.index') }}">Private Sub Category</a></div>
              <div class="breadcrumb-item">Edit Private Child Category</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.private_child_category.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Private Child Category</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.private_child_category.update',$childCategory->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="form-group col-12">
                                    <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">{{__('admin.Select Category')}}</option>
                                        @foreach ($categories as $category)
                                            <option {{ $childCategory->private_category_id  == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- {{dd($subCategories)}} --}}
                                <div class="form-group col-12">
                                    <label>{{__('admin.Sub Category')}} <span class="text-danger">*</span></label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">{{__('admin.Select Sub Category')}}</option>
                                        @foreach ($subCategories as $subCategory)
                                        <option {{  $childCategory->private_sub_category_id  == $subCategory->id  ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Child Category Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ $childCategory->name }}">
                                </div>
                                <div class="form-group col-12">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ $childCategory->slug }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option {{ $childCategory->status == 1 ? 'selected': '' }} value="1">{{__('admin.Active')}}</option>
                                        <option {{ $childCategory->status == 0 ? 'selected': '' }} value="0">{{__('admin.InActive')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $("#name").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })

            // if('{{ $childCategory->private_category_id }}'){
            //     $(this).die();
            //         $.ajax({
            //             type:"get",
            //             url:"{{url('/admin/private_subcategory-by-category/')}}"+"/"+categoryId,
            //             success:function(response){
            //                 $("#sub_category").html(response.subCategories);

            //             },
            //             error:function(err){

            //             }
            //         })
            //     }


            $("#category").on("change",function(){
                var categoryId = $("#category").val();
                if(categoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/admin/private_subcategory-by-category/')}}"+"/"+categoryId,
                        success:function(response){
                            $("#sub_category").html(response.subCategories);
                        },
                        error:function(err){
                               console.log(err);
                        }
                    })
                }
            })
        });
    })(jQuery);

    function convertToSlug(Text)
        {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-');
        }
</script>
@endsection
