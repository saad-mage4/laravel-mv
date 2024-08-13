@extends('admin.master_layout')
@section('title')
<title>Private Sub Category</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Private Sub Category</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.private_category.index') }}">Private Category</a></div>
              <div class="breadcrumb-item">Private Sub Category</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.private_sub_category.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th>{{__('admin.SN')}}</th>
                                    <th>{{__('admin.Sub Category')}}</th>
                                    <th>{{__('admin.Slug')}}</th>
                                    <th>{{__('admin.Category')}}</th>
                                    <th>{{__('admin.Status')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($subCategories as $index => $subCategory)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $subCategory->name }}</td>
                                        <td>{{ $subCategory->slug }}</td>
                                        <td>{{ $subCategory->category->name ?? "No data" }}</td>
                                        <td>
                                            @if($subCategory->status == 1)
                                            <a href="javascript:;" onclick="changeProductSubCategoryStatus({{ $subCategory->id }})">
                                                <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                            </a>

                                            @else
                                            <a href="javascript:;" onclick="changeProductSubCategoryStatus({{ $subCategory->id }})">
                                                <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                            </a>

                                            @endif
                                        </td>
                                        <td>
                                        <a href="{{ route('admin.private_sub_category.edit',$subCategory->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>

                                        {{-- @php
                                            $isPopular = false;
                                            if($pupoularCategory->sub_category_id_one == $subCategory->id){
                                                $isPopular = true;
                                            }else if($pupoularCategory->sub_category_id_two == $subCategory->id){
                                                $isPopular = true;
                                            }else if($pupoularCategory->sub_category_id_three == $subCategory->id){
                                                $isPopular = true;
                                            }else if($pupoularCategory->sub_category_id_four == $subCategory->id){
                                                $isPopular = true;
                                            }

                                            $isThreeCat = false;
                                            if($threeColCategory->sub_category_id_one == $subCategory->id){
                                                $isThreeCat = true;
                                            }else if($threeColCategory->sub_category_id_two == $subCategory->id){
                                                $isThreeCat = true;
                                            }else if($threeColCategory->sub_category_id_three == $subCategory->id){
                                                $isThreeCat = true;
                                            }
                                        @endphp --}}

                                        {{-- @if ($subCategory->childCategories->count() == 0 && $subCategory->products->count() == 0 && !$isThreeCat && !$isPopular)
                                            <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $subCategory->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#canNotDeleteModal" class="btn btn-danger btn-sm" disabled><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        @endif --}}

                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $subCategory->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>

                                        </td>
                                    </tr>
                                  @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                      <div class="modal-body">
                          {{__('admin.You can not delete this sub category. Because there are one or more child categories or popular sub categories or home page three column categories or products has been created in this sub category.')}}
                      </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                </div>
            </div>
        </div>
    </div>

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/private_sub_category/") }}'+"/"+id)
    }
    function changeProductSubCategoryStatus(id){
        var isDemo = "{{ env('APP_VERSION') }}"
        if(isDemo == 0){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/private_sub_category-status/')}}"+"/"+id,
            success:function(response){
                toastr.success(response)
            },
            error:function(err){}
        })
    }
</script>
@endsection
