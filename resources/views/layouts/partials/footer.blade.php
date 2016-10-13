<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jasny-bootstrap.min.js"></script>
<script src="/jquery-ui/jquery-ui.min.js"></script>
<script>
    $(function() {
        $("input[name=term]").autocomplete({
            source: "{{ route("contacts.autocomplete") }}",
            minLength: 3,
            select: function(event, ui) {
                $(this).val(ui.item.value);
            }
        });
    });
</script>

@yield('form-script')
</body>
</html>
