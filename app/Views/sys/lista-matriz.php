<!-- incluir os componentes modais antes do restante do documento -->
<?php echo view('components/matriz/modal-edit-matriz'); ?>
<?php echo view('components/matriz/modal-cad-matriz'); ?>    
<?php echo view('components/matriz/modal-deletar-matriz') ?>

<div class="page-header">
    <h3 class="page-title">GERENCIAR MATRIZES</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('/sys/home') ?>">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lista Matrizes</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <!-- mostrar ALERT em caso de erro -->
                <?php if (session()->has('erros')) : ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session('erros') as $erro) : ?>
                                <li> <i class="mdi mdi-alert-circle"></i><?= esc($erro) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- botões da parte de cima -->

                <div class="row">
                    <div class="col-12 mb-4">
                        <button type="button" class="btn btn-primary btn-icon-text" data-bs-toggle="modal" data-bs-target="#modal-cad-matriz"><i class="fa fa-plus-circle btn-icon-prepend"></i> Incluir Matriz</button>
                        <button type="button" class="btn btn-info btn-icon-text"><i class="fa fa-upload btn-icon-prepend"></i> Importar Matrizes do SUAP</a>
                    </div>
                </div>

                <!-- início da tabela -->

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mb-4" id="listagem-matriz">
                                
                                <!-- cabeçalho da tabela -->
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nome</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>

                                <!-- corpo da tabela -->

                                <tbody>

                                    <?php if (!empty($matrizes)): //verifica se a tabela tem dados ?>
                                        <?php foreach ($matrizes as $matriz): //loop para percorrer todos os professores retornados do bd ?>
                                            <tr>
                                                <td><?php echo esc($matriz['id']); ?></td>
                                                <td><?php echo esc($matriz['nome']); ?></td>

                                                <!-- essa celula monta os botões de ação que acionam modais -->

                                                <td>
                                                    <div class="d-flex">
                                                        <!-- o elemento <span> é apenas para mostrar o tooltip -->
                                                        <span data-bs-toggle="tooltip" data-placement="top" title="Atualizar dados do matriz">
                                                            <!-- botão com estilo, ativação do modal, e dados formados para transmitir ao modal -->
                                                            <button 
                                                                type="button"
                                                                class="justify-content-center align-items-center d-flex btn btn-inverse-success button-trans-success btn-icon me-1"                                                                
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-edit-matriz"
                                                                data-id="<?php echo esc($matriz['id']); ?>"
                                                                data-nome="<?php echo esc($matriz['nome']);?>"
                                                            >
                                                                <!-- icone do botão -->
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        </span>
                                                        
                                                        <!-- abaixo são repetidos os códigos acima para replicar os outros 2 botões -->

                                                        <span data-bs-toggle="tooltip" data-placement="top" title="Excluir matriz">
                                                            <button 
                                                                type="button"
                                                                class="justify-content-center align-items-center d-flex btn btn-inverse-danger button-trans-danger btn-icon me-1"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modal-deletar-matriz"
                                                                data-id="<?php echo esc($matriz['id']); ?>"
                                                                data-nome="<?php echo esc($matriz['nome']);?>"
                                                            >
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- caso não haja matriz cadastrado -->
                                        <tr>
                                            <td colspan="4">Nenhum matriz cadastrado.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- legendas no canto inferior da tela -->
                <div class="row">
                    <div class="col-12 mt-4">
                        <p class="card-description text-end"><i class="fa fa-edit text-success me-2"></i>Editar</p>
                        <p class="card-description text-end"><i class="fa fa-trash text-danger me-2"></i>Excluir</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- daqui pra baixo é javascript -->
    <script>
        //Para carregar a tradução dos itens da DataTable
        const dataTableLangUrl = "<?php echo base_url('assets/js/traducao-dataTable/pt_br.json'); ?>";

        //essa linha abaixo é para detectar que o documento foi completamente carregado e executar o código após isso
        $(document).ready(function(){

            //Verificar se tem curso para então "transformar" a tabela em DataTable
            <?php if (!empty($matrizes)): ?>
                
                //Cria a DataTable
                $("#listagem-matriz").DataTable({
                    
                    //Define as entradas de quantidade de linhas visíveis na tabela
                    aLengthMenu: [
                        [5, 15, 30, -1],
                        [5, 15, 30, "Todos"],
                    ],

                    //Define as questões de tradução/idioma
                    language: {
                            search: "Pesquisar:",
                            url: dataTableLangUrl,
                        },

                    //Ativa ordenação
                    ordering: true,
                    //Diz que a coluna 1 (segunda/nome) deve ser o padrão de ordenação ao carregar a tabela
                    order: [ [1, 'asc'] ],
                    //Desativa a ordenação por ações
                    columns: [null, null, { orderable: false }]
                });


                $('#modal-edit-matriz').on('show.bs.modal', function (event) {
                    // Obter o DOM do botão que ativou o modal
                    var button = $(event.relatedTarget); 
                    
                    // Extrair as informações dos atributos data-* 
                    var id = button.data('id');
                    var nome = button.data('nome');
                    
                    // Formar o modal com os dados preenchidos
                    var modal = $(this);
                    modal.find('#edit-id').val(id);
                    modal.find('#edit-nome').val(nome);
                    
                });

                $('#modal-deletar-matriz').on('show.bs.modal', function (event) {
                    // Button that triggered the modal
                    var button = $(event.relatedTarget); 

                    // Extract info from data-* attributes
                    var nome = button.data('nome');
                    var id = button.data('id');

                    var modal = $(this);
                    modal.find('#deletar-id').val(id);
                    modal.find('#deletar-nome').text(nome);
                });

                //Ativa os tooltips dos botões
                $('[data-bs-toggle="tooltip"]').tooltip();
                
            <?php endif; ?>

            // Exibe mensagem de sucesso se o flashdata estiver com 'sucesso'
            <?php if (session()->getFlashdata('sucesso')): ?>
                $.toast({
                    heading: 'Sucesso',
                    text: '<?php echo session()->getFlashdata('sucesso'); ?>',
                    showHideTransition: 'slide',
                    icon: 'success',
                    loaderBg: '#f96868',
                    position: 'top-center'
                });
            <?php endif; ?>
        });


    </script>