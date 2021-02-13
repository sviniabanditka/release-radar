$('[data-toggle="tab"],[id="logout"]').tooltip({
    trigger: 'hover',
    placement: 'top',
    animate: true,
    delay: 1000,
    container: 'body'
});

var hash = location.hash.replace(/^#/, '');
if (hash) {
    $('.nav-tabs a[href="#' + hash + '"]').tab('show');
}
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
})
