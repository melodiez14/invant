$(function () {
    $('#users-table').DataTable({processing: true, serverSide: true, ajax: '/api/customers'});
});