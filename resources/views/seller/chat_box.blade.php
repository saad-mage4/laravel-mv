<style>
.spinner-border {
    display: inline-block;
    width: 1.5rem;
    height: 1.5rem;
    vertical-align: text-bottom;
    border: .25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    -webkit-animation: .75s linear infinite spinner-border;
    animation: .75s linear infinite spinner-border;
    color: #fff;
}

#buttonContent i {
    display: inline;
    color: #ffffff
}
</style>
<div class="card-header">
    <h4>{{__('user.Chat with')}} {{ $customer->name }}</h4>
  </div>
  <div class="card-body chat-content">
    @foreach ($messages as $msg_index => $message)
        @if ($message->send_seller == $auth->id)
            <div class="chat-item chat-right" style="">
                <img src="{{ $auth->image ? asset($auth->image) : asset($defaultProfile->image) }}">
                <div class="chat-details">
                    <div class="chat-text">{{ $message->message }}</div>
                    <div class="chat-time">{{ $message->created_at->format('d F, Y, H:i A') }}</div>
                </div>
            </div>
        @else
            <div class="chat-item chat-left" style="">
                <img src="{{ $customer->image ? asset($customer->image) : asset($defaultProfile->image) }}">
                <div class="chat-details">
                    <div class="chat-text">{{ $message->message }}</div>
                    <div class="chat-time">{{ $message->created_at->format('d F, Y, H:i A') }}</div>
                </div>
            </div>
        @endif
    @endforeach
  </div>
  <div class="card-footer chat-form">
    <form id="chat-form">
      <input autocomplete="off" type="text" class="form-control" id="customer_message" placeholder="{{__('user.Type message')}}">
      <input type="hidden" id="customer_id" name="customer_id" value="{{ $customer->id }}">
      <button type="submit" class="btn btn-primary">
      <span id="buttonContent">
        <i class="fas fa-paper-plane"></i>
        <span id="messageLoader" class="spinner-border" style="display:none;"></span>
    </span>
      </button>
    </form>
  </div>


<script>

    (function($) {
    "use strict";
    $(document).ready(function () {
        scrollToBottomFunc()
        $("#chat-form").on("submit", function(event){
            event.preventDefault()
            var isDemo = "{{ env('APP_VERSION') }}"
            if(isDemo == 0){
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }

            let customer_message = $("#customer_message").val();
            let customer_id = $("#customer_id").val();
            $("#customer_message").val('');
            if(customer_message){
            $("#messageLoader").show();
             $("#buttonContent i").hide();
                $.ajax({
                    type:"get",
                    data : {message: customer_message , customer_id : customer_id},
                    url: "{{ route('seller.send-message') }}",
                    success:function(response){
                        $(".chat-content").html(response);
                        scrollToBottomFunc()
                    },
                    error:function(err){
                        console.error(err);
                    },
                    complete: function() {
                $("#messageLoader").hide();
                 $("#buttonContent i").show();
            }
                })
            }

        })
    });
  })(jQuery);

    function scrollToBottomFunc() {
        $('.chat-content').animate({
            scrollTop: $('.chat-content').get(0).scrollHeight
        }, 50);
    }
</script>

