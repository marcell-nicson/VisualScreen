<x-app-layout>

    {{-- <x-slot name="header">
 
    </x-slot> --}}
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 p-2 rounded relative" role="alert">
                            <strong class="font-bold">Erro!</strong>
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif
                   
                    <h1 class="mb-4">Novo Cliente</h1>

                    <form method="POST" action="{{ route('clientes.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="nome" class="block text-sm font-medium text-gray-700">Nome:</label>
                            <input type="text" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="nome" name="nome">
                        </div>

                        <div class="mb-4">
                            <label for="data_nascimento" class="block text-sm font-medium text-gray-700">Data de Nascimento:</label>
                            <input type="date" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="data_nascimento" name="data_nascimento">
                        </div>

                        <div class="mb-4">
                            <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone:</label>
                            <input type="text" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="telefone" name="telefone">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                            <input type="email" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="email" name="email">
                        </div>
                        
                        <button type="submit" class="mb-4 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 rounded" style="float: right;">Salvar</button>
                        <a href="{{ route('clientes.index') }}" class=" bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 rounded" style="float: right;">
                            Voltar
                        </a>
                          
                    </form>                    
                </div>
            </div>
    </div>

   

</x-app-layout>