
{{-- jquery --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{--  Toastr  --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
{{-- chart --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

{{-- sidebar --}}
<script>
    $(document).ready(function() {
        const mainContent = $('#main-content');
        const sidebar = $('#sidebar');

        $('#sidebarCollapse').on('click', function() {
            sidebar.toggleClass('active-sidebar');
            mainContent.toggleClass('active-main');
            const icon = $(this).find('i');
            if (icon.hasClass('fa-angles-left')) {
                icon.removeClass('fa-angles-left').addClass('fa-angles-right');
                mainContent.css('margin-left', '80px');
            } else {
                icon.removeClass('fa-angles-right').addClass('fa-angles-left');
                mainContent.css('margin-left', '250px');
            }
        });
    });

    function mapDataToFields(data) {
        $.map(data, function(value, index) {
            var input = $('[name="' + index + '"]');
            if ($(input).length && $(input).attr('type') !== 'file') {
                if (($(input).attr('type') == 'radio' || $(input).attr('type') == 'checkbox') && value == $(
                        input).val())
                    $(input).prop('checked', true);
                else
                    $(input).val(value).change();
            }
        });
    }

    // When form submission is failed then old form data will recovered by this mapDataToField function.
    @if (session()->getOldInput())
        mapDataToFields('{{ json_encode(session()->getOldInput()) }}');
    @endif

    {{--  @if (Session::has('old_input_values'))
        mapDataToFields("{{ json_encode(Session::get('old_input_values')) }}");
        var forget = "{{ Session::forget('old_input_values') }}"
    @endif  --}}

        {{--  Toastr Messages  --}}
        @if (session('success'))
            toastr.success("{{ session('success') }}")
        @elseif(session('error'))
            toastr.error("{{ session('error') }}")
        @elseif($errors->all())
            toastr.error("{{ $errors->all()[0] }}")
        @endif


    $(document).on('click', ".app_logout_btn", function() {
        $("#app_logout_form").submit();
    })

    // For Integer number in input validation from jquery
    $(document).on("input", ".input_float_number", function(evt) {
        var self = $(this);
        self.val(self.val().replace(/[^0-9.]/g, ''));
        if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57))
        {
            evt.preventDefault();
        }
    });

    // Only Number Whole Number Input Validation in jquery
    $('.input_integer_number').keypress(function() {
        return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57;
    });
</script>


@yield('script')
