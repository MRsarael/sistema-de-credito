class FormPerson {
    constructor(form) {
        this._form = form
        this._person = {}
    }

    async setPerson(person)
    {
        this._person = person
        this._form.querySelector('#input-name').value = this._person.name
        this._form.querySelector('#input-cpf').value = this._person.cpf
        this._form.querySelector('#input-idade').value = this._person.age
        this._form.querySelector('#input-email').value = this._person.email
    }

    async resetForm()
    {
        this._form.reset()
        this._form.className = ''
    }

    async save()
    {
        return new Promise(async (resolve, reject) => {
            let url = `${appUrl}/api/person`
            let method = 'POST'

            const data = {
                name: this._person.name,
                cpf: this._person.cpf,
                age: this._person.age,
                email: this._person.email
            }

            if(this._person.id) {
                data['id'] = this._person.id
                method = 'PUT'
            }
            
            fetch(url, {
                method: method,
                headers: {
                    accept: 'application.json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
                cache: 'default'
            }).then(async (response) => {
                const json = await response.json()
                
                if(json.error) {
                    throw new Error(json.message)
                }
    
                if(json.response) {
                    resolve(json.response)
                    return
                }
    
                throw new Error('Nenhum registro encontrado')
            }).catch(error => {
                reject({message: error.message})
            })
        })
    }
}
