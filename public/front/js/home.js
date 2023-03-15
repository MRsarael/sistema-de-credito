(async function () {
    'use strict'

    const divFormPerson = document.getElementById('modalFormPerson')
    const divModalDatailPerson = document.getElementById('modalDatailPerson')

    const modalFormPerson = new bootstrap.Modal(divFormPerson, {})
    const modalDatailPerson = new bootstrap.Modal(divModalDatailPerson, {})

    let formPerson = new FormPerson(divFormPerson)

    await addEventsButtonTableList(modalFormPerson, modalDatailPerson, divModalDatailPerson)
    await addEventsButtonModal(divFormPerson, modalFormPerson, modalDatailPerson, divModalDatailPerson)
})()

async function addEventsButtonModal(divFormPerson, modalFormPerson, modalDatailPerson, divModalDatailPerson)
{
    divFormPerson.querySelector('.close-modal').addEventListener('click', async () => {
        const divModal = document.getElementById('modalFormPerson')
        await closeModalRegister(divModal.querySelector('#form-person'), divModal.querySelector('.close-modal'), divModal.querySelector('.save-modal'))
        modalFormPerson.hide()
    })

    divFormPerson.querySelector('.save-modal').addEventListener('click', async () => {
        const divModal = document.getElementById('modalFormPerson')
        await registerPerson(divModal.querySelector('#form-person'), divModal.querySelector('.close-modal'), divModal.querySelector('.save-modal'))
        modalFormPerson.hide()
    })

    document.getElementById('modalDatailPerson').querySelector('.close-modal').addEventListener('click', async (element) => {
        const divModal = document.getElementById('modalDatailPerson')
        await modalDatailPerson.hide()
        await clearModalPersonDetail(divModal)
    })
}

async function addEventsButtonTableList(modalFormPerson, modalDatailPerson, divModalDatailPerson)
{
    document.getElementById('table-list').querySelector('tbody').querySelectorAll('.btn-see-person').forEach(element => {
        const id = element.dataset.identifier

        element.addEventListener('click', async () => {
            openLoad()

            getPerson(id).then(async (response) => {
                await buildPersonDetail(divModalDatailPerson, response)
                await buildToltipPersonDetail(divModalDatailPerson)
                await buildEventspPersonDetail(divModalDatailPerson)

                modalDatailPerson.show()
                closeLoad()
            }).catch(error => {
                showMessage('error', error.message)
                closeLoad()
            })
        })
    })

    document.getElementById('table-list').querySelector('tbody').querySelectorAll('.btn-edit-person').forEach(element => {
        const id = element.dataset.identifier

        element.addEventListener('click', async () => {
            await modalFormPerson.show()
        })
    })

    document.getElementById('table-list').querySelector('tbody').querySelectorAll('.btn-delete-person').forEach(element => {
        const id = element.dataset.identifier

        element.addEventListener('click', async () => {
            if(confirm("Tem certeza que deseja excluir esta pessoa?")) {
                console.log('Excluindo!!')
            }
        })
    })
}

