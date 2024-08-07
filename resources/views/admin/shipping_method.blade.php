@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Shipping')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Shipping')}}</h1>
            <div class="section-header-breadcrumb">
                {{-- onclick="changeProductTaxStatus({{ $shipping->id }})" --}}
              <a href="javascript:;" class="mr-3">
              <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
              </a>
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Shipping')}}</div>
            </div>
          </div>

                        @if ($admin_shipping_enabled == 1)
                        <div class="section-body mt-3">
                        <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
                        <div class="row mt-4">
                            <div class="col">
                                <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{__('admin.SN')}}</th>
                                                <th>{{__('admin.Title')}}</th>
                                                <th>{{__('admin.Fee')}}</th>
                                                <th>{{__('admin.Status')}}</th>
                                                <th>{{__('admin.Action')}}</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shippings as $index => $shipping)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $shipping->title }}</td>
                                                    <td>
                                                        @if ($shipping->is_free == 1)
                                                        {{ $setting->currency_icon }}{{ $shipping->minimum_order }} Up Condition
                                                        @else
                                                        {{ $setting->currency_icon }}{{ $shipping->fee }}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if($shipping->status == 1)
                                                        <a href="javascript:;" onclick="changeProductTaxStatus({{ $shipping->id }})">
                                                            <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                                        </a>

                                                        @else
                                                        <a href="javascript:;" onclick="changeProductTaxStatus({{ $shipping->id }})">
                                                            <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                                        </a>

                                                        @endif
                                                    </td>
                                                    <td>
                                                    <a href="{{ route('admin.shipping.edit',$shipping->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                    @if($shipping->is_free != 1)
                                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $shipping->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                    @endif
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
                        @endif

        </section>
      </div>

<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/shipping/") }}'+"/"+id)
    }
    function changeProductTaxStatus(id){
        var isDemo = "{{ env('APP_VERSION') }}"
        if(isDemo == 0){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/shipping-status/')}}"+"/"+id,
            success:function(response){
                toastr.success(response)
            },
            error:function(err){
                console.log(err);

            }
        })
    }
</script>
@endsection
