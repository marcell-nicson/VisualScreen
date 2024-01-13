<!-- arquivos.create.blade.php -->

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-200 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-200 p-4 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('arquivos.store') }}" method="POST" enctype="multipart/form-data" id="arquivoForm">
                        @csrf

                        <div class="mb-4">
                            <label for="cliente_id" class="block text-sm font-semibold text-gray-600">Cliente:</label>
                            <select name="cliente_id" id="cliente_id" class="border-gray-300 rounded-md p-2 w-full">
                                <!-- Opções do select para clientes -->
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tipo" class="block text-sm font-semibold text-gray-600">Tipo de Arquivo:</label>
                            <select name="tipo" id="tipo" class=" border-gray-300 rounded-md p-2 w-full">
                                <option value="video">Video</option>
                                <option value="foto">Foto</option>
                                <option value="link">Link</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="caminho_do_arquivo" class="block text-sm font-semibold text-gray-600">Caminho do Arquivo:</label>
                            <div id="caminho_do_arquivo_container">
                                <!-- Este é o local onde o input será dinamicamente adicionado pelo script -->
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="DataHoraInicio" class="block text-sm font-semibold text-gray-600">Data e Hora de Início:</label>
                            <input type="datetime-local" name="DataHoraInicio" id="DataHoraInicio" class="form-input border-gray-300 rounded-md p-2 w-full">
                        </div>

                        <div class="mb-4">
                            <label for="DataHoraFim" class="block text-sm font-semibold text-gray-600">Data e Hora de Fim:</label>
                            <input type="datetime-local" name="DataHoraFim" id="DataHoraFim" class="form-input border-gray-300 rounded-md p-2 w-full">
                        </div>

                        <div class="mb-4">
                            <label for="Status" class="block text-sm font-semibold text-gray-600">Status:</label>
                            <select name="Status" id="Status" class="border-gray-300 rounded-md p-2 w-full">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                                <option value="pausado">Pausado</option>
                            </select>
                        </div>

                        <button type="submit" class="mb-4 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 rounded" style="float: right;">Salvar</button>
                        <a href="{{ route('clientes.index') }}" class=" bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 rounded" style="float: right;">
                            Voltar
                        </a>
                    </form>
                    
                    <!-- Adicione este trecho de código dentro do seu formulário para exibir mensagens de erro -->
                    @if ($errors->any())
                        <div class="bg-red-200 p-4 mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <script>
                        // Por padrão, definir o tipo como 'video'
                        document.getElementById('tipo').value = 'video';

                        function adicionarCampoArquivo() {
                            var tipoValue = document.getElementById('tipo').value;
                            var caminhoDoArquivoContainer = document.getElementById('caminho_do_arquivo_container');

                            // Limpar container
                            caminhoDoArquivoContainer.innerHTML = '';

                            // Adicionar input de arquivo se o tipo for 'video' ou 'foto'
                            if (tipoValue === 'video' || tipoValue === 'foto') {
                                var inputFile = document.createElement('input');
                                inputFile.type = 'file';
                                inputFile.name = 'caminho_do_arquivo';
                                inputFile.id = 'caminho_do_arquivo';
                                inputFile.className = 'form-input border-gray-300 rounded-md p-2 w-full';
                                caminhoDoArquivoContainer.appendChild(inputFile);
                            }

                            // Adicionar input de texto se o tipo for 'link'
                            else if (tipoValue === 'link') {
                                var inputText = document.createElement('input');
                                inputText.type = 'text';
                                inputText.name = 'caminho_do_arquivo';
                                inputText.id = 'caminho_do_arquivo';
                                inputText.className = 'form-input border-gray-300 rounded-md p-2 w-full';
                                caminhoDoArquivoContainer.appendChild(inputText);
                            }
                        }

                        // Chamar a função ao carregar a página
                        adicionarCampoArquivo();

                        // Adicionar evento de alteração para chamar a função
                        document.getElementById('tipo').addEventListener('change', adicionarCampoArquivo);
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
