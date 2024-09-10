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
<div class="wsus__chat_area">
    <div class="wsus__chat_area_header">
      <h2>{{__('user.Chat with')}} {{ $seller->name }}</h2>
    </div>
    <div class="wsus__chat_area_body">
        @foreach ($messages as $msg_index => $message)
            @if($message->send_customer == $auth->id)
                <div class="wsus__chat_single single_chat_2">
                    <div class="wsus__chat_single_img">
                    <img src="{{ $auth->image ? asset($auth->image) : asset($defaultProfile->image) }}" alt="user" class="img-fluid">
                    </div>
                    <div class="wsus__chat_single_text">
                    <p>{{ $message->message }}</p>
                    <span>{{ $message->created_at->format('d F, Y, H:i A') }}</span>
                    </div>
                </div>
            @else
                <div class="wsus__chat_single">
                    <div class="wsus__chat_single_img">
                    <img src="{{ $seller->image ? asset($seller->image) : asset($defaultProfile->image) }}" alt="user" class="img-fluid">
                    </div>
                    <div class="wsus__chat_single_text">
                    <p>{{ $message->message }}</p>
                    <span>{{ $message->created_at->format('d F, Y, H:i A') }}</span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <div class="wsus__chat_area_footer">
      <form id="customerToSellerMsgForm">
        <input type="text" placeholder="{{__('user.Type Message')}}" id="seller_message" autocomplete="off">
        <input type="hidden" name="seller_id" id="seller_id" value="{{ $seller->id }}">
        {{-- <input type="hidden" name="seller_type" id="seller_type" value="{{ $seller->seller_type }}"> --}}
                <button type="submit">
                    <span id="buttonContent">
        <i class="fas fa-paper-plane"></i>
        <span id="messageLoader" class="spinner-border" style="display:none;"></span>
    </span>
                </button>
      </form>
    </div>
</div>

<script>

    (function($) {
    "use strict";
    $(document).ready(function () {
        scrollToBottomFunc()
        $("#customerToSellerMsgForm").on("submit", function(event){
            event.preventDefault();
            var isDemo = "{{ env('APP_VERSION') }}"
            if(isDemo == 0){
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }

            let seller_msg = $("#seller_message").val();
            let seller_id = $("#seller_id").val();
            // let seller_type = $("#seller_type").val();
            $("#seller_message").val('');
            if(seller_msg){
            $("#messageLoader").show();
             $("#buttonContent i").hide();
                $.ajax({
                    type:"get",
                    data : {
                        message: seller_msg ,
                        seller_id : seller_id,
                        // seller_type: seller_type ?? null
                    },
                    url: "{{ route('user.send-message') }}",
                    success:function(response){
                        $(".wsus__chat_area_body").html(response)
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
        $('.wsus__chat_area_body').animate({
            scrollTop: $('.wsus__chat_area_body').get(0).scrollHeight
        }, 50);
    }
</script>
