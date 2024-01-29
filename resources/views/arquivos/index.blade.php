<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">   
                    @if(session('success'))
                        <div class="bg-green-200 p-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="bg-red-200 p-4 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    <strong class=""> {{ $cliente->nome ?? '' }}</strong>
                    <div class="flex items-center justify-center mt-2">
                        <a href="{{ route('arquivos.create', ['cliente' => $cliente->id]) }}" class="bg-blue-500 hover:bg-blue-500 text-blue-700 hover:text-white font-bold py-2 px-4 rounded">
                            Adicionar Arquivos
                        </a>
                        
                    </div>                 
                    @isset($arquivos)
                        @if($arquivos->isEmpty())                        
                            <div class="flex items-center mt-2">
                                <p class="mr-2">Esse cliente ainda não tem arquivos!</p>                             
                            </div>                        
                        
                        @else                               
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                                @foreach($arquivos as $arquivo)
                                    <div class="bg-gray-100 p-4 rounded-md">
                                        {{-- Miniatura (substitua 'caminho_do_arquivo' pelo nome do campo que armazena o caminho do arquivo) --}}

                                        <div style="width: 250px; height: 150px;">
                                            @if ($arquivo->tipo == 'foto')                            
                                                <img style="width: 100%; height: 100%;" src="{{ asset('fotos/' . $arquivo->caminho_do_arquivo) }}" alt="foto" > 
                                            @elseif ($arquivo->tipo == 'video')
                                                <video controls style="width: 100%; height: 100%;">
                                                    <source src="{{ asset('fotos/' . $arquivo->caminho_do_arquivo) }}" type="video/mp4">
                                                </video>
                                            @elseif ($arquivo->tipo == 'link') 
                                                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $arquivo->caminho_do_arquivo }}?mute=1" frameborder="0" allowfullscreen></iframe>
                                            @endif
                                        </div>
                                        

                                        <div class="mt-2">                                           
                                            <p><strong>Cliente ID:</strong> {{ $arquivo->cliente_id }}</p>
                                            <p><strong>Video ID:</strong> {{ $arquivo->id }}</p>
                                            <p><strong>Tipo:</strong> {{ $arquivo->tipo }}</p>                                       
                                            {{-- <p><strong>Início:</strong> {{ \Carbon\Carbon::parse($arquivo->agendamentos->DataHoraInicio)->format('d/m/Y H:i:s') ?? '' }}</p>
                                            <p><strong>Fim:</strong> {{ \Carbon\Carbon::parse($arquivo->agendamentos->DataHoraFim)->format('d/m/Y H:i:s') ?? '' }}</p> --}}
                                          
                                            
                                            <form method="POST" action="{{ route('arquivos.update', ['arquivo' => $arquivo->id]) }}" id="updateForm">
                                                @csrf
                                                @method('PUT')
                                                
                                                <strong>Status:</strong>  
                                                <select name="status" id="status" onchange="this.form.submit()">                                                
                                                    <option  >{{ $arquivo->agendamentos->Status ?? ''}}</option>
                                                    <option value="inativo" {{ $arquivo->agendamentos->Status == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                                    <option value="pausado" {{ $arquivo->agendamentos->Status == 'pausado' ? 'selected' : '' }}>Pausado</option>
                                                    <option value="ativo" {{ $arquivo->agendamentos->Status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                                </select>
                                        
                                                <label for="DataHoraInicio" class="block text-sm ">Início:</label>                                               
                                                <input type="datetime-local" name="DataHoraInicio" id="DataHoraInicio" class="form-input rounded-md" value="{{ $arquivo->agendamentos->DataHoraInicio ?? '' }}" onchange="this.form.submit()" >
                                        
                                                <label for="DataHoraFim" class="block text-sm ">Fim:</label>
                                                <input type="datetime-local" name="DataHoraFim" id="DataHoraFim" class="form-input rounded-md" value="{{ $arquivo->agendamentos->DataHoraFim ?? '' }}" onchange="this.form.submit()">
                                            </form> 
                                            
                                               
                                            <td title="Excluir Arquivo" >
                                                <a href="#" onclick="confirmDelete({{ $arquivo->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-red-700 w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </a>
                                            </td> 
                                        </div>                                 
                                    </div>


                                    <div id="confirmDeleteModal_{{ $arquivo->id }}" class="fixed inset-0 z-10 w-screen overflow-y-auto hidden">
                                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                            </svg>
                                                        </div>
                                                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Confirmação de Exclusão</h3>
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-500">Tem certeza que deseja excluir este Arquivo?</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                                    <form method="POST" action="{{ route('arquivos.destroy', ['arquivo' => $arquivo->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                                            Excluir
                                                        </button>
                                                    </form>
                                                
                                                    <button onclick="closeModal({{ $arquivo->id }})" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    @if ($errors->any())
                                        <div class="bg-red-200 p-4 mb-4">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <script>
                                function confirmDelete($arquivoId) {
                                    document.getElementById(`confirmDeleteModal_${$arquivoId}`).classList.remove('hidden');
                                }
        
                                function closeModal($arquivoId) {
                                    document.getElementById(`confirmDeleteModal_${$arquivoId}`).classList.add('hidden');
                                }
                            </script>
                            <script>
                                function atualizarFormulario() {
                                    document.getElementById('updateForm').submit();
                                }
                            </script>
                            @endif
                        @else
                            <a href="#">Não existe nenhum arquivo</a>
                        @endisset
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
