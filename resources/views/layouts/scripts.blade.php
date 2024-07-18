<!-- Moment.js -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Date Range Picker JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- Initialize Date Range Picker -->
{{-- <script>
$(document).ready(function() {
    $('input[name="daterange"]').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        startDate: '{{ $tgl1 }}',
        endDate: '{{ $tgl2 }}'
    });
});
</script> --}}
<script type="text/javascript">
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
    </script>
    