async function buildPersonDetail(divModalDatailPerson, response)
{
    const idPerson = response.id

    let divTitle = divModalDatailPerson.querySelector('#modalDatailPersonLabel')
    let divBody = divModalDatailPerson.querySelector('.modal-body')

    let htmlDetail = '';
    let htmlCreditOfferModality = '';

    if(response.personalCreditOffer.length) {
        response.personalCreditOffer.forEach(creditOfferModality => {
            const idCreditOfferModality = creditOfferModality.id
            let htmlSimulationDetail = ''

            if(creditOfferModality.simulation.length) {
                htmlSimulationDetail += `
                    <tr>
                        <td colspan="5">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Quantidade máxima de parcelas</td><td>${creditOfferModality.simulation[0].max_installments}</td>
                                    </tr>
                                    <tr>
                                        <td>Quantidade mínima de parcelas</td><td>${creditOfferModality.simulation[0].min_installments}</td>
                                    </tr>
                                    <tr>
                                        <td>Valor máximo</td><td>${creditOfferModality.simulation[0].max_value}</td>
                                    </tr>
                                    <tr>
                                        <td>Valor mínimo</td><td>${creditOfferModality.simulation[0].min_value}</td>
                                    </tr>
                                    <tr>
                                        <td>Juros mensal</td><td>${creditOfferModality.simulation[0].month_interest}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                `
            }

            if(creditOfferModality.creditOfferModality.length) {
                const idInstitution = creditOfferModality.creditOfferModality[0].idInstitution
                const codCreditOfferModality = creditOfferModality.creditOfferModality[0].cod

                htmlCreditOfferModality += `
                    <tr>
                        <td scope="col">${creditOfferModality.creditOfferModality[0].description}</td>
                        <td scope="col">${creditOfferModality.creditOfferModality[0].nameInstitution}</td>
                        <td scope="col">${creditOfferModality.creditOfferModality[0].idGosat}</td>
                        <td scope="col">${creditOfferModality.creditOfferModality[0].cod}</td>
                        <td scope="col">
                            <button type="button" class="btn btn-outline-primary btn-sm btn-simulation-offer" data-bs-toggle="tooltip" data-bs-placement="top" title="Nova simulação"
                                data-identifier-person="${idPerson}"
                                data-cod-modality="${codCreditOfferModality}"
                                data-identifier-institution="${idInstitution}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                                    <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    ${htmlSimulationDetail}
                `
            }
        })

        htmlDetail = `
            <tr>
                <td colspan="4">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <td scope="col">Oferta</td>
                                <td scope="col">Instituição</td>
                                <td scope="col">ID Gosat</td>
                                <td scope="col">COD</td>
                                <td scope="col"></td>
                            </tr>
                        </thead>
                        <tbody>
                            ${htmlCreditOfferModality}
                        </tbody>
                    </table>
                </td>
            </tr>
        `
    }
    
    let html = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">CPF</th>
                        <th scope="col">Idade</th>
                        <th scope="col">E-mail</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="col">${response.cpf}</td>
                        <td scope="col">${response.age}</td>
                        <td scope="col">${response.email}</td>
                        <td scope="col">
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-consult-offer" data-bs-toggle="tooltip" data-bs-placement="top" title="Nova consulta de crédito" data-identifier="${idPerson}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                                    <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    ${htmlDetail}
                </tbody>
            </table>
        </div>
    `

    divBody.innerHTML = `${html}`
    divTitle.innerHTML = response.name
}

async function buildToltipPersonDetail(divModalDatailPerson)
{
    let divBody = divModalDatailPerson.querySelector('.modal-body')
    let tooltipTriggerList = [].slice.call(divBody.querySelectorAll('[data-bs-toggle="tooltip"]'))

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
}

async function buildEventspPersonDetail(divModalDatailPerson)
{
    let divBody = divModalDatailPerson.querySelector('.modal-body')

    divBody.querySelectorAll('.btn-simulation-offer').forEach(btn => {
        const identifiers = btn.dataset

        btn.addEventListener('click', async (button) => {
            openLoad()

            simulateCreditOffer(identifiers.identifierPerson, identifiers.codModality, identifiers.identifierInstitution).then(response => {
                closeLoad()
            }).catch(error => {
                closeLoad()
                showMessage('error', error.message)
            })
        })
    })

    divBody.querySelectorAll('.btn-consult-offer').forEach(btn => {
        console.log(btn.dataset.identifier)
    })
}

async function clearModalPersonDetail(divModal)
{
    divModal.querySelector('.modal-title').innerHTML = ''
    divModal.querySelector('.modal-body').innerHTML = ''
}

async function closeModalRegister(form, btnSave, btnEdit)
{
    console.log(form, btnSave, btnEdit)
}

async function registerPerson(form, btnSave, btnEdit)
{
    console.log(form, btnSave, btnEdit)
}

async function getPerson(id)
{
    return new Promise(async (resolve, reject) => {
        fetch(`${appUrl}/api/person/credit/offer/${id}`).then(async (response) => {
            if(String(response.status) !== '200') throw new Error(response.statusText)
            const json = await response.json()
            
            if(json.response.length) {
                const response = json.response[0]
                resolve(response)
                return
            }

            throw new Error('Nenhum registro encontrado')
        }).catch(error => {
            reject({message: error.message})
        })
    })
}

async function simulateCreditOffer(idPerson, codModality, idInstitution)
{
    return new Promise(async (resolve, reject) => {
        fetch(`${appUrl}/api/person/credit/simulation`, {
            method: 'POST',
            headers: {
                accept: 'application.json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                idPerson: idPerson,
                codModality: codModality,
                idInstitution: idInstitution
            }),
            cache: 'default'
        }).then(async (response) => {
            if(String(response.status) !== '200') throw new Error(response.statusText)
            const json = await response.json()

            if(json.response.length) {
                resolve(json.response)
                return
            }

            throw new Error('Nenhum registro encontrado')
        }).catch(error => {
            reject({message: error.message})
        })
    })
}

// ${appUrl}/api/credit

