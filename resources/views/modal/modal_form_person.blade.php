<div class="modal fade" id="modalFormPerson" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFormPersonLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormPersonLabel"></h5>
                {{-- <button type="button" class="btn-close close-modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                {{-- FORMULÁRIO --}}
                <div class="container">
                    <div class="col-md-12">
                        <form id="form-person" novalidate>
                            <div class="row mb-3">
                                <label for="input-name" class="col-sm-2 col-form-label">Nome</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="input-name" id="input-name" required>
                                </div>
                            </div>
                
                            <div class="row mb-3">
                                <label for="input-cpf" class="col-sm-2 col-form-label">CPF</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="input-cpf" id="input-cpf" placeholder="XXX.XXX.XXX-XX" onkeydown="javascript: cpf(this)" maxlength="14" required>
                                </div>
                            </div>
                
                            <div class="row mb-3">
                                <label for="input-idade" class="col-sm-2 col-form-label">Idade</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="input-idade" id="input-idade" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="input-email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="input-email" id="input-email" required>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>