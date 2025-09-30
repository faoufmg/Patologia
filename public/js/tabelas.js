// Tabela Visualizações
$(document).ready(function () {

    // Clonar o cabeçalho para adicionar campos de busca
    $('#visualizar thead tr').clone(true).appendTo('#visualizar thead');
    $('#visualizar thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Buscar ' + title + '" />');

        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });

    var table = $('#visualizar').DataTable({
        scrollY: "400px",
        scrollX: true,
        responsive: true,
        paging: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },
        columnDefs: [{
            className: 'dt-body-center',
            render: function (data, type, row) {
                if (type === 'display') {
                    return '<div class="divScroll">' + data + '</div>';
                }
                return data;
            }
        }],
        initComplete: function () {
            this.api().columns().every(function () {
                var that = this;
                $('input', this.header()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        },
        order: [
            [0, 'asc']
        ],
        lengthChange: true,
        dom: 'Brtip',
        buttons: [],
        displayLength: 500,
    });

});

// Tabela aprovar_aluno.php
$(document).ready(function () {

    // Clonar o cabeçalho para adicionar campos de busca
    $('#aprovar_aluno thead tr').clone(true).appendTo('#aprovar_aluno thead');
    $('#aprovar_aluno thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Buscar ' + title + '" />');

        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });

    var table = $('#aprovar_aluno').DataTable({
        scrollY: "300px",
        scrollX: true,
        responsive: true,
        paging: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },
        initComplete: function () {
            this.api().columns().every(function () {
                var that = this;
                $('input', this.header()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        },
        order: [
            [0, 'asc']
        ],
        lengthChange: false,
        dom: 'Brtip',
        buttons: [],
        displayLength: 50,
    });

});

// Tabela Pesquisas
$(document).ready(function () {

    // Clonar o cabeçalho para adicionar campos de busca
    $('#pesquisa_tabela thead tr').clone(true).appendTo('#pesquisa_tabela thead');
    $('#pesquisa_tabela thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Buscar ' + title + '" />');

        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });

        // Adicionar evento de clique ao campo de busca
        $('input', this).on('click', function () {
            // Remover a classe de destaque de todas as linhas
            $('#pesquisa_tabela tbody tr').removeClass('highlighted');

            // Adicionar a classe de destaque à linha que contém o campo clicado
            $(this).closest('tr').addClass('highlighted');
        });
    });

    var table = $('#pesquisa_tabela').DataTable({
        scrollY: "400px",
        scrollX: true,
        responsive: true,
        paging: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },
        columnDefs: [{
            className: 'dt-body-center',
            render: function (data, type, row) {
                if (type === 'display') {
                    return '<div class="divScroll">' + data + '</div>';
                }
                return data;
            }
        }],
        initComplete: function () {
            this.api().columns().every(function () {
                var that = this;
                $('input', this.header()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        },
        order: [
            [0, 'asc']
        ],
        lengthChange: false,
        dom: 'Brtip', // Adiciona o botão ao layout da tabela
        buttons: [],
        displayLength: 500,
    });

});
