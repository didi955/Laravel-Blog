@push('other-scripts')
<script src="{{ asset('build/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.markdown',
        plugins: 'lists searchreplace wordcount autosave',
        promotion: false,
        branding: false,
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | searchreplace | wordcount | restoredraft | forecolor | backcolor',
    });
</script>
@endpush

