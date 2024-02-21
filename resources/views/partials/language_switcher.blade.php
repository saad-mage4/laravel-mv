<div class="d-flex align-items-center switcher-container">
    <select name="switcher" id="switcher" class="form-control">
        @foreach($available_locales as $locale_name => $available_locale)
        @if($available_locale === $current_locale)
        <option selected hidden value="{{$available_locale}}">{{$locale_name}}</option>
        @else
        <option value="{{$available_locale}}">{{$locale_name}}</option>
        @endif
        @endforeach
    </select>
</div>
<script>
    $(document).ready(function() {
        $(document).on('change', '#switcher', function(e) {
            e.preventDefault();
            let val = $(this).find('option:selected').val();
            $.ajax({
                url: `language/${val}`,
                success: function() {
                    $('.switcher-container').append(`<div id="toast-container" class="toast-top-right"><div class="toast toast-success" aria-live="assertive" style=""><div class="toast-message">Language has been updated!</div></div></div>`);
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }
            })
        });
    });
</script>