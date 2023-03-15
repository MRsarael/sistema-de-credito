@extends('app')

@section('content')
    {{-- <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h6>
            <p id="description-form">Formulário de cadastro</p>
        </h6>
    </div>
    <hr/> --}}
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="row">
            <div class="col-md-6">
                <h5><p id="description-list">Alunos cadastrados</p></h5>
            </div>
            <div class="col-md-6">
                <p style="float:right;">
                    <button type="button" class="btn btn-primary btn-sm" id="btn-new-person">
                        Cadastrar pessoa
                    </button>
                </p>
            </div>
        </div>

        <hr/>
        
        <div class="row">
            @if ($data->isEmpty())
                <div class="alert alert-primary" role="alert">
                    Nenhum registro foi encontrado!
                </div>
            @else
                <div class="table-responsive list-person">
                    <table class="table table-striped table-hover" id="table-list">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">CPF</th>
                                <th scope="col">Email</th>
                                <th scope="col">Idade</th>
                                <th scope="col">Data</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                <tr>
                                    <td scope="col">{{ $key + 1 }}</td>
                                    <td scope="col">{{ $item->resource['name'] }}</td>
                                    <td scope="col">{{ Util::formatCpfCnpj($item->resource['cpf']) }}</td>
                                    <td scope="col">{{ $item->resource['email'] }}</td>
                                    <td scope="col">{{ $item->resource['age'] }}</td>
                                    <td scope="col">{{ Util::formatDataPt($item->resource['created_at']) }}</td>
                                    <td scope="col">
                                        <button type="button" class="btn btn-secondary btn-sm btn-see-person" data-identifier="{{ $item['id'] }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm btn-edit-person" data-identifier="{{ $item['id'] }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>
                                            </svg>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-person" data-identifier="{{ $item['id'] }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"></path>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    
    @component('modal.modal_form_person')
        <button type="button" class="btn btn-secondary close-modal">Fechar</button>
        <button type="button" class="btn btn-primary save-modal" data-identifier="">Salvar</button>
    @endcomponent

    @component('modal.modal_datail_person')
        <button type="button" class="btn btn-secondary close-modal">Fechar</button>
    @endcomponent
@stop

@section('scripts')
    <script src="{{ url('front/js/controller/FormPerson.js') }}"></script>
    <script src="{{ url('front/js/controller/Person.js') }}"></script>
    <script src="{{ url('front/js/home.js') }}"></script>
@stop
