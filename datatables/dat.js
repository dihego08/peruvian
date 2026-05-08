$(document).ready(function() {
    $.extend(true, $.fn.dataTable.defaults, {
        "buttons": [{
            "extend": 'copyHtml5',
            "text": 'Copiar',
            "className": 'btn btn-adiasoft btn-sm',
            exportOptions: {
                columns: ':visible'
            }
        }, {
            "extend": 'excelHtml5',
            "text": 'Excel',
            "className": 'btn btn-adiasoft btn-sm',
            exportOptions: {
                columns: ':visible'
            }
        }, {
            "extend": 'print',
            "text": 'IMPRIMIR',
            "className": 'btn btn-adiasoft btn-sm',
            'postfixButtons': ['colvisRestore'],
            exportOptions: {
                columns: ':visible'
            }
        }, {
            "extend": 'csvHtml5',
            "text": 'CSV',
            "className": 'btn btn-adiasoft btn-sm',
            "fieldSeparator": '|',
            "fieldBoundary": "",
            "extension": ".txt",
            exportOptions: {
                columns: ':visible'
            }
        }, 'colvis'],
        dom: '<"row"<"col-12 col-sm-12 col-md-6"l><"col-12 col-sm-12 col-md-6"f>>rt<"top"B><"col-12"i>p',
        "lengthMenu": [
            [10, 15, 20, -1],
            [10, 15, 20, "All"]
        ],
        "language": {
            "url": "../coopliv/includes/datatables/Spanish.json"
        },
        "order": [
            [0, "desc"]
        ],
        initComplete: function() {
            this.api().columns().every(function() {
                var column = this;
                var select = $('<select class="form-control form-control-sm"><option value=""></option></select>').appendTo($(column.footer()).empty()).on('change', function() {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                });
                column.data().unique().sort().each(function(d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>')
                });
            });
        }
    });
    $('.sorting').after().click(function() {
        table = $('.datatable').DataTable();
        table.columns.adjust().draw()
    });
});
$(window).load(function() {
    $('.sorting').after().click(function() {
        table = $('.datatable').DataTable();
        table.columns.adjust().draw()
    });
});